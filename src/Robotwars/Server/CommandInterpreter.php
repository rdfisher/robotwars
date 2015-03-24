<?php
namespace Robotwars\Server;
use Robotwars\Model\Command\TurnLeft;
use Robotwars\Model\Command\TurnRight;
use Robotwars\Model\Command\UTurn;
use Robotwars\Model\Command\MoveForward;
use Robotwars\Model\Command\Stop;
class CommandInterpreter
{
    public function getCommand($commandString)
    {
        $commandString = strtolower(trim($commandString));
        
        switch($commandString) {
            case 'left':
                return new TurnLeft();
                break;
            case 'right':
                return new TurnRight();
                break;
            case 'forward':
                return new MoveForward();
                break;
            case 'u-turn':
                return new UTurn();
                break;
            case 'stop':
                return new Stop();
                break;
        }
        
        throw new \Exception('Invalid command');
    }
}

