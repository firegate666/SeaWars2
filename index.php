<?
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
 	/* try to make sense of the query string */
 	$class  = $_REQUEST["class"];
	$method = $_REQUEST["method"];
	$id		= $_REQUEST["id"];
	$vars	= $_REQUEST;
	
	if(empty($method)) $method="show";
	
	if(class_exists($class)){
    	$class = new $class($id);
    if(method_exists($class, $method)) {
      $result = $class->$method($vars);
      if(is_string($result)) {
      	print "<html>\n<body>\n";
		print "<table width=100% border=1>\n";
		print "<tr>\n";
		print "<td width=100>&nbsp;</td>\n";
		print "<td>&nbsp;</td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td width=100>".($class->getNavigation())."</td>\n";
		print "<td>$result</td>\n";
		print "</tr>\n";
      	print "</body></html>\n";
      } else if(is_array($result)) {
      		switch($result['content']) {
      			case strtoupper("URL") : header("Location: ".$result['target']); break;
      		}
      }
    }
    else
      error("Method not not found",get_class($class),$method);
  } else {
    error("Class not found",$class,$method);
  }  
?>