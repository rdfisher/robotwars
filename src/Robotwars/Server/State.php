<?php
namespace Robotwars\Server;
use Robotwars\Model\Arena;

class State
{
    private $connections;
    private $arena;
    
    public function __construct(Arena $arena)
    {
        $this->connections = new \SplObjectStorage();
        $this->arena = $arena;
    }
    
    public function addConnection(Connection $connection)
    {
        $this->connections->attach($connection);
    }
    
    public function removeConnection(Connection $connection)
    {
        $this->connections->detach($connection);
    }
    
    public function getConnections()
    {
        return $this->connections;
    }
    
    public function getArena()
    {
        return $this->arena;
    }
    
    public function readyToAct()
    {
        foreach ($this->connections as $connection) {
            if (! $connection->hasCommand()) {
                return false;
            }
        }
        
        return true;
    }
    
    public function act()
    {
        $arena = $this->getArena();
        
        $robotsThatFellOffTheArena = array();
        
        foreach ($this->connections as $connection) {
            $robot = $connection->getRobot();
            
            $currentLocation = $robot->getLocation();
            $currentFacing = $robot->getFacing();
            
            $command = $connection->getCommandAndClear();

            $newLocation = $command->getNewLocation($currentLocation, $currentFacing);
            $newFacing = $command->getNewFacing($currentFacing);
            
            $robot->setLocation($newLocation);
            $robot->setFacing($newFacing);
            
            if (! $arena->containsLocation($newLocation)) {
                $robot->applyDamage();
                if (! $robot->isAlive()) {
                    $arena->removeRobot($robot);
                    $this->removeConnection($connection);
                    $socket = $connection->getSocket();
                    $socket->write('DEAD');
                    $socket->close();
                } else {
                    $robotsThatFellOffTheArena[] = $robot;
                }
            }
        }

        foreach($robotsThatFellOffTheArena as $robot) {
            $randomLocation = $arena->getRandomUnoccupiedLocation();
            $robot->setLocation($randomLocation);
        }

        foreach ($this->connections as $connection) {
            $robot = $connection->getRobot();
            
            $location = $robot->getLocation();
            $robotsAtLocation = $arena->getRobotsAt($location);
            if (count($robotsAtLocation) > 1) {
                foreach ($robotsAtLocation as $robotAtLocation) {
                    $robotAtLocation->applyDamage();
                    if (! $robotAtLocation->isAlive()) {
                        $arena->removeRobot($robot);
                        $this->removeConnection($connection);
                        $socket = $connection->getSocket();
                        $socket->write('DEAD');
                        $socket->close();
                    } else {
                        $randomLocation = $arena->getRandomUnoccupiedLocation();
                        $robot->setLocation($randomLocation);
                    }
                }
            }
        }

        foreach ($this->connections as $connection) {
            //shoot
            $robot = $connection->getRobot();
            $location = $robot->getLocation();
            $facing = $robot->getFacing();
            
            $command = new \Robotwars\Model\Command\MoveForward();
            $location = $command->getNewLocation($location, $facing);
            
            while($arena->containsLocation($location)) {
                $targets = $arena->getRobotsAt($location);
                
                foreach ($targets as $target) {
                    $target->applyDamage();
                    if (! $target->isAlive()) {
                        $arena->removeRobot($robot);
                        $this->removeConnection($connection);
                        $socket = $connection->getSocket();
                        $socket->write('DEAD');
                        $socket->close();
                    }
                }
                
                $location = $command->getNewLocation($location, $facing);
            }
        }
        
        foreach ($this->connections as $connection) {
            $socket = $connection->getSocket();
            $socket->write(json_encode($this->arena->toArray()) . "\n");
        }
        
        $this->render();
    }
    
    public function render()
    {
        passthru('clear');
        $line = array_fill(0, $this->arena->getWidth(), '.');
        $grid = array_fill(0, $this->arena->getHeight(), $line);
        $robotText = array();
        
        foreach ($this->connections as $connection) {
            $robot = $connection->getRobot();
            $symbol = ' ';
            switch ((string)$robot->getFacing()) {
                case 'north':
                    $symbol = "^";
                    break;
                case 'east':
                    $symbol = ">";
                    break;
                case 'south':
                    $symbol = "v";
                    break;
                case 'west':
                    $symbol = "<";
                    break;
            }
            $location = $robot->getLocation();
            $grid[$location->getY()][$location->getX()] = $symbol;
            
            $robotText[] = $robot->getName() . '@[' . $location->getX() . ',' . $location->getY() . '] Health: ' . $robot->getHealth();
        }
        foreach ($grid as $line) {
            echo implode(' ', $line) . "\n";
        }
        
        echo "\n";
        echo implode("\n", $robotText);
    }
}