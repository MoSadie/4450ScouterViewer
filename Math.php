<?php

/**
 * Created by Caleb Milligan on 1/30/2016.
 */
class Math {
	/**
	 * Calculate the standard deviation of a numerical array
	 * @param array $arr <p>
	 * The input array.
	 * </p>
	 * @return float standard deviation of the input array as a float
	 */
	public static function standardDeviation($arr) {
		$total = array_sum($arr);
		$avg = $total / sizeof($arr);
		$variation = 0;
		foreach ($arr as $value) {
			$variation += pow($value - $avg, 2);
		}
		$variation /= sizeof($arr) - 1;
		return sqrt($variation);
	}
}