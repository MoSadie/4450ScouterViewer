<?php
/**
 * Created by Caleb Milligan on 2/24/2016.
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
			<script src="../js/scouting.js"></script>
		</head>
		<body>
			<form action="" method="post" enctype="multipart/form-data">
				<h3>Upload Images:</h3>
				<input id="file_selector" multiple="" name="userfile[]" type="file" accept="image/*"
					   onchange="verifyUpload()"/><br/>
				<input id="upload_button" disabled="disabled" type="submit" class="btn-sm" value="Upload"/>
			</form>
		</body>
	</html>
<?php
try {
	// If this is present, then we've uploaded some images
	if (isset($_FILES['userfile'])) {
		$failed = "";
		// Loop over each uploaded file
		for ($i = 0; $i < sizeof($_FILES['userfile']['name']); $i++) {
			// Get file info
			$file_name = strtoupper($_FILES['userfile']['name'][$i]);
			$file_tmp = $_FILES['userfile']['tmp_name'][$i];
			$file_type = strtolower($_FILES['userfile']['type'][$i]);
			// Validate file type
			if (!exif_imagetype($file_tmp)) {
				$failed .= "$file_name (Invalid file type)\\n";
				continue;
			}
			// Validate file name
			$file_name_no_ext = pathinfo($file_name, PATHINFO_FILENAME);
			if (!preg_match("/ROBOT_\\d+$/", $file_name_no_ext)) {
				$failed .= "$file_name (Invalid file name)\\n";
				continue;
			}
			// Move the file
			if (!move_uploaded_file($file_tmp, "../uploaded/images/$file_name_no_ext.jpg")) {
				$failed .= "$file_name (Copy failed)\\n";
			}
		}
		// If there were some invalid files, send an alert
		if (!empty($failed)) {
			?>
			<script type="text/javascript">
				alert("Failed to upload files:\n<?php echo $failed?>");
			</script>
			<?php
		}
	}
}
catch (Throwable $e) {
	error_log($e->__toString());
	header("Location: errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
	exit();
}
?>