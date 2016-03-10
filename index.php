<?php
include_once "MyPDO.php";
/**
 * Created by Caleb Milligan on 2/1/2016.
 */
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Scouter Data Viewer</title>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/scouting.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/scouting.js"></script>
		<?php
		try {
			$db = new MyPDO();
		}
		catch (Exception $e) {
			error_log($e->__toString());
			header("Location: ./errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
			exit();
		}
		?>
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
						<input id="submit_team" type="submit" class="btn-sm" value="Go" title="Go"
							   onclick="getScores()">
					</form>
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
					<div class="row">
						<div class="col-lg-6">
							<h4 id="team_name"><span>Team Name: </span></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<h4><span>Average goals: </span><span id="average_score"></span></h4>
						</div>
						<div class="col-lg-6">
							<h4><span>Reliability: </span><span id="reliability"></span></h4>
						</div>
						<div class="col-lg-6">
							<div><h4>Robot description: </h4><span id="robot_description"></span></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Autonomous Notes: </h4><span id="auto_notes"></span></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Drive Base Notes: </h4><span id="drive_base_notes"></span></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Pickup Notes: </h4><span id="pickup_notes"></span></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Shooting Notes: </h4><span id="shooting_notes"></span></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Defense Notes: </h4><span id="defense_notes"></span></div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="table-responsive">
				<table id="data_table" class="table">
					<thead>
						<tr>
							<td>Team #</td>
							<td>Match #</td>
							<td>Scouter Name</td>
							<td>No-Show</td>
							<td>D.O.F</td>
							<td>Autonomous Behavior</td>
							<td>Defended</td>
							<td>Pickup Speed</td>
							<td>Portcullis Crosses</td>
							<td>Portcullis Speed</td>
							<td>Chival de Frise Crosses</td>
							<td>Chival de Frise Speed</td>
							<td>Moat Crosses</td>
							<td>Moat Speed</td>
							<td>Ramparts Crosses</td>
							<td>Ramparts Speed</td>
							<td>Drawbridge Crosses</td>
							<td>Drawbridge Speed</td>
							<td>Sally Port Crosses</td>
							<td>Sally Port Speed</td>
							<td>Rock Wall Crosses</td>
							<td>Rock Wall Speed</td>
							<td>Rough Terrain Crosses</td>
							<td>Rough Terrain Speed</td>
							<td>Low Bar Crosses</td>
							<td>Low Bar Speed</td>
							<td>Low Goals</td>
							<td>High Goals</td>
							<td>Endgame</td>
							<td>Notes</td>
						</tr>
					</thead>
					<tbody id="match_data">

					</tbody>
				</table>
			</div>
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
