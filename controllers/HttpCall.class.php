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
abstract class HttpCall {

     //CONFIGURATION OF THIS CLASS
     protected $AVAILABLE_LANGUAGES = array("EN", "NL", "FR", "DE");
     private $detectLanguage = true;
     private $doErrorhandling = true;

     //DON'T TOUCH
     private $lang = "EN";
     private $pageName;

     /**
      * Function is used for API Requests
      * @return array will return an associative array of page specific variables
      */
     protected abstract function loadContent();

     /*
      * This function will build our page
      * @pageName Name to our file.
      */ 
     public function buildPage($pageName) { 
       if ($this->detectLanguage) {
	 $this->detectLanguage();
       }
       try {
	 // load stations
	 $content = $this->loadContent();

	 // load globals
	 $globals = $this->loadGlobals();

	 // internationalization
	 $i18n = $this->loadI18n();
	 
	 // page to build
	 $file = $this->getIncludeFile($pageName);
	 if(!file_exists("../" . $file)) {
	   throw new Exception("Wrong pagename given!");
	 }
	 //we want to ensure that no new page will be generated when the page is being created - so we're only going to log the error.
	 set_error_handler("logerror");
	 include($file);
       } catch(Exception $e){
	 $this->buildError($this->getLang(), $pageName, $e);
       }
     }

     protected abstract function getIncludeFile($pageName);

     /*
      * Function to change detectLanguage
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
	  if(isset($_COOKIE["language"])) {
	    $this->setLanguage($_COOKIE["language"]);
	  } else if(in_array(strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)), $this->AVAILABLE_LANGUAGES)) {
	    $this->setLanguage(strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)));
	  } else {
	    $this->setLanguage("EN");
	  }
	  if(isset($_GET["lang"])) {
	    $this->setLanguage($_GET["lang"]);
	    setcookie("language", $_GET["lang"], time() + 60 * 60 * 24 * 360);
	  }
     }

     /*
      * Load global variables
      */
     private function loadGlobals() {
       include(ini_get('include_path') . 'config.php');
      
       $globals['iRail'] = "iRail";
       $globals['messageOfTheDay'] = $motd;
       $globals['rowsToShow'] = $rowstoshow;
       $globals['refreshInterval'] = $refreshinterval;
       $globals['cycleInterval'] = $cycleinterval;
       $globals['companyLogo'] = ini_get('include_path') . 'logos/' . $companylogo;

       return $globals;
     }

     /*
      * Function to change the language attribute
      * Will check if language is available
      * @lang contains language string
      */
     public function setLanguage($lang) {
       if (in_array($lang, $this->AVAILABLE_LANGUAGES)) {
	 $this->lang = $lang;
       } else{
	 throw new Exception("Language is not supported!");
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

     // Build error
     public function buildError($lang, $pageName, $error){
       if($this->doErrorhandling) {
	 errorhandler(500, $error->getMessage());
       }
     }
}

/*
 * Function that handles the errors
 * Will include custom error page with the errors within the contect array
 */
function errorhandler($errno, $errstr){
  logerror($errno, $errstr);
  $content = array("message" => $errstr);

  exit(0);
}

function logerror($errno, $errstr){
  // coming soon
}

?>
