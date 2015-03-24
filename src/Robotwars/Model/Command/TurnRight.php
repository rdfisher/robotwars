<?php
namespace Robotwars\Model\Command;
use Robotwars\Model\Command;

class TurnRight implements Command
{
    public function getNewFacing(\Robotwars\Model\Facing $facing)
    {
        switch ((string)$facing) {
            case \Robotwars\Model\Facing::NORTH:
                return new \Robotwars\Model\Facing(\Robotwars\Model\Facing::EAST);
                break;
            case \Robotwars\Model\Facing::EAST:
                return new \Robotwars\Model\Facing(\Robotwars\Model\Facing::SOUTH);
                break;
            case \Robotwars\Model\Facing::SOUTH:
                return new \Robotwars\Model\Facing(\Robotwars\Model\Facing::WEST);
                break;
            case \Robotwars\Model\Facing::WEST:
                return new \Robotwars\Model\Facing(\Robotwars\Model\Facing::NORTH);
                break;
        }
    }

    public function getNewLocation(\Robotwars\Model\Location $location, \Robotwars\Model\Facing $facing)
    {
        return $location;
    }

}