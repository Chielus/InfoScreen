<?php
include("config.php");
//var_dump($content["NMBS"]);
foreach($content[$panel] as $liveboard){
     echo $liveboard["station"] . " " ."<br/>";
}
?>

