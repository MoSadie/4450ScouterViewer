<?php
/**
 * Created by Caleb Milligan on 3/9/2016.
 */
include_once "MyPDO.php";
include_once "Naming.php";

$query = "SELECT * FROM `stand_scouting`";
$overridden = false;
if (isset($_GET["order"])) {
	$order_params = json_decode($_GET["order"]);
	if ($order_params && sizeof($order_params) > 0) {
		$query .= " ORDER BY ";
		foreach ($order_params as $order_param) {
			if (preg_match('/\s/', $order_param)) {
				continue;
			}
			$query .= "`$order_param` ASC, ";
		}
		$query = rtrim($query, ", ");
		$overridden = true;
	}
}
if(!$overridden){
	$query .=" ORDER BY `match_number` ASC, `team_number` ASC";
}
$db = new MyPDO();
$statement = $db->prepare($query);
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
		echo $match["team_name"];
		echo "</td><td>";
		echo Naming::getSpeedName($match["pickup_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["portcullis_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["chival_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["moat_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["ramparts_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["drawbridge_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["sally_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["rock_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["rough_speed"]);
		echo "</td><td>";
		echo Naming::getSpeedName($match["low_speed"]);
		echo "</td><td>";
		echo $match["low_goals"];
		echo "</td><td>";
		echo $match["high_goals"];
		echo "</td><td>";
		echo Naming::getEndgameName($match["endgame"]);
		echo "</td></tr>";
	}
}
$statement->closeCursor();
exit();
