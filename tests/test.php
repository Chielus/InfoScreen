<?php
require_once('simpletest/autorun.php');
require_once('../datamodel/DataLayer.class.php');
require_once('../datamodel/APICall.class.php');

class FileTestCase extends UnitTestCase {
    private $data;
	private $contentarray = array();
	
	function FileTestCase() {
    }
    
	//This will happen before test
    function setUp() {
	//------------------
	//PIETER waarom geeft die array(0) { } 
	//------------------
		$systems = array("NMBS", "MIVB");
		$data = new DataLayer("EN");
		$output = $data->getStations(4.35, 50.85, $systems);
		var_dump($output);
    }
    
	//This will happen after test
    function tearDown() {
        
    }
    
	//Test function to see if it works
    function testNull() {
		$variable = null;
		$this->assertNull($variable);
    }
}
?>