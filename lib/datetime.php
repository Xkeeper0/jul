<?php
	function timedelta($array1, $array2) {
		$time = array();
		$time['seconds'] = 60 - abs($array1['seconds'] - $array2['seconds']);
		$time['minutes'] = abs($array1['minutes'] - $array2['minutes']);
		$time['hours']   = abs($array1['hours'] - $array2['hours']);

		return $time;
	}
?>
