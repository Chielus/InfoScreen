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
include_once("controllers/HttpCall.class.php");
include_once("model/DataLayer.class.php");

// our database model
include_once("dbmodel/Customer.php");
include_once("dbmodel/Db.php");
include_once("dbmodel/Settings.php");
include_once("dbmodel/Stations.php");
include_once("dbmodel/Infoscreen.php");


//Step 1: Implement the abstract Page class
//This class will automatically include necessary stuff such like error handling
class PageHandler extends HttpCall{
     protected $AVAILABLE_TEMPLATES = array("default", "FlatTurtle", "iRail");
     private $template = "FlatTurtle";
private $detectTemplate = false;
   /**
    * Function is used for API Requests
    * @return array will return an associative array of page specific variables.
    */
     protected function loadContent(){

	  //Step 2: Get the get vars, change them to the right format & boom
	  $data = new DataLayer($this->getLang());	          

	  $id = 1;
	  $infoscreen = new Infoscreen($id);

	  $stationids = $infoscreen->getStationIds();
	  $nmbs = $stationids["NMBS"];
 	  $mivb = $stationids["MIVB"];	

	  $mivbarray = array();
	  foreach($mivb as $i) {
 		array_push($mivbarray, array("name" => $i, "distance" => 10));
	  }

	  $nmbsarray = array();
	  foreach($nmbs as $i) {
		include("controllers/idtoarray.php");
		$name = $idtoname[$i];
 		array_push($nmbsarray, array("name" => $name, "distance" => 10));
	  }
	

	//3000m/hour = 50m/minute
	for($i = 0; $i < sizeof($mivbarray); $i++){
	     $mivbarray[$i]["walking"] = round($mivbarray[$i]["distance"]/50);
	}
	for($i = 0; $i < sizeof($nmbsarray); $i++){
	     $nmbsarray[$i]["walking"] = round($nmbsarray[$i]["distance"]/50);
	}

	$data = array("MIVB" => $mivbarray, "NMBS" => $nmbsarray);

	$content = array();
	  $content = array_merge($content, $data);  

	  $content["motd"] = $infoscreen->getMotd();
 	  $content["rowstoshow"] = $infoscreen->getSettingValue("rowstoshow") == null ? 10 : $infoscreen->getSettingValue("rowstoshow");
          $content["refreshinterval"] = $infoscreen->getSettingValue("refreshinterval") == null ? 60 : $infoscreen->getSettingValue("refreshinterval");
	  $content["cycleinterval"] = $infoscreen->getSettingValue("cycleinterval") == null ? 10 : $infoscreen->getSettingValue("cycleinterval");
	  $content["logo"] = $infoscreen->getSettingValue("logo") == null ? "templates/FlatTurtle/img/logo.png" : $infoscreen->getSettingValue("logo");

	  return $content;
     }

	
     protected function getIncludeFile($pageName){
	if($this->detectTemplate){
		$this->detectTemplate();
	}
	  return "templates/" . $this->template . "/" . $pageName . ".php";
     }
/*
 * Function to change template attribute
 * Will check if template exists
 * @template contains template string name
 */
     public function setTemplate($template) {
          if (in_array($template, $this->AVAILABLE_TEMPLATES)) {
               $this->template = $template;
          }else{
               throw new Exception("template doesn't exist");
          }
     }
/*
 * Function to change Detectlanguage, Boolean
 */
     public function setDetectTemplate($bool) {
          $this->detectTemplate = $bool;
     }

/*
 * Function to detect language
 * Will check for cookie first else if in the "HTTP_ACCEPT_LANGUAGE" else default "EN"
 * Will also check GET to check for a language.
 */
     private function detectTemplate() {
          if (isset($_COOKIE["template"])) {
               $this->setTemplate($_COOKIE["template"]);
          }
          if (isset($_GET["template"])) {
               $this->setTemplate($_GET["template"]);
               setcookie("template", $_GET["template"], time() + 60 * 60 * 24 * 360);
          }
     }
}

//Step 3: load the process
$instance = new PageHandler();
$instance->setDetectTemplate(true);
$instance->buildPage($_GET["page"]);

?>
