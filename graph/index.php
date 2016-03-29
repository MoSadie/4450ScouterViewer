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
		<title>April Fool!</title>
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/scouting.css">
		<script type="application/javascript" src="../js/jquery-1.11.3.min.js"></script>
		<script type="application/javascript" src="../js/bootstrap.js"></script>
		<script type="application/javascript" src="../js/Chart.Core.js"></script>
		<script type="application/javascript" src="../js/Chart.Line.js"></script>
		<script type="application/javascript" src="../js/graph.js"></script>
		<script type="application/javascript" src="../js/doge.js"></script>
	</head>
	<body>
		<header>
			<h1>olympia robotics federation</h1>
			<h2>10</h2>
		</header>
		<div class="container">
			<div class="row">
				<div class="team_query">
					<h3>very question many search:</h3>
					<form onsubmit="doGraph();return false">
						<input id="team_number" type="number" title="Team #" placeholder="tem #" min="0"
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
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">many portcullis</h3>
					<canvas id="graph-portcullis" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">such chival de frise</h3>
					<canvas id="graph-chival" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">wow so water games</h3>
					<canvas id="graph-moat" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">many ramparts</h3>
					<canvas id="graph-ramparts" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">wowe drawbirdge</h3>
					<canvas id="graph-drawbridge" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">much sally prote</h3>
					<canvas id="graph-sally" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">many rock such wall</h3>
					<canvas id="graph-rock" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">very rough terrain</h3>
					<canvas id="graph-rough" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: ur 2 slo&#10;1: fast&#10;2: way 2 fast&#10;3: sanic speed">wowe such limbo</h3>
					<canvas id="graph-low" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col col-xs-4">
					<h3>in-holes</h3>
					<canvas id="graph-goals" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="0: nope&#10;1: sat on th rampd&#10;2: way up high">finisher</h3>
					<canvas id="graph-endgame" class="graph-container" style="width: 100%; height:100%"></canvas>
				</div>
				<div class="col col-xs-4">
					<h3 title="Values are approximate and do not account for factors&#10;such as penalties, auto/tele/endgame score differences,&#10;or shiba inus on the field">
						totle ponts</h3>
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
		<script type="text/javascript">
			initDoge("../images/doge.png");
		</script>
		<?php
		if ($team) {
			?>
			<script type="text/javascript">doGraph()</script>
			<?php
		}
		?>
	</body>
</html>
