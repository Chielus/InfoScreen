<?php
include("config.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>iRail.be</title>
		<link rel="stylesheet" type="text/css" href="/templates/default/css/main.css"/>

		<!-- refresh tag for now -->
        <meta http-equiv="refresh" content="<?=$timeout ?>" >
		<script type="text/javascript">
		var viewportwidth;
		var viewportheight;
		var counterNMBS = 1;
        var counterMIVB = 1;
        var timeOut = <? echo "\"" . $timeout . "\"" ?>;


		if (typeof window.innerWidth != 'undefined')
		{
			viewportwidth = window.innerWidth,
			viewportheight = window.innerHeight
		}

		
        function panelNMBS(){
			var holder = document.getElementById("panelNMBS");
			var childNodeArray = holder.childNodes;
			var l = childNodeArray.length;
			var time = timeOut/ l;
			childNodeArray.item(1).style.visibility = 'visible'; 
			for(var i = 1; i < l; i++){
			     if(i == counterNMBS){
			         childNodeArray.item(i).style.visibility = 'visible'; 
			     }else{
			         childNodeArray.item(i).style.visibility = 'hidden'; 
			     }
			}
            counterNMBS++;
            if(counterNMBS == l){
			     counterNMBS = 1;
            }			
			setTimeout("panelNMBS()", time)
        }
        function panelMIVB(){
			var holder = document.getElementById("panelMIVB");
			var childNodeArray = holder.childNodes;
			var l = childNodeArray.length;
			var time = timeOut / l;
			childNodeArray.item(1).style.visibility = 'visible'; 
			for(var i = 1; i < l; i++){
			     if(i == counterMIVB){
			         childNodeArray.item(i).style.visibility = 'visible'; 
			     }else{
			         childNodeArray.item(i).style.visibility = 'hidden'; 
			     }
			}
            counterMIVB++;
            if(counterMIVB == l){
			     counterMIVB = 1;
            }			
			setTimeout("panelMIVB()", time)
        }
        
        function reloadPage(){
            setTimeout("location.reload(true);", timeOut);
        }
		</script>
	</head>

	<body onload="panelNMBS(); panelMIVB(); reloadPage();">
		<!-- include headpanel.php into this page -->
		<div class="headpanel"><? include("templates/default/headpanel.php"); ?></div>
		<!-- Include the two panels into this page -->
		<div id="panelNMBS" class="panel"><h2>NMBS</h2><? $panel = "NMBS" ; include("templates/default/livepanel.php"); ?></div>
		<div id="panelMIVB" class="panel"><h2>MIVB</h2><? $panel = "MIVB" ; include("templates/default/livepanel.php"); ?></div>
		<!-- <div class="footer">Data used from api.iRail.be - Resistance is futile</div> -->
	</body>
</html>
