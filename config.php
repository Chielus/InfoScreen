<?php

$APIurl = "http://api.iRail.be/";
//Link to iRail API
$iRailAgent = "InfoScreen v0.1";

$template = "default";
$vicinity = 0.2;	//stations closer than 200m

$systems = array("NMBS", "MIVB");

$timeout = 60000;
//For the settimeout interval

//	-------------------------
//	|        panel0         |
//	|-----------------------|
//	|           |           |
//	|  panel1   |  panel2   |
//	|_______________________|

$panel0 = "add";
$panel1 = "MIVB";
$panel2 = "NMBS";

?>
