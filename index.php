<?php
include_once "MyPDO.php";
/**
 * Created by Caleb Milligan on 2/1/2016.
 */

function getSpeedName($speed) {
	switch ((int)$speed) {
		case 0:
			return "Slow";
		case 1:
			return "Medium";
		case 2:
			return "Fast";
		default:
			return "N/A";
	}
}

function getEndgameName($endgame){
	switch((int)$endgame){
		case 0:
			return "Parked on ramp";
		case 1:
			return "Climbed tower";
		default:
			return "N/A";
	}
}

$db = new MyPDO();
$matches = array();
$teams = array();
$raw_query = "SELECT * FROM `stand_scouting` ORDER BY `team_number` ASC, `match_number`";
$statement = $db->prepare($raw_query);
$success = $statement->execute();
if ($success) {
	for ($i = 0; $i < $statement->rowCount(); $i++) {
		array_push($matches, $statement->fetch(PDO::FETCH_ASSOC));
	}
}
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
						<input id="submit_team" type="button" class="btn-sm" value="Go" title="Go" onclick="getScores()">
					</form>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-7">
					<div class="media">
						<img class="center-block" src="images/yoglo.jpg">
					</div>
				</div>
				<div class="col-xs-5 well">
					<div class="row">
						<div class="col-lg-6">
							<h4><span>Team Name: </span>ORF</h4>
						</div>

					</div>
					<div class="row">
						<div class="col-lg-6">
							<h4 id="average_score"><span>Average score: </span>23</h4>
						</div>
						<div class="col-lg-6">
							<h4 id="reliability"><span>Reliability: </span>4</h4>
						</div>
						<div class="col-lg-6">
							<p id="autonomous_behavior"><h4>Autonomous behavior: </h4>We did some crazy cool stuff.</p>
						</div>
						<div class="col-lg-6">
							<p id="robot_description"><h4>Robot description: </h4>It's got wheels, and some pneumatics.</p>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-7 col-sm-12">
					<h2>Obstacles</h2>
					<datalist id="match_numbers">
						<option label="1" value="1"></option>
						<option label="2" value="2"></option>
						<option label="4" value="4"></option>
					</datalist>
					<form>
						<input id="match_number" type="number" title="Match #" placeholder="Match #" list="match_numbers"/>
						<input id="submit_match" type="button" class="btn-sm" value="Go" title="Go">
					</form>
					<hr>
				</div>
				<div id="obstacle_data" class="col-lg-7 col-sm-12">
					<div class="container">
						<div class="row">
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Portcullis</span>
											</h4>
										</div>
										<div class="col-xs-4">
											<h4>Slow</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span
													class="label label-default">Chival de Frise</span></h4>
										</div>
										<div class="col-xs-4">
											<h4>Medium</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Moat</span></h4>
										</div>
										<div class="col-xs-4">
											<h4>Fast</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Ramparts</span>
											</h4>
										</div>
										<div class="col-xs-4">
											<h4>Fast</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Drawbridge</span>
											</h4>
										</div>
										<div class="col-xs-4">
											<h4>N/A</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Sally Port</span>
											</h4>
										</div>
										<div class="col-xs-4">
											<h4>N/A</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Rock Wall</span>
											</h4>
										</div>
										<div class="col-xs-4">
											<h4>Medium</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span
													class="label label-default">Rough Terrain</span></h4>
										</div>
										<div class="col-xs-4">
											<h4>Slow</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="container">
									<div class="row">
										<div class="col-xs-8">
											<h4 class="text-right"><span class="label label-default">Low Bar</span></h4>
										</div>
										<div class="col-xs-4">
											<h4>Slow</h4>
										</div>
									</div>
								</div>
							</div>
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
				<tbody>
					<?php
					foreach ($matches as $match) {
						echo "<tr><td>";
						echo $match["team_number"];
						echo "</td><td>";
						echo $match["match_number"];
						echo "</td><td>";
						echo $match["team_name"];
						echo "</td><td>";
						echo getSpeedName($match["pickup_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["portcullis_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["chival_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["moat_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["ramparts_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["drawbridge_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["sally_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["rock_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["rough_speed"]);
						echo "</td><td>";
						echo getSpeedName($match["low_speed"]);
						echo "</td><td>";
						echo $match["low_goals"];
						echo "</td><td>";
						echo $match["high_goals"];
						echo "</td><td>";
						echo getEndgameName($match["endgame"]);
						echo "</td></tr>";
					}
					?>
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
