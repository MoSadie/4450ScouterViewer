<?php
include_once "MyPDO.php";
/**
 * Created by Caleb Milligan on 2/1/2016.
 */
if (isset($_FILES['userfile'])) {
	for ($i = 0; $i < sizeof($_FILES['userfile']['name']); $i++) {
		$file_name = strtoupper($_FILES['userfile']['name'][$i]);
		$file_tmp = $_FILES['userfile']['tmp_name'][$i];
		$file_type = strtolower($_FILES['userfile']['type'][$i]);
		echo "$file_type</hr>";
		if($file_type != "image/jpeg"){
			continue;
		}
		$file_name = pathinfo($file_name, PATHINFO_FILENAME);
		echo "$file_name</hr>";
		move_uploaded_file($file_tmp, "uploaded/images/$file_name.JPG");
	}
}
$db = new MyPDO();
$teams = array();
$raw_query = "SELECT * FROM `pit_scouting` ORDER BY `team_number` ASC";
$statement = $db->prepare($raw_query);
$success = $statement->execute();
if ($success) {
	for ($i = 0; $i < $statement->rowCount(); $i++) {
		array_push($teams, $statement->fetch(PDO::FETCH_ASSOC));
	}
}
$statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Scouter Data Viewer</title>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/scouting.css">
	</head>
	<body>
		<div class="container">
			<hr>
			<div class="row">
				<div class="col-xs-6">
					<datalist id="team_numbers">
						<?php
						$db = new MyPDO();
						$statement = $db->prepare("SELECT DISTINCT(`team_number`) FROM `stand_scouting` ORDER BY `team_number` ASC");
						$statement->execute();
						for ($i = 0; $i < $statement->rowCount(); $i++) {
							$temp_team_number = $statement->fetchColumn(0);
							echo "<option label='$temp_team_number' value='$temp_team_number'></option>";
						}
						$statement->closeCursor();
						?>
					</datalist>
					<form>
						<input id="team_number" type="number" title="Team #" placeholder="Team #" list="team_numbers"/>
						<input id="submit_team" type="button" class="btn-sm" value="Go" title="Go"
							   onclick="getScores()">
					</form>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-7">
					<div class="row">
						<div class="media">
							<img id="robot_image" class="center-block" src="images/FIRST-Logo.png">
						</div>
						<form action="" method="post" enctype="multipart/form-data">
							<h3>Upload Images:</h3>
							<input multiple="" name="userfile[]" type="file" accept="image/jpeg"/><br/>
							<input type="submit" value="Upload"/>
						</form>
					</div>
				</div>
				<div class="col-xs-5 well">
					<div class="row">
						<div class="col-lg-6">
							<h4 id="team_name"><span>Team Name: </span></h4>
						</div>
						<div class="col-lg-6">
							<h4 id="scouter_name"><span>Scouter: </span></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<h4 id="average_score"><span>Average score: </span></h4>
						</div>
						<div class="col-lg-6">
							<h4 id="reliability"><span>Reliability: </span></h4>
						</div>
						<div class="col-lg-6">
							<div id="robot_description"><h4>Robot description: </h4></div>
						</div>
						<div class="col-lg-6">
							<div><h4>Autonomous behavior: </h4>
								<ol class="scrolling_list" id="autonomous_behavior">

								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<td>Team #</td>
							<td>Match #</td>
							<td>Scouter Name</td>
							<td>Pickup Speed</td>
							<td>Portcullis Speed</td>
							<td>Chival de Frise Speed</td>
							<td>Moat Speed</td>
							<td>Ramparts Speed</td>
							<td>Drawbridge Speed</td>
							<td>Sally Port Speed</td>
							<td>Rock Wall Speed</td>
							<td>Rough Terrain Speed</td>
							<td>Low Bar Speed</td>
							<td>Low Goals</td>
							<td>High Goals</td>
							<td>Endgame</td>
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
			</footer>
			<script src="js/jquery-1.11.3.min.js"></script>
			<script src="js/bootstrap.js"></script>
			<script src="js/scouting.js"></script>
	</body>
</html>
