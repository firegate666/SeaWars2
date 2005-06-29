<?
	/**
	* one file to rule them all
	*/
	require_once dirname(__FILE__).'/config/All.inc.php';
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
	if(get_config('noframes', false)) {
		?>
		<script language="JavaScript"> 
  			<!--
   				if(top.frames.length > 0) {
					document.write('Die Seite wurde innerhalb eines Frames dargestellt. Es erfolgt ein Reload.');
					top.location.href=self.location;
    			}
  			//--> 
		</script> 
		<?
	}  
  
  
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
