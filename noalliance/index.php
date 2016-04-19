<?php
/**
 * Created by Caleb Milligan on 3/28/2016.
 */
include_once "../MyPDO.php";
try {
	$db = new MyPDO();
}
catch (Exception $e) {
	error_log($e->__toString());
	header("Location: ./errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
	exit();
}
?>
<!DOCTYPE html>
<!--
  -- Created by Caleb Milligan on 3/28/2016.
  -->
<html>
	<head>
		<meta charset="utf-8">
		<meta name="author" content="Caleb Milligan">
		<title>No-Alliance List</title>
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/scouting.css">
		<link rel="stylesheet" type="text/css" href="../css/tablehighlight.css">
		<link rel="stylesheet" type="text/css" href="../css/tablepager.css">
		<link rel="stylesheet" type="text/css" href="../css/noalliance.css">
		<script type="application/javascript" src="../js/jquery-1.11.3.min.js"></script>
		<script type="application/javascript" src="../js/bootstrap.js"></script>
		<script type="application/javascript" src="../js/tablepager.js"></script>
		<script type="application/javascript" src="../js/tablehighlight.js"></script>
	</head>
	<body>
		<header>
			<h1>Olympia Robotics Federation</h1>
			<h2>2016 FIRST Stronghold Scouter</h2>
			<hr>
			<h3>No-Alliance List</h3>
		</header>
		<form onsubmit="return false;">
			<img src="../images/first.png" onclick="Pager.getPager('.selectable').firstPage()">
			<img src="../images/prev.png" onclick="Pager.getPager('.selectable').prevPage()">
			<input title="Page" class="selectable-paginator_selector" type="text"
				   onchange="Pager.getPager('.selectable').inputPage(this)">
			<img src="../images/next.png" onclick="Pager.getPager('.selectable').nextPage()">
			<img src="../images/last.png" onclick="Pager.getPager('.selectable').lastPage()">
		</form>
		<div class="table-responsive">
			<table class="table selectable">
				<thead>
					<tr>
						<th>Team #</th>
						<th>Match #</th>
						<th>Reason</th>
					</tr>
				</thead>
				<tbody>
					<?php
					try {
						$statement = $db->prepare("SELECT `match_number`, `team_number`, `no_alliance_reason` FROM `stand_scouting` WHERE `no_alliance`=1 ORDER BY `team_number` ASC");
						$statement->execute();
						for ($i = 0; $i < $statement->rowCount(); $i++) {
							$match = $statement->fetch(PDO::FETCH_ASSOC);
							echo "<tr><td>";
							echo $match["team_number"];
							echo "</td><td>";
							echo $match["match_number"];
							echo "</td><td>";
							echo $match["no_alliance_reason"];
							echo "</td></tr>";
						}
						$statement->closeCursor();
					}
					catch (Exception $e) {
						error_log($e->__toString());
						header("Location: ../errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
						exit();
					}
					?>
				</tbody>
			</table>
		</div>
		<form onsubmit="return false;">
			<img src="../images/first.png" onclick="Pager.getPager('.selectable').firstPage()">
			<img src="../images/prev.png" onclick="Pager.getPager('.selectable').prevPage()">
			<input title="Page" class="selectable-paginator_selector" type="text"
				   onchange="Pager.getPager('.selectable').inputPage(this)">
			<img src="../images/next.png" onclick="Pager.getPager('.selectable').nextPage()">
			<img src="../images/last.png" onclick="Pager.getPager('.selectable').lastPage()">
		</form>
		<script type="text/javascript">
			new Pager(".selectable").setPageSize(18).paginate().displayPage();
		</script>
		<footer class="text-center">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<!--<p>Star our <a href="https://github.com/Short-Circuit/4450ScouterViewer">site</a> or <a
									href="https://github.com/Short-Circuit/4450Scouter">app</a> on GitHub</p>-->
						<p>Copyright © Olympia Robotics Federation. All rights reserved.</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
