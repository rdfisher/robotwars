<?php
include_once '../vendor/autoload.php';

$arena = new Robotwars\Model\Arena(10,10);
$serverState = new Robotwars\Server\State($arena);
$serverState->render();
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$socket->on('connection', function ($conn)  use ($serverState) {
    $serverConnection = null;
    
    $conn->on('data', function ($data) use ($conn, &$serverConnection, $serverState) {
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
                $conn->write($ex->getMessage());
                $serverState->removeConnection($serverConnection);
                $conn->close();
            }
        }
    });
});
$socket->listen(1337);
$loop->run();