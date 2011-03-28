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
	  foreach($stations["station"] as $station){ 
	       $dist = $this->distance($x,$station["locationX"],$y,$station["locationY"]);
	       if( $dist < $vicinity){		    
		    $station["distance"] = floor($dist*1000);
		    $station["distance"] .= "m";
		    $output[sizeof($output)] = $station;
	       }
	  }
	  $output = $this->removeDuplicates($output);
	  return $output;
     }

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
//	  print_r($newarray);
	  
	  return $newarray;
     }
     
     private function distance($x1,$x2,$y1,$y2){
	  return (3958*pi()*sqrt(($y2-$y1)*($y2-$y1) + cos($y2/57.29578)*cos($y1/57.29578)*($x2-$x1)*($x2-$x1))/180);
     }
     

     public function getLiveboard($x,$y, $direction, $system){
//all stations in a radius of Xkm
	  $stations = $this->getStations($x,$y,$system);	  
	  $output = array();
	  $i = 0;
	  foreach($stations as $station){
	       $args = array(
		    "lang" => "NL",
		    "station" => $station["name"],
		    "arrdep" => $direction,
		    "system" => $system
		    );
	       try{
		    $output[$i] = APICall::execute("liveboard", $args);
		    $output[$i]["stationinfo"] = $station;
		    $i++;
	       }catch(Exception $e){
		    throw $e;
	       }
	  }
	  //print_r($output);
	  
	  return $output;
     }

}
?>