<?php
/**
 * Created by Caleb Milligan on 3/9/2016.
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Scouter Data Viewer</title>
		<link rel="stylesheet" href="../css/bootstrap.css">
		<link rel="stylesheet" href="../css/scouting.css">
		<script src="../js/jquery-1.11.3.min.js"></script>
		<script src="../js/bootstrap.js"></script>
		<script src="../js/sort.js"></script>
	</head>
	<body>
		<header>
			<h1>Olympia Robotics Federation</h1>
			<h2>2016 FIRST Stronghold Scouter</h2>
		</header>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
						<table id="data_table" class="table">
							<thead>
								<tr>
									<td>Team #<br/><input type="checkbox" name="sort" title="Team #" onchange="toggleSort('team_number')"></td>
									<td>Match #<br/><input type="checkbox" name="sort" title="Match #" onchange="toggleSort('match_number')"></td>
									<td>Scouter Name<br/><input type="checkbox" name="sort" title="Scouter Name" onchange="toggleSort('team_name')"></td>
									<td>Pickup Speed<br/><input type="checkbox" name="sort" title="Pickup Speed" onchange="toggleSort('pickup_speed')"></td>
									<td>Portcullis Spee<br/><input type="checkbox" name="sort" title="Portcullis Speed" onchange="toggleSort('portcullis_speed')"></td>
									<td>Chival de Frise Speed<br/><input type="checkbox" name="sort" title="Chival de Frise Speed" onchange="toggleSort('chival_speed')"></td>
									<td>Moat Speed<br/><input type="checkbox" name="sort" title="Moat Speed" onchange="toggleSort('moat_speed')"></td>
									<td>Ramparts Speed<br/><input type="checkbox" name="sort" title="Ramparts Speed" onchange="toggleSort('ramparts_speed')"></td>
									<td>Drawbridge Speed<br/><input type="checkbox" name="sort" title="Drawbridge Speed" onchange="toggleSort('drawbridge_speed')"></td>
									<td>Sally Port Speed<br/><input type="checkbox" name="sort" title="Sally Port Speed" onchange="toggleSort('sally_speed')"></td>
									<td>Rock Wall Speed<br/><input type="checkbox" name="sort" title="Rock Wall Speed" onchange="toggleSort('rock_speed')"></td>
									<td>Rough Terrain Speed<br/><input type="checkbox" name="sort" title="Rough Terrain Speed" onchange="toggleSort('rough_speed')"></td>
									<td>Low Bar Speed<br/><input type="checkbox" name="sort" title="Low Bar Speed" onchange="toggleSort('low_speed')"></td>
									<td>Low Goals<br/><input type="checkbox" name="sort" title="Low Goals" onchange="toggleSort('low_goals')"></td>
									<td>High Goals<br/><input type="checkbox" name="sort" title="High Goals" onchange="toggleSort('high_goals')"></td>
									<td>Endgame<br/><input type="checkbox" name="sort" title="Endgame" onchange="toggleSort('endgame')"></td>
								</tr>
							</thead>
							<tbody id="match_data">

							</tbody>
						</table>
					</div>
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
		</footer>
	</body>
</html>
