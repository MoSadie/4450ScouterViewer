<?php
include_once "MyPDO.php";
include_once "Math.php";
/**
 * Created by Caleb Milligan on 2/1/2016.
 */
function getScores($team_number) {
	$db = new MyPDO();
	$statement = $db->prepare("SELECT `low_goals`, `high_goals` FROM `stand_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team_number, PDO::PARAM_INT);
	$success = $statement->execute();
	$scores = array();
	if (!$success) {
		return $scores;
	}
	for ($i = 0; $i < $statement->rowCount(); $i++) {
		$data = $statement->fetch(PDO::FETCH_ASSOC);
		array_push($scores, $data["low_goals"] + $data["high_goals"]);
	}
	return $scores;
}

function getAverageScore(&$scores) {
	return array_sum($scores) / sizeof($scores);
}

function getReliability(&$scores) {
	return Math::standardDeviation($scores);
}

$team_number = -1;
if (isset($_GET["team"])) {
	$team_number = (int)$_GET["team"];
}
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
						?>
					</datalist>
					<form>
						<input type="number" title="Team #" placeholder="Team #" list="team_numbers"/>
						<input type="button" class="btn-sm" value="Go" title="Go">
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
							<h4><span>Average score: </span>23</h4>
						</div>
						<div class="col-lg-6">
							<h4><span>Reliability: </span>4</h4>
						</div>
						<div class="col-lg-6">
							<p><h4>Autonomous behavior: </h4>We did some crazy cool stuff.</p>
						</div>
						<div class="col-lg-6">
							<p><h4>Robot description: </h4>It's got wheels, and some pneumatics.</p>
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
						<input type="number" title="Match #" placeholder="Match #" list="match_numbers"/>
						<input type="button" class="btn-sm" value="Go" title="Go">
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
	</body>
</html>
