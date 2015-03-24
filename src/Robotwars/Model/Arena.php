<?php

namespace Robotwars\Model;

class Arena
{
    private $height;
    private $width;
    private $robots;
    
    public function __construct($width, $height)
    {
        $this->height = (int)$height;
        $this->width = (int)$width;
        $this->robots = array();
    }
    
    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }
    
    public function addRobot(Robot $robot)
    {
        $this->robots[] = $robot;
    }
    
    public function removeRobot(Robot $robot)
    {
        foreach ($this->robots as $i => $r) {
            if ($r === $robot) {
                unset($this->robots[$i]);
            }
        }
    }
    
    public function getRobots()
    {
        return $this->robots;
    }
    
    public function getRobotsAt(Location $location)
    {
        $robots = array();
        foreach ($this->robots as $robot) {
            $robotLocation = $robot->getLocation();
            if ($robotLocation->getX() !== $location->getX()) continue;
            if ($robotLocation->getY() !== $location->getY()) continue;
            $robots[] = $robot;
        }
        return $robots;
    }
    
    public function getRobotByName($name)
    {
        foreach ($this->getRobots() as $robot) {
            if ($robot->getName() == $name) {
                return $robot;
            }
        }
    }
    
    public function getRandomUnoccupiedLocation()
    {
        $location = null;
        
        while (! $location) {
            $x = rand(0, $this->getWidth());
            $y = rand(0, $this->getHeight());
            $potentialLocation = new Location($x, $y);
            $inhabitants = $this->getRobotsAt($potentialLocation);
            if (empty($inhabitants)) {
                $location = $potentialLocation;
            }
        }
        
        return $location;
    }
    
    public function containsLocation(Location $location)
    {
        if ($location->getX() < 0) return false;
        if ($location->getX() >= $this->width) return false;
        if ($location->getY() < 0) return false;
        if ($location->getY() >= $this->height) return false;
        return true;
    }
    
    public function toArray()
    {
        $robots = array();
        
        foreach ($this->getRobots() as $robot) {
            $robots[] = $robot->toArray();
        }
        
        return array(
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'robots' => $robots
        );
    }
}
