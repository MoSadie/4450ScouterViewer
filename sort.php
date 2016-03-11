<?php
/**
 * Created by Caleb Milligan on 3/9/2016.
 */
include_once "MyPDO.php";
include_once "Naming.php";

$query = "SELECT * FROM `stand_scouting` ORDER BY ";
if (isset($_GET["order"])) {
	$order_params = json_decode($_GET["order"], true);
	if ($order_params && sizeof($order_params) > 0) {
		foreach ($order_params as $order_param => $order_dir) {
			if (preg_match('/\s/', $order_param) || !preg_match('/^(ASC|DESC)$/i', $order_dir)) {
				continue;
			}
			$query .= "`$order_param` $order_dir, ";
		}
	}
}
$query .= "`match_number` ASC, `team_number` ASC";
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
