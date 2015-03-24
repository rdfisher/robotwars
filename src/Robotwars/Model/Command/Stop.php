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
        return $location;
    }

}

