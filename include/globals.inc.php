<?
	function redirect($target){
		$result = array('content'=>'URL', 'target' => $target);
		return $result;
	}		

  function error($error, $class, $method) {
    $error = new Error($error,$class,$method);
    die($error->show());
  }
  
  function print_a($array) {
  	echo("<pre>\n");
  	print_r($array);
  	echo("</pre>\n");
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
  	$diff_x = pow(x2-x1,2);
  	$diff_y = pow(y2-y1,2);
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