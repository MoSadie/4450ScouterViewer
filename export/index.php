<?php
$file = uniqid("", true) . ".sql";
$output = `mysqldump -u username -ppassword --no-create-info --skip-triggers database pit_scouting stand_scouting total_points > $file`;
if (file_exists($file)) {
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
	unlink($file);
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">window.close()</script>
	</head>
</html>
