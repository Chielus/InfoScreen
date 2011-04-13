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
include_once("controllers/HttpRequest.class.php");

//Step 1: Implement the abstract Page class
//This class will automatically include necessary stuff such like error handling
class PageHandler extends HttpRequest{
     protected $AVAILABLE_TEMPLATES = array("default");
     private $template = "default";
   /**
    * Function is used for API Requests
    * @return array will return an associative array of page specific variables.
    */
     protected function loadContent(){
	  //Step 2: Get the get vars, change them to the right format & boom
	//...
     }
	
protected function getIncludeFile($pageName){
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

}

//Step 3: load the process
$instance = new PageHandler();
$instance->buildPage($_GET["page"]);

?>
