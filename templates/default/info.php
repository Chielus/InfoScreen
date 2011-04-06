<?php
include("config.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>iRail.be</title>
		<link rel="stylesheet" type="text/css" href="/templates/default/css/main.css"/>

		<!-- refresh tag for now -->
		<meta http-equiv="refresh" content="60" >

	</head>
		<!-- JS refresh method, onload -->
		<body onload="javascript:setTimeout(\"location.reload(true);\",<?=$timeout ?>);">
		<!-- include headpanel.php into this page -->
		<div class="headpanel"><? include("templates/default/headpanel.php"); ?></div>
		<!-- Include the two panels into this page -->
		<div class="panel"><h2>NMBS</h2><? $panel = "NMBS" ; include("templates/default/livepanel.php"); ?></div>
		<div class="panel"><h2>MIVB</h2><? $panel = "MIVB" ; include("templates/default/livepanel.php"); ?></div>
		<div class="footer">Data used from api.iRail.be - Resistance is futile</div>
	</body>
</html>
