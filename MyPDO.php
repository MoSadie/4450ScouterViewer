<?php

/**
 * Created by Caleb Milligan on 1/25/2016.
 */
class MyPDO extends PDO {
	public function __construct() {
		try {
			parent::__construct("mysql:host=localhost;dbname=database;", "username", "password", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch(Throwable $e){
			error_log($e->__toString());
			header("Location: errpage.php?timestamp=" . time() . "&err=" . get_class($e), true);
			exit();
		}
	}
}
