<?php
/* Copyright (C) 2011 by iRail vzw/asbl 
 *
 * This will catch all requests through the hidden url-rewrite service (see .htaccess). It will generate a Model and a View through the Page class.
 *
 * @author Pieter Colpaert
 * @license AGPL
 */
//set the include path to the root
ini_set('include_path', '../');
//Step 0: Include all necessary files
include_once("requesthandlers/Page.class.php");
include_once("datamodel/DataLayer.class.php");

//Step 1: Implement the abstract Page class
//This class will automatically include necessary stuff such like error handling
class Controller extends Page{
   /**
    * Function is used for API Requests
    * @return array will return an associative array of page specific variables.
    */
     protected function loadContent(){
	  $data = new DataLayer($this->getLang());
	  //Step 2: Get the get vars, change them to the right format & boom
	  extract($_GET); //this will get all the GET vars and put them in normal PHP vars
	  if($page == "info"){
	       include("config.php");
	       $contentarray;
	       if(!isset($x)){
                    //brussels central50.85, 4.35
		    $x=4.35;
	       }
	       if(!isset($y)){
		    //brussels central
		    $y=50.85;
	       }
	       if(!isset($direction)){
		    $direction="DEP";
	       }
	       //for each system we will output something in the contentarray
	       foreach($systems as $current){
		    $contentarray[$current] = $data->getLiveboard($x,$y,$direction,$current);
	       } 
	       return $contentarray;
	  }else if($page == "error"){
//this will only be apache errors
	       if(isset($message)){
		    return array("message" => $message);
	       }
	       return array("message" => "unknown error");
	  }
     }
}

//Step 3: load the process
$instance = new Controller();
$instance->buildPage($_GET["page"]);

?>