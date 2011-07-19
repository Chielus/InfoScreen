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

include_once('model/APICall.class.php');
class DataLayer {

  private $lang;
  private $stationsnmbs;
  private $stationsmivb;

  // Constract array of nmbs and mivb stations
  public function __construct($lang){
    $this->lang = $lang;
    
    $jsonnmbs = json_decode(file_get_contents(ini_get('include_path') . 'model/stationsnmbs.json'));

    foreach ($jsonnmbs->station as $station) {
      $this->stationsnmbs[$station->standardname]['name'] = $station->name;
      $this->stationsnmbs[$station->standardname]['id'] = $station->id;
      $this->stationsnmbs[$station->standardname]['lat'] = $station->locationY;
      $this->stationsnmbs[$station->standardname]['long'] = $station->locationX;
    }

    $jsonmivb = json_decode(file_get_contents(ini_get('include_path') . 'model/stationsmivb.json'));

    foreach ($jsonmivb->station as $station) {
      $this->stationsmivb[$station->standardname]['name'] = $station->name;
      $this->stationsmivb[$station->standardname]['id'] = $station->id;
      $this->stationsmivb[$station->standardname]['lat'] = $station->locationY;
      $this->stationsmivb[$station->standardname]['long'] = $station->locationX;
    }
  }

  // Get the stations relative to a certain point
  // We suppose the average walking speed to be 3km/hour or 50m/minute
  // Precondition: $nmbs and $mivb are arrays of standardnames
  // @return array of both nmbs and mivb stations
  public function getStationsData($nmbs, $mivb, $lat, $long) {
    for($i = 0; $i < sizeof($nmbs); $i++) {
      $nmbsarray[$i]['name'] = $this->stationsnmbs[$nmbs[$i]]['name'];
      $nmbsarray[$i]['distance'] = round($this->distance_to_station_nmbs($nmbs[$i], $lat, $long));
      $nmbsarray[$i]['walking'] = round($nmbsarray[$i]['distance'] / 50);
    }

    for($i = 0; $i < sizeof($mivb); $i++) {
      $mivbarray[$i]['name'] = $this->stationsmivb[$mivb[$i]]['name'];
      $mivbarray[$i]['distance'] = round($this->distance_to_station_mivb($mivb[$i], $lat, $long));
      $mivbarray[$i]['walking'] = round($mivbarray[$i]["distance"] / 50);
    }

    return array("NMBS" => $nmbsarray, "MIVB" => $mivbarray);
  }

  // @return distance to an nmbs station
  private function distance_to_station_nmbs($standardname, $lat, $long) {
    return $this->pc_sphere_distance($lat, $long, $this->stationsnmbs[$standardname]['lat'], $this->stationsnmbs[$standardname]['long']);
  }

  // @return distance to an mivb station 
  private function distance_to_station_mivb($standardname, $lat, $long) {
    return $this->pc_sphere_distance($lat, $long, $this->stationsmivb[$standardname]['lat'], $this->stationsmivb[$standardname]['long']);
  }

  // @return distance between two given points
  private function pc_sphere_distance($lat1, $lon1, $lat2, $lon2) {
    $radius = 6378.135;

    $rad = doubleval(M_PI / 180.0);
    $lat1 = doubleval($lat1) * $rad;
    $lon1 = doubleval($lon1) * $rad;
    $lat2 = doubleval($lat2) * $rad;
    $lon2 = doubleval($lon2) * $rad;

    $theta = $lon2 - $lon1;
    $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));
    if ($dist < 0) {
      $dist += M_PI;
    }

    return $dist = $dist * $radius * 1000;
  }

  // @return closest mivb and nmbs stations according to the give vicinity
  public function getClosestStationsData($lat, $long, $vicinity){
    // Loop and check if distance to nmbs station is smaller than the vicinity, you only want to get nmbs stations nearby
    $i = 0;
    $nmbsarray = array();
    foreach($this->stationsnmbs as $standardname => $stationarray) {
      $distance = $this->pc_sphere_distance($lat, $long, $stationarray['lat'], $stationarray['long']);
      if(!is_nan($distance) && $distance < ($vicinity * 1000)) {
	$nmbsarray[$i]['name'] = $stationarray['name'];
	$nmbsarray[$i]['distance'] = round($distance);
	$nmbsarray[$i]['walking'] = round($distance / 50);
	$i = $i + 1;
      }
    }

    // Loop and check if distance to mivb station is smaller than the vicinity, you only want to get mivb stations nearby
    $i = 0;
    $mivbarray = array();
    foreach($this->stationsmivb as $standardname => $stationarray) {
      $distance = $this->pc_sphere_distance($lat, $long, $stationarray['lat'], $stationarray['long']);
      if(!is_nan($distance) && $distance < ($vicinity * 1000)) {
	$mivbarray[$i]['name'] = $stationarray['name'];
	$mivbarray[$i]['distance'] = round($distance);
	$mivbarray[$i]['walking'] = round($distance / 50);
	$i = $i + 1;
      }
    }
    
    return array("NMBS" => $this->removeDuplicates($nmbsarray), "MIVB" => $this->removeDuplicates($mivbarray));
  }

  /*
   * Function that will remove duplicate stations
   * @return array without duplicates
   */
  private function removeDuplicates($nodes) {
    $newarray = array();
    for($i = 0; $i < sizeof($nodes); $i++) {
      $duplicate = false;
      for($j = 0; $j < $i; $j++) {
	if($nodes[$i]['name'] == $nodes[$j]['name']){
	  $duplicate = true;
	  break; // Sorry father for I have sinned
	}
      }
      if(!$duplicate) {
	$newarray[sizeof($newarray)] = $nodes[$i];
      }
    }

    return $newarray;
  }

}
?>
