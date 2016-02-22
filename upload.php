<?php
include_once "MyPDO.php";
/**
 * Created by Caleb Milligan on 2/17/2016.
 */
// We need this to check validity
if (!isset($_SERVER["HTTP_USER_AGENT"])) {
	http_response_code(401);
	exit();
}
// Not us? Then we don't want your data!
$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (!preg_match("/4450Scouting\\/*.+/", $user_agent)) {
	http_response_code(401);
	exit();
}
// Ensure the image directory is created
$image_dir = "uploaded/images";
if (!file_exists($image_dir)) {
	mkdir($image_dir, 0777, true);
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
	generateInsert($db, "pit_scouting", $team);
}
// Save each match from stand scouting
foreach ($matches as $match) {
	// Ensure there are no duplicate entries
	$statement = $db->prepare("DELETE FROM `stand_scouting` WHERE `team_number`=:team_number AND `match_number`=:match_number");
	$statement->bindParam(":team_number", $match["team_number"]);
	$statement->bindParam(":match_number", $match["match_number"]);
	$statement->execute();
	// Generate and exec insert
	generateInsert($db, "stand_scouting", $match);
}

/**
 * Automatically generate and execute an SQL INSERT statement for the supplied data
 *
 * @param $db PDO
 * @param $table_name string
 * @param $values array
 */
function generateInsert(&$db, $table_name, &$values) {
	// Don't bother with empty values
	if (sizeof($values) === 0) {
		return;
	}
	// Define query strings
	$query = "INSERT INTO `$table_name` (";
	$values_query = "VALUES (";
	// Append each column and binding name
	foreach ($values as $column_name => $value) {
		$query .= "`$column_name`, ";
		$values_query .= ":$column_name, ";
	}
	// Delete trailing commas and whitespace
	$values_query = substr($values_query, 0, strlen($values_query) - 2) . ")";
	$query = substr($query, 0, strlen($query) - 2) . ") " . $values_query;
	// Prepare the statement
	$statement = $db->prepare($query);
	// Bind each value
	foreach ($values as $column_name => $value) {
		$statement->bindValue(":" . $column_name, $value);
	}
	// Execute the statement
	$statement->execute();
}
