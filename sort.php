<?php
/**
 * Created by Caleb Milligan on 3/9/2016.
 */
include_once "MyPDO.php";
include_once "Naming.php";

$query = "SELECT `stand_scouting`.*, `c`.`total_points` FROM `stand_scouting` LEFT JOIN `total_points` AS `c` ON 
			`c`.`team_number`=`stand_scouting`.`team_number` AND `c`.`match_number`=`stand_scouting`.`match_number` ORDER BY ";
//$query = "SELECT `stand_scouting`.*, `total_points`.`total_points` FROM `stand_scouting` ORDER BY ";
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
		echo Naming::matchDataToTableRow($match);
	}
}
$statement->closeCursor();
exit();
