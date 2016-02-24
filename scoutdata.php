<?php
/**
 * Created by Caleb Milligan on 2/23/2016.
 */
include_once "MyPDO.php";
include_once "Math.php";
if (!isset($_GET["action"]) || !isset($_GET["team"])) {
	http_response_code(400);
	exit();
}
$action = $_GET["action"];
$team = $_GET["team"];
$db = new MyPDO();
if ($action == "getinfo") {
	/*
	$statement = $db->prepare("SELECT * FROM `stand_scouting` WHERE `team_number`=:team_number ORDER BY `match_number` ASC");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	$matches = array();
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			array_push($matches, $statement->fetch(PDO::FETCH_ASSOC));
		}
	}
	$statement->closeCursor();
	echo json_encode($matches);
	exit();
	*/
	$scores = getScores($team);
	$average = getAverageScore($scores);
	$reliability = getReliability($scores);
	$data = array("average" => $average, "variance" => $reliability);

	$statement = $db->prepare("SELECT `robot_description`, `team_name` FROM `pit_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	if ($success && $statement->rowCount() > 0) {
		$data = array_merge($data, $statement->fetch(PDO::FETCH_ASSOC));
	}
	$statement = $db->prepare("SELECT `match_number`, `autonomous_behavior` FROM `stand_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	$auto_behavior = array();
	if ($success && $statement->rowCount() > 0) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			array_push($auto_behavior, $statement->fetch(PDO::FETCH_ASSOC));
		}
	}
	$data["auto_behavior"] = $auto_behavior;

	$json = json_encode($data);
	echo $json;
	exit();
}
if ($action == "getmatches") {
	$matches = array();
	$raw_query = "SELECT * FROM `stand_scouting` ORDER BY `team_number` ASC, `match_number`";
	$statement = $db->prepare($raw_query);
	$success = $statement->execute();
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			array_push($matches, $statement->fetch(PDO::FETCH_ASSOC));
		}
	}
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
}

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
	$statement->closeCursor();
	return $scores;
}

function getAverageScore(&$scores) {
	return array_sum($scores) / sizeof($scores);
}

function getReliability(&$scores) {
	return Math::standardDeviation($scores);
}


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

function getEndgameName($endgame) {
	switch ((int)$endgame) {
		case 0:
			return "Parked on ramp";
		case 1:
			return "Climbed tower";
		default:
			return "N/A";
	}
}

?>