<?php
/**
 * Created by Caleb Milligan on 2/1/2016.
 */
include_once "MyPDO.php";
try {
	$db = new MyPDO();
}
catch (Exception $e) {
	error_log($e->__toString());
	header("Location: ./errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
	exit();
}
?>
<!DOCTYPE html>
<!--
  -- Created by Caleb Milligan on 2/1/2016.
  -->
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>April Fool!</title>
		<title>Scouter Data Viewer</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/scouting.css">
		<link rel="stylesheet" type="text/css" href="css/tablepager.css">
		<link rel="stylesheet" type="text/css" href="css/tablehighlight.css">
		<script type="application/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="application/javascript" src="js/bootstrap.js"></script>
		<script type="application/javascript" src="js/tablepager.js"></script>
		<script type="application/javascript" src="js/scouting.js"></script>
		<script type="application/javascript" src="js/tablehighlight.js"></script>
		<script type="application/javascript" src="js/doge.js"></script>
		<style>body {
				background: #0F0F0F url("images/da_beckgrond.jpg") repeat !important;
			}</style>
	</head>
	<body>
		<header>
			<h1>olympia robotics federation</h1>
			<h2>2016 first strongdoge scouter</h2>
		</header>
		<hr>
		<div class="container">
			<div class="row">
				<div class="team_query">
					<h3>very search much query:</h3>
					<form onsubmit="getScores();return false">
						<input id="team_number" type="number" title="Team #" placeholder="tem #" min="0"
							   list="team_numbers"/>
						<input id="submit_team" type="submit" class="btn-sm" value="Go" title="Go">
					</form>
					<br>
					<h3><a href="sort">so sort many entries</a></h3>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-7">
					<div class="row">
						<div class="media">
							<img id="robot_image" class="img-responsive img-rounded center-block"
								 src="images/tem_logoe.png">
						</div>
					</div>
				</div>
				<div class="col-xs-5 william">
					<div class="container">
						<div class="row">
							<div class="col-lg-6">
								<h4><span>tem name: </span><span id="team_name"></span></h4>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><span>avg in-holes: </span><span id="average_score"></span></h4>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><span>relibility: </span><span id="reliability"></span></h4>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>robot lookie: </h4><span id="robot_description"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>self-move thing: </h4><span id="auto_notes"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>scooter bit: </h4><span id="drive_base_notes"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>grabby grab: </h4><span id="pickup_notes"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>pew pew bit: </h4><span id="shooting_notes"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>defendy things: </h4><span id="defense_notes"></span></div>
							</div>
						</div>
						<br>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><a id="graph_link" href="graph">many graphs</a></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<form onsubmit="return false;">
				<img src="images/first.png" onclick="Pager.getPager('.selectable').firstPage()">
				<img src="images/prev.png" onclick="Pager.getPager('.selectable').prevPage()">
				<input title="Page" class="selectable-paginator_selector" type="text"
					   onchange="Pager.getPager('.selectable').inputPage(this)">
				<img src="images/next.png" onclick="Pager.getPager('.selectable').nextPage()">
				<img src="images/last.png" onclick="Pager.getPager('.selectable').lastPage()">
			</form>
			<div class="table-responsive">
				<table id="data_table" class="table selectable">
					<thead>
						<tr>
							<th>metch #</th>
							<th>tem #</th>
							<th>scooter name</th>
							<th>left us alone ;n;</th>
							<th>ripperoni</th>
							<th>self-drivey</th>
							<th>didefend</th>
							<th>grabby speed</th>
							<th>portcullis-crossings</th>
							<th>portcullis-zoom</th>
							<th>teeter-totties</th>
							<th>teeter-zoom</th>
							<th>moat-swimmings</th>
							<th>moat-zoom</th>
							<th>ramports-crossings</th>
							<th>ramports-zoom</th>
							<th>scribblebridge-crossings</th>
							<th>scribblebridge-zoom</th>
							<th>sally's-crossings</th>
							<th>sally's-zoom</th>
							<th>mountain-crossings</th>
							<th>mountain-zoom</th>
							<th>rumble-crossings</th>
							<th>rumble-zoom</th>
							<th>limbo-crossings</th>
							<th>limbo-zoom</th>
							<th>short in-holes</th>
							<th>tall in-holes</th>
							<th>finisher</th>
							<th>lotsa points</th>
							<th>nots</th>
						</tr>
					</thead>
					<tbody id="match_data">
					
					</tbody>
				</table>
			</div>
			<form onsubmit="return false;">
				<img src="images/first.png" onclick="Pager.getPager('.selectable').firstPage()">
				<img src="images/prev.png" onclick="Pager.getPager('.selectable').prevPage()">
				<input title="Page" class="selectable-paginator_selector" type="text"
					   onchange="Pager.getPager('.selectable').inputPage(this)">
				<img src="images/next.png" onclick="Pager.getPager('.selectable').nextPage()">
				<img src="images/last.png" onclick="Pager.getPager('.selectable').lastPage()">
			</form>
			<footer class="text-center">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<p>Copyright © Olympia Robotics Federation. All rights reserved.</p>
						</div>
					</div>
				</div>
				<datalist id="team_numbers">
					<?php
					try {
						// Select distinct team numbers from all available tables
						$statement = $db->prepare("SELECT DISTINCT(`team_number`) FROM `stand_scouting` UNION DISTINCT SELECT DISTINCT(`team_number`) FROM `pit_scouting` ORDER BY `team_number` ASC;");
						$statement->execute();
						// Add each team number to the datalist
						for ($i = 0; $i < $statement->rowCount(); $i++) {
							$temp_team_number = $statement->fetchColumn(0);
							echo "<option label='$temp_team_number' value='$temp_team_number'></option>";
						}
						$statement->closeCursor();
					}
					catch (Exception $e) {
						error_log($e->__toString());
						header("Location: errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
						exit();
					}
					?>
				</datalist>
			</footer>
		</div>
		<script type="text/javascript">
			initDoge("images/doge.png");
		</script>
	</body>
</html>
