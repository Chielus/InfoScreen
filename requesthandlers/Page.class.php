<?php
  /* Copyright (C) 2011 by iRail vzw/asbl
   *
   * This is the start of all pages. It uses the template design pattern to
   * create the page: it will need a template and language chosen by the user.
   *
   * Usage:
   * 
   * If you want to use this class you need to override it into a new class, preferably from the page where you're going to start
   *
   * @author Pieter Colpaert
   * @license aGPL
   */
set_error_handler("errorhandler");
abstract class Page {

     //CONFIGURATION OF THIS CLASS
     protected $AVAILABLE_TEMPLATES = array("default"); 
     protected $AVAILABLE_LANGUAGES = array("EN", "NL", "FR", "DE");
     private $template;
     private $detectLanguage = true;
     private $doErrorhandling = true;

     //DON'T TOUCH
     private $lang = "EN";
     private $pageName;

     /**
      * Function is used for API Requests
      * @return array will return an associative array of page specific variables.
      */
     protected abstract function loadContent();

/*
 * This function will build our page
 * @pageName Name to our file.
 */ 
     public function buildPage($pageName) {
	  include("config.php");
	  $this->template = $template;
	  //If true we will try to find the language 
	  if ($this->detectLanguage) {
	       $this->detectLanguage();
	  }
	  try{
	       $content = $this->loadContent();
	       $globals = $this->loadGlobals();
	       $i18n = $this->loadI18n();
	       $file = "templates/" . $this->template . "/" . $pageName . ".php";
	       //../ added because that's the iniset's includepath
	       if(!file_exists("../" . $file)){
	           throw new Exception("Wrong pagename given");
	       }
	       //we want to ensure that no new page will be generated when the page is being created - so we're only going to log the error.
	       set_error_handler("logerror");
	       include($file);
	  }catch(Exception $e){
	       $this->buildError($this->getLang(), $pageName, $e);
	  }
     }
/*
 * Function to change Detectlanguage, Boolean
 */
     public function setDetectLanguage($bool) {
	  $this->detectLanguage = $bool;
     }

/*
 * Function to detect language
 * Will check for cookie first else if in the "HTTP_ACCEPT_LANGUAGE" else default "EN"
 * Will also check GET to check for a language.
 */
     private function detectLanguage() {
	  if (isset($_COOKIE["language"])) {
	       $this->setLanguage($_COOKIE["language"]);
	  }else if(in_array(strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2)), $this->AVAILABLE_LANGUAGES)){
	       $this->setLanguage(strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2)));
	  }else{
	       $this->setLanguage("EN");
	  }
	  if (isset($_GET["lang"])) {
	       $this->setLanguage($_GET["lang"]);
	       setcookie("language", $_GET["lang"], time() + 60 * 60 * 24 * 360);
	  }
     }

/*
 * Load Global variables
 */
     private function loadGlobals() {
	  $globals =array();
//	 $globals["GoogleAnalytics"] = file_get_contents("includes/googleAnalytics.php");
//	 $globals["footer"] = file_get_contents("includes/footer.php");
	  $globals["iRail"] = "iRail";
	  return $globals;
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
 * Function to change the language attribute
 * Will check if language is available
 * @lang contains language string
 */
     public function setLanguage($lang) {
	  if (in_array($lang, $this->AVAILABLE_LANGUAGES)) {
	       $this->lang = $lang;
	  }else{
	       throw new Exception("language doesn't exist");
	  }
     }

/*
 * Function to load language, will include the language file
 * return Array of the language
 */
     private function loadI18n() {
	  if(in_array($this->lang,$this->AVAILABLE_LANGUAGES)){
	       include("i18n/". strtoupper($this->lang) . ".php");
	  }
	  return $i18n;
     }
/*
 * return the language string
 */
     public function getLang() {
	  return $this->lang;
     }

/*
 * Function that will build the errors
 * 
 */
     public function buildError($lang, $pageName, $e){
	  if($this->doErrorhandling)
	       errorhandler(500,$e->getMessage());
     }
  }

/*
 * Function that handles the errors
 * will include custom error page with the errors within the contect array
 */
function errorhandler($errno,$errstr){
     logerror($errno,$errstr);
     $content = array("message"=> $errstr);
     $file = "templates/iRail/error.php";
     include($file);
     exit(0);
}
function logerror($errno,$errstr){
     //TODO
}

?>
