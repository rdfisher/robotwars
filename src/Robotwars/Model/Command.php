<?php
namespace Robotwars\Model;

interface Command
{
    public function getNewLocation(Location $location, Facing $facing);
    public function getNewFacing(Facing $facing);
    public function __toString();
}