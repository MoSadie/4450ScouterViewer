<?php
/**
 * Created by Caleb Milligan on 2/23/2016.
 */
include_once "MyPDO.php";
include_once "Math.php";
include_once "Naming.php";
// We need these parameters
if (!isset($_GET["action"]) || !isset($_GET["team"])) {
	http_response_code(400);
	exit();
}
$action = $_GET["action"];
$team = $_GET["team"];
$db = new MyPDO();
// Get team info
if ($action == "getinfo") {
	$scores = getScores($team);
	$average = getAverageScore($scores);
	$reliability = Naming::getReliability($scores);
	// Construct data array
	$data = array("average" => $average, "variance" => $reliability);
	// Get robot description and scouter name
	$statement = $db->prepare("SELECT * FROM `pit_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	// Add values to data array
	if ($success && $statement->rowCount() > 0) {
		$data = array_merge($data, $statement->fetch(PDO::FETCH_ASSOC));
	}
	$statement->closeCursor();
	// If an image has been uploaded for this robot, specify its URL
	$imgname = "uploaded/images/ROBOT_" . $team . ".jpg";
	// Otherwise, use the default image
	if (!file_exists($imgname)) {
		$imgname = "images/ORF_Logo.png";
	}
	$data["image"] = $imgname;
	// Encode JSON and return
	$json = json_encode($data);
	http_response_code(200);
	header('Content-Type: application/json;charset=utf-8');
	echo $json;
	exit();
}
if ($action == "getmatches") {
	// Select and order all match data for the specified team
	$statement = $db->prepare("SELECT * FROM `stand_scouting` WHERE `team_number`=:team_number ORDER BY `team_number` ASC, `match_number` ASC");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	http_response_code(200);
	header('Content-Type: text/html;charset=utf-8');
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			$match = $statement->fetch(PDO::FETCH_ASSOC);
			echo "<tr><td>";
			echo $match["team_number"];
			echo "</td><td>";
			echo $match["match_number"];
			echo "</td><td>";
			echo $match["scouter_name"];
			echo "</td><td>";
			echo Naming::getBooleanName($match["no_show"]);
			echo "</td><td>";
			echo Naming::getBooleanName($match["died_on_field"]);
			echo "</td><td>";
			echo $match["autonomous_behavior"];
			echo "</td><td>";
			echo Naming::getBooleanName($match["defended"]);
			echo "</td><td>";
			echo Naming::getSpeedName($match["pickup_speed"]);
			echo "</td><td>";
			echo $match["portcullis_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["portcullis_speed"]);
			echo "</td><td>";
			echo $match["chival_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["chival_speed"]);
			echo "</td><td>";
			echo $match["moat_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["moat_speed"]);
			echo "</td><td>";
			echo $match["ramparts_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["ramparts_speed"]);
			echo "</td><td>";
			echo $match["drawbridge_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["drawbridge_speed"]);
			echo "</td><td>";
			echo $match["sally_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["sally_speed"]);
			echo "</td><td>";
			echo $match["rock_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["rock_speed"]);
			echo "</td><td>";
			echo $match["rough_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["rough_speed"]);
			echo "</td><td>";
			echo $match["low_crosses"];
			echo "</td><td>";
			echo Naming::getSpeedName($match["low_speed"]);
			echo "</td><td>";
			echo $match["low_goals"];
			echo "</td><td>";
			echo $match["high_goals"];
			echo "</td><td>";
			echo Naming::getEndgameName($match["endgame"]);
			echo "</td><td>";
			echo $match["notes"];
			echo "</td></tr>";
		}
	}
	$statement->closeCursor();
	exit();
}

function getScores($team_number) {
	$db = new MyPDO();
	$statement = $db->prepare("SELECT `low_goals`, `high_goals` FROM `stand_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team_number, PDO::PARAM_INT);
	$success = $statement->execute();
	$scores = array();
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			$data = $statement->fetch(PDO::FETCH_ASSOC);
			array_push($scores, $data["low_goals"] + $data["high_goals"]);
		}
	}
	$statement->closeCursor();
	return $scores;
}

function getAverageScore(&$scores) {
	if (sizeof($scores) < 1) {
		return 0;
	}
	return round(array_sum($scores) / sizeof($scores));
}


?>