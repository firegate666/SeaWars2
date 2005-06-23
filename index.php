<?
	/**
	* one file to rule them all
	*/
	require_once dirname(__FILE__).'/config/All.inc.php';
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
 	$class  = $_REQUEST["class"];
	$method = $_REQUEST["method"];
	$id	    = $_REQUEST["id"];
	$vars	= array_merge(array(), $_REQUEST);
	
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
	if(isset($_CONFIG['usedefaults']) && $_CONFIG['usedefaults']) {
		if(empty($class) && isset($_CONFIG["default_class"]))
			$class  = $_CONFIG["default_class"];
		if(empty($method) && isset($_CONFIG["default_method"]))
			$method = $_CONFIG["default_method"];
		if(empty($id) && $_CONFIG["default_id"])
			$id     = $_CONFIG["default_id"];
	}
	
	/**
	* Class and method invoking
	*/
	if(class_exists($class)){ // is there a class with that name?
    	$newclass = new $class($id);
    	if(method_exists($newclass, $method)) { // is there a method with that name for that class
      		if(!$newclass->acl($method)) error("DENIED", $class, $method); // are you allowed to call?
      		$result = $newclass->$method($vars);
      		if(strtolower($class) == "page") { // are you a page
      			print $result;
      		} else if(is_string($result)) { // results a string?
	      		if(isset($_CONFIG["game"])) {
		      		$game = new SeaWars($newclass->getMainLayout());
	      			$game->setNavigation($newclass->getNavigation($vars));
	      			$game->setMainBody($result);
	      			print $game->show($vars);
	      		} else
	      			print $result;
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
