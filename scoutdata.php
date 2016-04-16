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
	$defenses_crossed = [];
	$statement = $db->prepare("SELECT SUM(`portcullis_crosses`) + SUM(`portcullis_speed`) AS `portcullis_crossed`, SUM(`chival_crosses`) + SUM(`chival_speed`) AS `chival_crossed`, SUM(`moat_crosses`) + SUM(`moat_speed`) AS `moat_crossed`, SUM(`ramparts_crosses`) + SUM(`ramparts_speed`) AS `ramparts_crossed`, SUM(`drawbridge_crosses`) + SUM(`drawbridge_speed`) AS `drawbridge_crossed`, SUM(`sally_crosses`) + SUM(`sally_speed`) AS `sally_crossed`, SUM(`rock_crosses`) + SUM(`rock_speed`) AS `rock_crossed`, SUM(`rough_crosses`) + SUM(`rough_speed`) AS `rough_crossed`, SUM(`low_crosses`) + SUM(`low_speed`) AS `low_crossed` FROM `stand_scouting` WHERE `team_number`=:team_number");
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	if ($success && $statement->rowCount() > 0) {
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result["portcullis_crossed"] > 0) {
			array_push($defenses_crossed, "Portcullis");
		}
		if ($result["chival_crossed"] > 0) {
			array_push($defenses_crossed, "Chival de Frise");
		}
		if ($result["moat_crossed"] > 0) {
			array_push($defenses_crossed, "Moat");
		}
		if ($result["ramparts_crossed"] > 0) {
			array_push($defenses_crossed, "Ramparts");
		}
		if ($result["drawbridge_crossed"] > 0) {
			array_push($defenses_crossed, "Drawbridge");
		}
		if ($result["sally_crossed"] > 0) {
			array_push($defenses_crossed, "Sally Port");
		}
		if ($result["rock_crossed"] > 0) {
			array_push($defenses_crossed, "Rock Wall");
		}
		if ($result["rough_crossed"] > 0) {
			array_push($defenses_crossed, "Rough Terrain");
		}
		if ($result["low_crossed"] > 0) {
			array_push($defenses_crossed, "Low Bar");
		}
	}
	$statement->closeCursor();
	$data["defenses_crossed"] = $defenses_crossed;

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
if ($action == "getmatches" || $action == "getrawmatches") {
	// Select and order all match data for the specified team
	$statement = $db->prepare("SELECT `stand_scouting`.*, `c`.`total_points` FROM `stand_scouting` LEFT JOIN `total_points` 
				  				AS `c` ON `c`.`team_number`=`stand_scouting`.`team_number` AND 
				  				`c`.`match_number`=`stand_scouting`.`match_number` WHERE `c`.`team_number`=:team_number ORDER 
				  				BY `stand_scouting`.`match_number` ASC");
	/*
	$statement = $db->prepare("SELECT * FROM `stand_scouting` 
			WHERE `team_number`=:team_number ORDER BY `match_number` ASC");
	*/
	$statement->bindParam(":team_number", $team, PDO::PARAM_INT);
	$success = $statement->execute();
	http_response_code(200);
	header('Content-Type: text/html;charset=utf-8');
	$matches = [];
	if ($success) {
		for ($i = 0; $i < $statement->rowCount(); $i++) {
			$match = $statement->fetch(PDO::FETCH_ASSOC);
			if ($action == "getmatches") {
				echo Naming::matchDataToTableRow($match);
			}
			else {
				array_push($matches, $match);
			}
		}
		if ($action == "getrawmatches") {
			echo json_encode($matches);
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