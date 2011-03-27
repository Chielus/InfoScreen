<?php
include("config.php");

if(!function_exists("calculateWaitingTime")){
     function calculateWaitingTime($ut){
	  $atm = date('U');
	  if($ut-$atm >0)
	  return formatDuration($ut - $atm);
	  else return NULL;
     }

     function formatDuration($time){
	  $minutes = ($time / 60) % 60;
	  $hours = floor($time / 3600);
	  //if($minutes < 10) $minutes = "0" . $minutes;
	  //if($hours < 10) $hours = "0" . $hours;
	  if($hours > 0){
	       return "~" . $hours . ":" . $minutes;
	  }else{
	       return "~" . $minutes . "'";
	  }
     }
}

foreach($content[$panel] as $liveboard){
     if(isset($liveboard["station"])){
	  echo "<h2>" . $liveboard["station"] . "</h2>";
	  echo "<ul>";
	  foreach($liveboard["departures"]["departure"] as $dep){
	       if(!is_null(calculateWaitingTime($dep["time"]))){
		    $dur = calculateWaitingTime($dep["time"]);
		    echo "<li>". $dur . " " . $dep["station"] . "</li>";
	       }    
	  }
	  echo "</ul>";
     }
}
?>