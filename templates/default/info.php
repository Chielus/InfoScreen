<?php
   include("config.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>iRail.be</title>
    <link rel="stylesheet" type="text/css" href="/templates/default/css/main.css"/>
    <script href="/templates/default/js/main.js"></script>
    <script>
      <?php
	 var_dump($content);
	 ?>
    </script>
  </head>
  <body onload="panelNMBS(); panelMIVB(); reloadPage();">
    <!-- include headpanel.php into this page -->
    <div class="headpanel">
      <?php
	 include("templates/default/headpanel.php");
	 ?>
    </div>
    <!-- Include the two panels into this page -->
    <div id="panelNMBS" class="panel">
      <h2>NMBS</h2>
      <?php
	$panel = "NMBS" ; include("templates/default/livepanel.php"); 
	 ?>
    </div>
    <div id="panelMIVB" class="panel">
      <h2>MIVB</h2>
      <?php 
	 $panel = "MIVB" ;
	 include("templates/default/livepanel.php"); 
	?>
    </div>
  </body>
</html>
