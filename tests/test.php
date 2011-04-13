<?php
ini_set("include_path", "../");
include_once('tests/simpletest/autoruan.php');
include_once('model/DataLayer.class.php');
include_once('model/APICall.class.php');

class FileTestCase extends UnitTestCase {
	function FileTestCase() {
		print "<ul>";
		print "<li>1 - Empty liveboard</li>";
		print "<li>2 - Brussel Central Distance</li>";
		print "</ul>";	
	}

	//This will happen before test
	function setUp() {
 
	}
	//This will happen after test
	function tearDown() {

	}
	//Test function to see if it works
	function testNull() {
		$data = new DataLayer("EN");
		$variable = $data->getLiveboard(0, 0,"DEPef", "lolol");
		$this->assertEqual($variable, array());
	}
	
	function testBrxCntDist(){
		$systems = array("NMBS", "MIVB");
		$data = new DataLayer("EN");
		//Brussel Centraal liveboard
		$liveboard = $data->getLiveboard(4.35, 50.85,"DEP", $systems[0]);
		$this->assertEqual($liveboard[0]["stationinfo"]["distance"], "455m");
	}
}
?>
