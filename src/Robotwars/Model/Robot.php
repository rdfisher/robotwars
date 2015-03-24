<?php

namespace Robotwars\Model;

class Robot
{
    private $location;
    private $facing;
    private $health;
    private $name;
    private $command;
    
    public function __construct($name, Location $location, Facing $facing, $health = 6)
    {
        $this->location = $location;
        $this->facing = $facing;
        $this->health = (int)$health;
        $this->name = (string)$name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }
    
    public function getFacing()
    {
        return $this->facing;
    }

    public function setFacing(Facing $facing)
    {
        $this->facing = $facing;
    }

    public function getHealth()
    {
        return $this->health;
    }
    
    public function applyDamage($damage = 1)
    {
        $this->health -= $damage;
    }
    
    public function isAlive()
    {
        return $this->health > 0;
    }
    
    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'health' => $this->getHealth(),
            'location' => $this->getLocation()->toArray(),
            'facing' => (string)$this->getFacing(),
        );
    }
}