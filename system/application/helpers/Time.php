<?php
/**
 * Provides simple time tools
 *
 *
 **/

class Time {


	    function timeAgo($from_time, $to_time = null, $include_seconds = false) {
			if (!$to_time) {
				$to_time = time();
			}
		
	        $distance_in_minutes = round(abs($to_time - $from_time) / 60);
	        $distance_in_seconds = round(abs($to_time - $from_time));

	        if ($distance_in_minutes >= 0 and $distance_in_minutes <= 1) {
	            if (!$include_seconds) {
	                return ($distance_in_minutes == 0) ? 'Less than a minute' : '1 minute';
	            } else {
	                if ($distance_in_seconds >= 0 and $distance_in_seconds <= 4) {
	                    return 'Less than 5 seconds';
	                } elseif ($distance_in_seconds >= 5 and $distance_in_seconds <= 9) {
	                    return 'Less than 10 seconds';
	                } elseif ($distance_in_seconds >= 10 and $distance_in_seconds <= 19) {
	                    return 'Less than 20 seconds';
	                } elseif ($distance_in_seconds >= 20 and $distance_in_seconds <= 39) {
	                    return 'Lalf a minute';
	                } elseif ($distance_in_seconds >= 40 and $distance_in_seconds <= 59) {
	                    return 'Less than a minute';
	                } else {
	                    return '1 minute';
	                }
	            }
	        } elseif ($distance_in_minutes >= 2 and $distance_in_minutes <= 44) {
	            return $distance_in_minutes . ' minutes';
	        } elseif ($distance_in_minutes >= 45 and $distance_in_minutes <= 89) {
	            return 'About 1 hour';
	        } elseif ($distance_in_minutes >= 90 and $distance_in_minutes <= 1439) {
	            return 'About ' . round(floatval($distance_in_minutes) / 60.0) . ' hours';
	        } elseif ($distance_in_minutes >= 1440 and $distance_in_minutes <= 2879) {
	            return '1 day';
	        } elseif ($distance_in_minutes >= 2880 and $distance_in_minutes <= 43199) {
	            return 'About ' . round(floatval($distance_in_minutes) / 1440) . ' days';
	        } elseif ($distance_in_minutes >= 43200 and $distance_in_minutes <= 86399) {
	            return 'About 1 month';
	        } elseif ($distance_in_minutes >= 86400 and $distance_in_minutes <= 525599) {
	            return round(floatval($distance_in_minutes) / 43200) . ' months';
	        } elseif ($distance_in_minutes >= 525600 and $distance_in_minutes <= 1051199) {
	            return 'About 1 year';
	        } else {
	            return 'Over ' . round(floatval($distance_in_minutes) / 525600) . ' years';
	        }
		}

}
?>