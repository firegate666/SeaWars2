<?
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
 	/* try to make sense of the query string */
 	$class  = $_REQUEST["class"];
	$method = $_REQUEST["method"];
	$id		= $_REQUEST["id"];
	$vars	= $_REQUEST;
	
	if(isset($admin)) {
		include('admin.php');
		die();
	}
	
	if(empty($class)) $class="Login";
	if(empty($method)) $method="show";
	
	if(class_exists($class)){
    	$newclass = new $class($id);
    	if(method_exists($newclass, $method)) {
      		if(!$newclass->acl($method)) error("DENIED",$class,$method);
      		$result = $newclass->$method($vars);
      		if(strtolower($class)=="page") {
      			print $result;
      		} else if(is_string($result)) {
	      		$game = new SeaWars($newclass->getMainLayout());
      			$game->setNavigation($newclass->getNavigation());
      			$game->setMainBody($result);
      			print $game->show();
      		} else if(is_array($result)) {
      			switch($result['content']) {
      				case strtoupper("URL") : header("Location: ".$result['target']); break;
      			}
      		}
    	}
    	else
      		error("Method not not found",$class,$method);
	} else {
    	error("Class not found",$class,$method);
  	}  
?>