<?/** * return value from global configs *  * @param	String	$name	name of config * @param	String	$default	if not found return this * @return	String	config value */function get_config($name, $default = '') {	global $_CONFIG;	if(isset($_CONFIG[$name]))		return $_CONFIG[$name];	else		return $default;}/** * return random string * @name_laenge		length of string */function randomstring($name_laenge) {
	$zeichen = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789";
	$name_neu = "";

	mt_srand((double) microtime() * 1000000);
	for ($i = 0; $i < $name_laenge; $i ++) {
		$name_neu .= $zeichen {
			mt_rand(0, strlen($zeichen))
		};
	}
	return $name_neu;
}

/** * create redirect */function redirect($target) {
	$result = array ('content' => 'URL', 'target' => $target);
	return $result;
}

/** * Create error */function error($error, $class, $method, $vars=array()) {
	$error = new Error($error, $class, $method);
	die($error->show($vars));
}

/** * improved print_r */function print_a($array) {
	echo ("<pre>\n");
	print_r($array);
	echo ("</pre>\n");
}

/**
 * Calcualtes distance between (x1, y1) and (x2, y2)
 * 
 * @param $x1 x1
 * @param $y1 y1
 * @param $x2 x2
 * @param $y2 y2
 * @return distance
 */
function distance($x1, $y1, $x2, $y2) {
	$diff_x = pow(x2 - x1, 2);
	$diff_y = pow(y2 - y1, 2);
	$result = sqrt(diff_x + diff_y);
	return result;
}

/**
 * Gets client IP
 * @return IP as String
 */
function getClientIP() {
	return $_SERVER['REMOTE_ADDR'];
}
?>