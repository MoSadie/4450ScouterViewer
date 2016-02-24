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
	$imgname = "uploaded/images/ROBOT_" . $team . ".JPG";
	if(!file_exists($imgname)){
		$imgname = "images/FIRST-Logo.png";
	}
	$data["image"] = $imgname;
	$json = json_encode($data);
	http_response_code(200);
	header('Content-Type: application/json;charset=utf-8');
	echo $json;
	exit();
}
if ($action == "getmatches") {
	$matches = array();
	$statement = $db->prepare("SELECT * FROM `stand_scouting` WHERE `team_number`=:team_number ORDER BY `team_number` ASC, `match_number`");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			array_push($matches, $statement->fetch(PDO::FETCH_ASSOC));
		}
	}
	$data = "";
	foreach ($matches as $match) {
		$data .= "<tr><td>";
		$data .= $match["team_number"];
		$data .= "</td><td>";
		$data .= $match["match_number"];
		$data .= "</td><td>";
		$data .= $match["team_name"];
		$data .= "</td><td>";
		$data .= getSpeedName($match["pickup_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["portcullis_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["chival_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["moat_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["ramparts_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["drawbridge_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["sally_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["rock_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["rough_speed"]);
		$data .= "</td><td>";
		$data .= getSpeedName($match["low_speed"]);
		$data .= "</td><td>";
		$data .= $match["low_goals"];
		$data .= "</td><td>";
		$data .= $match["high_goals"];
		$data .= "</td><td>";
		$data .= getEndgameName($match["endgame"]);
		$data .= "</td></tr>";
	}
	http_response_code(200);
	header('Content-Type: text/html;charset=utf-8');
	echo $data;
	exit();
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
	if(sizeof($scores) < 1){
		return 0;
	}
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