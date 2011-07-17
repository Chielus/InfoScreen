<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="refresh" content="1800"><!--Refresh the page each half hour to update the source code-->
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="shortcut icon" href="/templates/FlatTurtle/favicon.ico">
	<link rel="apple-touch-icon" href="/templates/FlatTurtle/apple-touch-icon.png">
	<link rel="stylesheet" href="/templates/FlatTurtle/css/style.css?v=2">
</head>
<body>
	<div id="container">
		<div id="app">Loading&hellip;</div>

		<footer>

		</footer>
	</div>

	<script type="text/x-jqote-template" id="App-template">
    <![CDATA[
		<div class="App">
			<header>
				<div class="Clock"></div>
				<% if (this.companyLogo) { %>
					<img src="<%= this.companyLogo %>">
				<% } %>
			</header>
			<div id="main" role="main">
				<% if (this.messageOfTheDay) { %>
					<div class="messageOfTheDay">
						<%= this.messageOfTheDay %>
					</div>
				<% } %>
				<div class="SystemPane nmbs"></div>
				<div class="SystemPane mivb"></div>
			</div>
		</div>
    ]]>
	</script>

	<script type="text/x-jqote-template" id="Clock-template">
    <![CDATA[
		<div class="Clock">
			<%= this.time() %>
		</div>
    ]]>
	</script>

	<script type="text/x-jqote-template" id="SystemPane-template">
    <![CDATA[
		<div class="SystemPane">
			<div>
				<h2>
					<%! this.name %> 
					<small>departures</small>
					<span class="credits">powered by <em>iRail</em></span>
				</h2>
				<div class="liveboardsTicker">
				</div>
				<div class="liveboards">
				</div>
			</div>
		</div>
    ]]>
	</script>
	
	<script type="text/x-jqote-template" id="LiveBoardsTicker-template">
    <![CDATA[
		<div class="liveboardsTicker">
			<ol>
				<% for (var i=0; i<this.liveBoardsCount;i++) { %>
					<li<%= i==this.currentLiveBoardIndex ? ' class="current"' : '' %>>&nbsp;</li>
				<% } %>
			</ol>
		</div>
    ]]>
	</script>
	
	<script type="text/x-jqote-template" id="LiveBoard-template">
    <![CDATA[
		<div class="LiveBoard">
			<h3><%! this.name %> <small>(<%! this.distanceMeters %>m / <%! this.distanceWalking %>min walk)</small></h3>
			<table>
				<tbody>
				</tbody>
			</table>
		</div>
    ]]>
	</script>

	<script type="text/x-jqote-template" id="LiveBoardRow-template">
    <![CDATA[
		<tr class="LiveBoardRow<%= this.cancelled ? ' cancelled' : '' %>">
			<td<%= this.delay ? ' class="delayed"' : '' %>>
				<span>
					<%= this.time %> 
					<% if (this.delay) { %>
						<em><%= this.timeWithDelay %></em>
					<% } %>
				</span>
			</td>
			<% if (this.system=="nmbs") { %>
				<td><span class="lineCode type"><span><%= this.type %></span></span></td>
				<td><%= this.destination %></td>
				<td><span class="lineCode platform"><span><%= this.cancelled ? "-" : this.platform || "-" %></span></span></td>
			<% } else if (this.system=="mivb") { %>
				<td><span class="lineCode line line<%= this.line %>"><span><%= this.line %></span></span></td>
				<td><%= this.destination %></td>
			<% } %>
		</tr>
    ]]>
	</script>

	<script type="text/x-jqote-template" id="EmptyDiv-template">
    <![CDATA[
		<div class="Empty"></div>
    ]]>
	</script>

	<script type="text/x-jqote-template" id="EmptyTr-template">
    <![CDATA[
		<tr class="Empty"></tr>
    ]]>
	</script>
		
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="/templates/FlatTurtle/js/libs/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
	<script src="/templates/FlatTurtle/js/plugins.js"></script>
	<script src="/templates/FlatTurtle/js/libs/jqote2.js"></script>
	<script src="/templates/FlatTurtle/js/libs/irail.js"></script>
	<script>
	window.LiveBoardConfig = {
	"messageOfTheDay" : "",
      //	"messageOfTheDay" : "Message of the day is this long sentence. So please read it. <br />Notice that there is still some of the timetable visible below.",
		"rowsToShow" : 10,
		"refreshLiveboardsInterval" : 60,
		"cycleLiveboardsInterval" : 10,
		"companyLogo" : "/templates/FlatTurtle/img/logo.png",
		"liveboards" : {
			"nmbs" : [
			     <?php
			     foreach($content["NMBS"] as $s){
			echo '	  
				{
					"name" : "'. $s["name"].'",
					"distanceMeters" : "'.$s["distance"].'",
					"distanceWalking" : "'. $s["walking"].'"
				},';
			     }
?>
			],
			"mivb" : [
			     <?php
			     foreach($content["MIVB"] as $s){
			echo '	 
				{
					
					"name" : "'. $s["name"].'",
					"distanceMeters" : "'.$s["distance"].'",
					"distanceWalking" : "'. $s["walking"].'"
				},';
				 
			     }
?>			]
		}
	};
	</script>
	<script src="/templates/FlatTurtle/js/app.js"></script>
</body>
</html>
