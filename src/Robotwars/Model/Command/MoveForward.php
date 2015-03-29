<?php
namespace Robotwars\Model\Command;
use Robotwars\Model\Command;

class MoveForward implements Command
{
    public function getNewFacing(\Robotwars\Model\Facing $facing)
    {
        return $facing;
    }

    public function getNewLocation(\Robotwars\Model\Location $location, \Robotwars\Model\Facing $facing)
    {
        $x = $location->getX();
        $y = $location->getY();
        
        switch ((string)$facing) {
            case \Robotwars\Model\Facing::NORTH:
                $y++;
                break;
            case \Robotwars\Model\Facing::SOUTH:
                $y--;
                break;
            case \Robotwars\Model\Facing::EAST:
                $x++;
                break;
            case \Robotwars\Model\Facing::WEST:
                $x--;
                break;
        }
        
        return new \Robotwars\Model\Location($x, $y);
    }

    public function __toString()
    {
        return 'forward';
    }

}

