<?php
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
	       return $hours . ":" . $minutes;
	  }else{
		if($minutes == "0"){
			return "(departing)";
		}else{
			return $minutes . "m";
		}  
	}
     }
     function formatDelay($time){
	  if($time > 59){
	  return "+".formatDuration($time);
	  }
	  
	  return "&nbsp;";
     }
     
}

foreach($content[$panel] as $liveboard){
     if(isset($liveboard["station"]) && sizeof($liveboard["departures"]["departure"]) > 0){
	  echo "<h3><div class=\"subject\">" . $liveboard["station"] . "</div> <div class=\"distance\">" . $liveboard["stationinfo"]["distance"] . "</div></h3>";
	  echo "<ul>";
	  $i = 0;
	  foreach($liveboard["departures"]["departure"] as $dep){
	       if(!is_null(calculateWaitingTime($dep["time"]))){
		    $dur = calculateWaitingTime($dep["time"]);
//li class: item0/item1 - div classes: eta, delay, station
		    echo "<li class=\"item". $i%2 ."\"><div class=\"eta\">". $dur . "</div> <div class=\"delay\">". formatDelay($dep["delay"]) . "</div> <div class=\"station\">" . $dep["station"] . "</div></li>";
	       }
	       $i++;
	  }
	  echo "</ul>";
     }
}
?>
