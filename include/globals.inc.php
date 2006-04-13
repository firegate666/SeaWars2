<?/** * Decode URI class/method/id/param=value/param2=value/... */function decodeURI($qs, &$class, &$method, &$id='', &$vars=array()) {		$qs = explode("/", $qs);		if (isset($qs[0]))			$class = $qs[0];		else return;		unset($qs[0]);		if (isset($qs[1]))			$method = $qs[1];		else return;		unset($qs[1]);		if (isset($qs[2])) {			$id = $qs[2];			unset($qs[2]);		}		foreach($qs as $var) {			$var = explode("=", $var);			if (isset($var[0])) {				$vars[$var[0]] = '';				if (isset($var[1]))					$vars[$var[0]] = $var[1];			}		}	}/** * return value from global configs *  * @param	String	$name	name of config * @param	String	$default	if not found return this * @return	String	config value */function get_config($name, $default = '') {	global $_CONFIG;	if(isset($_CONFIG[$name]))		return $_CONFIG[$name];	else		return $default;}/** * Encrypts password, if encryption is enabled, else returns password *  * @param	String	$password	plain-text password * @return	String	modified password */function myencrypt($password) {	if(get_config('encryptpwd', false))		return sha1($password);	else		return $password;}/** * return random string * @param	int	name_laenge		length of string */function randomstring($name_laenge) {
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
/** * xml output */function xml($output) {	$result = array ('content' => 'XML', 'output' => $output);	return $result;}
/** * Create error */function error($error, $class, $method, $vars=array()) {
	global $mysql;	if(class_exists('Error')) {		$error = new Error($error, $class, $method);		print $error->show($vars);		$mysql->disconnect();	} else		print "$class -> $method: $error";	die();
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
}function std2impDate($date) {	$date_temp = explode("-",$date);	if (count($date_temp) != 3)		return "";	$std_jahr = $date_temp[0];	$std_monat = $date_temp[1];	$std_tag = $date_temp[2];	$pz = 0;	$m = intval($std_jahr / 1000);	$jz = $std_jahr - $m*1000;	if(($std_jahr % 1000) != 0)		$m++;	// Jahrestausendstel bestimmen	$datum = getdate(mktime(0,0,0,$std_monat,$std_tag,$std_jahr));	$day_of_year = ($datum['yday'])+1;	$schaltjahr = date("L",mktime(0,0,0,12,31,$std_jahr));	if($schaltjahr==1)		$maxdays = 366;	else		$maxdays = 365;	$jt = intval((1000 / $maxdays) * $day_of_year);	// Beautify	while(strlen($jz)<3) { $jz = "0".$jz; }	while(strlen($jt)<3) { $jt = "0".$jt; }	while(strlen($m)<2) { $m = "0".$m; }	return "$pz-$jt.$jz/M$m";}
?>