<?php
/**
 * Created by Caleb Milligan on 3/25/2016.
 */
include_once "../MyPDO.php";
try {
	$db = new MyPDO();
}
catch (Exception $e) {
	error_log($e->__toString());
	header("Location: ../errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
	exit();
}
$team = "";
if (isset($_GET["team"])) {
	$team = $_GET["team"];
}
?>
<!DOCTYPE html>
<!--
  -- Created by Caleb Milligan on 2/1/2016.
  -->
<html>
	<head>
		<link rel="stylesheet" href="../css/bootstrap.css">
		<link rel="stylesheet" href="../css/scouting.css">
		<script src="../js/jquery-1.11.3.min.js"></script>
		<script src="../js/bootstrap.js"></script>
		<script src="../js/Chart.Core.js"></script>
		<script src="../js/Chart.Line.js"></script>
		<script src="../js/graph.js"></script>
	</head>
	<body>
		<header>
			<h1>Olympia Robotics Federation</h1>
			<h2>2016 FIRST Stronghold Scouter</h2>
		</header>
		<div class="container">
			<div class="row">
				<div class="team_query">
					<h3>Search by team:</h3>
					<form onsubmit="doGraph();return false">
						<input id="team_number" type="number" title="Team #" placeholder="Team #" min="0"
							   list="team_numbers" value="<?php echo $team ?>"/>
						<input id="submit_team" type="submit" class="btn-sm" value="Go" title="Go">
					</form>
				</div>
			</div>
		</div>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Portcullis</h3>
					<canvas id="graph-portcullis" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Chival de Frise</h3>
					<canvas id="graph-chival" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Moat</h3>
					<canvas id="graph-moat" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Ramparts</h3>
					<canvas id="graph-ramparts" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Drawbridge</h3>
					<canvas id="graph-drawbridge" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Sally Port</h3>
					<canvas id="graph-sally" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Rock Wall</h3>
					<canvas id="graph-rock" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Rough Terrain</h3>
					<canvas id="graph-rough" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: No cross&#10;1: Slow&#10;2: Medium&#10;3: Fast">Low Bar</h3>
					<canvas id="graph-low" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3>Goals</h3>
					<canvas id="graph-goals" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: Did nothing&#10;1: Parked on ramp&#10;2: Climbed tower">Endgame</h3>
					<canvas id="graph-endgame" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="Values are approximate and do not account for factors&#10;such as penalties or auto/tele/endgame score differences">
						Total Points Earned</h3>
					<canvas id="graph-points" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
		</div>
		<footer class="text-center">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<p>Copyright © Olympia Robotics Federation. All rights reserved.</p>
					</div>
				</div>
			</div>
			<datalist id="team_numbers">
				<?php
				try {
					// Select distinct team numbers from all available tables
					$statement = $db->prepare("SELECT DISTINCT(`team_number`) FROM `stand_scouting` UNION DISTINCT SELECT DISTINCT(`team_number`) FROM `pit_scouting` ORDER BY `team_number` ASC;");
					$statement->execute();
					// Add each team number to the datalist
					for ($i = 0; $i < $statement->rowCount(); $i++) {
						$temp_team_number = $statement->fetchColumn(0);
						echo "<option label='$temp_team_number' value='$temp_team_number'></option>";
					}
					$statement->closeCursor();
				}
				catch (Exception $e) {
					error_log($e->__toString());
					header("Location: errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
					exit();
				}
				?>
			</datalist>
		</footer>
		<?php
		if ($team) {
			?>
			<script type="text/javascript">doGraph()</script>
			<?php
		}
		?>
	</body>
</html>
