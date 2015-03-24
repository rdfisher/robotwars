<?php
namespace Robotwars\Model;

class Facing
{
    const NORTH = 'north';
    const SOUTH = 'south';
    const EAST = 'east';
    const WEST = 'west';
    
    private $value;
    
    public function __construct($facing)
    {
        if (! in_array($facing, [self::NORTH, self::SOUTH, self::EAST, self::WEST])) {
            throw new \InvalidArgumentException('Invalid facing: ' . $facing);
        }
        
        $this->value = $facing;
    }

    public function __toString()
    {
        return $this->value;
    }
    
    public static function getRandom()
    {
        $directions = [self::NORTH, self::SOUTH, self::EAST, self::WEST];
        shuffle($directions);
        return new self($directions[0]);
    }
}