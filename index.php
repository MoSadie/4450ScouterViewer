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
	</head>
	<body>
		<header>
			<h1>Olympia Robotics Federation</h1>
			<h2>2016 FIRST Stronghold Scouter</h2>
		</header>
		<hr>
		<div class="container">
			<div class="row">
				<div class="team_query">
					<h3>Search by team:</h3>
					<form onsubmit="getScores();return false">
						<input id="team_number" type="number" title="Team #" placeholder="Team #" min="0"
							   list="team_numbers"/>
						<input id="submit_team" type="submit" class="btn-sm" value="Go" title="Go">
					</form>
					<br>
					<h3>Or <a href="sort">view a sortable table</a></h3>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-7">
					<div class="row">
						<div class="media">
							<img id="robot_image" class="img-responsive img-rounded center-block"
								 src="images/ORF_Logo.png">
						</div>
					</div>
				</div>
				<div class="col-xs-5 william">
					<div class="container">
						<div class="row">
							<div class="col-lg-6">
								<h4><span>Team Name: </span><span id="team_name"></span></h4>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><span>Average goals: </span><span id="average_score"></span></h4>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><span>Reliability: </span><span id="reliability"></span></h4>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Robot description: </h4><span id="robot_description"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Autonomous Notes: </h4><span id="auto_notes"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Drive Base Notes: </h4><span id="drive_base_notes"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Pickup Notes: </h4><span id="pickup_notes"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Shooting Notes: </h4><span id="shooting_notes"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<div><h4>Defense Notes: </h4><span id="defense_notes"></span></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6">
								<h4><a id="graph_link" href="graph">View Graphic Data</a></h4>
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
							<th>Match #</th>
							<th>Team #</th>
							<th>Scouter Name</th>
							<th>No-Show</th>
							<th>D.o.F.</th>
							<th>Autonomous Behavior</th>
							<th>Defended</th>
							<th>Pickup Speed</th>
							<th>Portcullis Crosses</th>
							<th>Portcullis Speed</th>
							<th>Chival de Frise Crosses</th>
							<th>Chival de Frise Speed</th>
							<th>Moat Crosses</th>
							<th>Moat Speed</th>
							<th>Ramparts Crosses</th>
							<th>Ramparts Speed</th>
							<th>Drawbridge Crosses</th>
							<th>Drawbridge Speed</th>
							<th>Sally Port Crosses</th>
							<th>Sally Port Speed</th>
							<th>Rock Wall Crosses</th>
							<th>Rock Wall Speed</th>
							<th>Rough Terrain Crosses</th>
							<th>Rough Terrain Speed</th>
							<th>Low Bar Crosses</th>
							<th>Low Bar Speed</th>
							<th>Low Goals</th>
							<th>High Goals</th>
							<th>Endgame</th>
							<th>Total Points</th>
							<th>Notes</th>
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
	</body>
</html>
