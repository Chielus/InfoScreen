<?php
/* Copyright (C) 2011 by iRail vzw/asbl
 *
 * This is the DataLayer. It's a class that will perform APICalls only once per HTTP request.
 *
 * Usage:
 * Call the right get functions
 * 
 * If you want to use this class you need to override it into a new class, preferably from the page where you're going to start
 *
 * @author Pieter Colpaert
 * @license aGPL
 *
 */
include_once("datamodel/APICall.class.php");
class DataLayer {
     private $lang;
     private $stations;

     public function __construct($lang){
	  $this->lang = $lang;
     }
/**
 * Returns a list of all stations according to the lang variable and what the API returns. Normally it will have these variables:
 * array[i] -> name
            -> locationX
            -> locationY
            -> standardname
 */
     public function getStations($x,$y,$system){
	  include("config.php");
          //check if the stationslist hasn't been loaded yet
	  if(!isset($this->stations)){
	       try{
		    $this->stations = APICall::execute("stations");
	       }catch(Exception $e){
		    throw $e;
	       }
	  }
	  $output = array();
	  foreach($this->stations["station"] as $station){ 
	       if( $this->distance($x,$station["locationX"],$y,$station["locationY"]) < $vicinity){
		    $output[sizeof($output)] = $station;
	       }
	  }
	  return $output;
     }

     private function distance($x1,$x2,$y1,$y2){
	  return (sqrt(($x2-$x1)*($x2-$x1) + ($y2-$y1)*($y2-$y1))) * 111.325;
     }
     

     public function getLiveboard($x,$y, $direction, $system){
//all stations in a radius of Xkm
	  $stations = $this->getStations($x,$y,$system);	  
	  $output = array();
	  $i = 0;
	  foreach($stations as $station){
	       $args = array(
		    "lang" => $this->lang,
		    "station" => $station["name"],
		    "arrdep" => $direction,
		    "system" => $system
		    );
	       try{
		    $output[$i] = APICall::execute("liveboard", $args);
		    $i++;
	       }catch(Exception $e){
		    throw $e;
	       }
	  }
	  return $output;
     }

}
?>