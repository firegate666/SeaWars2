<?
	require_once dirname(__FILE__).'/config/All.inc.php';
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
 	/* try to make sense of the query string */
 	$class  = $_REQUEST["class"];
	$method = $_REQUEST["method"];
	$id	    = $_REQUEST["id"];
	$vars	= $_REQUEST;
	
	/**
	 * Admincall?
	 */
	if(isset($admin)) {
		include('admin.php');
		die();
	}
	
	/**
	 * Default handling
	 */
	if(empty($class))  $class="Page";
	if(empty($method)) $method="show";
	
	if(class_exists($class)){ // is there a class with that name?
    	$newclass = new $class($id);
    	if(method_exists($newclass, $method)) { // is there a method with that name for that class
      		if(!$newclass->acl($method)) error("DENIED", $class, $method); // are you allowed to call?
      		$result = $newclass->$method($vars);
      		if(strtolower($class)=="page") { // are you a page
      			print $result;
      		} else if(is_string($result)) { // results a string?
	      		$game = new SeaWars($newclass->getMainLayout());
      			$game->setNavigation($newclass->getNavigation());
      			$game->setMainBody($result);
      			print $game->show();
      		} else if(is_array($result)) { // results an array?
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