<?php
/**
 * Created by Caleb Milligan on 1/30/2016.
 */
include_once "MyPDO.php";
include_once "Math.php";
$db = new MyPDO();
$column_whitelist = array();
$statement = $db->prepare("EXPLAIN `scouting`");
$statement->execute();
for ($i = 0; $i < $statement->rowCount(); $i++) {
	$row = $statement->fetch(PDO::FETCH_ASSOC);
	array_push($column_whitelist, $row["Field"]);
}
$order_by = "match_number";
$order_dir = "ASC";
if (isset($_GET["order_dir"])) {
	$order_dir = $_GET["order_dir"];
	switch (strtolower($order_dir)) {
		case "asc":
		case "desc":
			break;
		default:
			$order_dir = "asc";
	}
}
if (isset($_GET["order_by"])) {
	$temp_order_by = $_GET["order_by"];
	if (in_array($temp_order_by, $column_whitelist)) {
		$order_by = $temp_order_by;
	}
}
if (isset($_GET["team"])) {
	$team_number = $_GET["team"];
	$raw_query = "SELECT * FROM `scouting` WHERE `team_number`=:team_number ORDER BY `" . $order_by . "` " . $order_dir;
	$statement = $db->prepare($raw_query);
	$statement->bindParam(":team_number", $team_number, PDO::PARAM_INT);
}
else {
	$raw_query = "SELECT * FROM `scouting` ORDER BY `" . $order_by . "` " . $order_dir;
	$statement = $db->prepare($raw_query);
}
$success = $statement->execute();

function getAverageScore() {
	$db = new MyPDO();
}

?>
<!DOCTYPE html>
<html>
	<head>
		<script src="scripts/jquery-1.12.0.js" type="application/javascript"></script>
		<script src="scripts/index.js" type="application/javascript"></script>
		<link rel="stylesheet" type="text/css" href="styles/main_old.css">
	</head>
	<body>
		<div class="table_wrapper">
			<table>
				<thead>
					<tr>
						<?php
						if ($success) {
							for ($i = 0; $i < $statement->columnCount(); $i++) {
								$meta = $statement->getColumnMeta($i);
								$new_order_dir = "asc";
								if (isset($_GET["order_dir"])) {
									$order_dir = strtolower($_GET["order_dir"]) === "asc" ? "asc" : "desc";
									if (strtolower($order_dir) === "asc") {
										$new_order_dir = "desc";
									}
								}
								$img = "";
								if ($order_by === $meta["name"]) {
									$img = "<img style='display:inline' src=images/sort_" . $order_dir . ".png>";
									$url = http_build_query(array_merge($_GET, array("order_by" => $meta["name"], "order_dir" => $new_order_dir)));
								}
								else{
									$url = http_build_query(array_merge($_GET, array("order_by" => $meta["name"], "order_dir" => "asc")));
								}
								echo "<td><a href=?" . $url . "><p style='display:inline'>" . $meta["name"] . "</p>";
								echo $img . "</a></td>";
							}
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($success) {
						$column_count = $statement->columnCount();
						for ($i = 0; $i < $statement->rowCount(); $i++) {
							echo "<tr>";
							$row = $statement->fetch(PDO::FETCH_ASSOC);
							foreach ($row as $key => $value) {
								echo "<td>" . $value . "</td>";
							}
							echo "</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>
