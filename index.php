<?
	/**
	* one file to rule them all
	*/
	require_once dirname(__FILE__).'/config/All.inc.php';

	// start/restore session
	session_save_path('cache');
	session_start();

	require_once dirname(__FILE__).'/include/All.inc.php';
  
	$s = new Session();

	/**
	 * Admincall?
	 */
	if(isset($_REQUEST["admin"])) {
		include('admin/admin.php');
	  	$mysql->disconnect(); // remember to close connection
		die();
	}

	/**
	 * decode query string
	 */
 	if (isset($_REQUEST['class']) ||
 		isset($_REQUEST['method']) || 
 		isset($_REQUEST['id'])) {
		 	$class  = $_REQUEST["class"];
			$method = $_REQUEST["method"];
			$id	    = $_REQUEST["id"];
			$vars	= array_merge(array(), $_REQUEST);
	} else {
		// new style
		$qs = $_SERVER['QUERY_STRING'];
		decodeURI($qs, $class, $method, $id, $vars);
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
      			/* count statistic */
				$ps = new PageStatistic();
				$ps->set('template', $id);
				$ps->store();
				// output			
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
  	if(get_config('debug', false)) {
  		print "<hr><b>Queries executed:</b> ".($mysql->getQuerycount());
  		print " - ";
  		print '<a href="?class=template&method=clearcache">Clear Cache</a>';
  		if(get_config('game', false)) {
  			print " - ";
  			print '<a href="?class=techtree&method=dropall">Forschungen zurücksetzen</a>';
  		}
  	}
  	// clean up the mess
  	$mysql->disconnect();
?>