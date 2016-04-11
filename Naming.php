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
			case 2:
				return "Fast";
			case 1:
				return "Medium";
			case 0:
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

	static function getBooleanName($val){
		return $val ? "Yes" : "No";
	}

	static function matchDataToTableRow(&$match){
		$data = "<tr><td>";
		$data .= $match["match_number"];
		$data .= "</td><td>";
		$data .= $match["team_number"];
		$data .= "</td><td>";
		$data .= $match["scouter_name"];
		$data .= "</td><td>";
		$data .= Naming::getBooleanName($match["no_show"]);
		$data .= "</td><td>";
		$data .= Naming::getBooleanName($match["died_on_field"]);
		$data .= "</td><td>";
		$data .= $match["autonomous_behavior"];
		$data .= "</td><td>";
		$data .= Naming::getBooleanName($match["defended"]);
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["pickup_speed"]);
		$data .= "</td><td>";
		$data .= $match["portcullis_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["portcullis_speed"]);
		$data .= "</td><td>";
		$data .= $match["chival_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["chival_speed"]);
		$data .= "</td><td>";
		$data .= $match["moat_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["moat_speed"]);
		$data .= "</td><td>";
		$data .= $match["ramparts_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["ramparts_speed"]);
		$data .= "</td><td>";
		$data .= $match["drawbridge_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["drawbridge_speed"]);
		$data .= "</td><td>";
		$data .= $match["sally_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["sally_speed"]);
		$data .= "</td><td>";
		$data .= $match["rock_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["rock_speed"]);
		$data .= "</td><td>";
		$data .= $match["rough_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["rough_speed"]);
		$data .= "</td><td>";
		$data .= $match["low_crosses"];
		$data .= "</td><td>";
		$data .= Naming::getSpeedName($match["low_speed"]);
		$data .= "</td><td>";
		$data .= $match["low_goals"];
		$data .= "</td><td>";
		$data .= $match["high_goals"];
		$data .= "</td><td>";
		$data .= Naming::getEndgameName($match["endgame"]);
		$data .= "</td><td>";
		if(isset($match["total_points"])) {
			$data .= $match["total_points"];
		}
		else{
			$data .= "?";
		}
		$data .= "</td><td>";
		$data .= $match["notes"];
		$data .= "</td></tr>";
		return $data;
	}
}