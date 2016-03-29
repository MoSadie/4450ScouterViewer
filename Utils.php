<?php

/**
 * Created by Caleb Milligan on 3/29/2016.
 */
class Utils {
	/**
	 * Automatically generate and execute an SQL INSERT statement for the supplied data
	 *
	 * @param $db PDO
	 * @param $table_name string
	 * @param $values array
	 */
	static function generateInsert(&$db, $table_name, &$values) {
		// Don't bother with empty values
		if (sizeof($values) === 0) {
			return;
		}
		// Define query strings
		$query = "REPLACE INTO `$table_name` (";
		$values_query = "VALUES (";
		// Append each column and binding name
		foreach ($values as $column_name => $value) {
			$query .= "`$column_name`, ";
			$values_query .= ":$column_name, ";
		}
		// Delete trailing commas and whitespace
		$values_query = substr($values_query, 0, strlen($values_query) - 2) . ")";
		$query = substr($query, 0, strlen($query) - 2) . ") " . $values_query;
		// Prepare the statement
		$statement = $db->prepare($query);
		// Bind each value
		foreach ($values as $column_name => $value) {
			$statement->bindValue(":" . $column_name, $value);
		}
		// Execute the statement
		$statement->execute();
	}
	
	static function calcTotalScore(&$match) {
		$score = 0;
		$score += $match["high_goals"] * 5;
		$score += $match["low_goals"] * 2;
		if ($match["high_goals"] + $match["low_goals"] >= 8) {
			$score += 25;
		}
		$defenses = ["portcullis", "chival", "moat", "ramparts", "drawbridge", "sally", "rock", "rough", "low"];
		foreach ($defenses as $key) {
			$crosses = min(max(min($match[$key . "_speed"], 1), $match[$key . "_crosses"]), 3);
			if ($crosses >= 3) {
				$score += 20;
			}
			$score += $crosses * 5;
		}
		switch ((int)$match["endgame"]) {
			case 1:
				$score += 5;
				break;
			case 2:
				$score += 15;
				break;
		}
		return $score;
	}
}