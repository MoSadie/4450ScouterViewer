<?php
/**
 * Created by Caleb Milligan on 2/24/2016.
 */
if(!isset($_FILES['userfile'])){
	echo "No files?";
	exit();
}
for($i = 0; $i < sizeof($_FILES['userfile']); $i++){
	$tmp_name = $_FILES['userfile']['tmp_name'];
	$name = $_FILES['userfile']['name'][$i];
	$size = $_FILES['userfile']['size'][$i];
	echo "<h3>$name ($tmp_name) - $size bytes</h3><br/>";
}