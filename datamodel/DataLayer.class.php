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
		    $args = array("system" => $system);
		    $this->$system = APICall::execute("stations",$args);
	       }catch(Exception $e){
		    throw $e;
	       }
	  }
	  $output = array();
	  $stations = $this->$system;
	  foreach($stations["station"] as $station){ 
	       if( $this->distance($x,$station["locationX"],$y,$station["locationY"]) < $vicinity){
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
	  return $newarray;
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