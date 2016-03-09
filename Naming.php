<?php
/**
 * Created by Caleb Milligan on 3/9/2016.
 */

class Naming {
	static function getReliability(&$scores) {
		$raw_value = Math::standardDeviation($scores);
		$rounded = round($raw_value);
		if ($raw_value <= 3.0) {
			return "Very (~$rounded goals variance)";
		}
		if ($raw_value <= 7.0) {
			return "Somewhat (~$rounded goals variance)";
		}
		else {
			return "Unreliable (~$rounded goals variance)";
		}
	}

	static function getSpeedName($speed) {
		switch ((int)$speed) {
			case 0:
				return "Fast";
			case 1:
				return "Medium";
			case 2:
				return "Slow";
			default:
				return "N/A";
		}
	}

	static function getEndgameName($endgame) {
		switch ((int)$endgame) {
			case 0:
				return "Parked on ramp";
			case 1:
				return "Climbed tower";
			default:
				return "N/A";
		}
	}
}