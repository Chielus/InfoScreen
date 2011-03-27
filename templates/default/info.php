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
<body onload="javascript:setTimeout(\"location.reload(true);\",<?=$timeout ?>);">
<div class="headpanel"><? include("templates/default/headpanel.php"); ?></div>
<div class="panel"><h1>NMBS</h1><? $panel = "NMBS" ; include("templates/default/livepanel.php"); ?></div>
<div class="panel"><h1>MIVB</h1><? $panel = "MIVB" ; include("templates/default/livepanel.php"); ?></div>
</body>
</html>
