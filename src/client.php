<?php
include_once '../vendor/autoload.php';

$host = $argv[1];
$port = isset($argv[2]) ? $argv[2] : 1337;

$loop = React\EventLoop\Factory::create();
$dnsResolverFactory = new React\Dns\Resolver\Factory();
$dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);
$connector = new React\SocketClient\Connector($loop, $dns);

$connector->create($host, $port)->then(function (React\Stream\Stream $stream) {
    $brain = new \Robotwars\Client\Brain();
    
    $stream->write($brain->getName());
    
    $shutdown = function() {
        die("\nCONNECTION CLOSED\n");
    };
    
    $stream->on('close', $shutdown);
    $stream->on('error', $shutdown);
    
    $stream->on('data', function($data) use ($stream, $brain) {
        if (empty($data)) return;
        
        $arenaData = json_decode($data, true);
        $arena = new Robotwars\Model\Arena($arenaData['width'], $arenaData['height']);
        
        $me = null;
        
        foreach ($arenaData['robots'] as $robotData) {
            $location = new Robotwars\Model\Location($robotData['location']['x'], $robotData['location']['x']);
            $robot = new \Robotwars\Model\Robot(
                $robotData['name'], 
                $location, 
                new \Robotwars\Model\Facing($robotData['facing']), 
                $robotData['health']
            );
            $arena->addRobot($robot);
            if ($robot->getName() == $brain->getName()) {
                $me = $robot;
            }
        }
        
        $command = $brain->getCommand($arena, $me);
        $stream->write((string)$command . "\n");
    });
});

$loop->run();