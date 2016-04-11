<?php
include_once "MyPDO.php";
include_once "Utils.php";
/**
 * Created by Caleb Milligan on 2/17/2016.
 */
// We need this to check validity
if (!isset($_SERVER["HTTP_USER_AGENT"])) {
	echo file_get_contents("unauthorized.html");
	http_response_code(401);
	exit();
}
// Not us? Then we don't want your data!
$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (!preg_match("/4450Scouting\\/*.+/", $user_agent)) {
	echo file_get_contents("unauthorized.html");
	http_response_code(401);
	exit();
}
// Read JSON from input
$data = file_get_contents("php://input");
// No data? No good!
if (!$data) {
	http_response_code(400);
	exit();
}
// Decode JSON into an associative array
$bundle = json_decode($data, true);
// Invalid JSON or missing data? Definitely no good!
if (!$bundle || !isset($bundle["matches"]) || !isset($bundle["teams"])) {
	http_response_code(400);
	exit();
}
$matches = $bundle["matches"];
$teams = $bundle["teams"];
$db = new MyPDO();
// Save each team from pit scouting
foreach ($teams as $team) {
	// Ensure there are no duplicate entries
	$statement = $db->prepare("DELETE FROM `pit_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team["team_number"]);
	$statement->execute();
	// Generate and exec insert
	Utils::generateInsert($db, "pit_scouting", $team);
}
// Save each match from stand scouting
foreach ($matches as $match) {
	// Ensure there are no duplicate entries
	$statement = $db->prepare("DELETE FROM `stand_scouting` WHERE `team_number`=:team_number AND `match_number`=:match_number");
	$statement->bindParam(":team_number", $match["team_number"]);
	$statement->bindParam(":match_number", $match["match_number"]);
	$statement->execute();
	// Generate and exec insert
	Utils::generateInsert($db, "stand_scouting", $match);
	$total_points = [];
	$total_points["match_number"] = $match["match_number"];
	$total_points["team_number"] = $match["team_number"];
	$total_points["total_points"] = Utils::calcTotalScore($match);
	$statement = $db->prepare("DELETE FROM `total_points` WHERE `team_number`=:team_number AND `match_number`=:match_number");
	$statement->bindParam(":team_number", $match["team_number"]);
	$statement->bindParam(":match_number", $match["match_number"]);
	$statement->execute();
	Utils::generateInsert($db, "total_points", $total_points);
}
