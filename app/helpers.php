<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 5/11/2016
 * Time: 20:02
 */

function compare_by_int_key($a, $b) {
	if ($a['duration']["value"] == $b['duration']["value"]) {
		return 0;
	}
	return ($a['duration']["value"] < $b['duration']["value"]) ? -1 : 1;
}