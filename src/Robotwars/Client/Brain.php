<?php
namespace Robotwars\Client;

use Robotwars\Model\Arena;
use Robotwars\Model\Robot;
use Robotwars\Model\Command;
use Robotwars\Model\Command\Stop;
use Robotwars\Model\Command\TurnLeft;
use Robotwars\Model\Command\TurnRight;
use Robotwars\Model\Command\UTurn;
use Robotwars\Model\Command\MoveForward;

class Brain
{
    public function getName()
    {
        return 'Jim';
    }
    
    /**
     * @param Arena $arena
     * @param Robot $you
     * @return Command
     */
    public function getCommand(Arena $arena, Robot $you)
    {
        sleep(1);
        return new MoveForward();
    }
}