<?php
include_once '../vendor/autoload.php';

$arena = new Robotwars\Model\Arena(10,10);
$serverState = new Robotwars\Server\State($arena);
$serverState->render();
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$socket->on('connection', function ($conn)  use ($serverState) {
    $serverConnection = null;
    
    $cleanup = function() use ($conn, &$serverConnection, $serverState) {
        if ($serverConnection) {
            $robot = $serverConnection->getRobot();
            $serverState->getArena()->removeRobot($robot);
            $serverState->removeConnection($serverConnection);
        }
        $conn->close();
    };
    
    $conn->on('data', function ($data) use ($conn, &$serverConnection, $serverState, $cleanup) {
        $arena = $serverState->getArena();
        if (! $serverConnection) {
            $name = trim($data);
            $serverConnection = new Robotwars\Server\Connection($name, $arena, $conn);
            $serverState->addConnection($serverConnection);
            $conn->write(json_encode($arena->toArray())) . "\n";
        } else {
            $commandInterpreter = new \Robotwars\Server\CommandInterpreter();
            $robot = $serverConnection->getRobot();
            try {
                $command = $commandInterpreter->getCommand($data);
                $serverConnection->setCommand($command);
                
                if ($serverState->readyToAct()) {
                    $serverState->act();
                }
            } catch (Exception $ex) {
                $conn->close();
            }
        }
    });
    
    $conn->on('close', $cleanup);
    $conn->on('error', $cleanup);
});
$socket->listen(1337);

//http server returning arena state
$detailsSocket = new React\Socket\Server($loop);
$http = new React\Http\Server($detailsSocket);
$http->on('request', function ($request, $response) use ($serverState) {
    $headers = array('Content-Type' => 'application/json');
    $response->writeHead(200, $headers);
    $response->end(json_encode($serverState->getArena()->toArray()));
});
$detailsSocket->listen(8080);

$loop->run();