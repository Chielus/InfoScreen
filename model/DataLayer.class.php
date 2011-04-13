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
include_once("model/APICall.class.php");
class DataLayer {
     private $lang; //Language attribute, holds the language that will be used. {EN, NL, ...}

/*
 * Constructor, sets the $lang attribute of this class
 * @lang = language parameter
 */
     public function __construct($lang){
	  $this->lang = $lang;
     }

     public function getStations(){
	  $toreturn = array("NMBS" => $this->getClosestStations(50.86,4.36,"NMBS"), "MIVB" => $this->getClosestStations(50.86,4.36,"MIVB"));
	  return $toreturn;
     }

/**
 * Returns a list of all stations according to the lang variable and what the API returns. Normally it will have these variables:
 * array[i] -> name
            -> locationX
            -> locationY
            -> standardname
 */
     private function getClosestStations($y,$x,$system){
	  include("config.php");
      //check if the stationslist hasn't been loaded yet and load the systems
	  if(!isset($this->$system)){
	       try{
		    $args = array("system" => $system, "lang" => "NL");
		    $this->$system = APICall::execute("stations",$args);
	       }catch(Exception $e){
		    throw $e;
	       }
	  }
	  $output = array();
	  $stations = $this->$system;
	  //Loop and check if distance is smaller then the vicinity, you only want to get stations nearby
	  foreach($stations["station"] as $station){ 
	       $dist = $this->distance($x,$station["locationX"],$y,$station["locationY"]);
	       if(!is_nan($dist) && $dist < $vicinity){
		    $station["distance"] = floor($dist*1000);
		    $station["distance"] .= "m";
		    $output[sizeof($output)] = $station;
	       }
	  }
	  $output = $this->removeDuplicates($output);
	  return $output;
     }

/*
 * function that will remove duplicate stations
 * @nodes array of stations
 * return newarray with no duplicates
 */
     private function removeDuplicates($nodes){
	  $newarray = array();
	  for($i = 0; $i < sizeof($nodes); $i++){
	       $duplicate = false;
	       for($j = 0; $j < $i; $j++){
		    if($nodes[$i]["name"] == $nodes[$j]["name"]){
			 $duplicate = true;
			 break;//sorry father for I have sinned
		    }
	       }
	       if(!$duplicate){
		    $newarray[sizeof($newarray)] = $nodes[$i];
	       }
	  }  
	  return $newarray;
     }

     private function distance($x1,$x2,$y1,$y2){
	  $R = 6371; // km
	  $dY = deg2rad($y2-$y1);
	  $dX = deg2rad($x2-$x1);
	  $a = sin($dY/2) * sin($dY/2) + cos(deg2rad($y1)) * cos(deg2rad($y2)) *sin($dX/2) * sin($dY/2);
	  $c = 2 * atan2(sqrt($a), sqrt(1-$a));
	  return $R * $c;
     }
}
?>
