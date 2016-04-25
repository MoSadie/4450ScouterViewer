<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Caleb Milligan">
		<title>Export data</title>
		<link rel="stylesheet" type="text/css" href="../css/scouting.css">
		<link rel="stylesheet" type="text/css" href="../css/errpage.css">
	</head>
	<body>
		<p>Please wait while your download is prepared</p>
		<div class="image-center">
			<img width="64" height="64" src="../images/loading_icon.gif">
		</div>
	</body>
</html>
<?php
$file = uniqid("", true) . ".sql";
$output = `mysqldump -u username -ppassword --no-create-info --skip-triggers database pit_scouting stand_scouting total_points > $file`;
if (file_exists($file) && filesize($file) > 0) {
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=scoutdata.sql');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	if (file_exists($file)) {
		unlink($file);
	}
}
else {
	if (file_exists($file)) {
		unlink($file);
	}
	header("Location: ../errpage.php?timestamp=" . time());
}
?>