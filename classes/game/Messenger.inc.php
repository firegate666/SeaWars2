<?
	$template_classes[] = 'messenger';

  class Messenger extends AbstractClass {
  	
  	/**
	 * check if method is allowed
	 * @param	String	$method	method to test
	 * @return	boolean	true/false
	 */
	function acl($method){
          return Login::isLoggedIn();
        }
    
    /**
	 * Show Messenger Main Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    function show($vars) {
    	return $this->inbox($vars);
	}
	
	function get_messages($vars){
		$result=new Mitteilung(1);
		return $result;
	}
	
	/**
	 * Show Messenger Inbox Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
	function inbox($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		$result.=$this->getLayout($array, "table_messages", $vars);	
		$nachricht=$this->get_messages($vars);
		$result.=$nachricht->__toString();
		return $result;
	}
	
	/**
	 * Show Messenger Outbox Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
	function outbox($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		return $result;
	}
    
    /**
	 * Show Messenger New Message Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    function new_message($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		$content=$this->getLayout($array, "new_Message_Form", $vars);
		$result.=$this->getForm($content,"messenger","message_send","message_send");
		return $result;
	}
	
	/** 
	 * Wird von dem Form new_message aufgerufen und verarbeitet die Formulareingaben
	 * in die eine neue Nachricht eingegeben wurde.
	 * 
	 * @param	String[]	$vars	request parameter
	 */
	function message_send($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		$nachricht=new Mitteilung();	//Neue Mitteilung erzeugen
		$errormessage=$nachricht->parse_html_imput($vars); //Forumlareingaben überprüfen und zuweisen
		if ($errormessage=="Nachricht gesendet") {
			$nachricht->store();	//Nachricht in SQL-DB abspeichern
		}
		$result.=$errormessage;
		return $result;
	}
  }
?>