<?php
include("config.php");
//var_dump($content["NMBS"]);
foreach($content["NMBS"] as $liveboard){
     echo $liveboard["station"] . " " ."<br/>";
}
?>

