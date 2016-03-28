<?php
/**
 * Created by Caleb Milligan on 2/25/2016.
 */
date_default_timezone_set("UTC");
$timestamp = time();
$error = "Unknown";
$suffix = " (approximate)";
if (isset($_GET["timestamp"])) {
	$timestamp = $_GET["timestamp"];
	$suffix = "";
}
if (isset($_GET["err"])) {
	$error = $_GET["err"];
}
$timestring = date("d-M-Y H:i:s T", (int)$timestamp) . $suffix;
$subject = rawurlencode("ORF Stronghold Scouting Error");
$body = "System time: $timestring\n";
$body .= "Error: $error\n";
$body .= "-------DO NOT MODIFY ABOVE THIS LINE-------\n\n";
$body = rawurlencode($body);
?>
<!DOCTYPE html>
<!--
  -- Created by Caleb Milligan on 2/25/2016.
  -->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Oops!</title>
		<link rel="stylesheet" type="text/css" href="css/scouting.css">
		<link rel="stylesheet" type="text/css" href="css/errpage.css">
		<script src="js/doge.js"></script>
		<style>body {
				background: #0F0F0F url("images/da_beckgrond.jpg") repeat !important;
			}</style>
	</head>
	<body>
		<p>
			An error has occurred!<br/>
			Please alert a system administrator,<br/>
			or contact Caleb Milligan at<br/>
			<a href="mailto:milligancn&#64;students&#46;osd&#46;wednet&#46;edu?subject=<?php echo $subject ?>&body=<?php echo $body ?>">
				milligancn&#64;students&#46;osd&#46;wednet&#46;edu
			</a>
		</p>
		<script type="text/javascript">
			initDoge("images/doge.png");
		</script>
	</body>
</html>