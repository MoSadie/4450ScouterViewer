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
if ($action == "getmatches") {
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
	$json = json_encode(array("average"=>$average, "variance"=>$reliability));
	echo $json;
	exit();
}
if($action == "getinfo"){
	$statement = $db->prepare("SELECT * FROM `pit_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	$team_info = array();
	if ($success) {
		$team_info = $statement->fetch(PDO::FETCH_ASSOC);
	}
	$statement->closeCursor();
	echo json_encode($team_info, JSON_PRETTY_PRINT);
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
	return array_sum($scores) / sizeof($scores);
}

function getReliability(&$scores) {
	return Math::standardDeviation($scores);
}
?>