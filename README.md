# robotwars

Premise:

Robots battling it out for supremecy in an arena.

Running the server:

php server.php

Protocol:

 * Clients connect to the server on port 1337
 * Commands should be sent followed by a line break (\n)
 * Responses from the server are json giving the current state of the arena.
 * The first command given should be the name for the robot
 * Following commands should be an order for the robot (left, right, forward, u-turn, stop)
 * no response is sent until all connected clients have submitted an order
 * when the robot is defeated, the client disconnects
 
 Rules:
 * A robot begins with 6 health. At 0 health it is destroyed.
 * If a robot leaves the arena, 1 damage is inflicted and the robot is teleported to a random empty location
 * If 2 robots collide, 1 damage is dealt to each and both are teleported to random empty locations
 * after all orders are resolved, all robots fire a laser straight forward, dealing 1 damage to all robots the beam hits.
  
Implementing a client:

In Robotwars\Client\Brain.php, implement the 2 methods:
 * getName() - should return a string giving the name of your robot
 * getCommand() - should return a Robotwars\Model\Command

Running the client:

php client.php <server hostname or IP address>

The web interface:

point your browser at http://<server hostname or IP address>:8080