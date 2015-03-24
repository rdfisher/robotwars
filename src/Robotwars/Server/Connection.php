<?php

namespace Robotwars\Server;
use Robotwars\Model\Robot;
use Robotwars\Model\Arena;
use Robotwars\Model\Location;
use Robotwars\Model\Facing;
use Robotwars\Model\Command;

class Connection
{
    private $robot;
    private $command;
    private $socket;
    
    public function __construct($name, Arena $arena, $socket)
    {
        $location = $arena->getRandomUnoccupiedLocation();
        $facing = Facing::getRandom();
        $this->robot = new Robot($name, $location, $facing);
        $arena->addRobot($this->robot);
        
        $this->socket = $socket;
    }
    
    public function getSocket()
    {
        return $this->socket;
    }
    
    public function getRobot()
    {
        return $this->robot;
    }
    
    public function setCommand(Command $command)
    {
        $this->command = $command;
    }
    
    public function getCommandAndClear()
    {
        $command = $this->command;
        $this->command = null;
        return $command;
    }
    
    public function hasCommand()
    {
        return (bool)$this->command;
    }
}