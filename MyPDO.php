<?php

/**
 * Created by Caleb Milligan on 1/25/2016.
 */
class MyPDO extends PDO {
	public function __construct() {
		parent::__construct("mysql:host=localhost;dbname=scouting;", "root", "PIrAtEs", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
}