<?php
/*
 * due to different systems & performance,
 * as well as different network connectivity,
 * the calculation of the average speed per
 * wireguard tunnel makes no sense. There are
 * too many parameters that were not considered
 * in this old calculation to make sense.
 *
 * To ensure downward compatibility, this retrieval "speed.php" option remains available.
 *
 * The higher this number, the more nodes will connect to this supernode.
*/
echo "5";
