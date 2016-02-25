<?php
/**
 * Created by Caleb Milligan on 2/17/2016.
 */
// We need this to check validity
if (!isset($_SERVER["HTTP_USER_AGENT"])) {
	echo file_get_contents("unauthorized.html");
	http_response_code(401);
	exit();
}
// Not us? Then we don't want your data!
$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (!preg_match("/4450Scouting\\/*.+/", $user_agent)) {
	echo file_get_contents("unauthorized.html");
	http_response_code(401);
	exit();
}
if(!isset($_GET["filename"])){
	http_response_code(400);
	exit();
}
$filename = strtoupper($_GET["filename"]);
// Ensure the image directory is created
$image_dir = "uploaded/images/";
$tmp_dir = $image_dir . "tmp/";
if (!file_exists($image_dir)) {
	mkdir($image_dir, 0777, true);
}
if(!file_exists($tmp_dir)){
	mkdir($tmp_dir, 0777, true);
}
// Read image from input
$data = file_get_contents("php://input");
// No data? No good!
if (!$data) {
	http_response_code(400);
	exit();
}
$file_dir = $image_dir . $filename;
$temp_file_dir = $tmp_dir . $filename;
$fhandle = fopen($temp_file_dir, "wb");
fwrite($fhandle, $data);
fclose($fhandle);
$file_name_no_ext = pathinfo($temp_file_dir, PATHINFO_FILENAME);
if (!preg_match("/ROBOT_\\d+$/", $file_name_no_ext) || !exif_imagetype($temp_file_dir)) {
	unlink($temp_file_dir);
	http_response_code(400);
	exit();
}
$file_dir = $image_dir . $file_name_no_ext . ".jpg";
rename($temp_file_dir, $file_dir);
http_response_code(200);
