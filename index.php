<?
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';
  
 	/* try to make sense of the query string */
 	$class  = $_REQUEST["class"];
	$method = $_REQUEST["method"];
	$id		= $_REQUEST["id"];
	$vars	= $_REQUEST["vars"];
	
	/* TESTS */
	$test = new Insel(1);
	$test->printout();
	$test->set('spieler_id', 5);
	$test->store();
	
	// TESTENDE
	
		
  echo("<p>URL String: ");
  print_a($_REQUEST);
  print_a($vars);

  if(class_exists($class)){
    $class = new $class($id);
    if(method_exists($class, $method))
      $class->$method($vars);
    else
      error("Method not not found",get_class($class),$method);
  } else {
    error("Class not found",$class,$method);
  }  
  
  /*session_start();
  if(!session_is_registered(session)) {
     $session = new Session();
     session_register(session);
     echo("<p>Session Instanz neu angelegt</p>");
  } else {
    //$session = unserialize($_SESSION["$session"]);
    echo("<p>Session Instanz existiert</p>");
  }
  print_r($_SESSION);
  echo("SID: ".SID);
  echo("<p>Session registered?<br>");
  if($session->isRegistered()) echo("TRUE"); else echo("FALSE");*/


?>


