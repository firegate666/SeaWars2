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
		include('admin/admin.php');
		die();
	}
	/**
	 * Default handling
	 */
	if(get_config('usedefaults', true)) {
		if(empty($class))
			$class  = get_config("default_class");
		if(empty($method))
			$method = get_config("default_method");
		if(empty($id))
			$id     = get_config("default_id");
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
	      		if(get_config("game", false)) {
		      		$game = new SeaWars($newclass->getMainLayout());
	      			$game->setNavigation($newclass->getNavigation($vars));
	      			$game->setMainBody($result);
	      			print $game->show($vars);
	      		} else
	      			print $result;
      		} else if(is_array($result)) { // results an array?
      			switch(strtoupper($result['content'])) {
      				case "URL" : header("Location: ".$result['target']); break;
      				case "XML" : {
      					header("Content-Type: application/xml; charset=".Setting::get('charset',''));
      					print $result['output']; break;
      				}
      				default : error("Wrong content found", 'index.php', 'return handling');
      			}
      		}
    	}
    	else
      		error("Method not not found",$class,$method);
	} else {
    	error("Class not found",$class,$method);
  	}  
?>