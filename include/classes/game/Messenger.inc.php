<?
	$template_classes[] = 'messenger';

  class Messenger extends AbstractNavigationClass {
  	
  	/**
	 * check if method is allowed
	 * @param	String	$method	method to test
	 * @return	boolean	true/false
	 */
	public function acl($method){
          return Login::isLoggedIn();
        }
    
    /**
	 * Show Messenger Main Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    public function show($vars) {
    	return $this->inbox($vars);
	}
	
	/**
	 * Selektiert aus der DB alle Nachrichten, die an mich gerichtet sind
	 * zur Zeit als Test!
	 */
	protected function get_messages_to_me(){
		$result[]=new Mitteilung(1);
		$result[]=new Mitteilung(2);
		return $result;
	}
	
	/**
	 * Show Messenger Inbox Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
	public function inbox($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		$nachrichten=$this->get_messages_to_me($vars);
		$array["rows"]="";
		foreach ($nachrichten as $nachricht){
			//$result.=$nachricht->__toString();
			$array["rows"].=$nachricht->show_as_table();
		}
		$result.=$this->getLayout($array, "table_messages", $vars);	
		return $result;
	}
	
	/**
	 * Show Messenger Outbox Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
	public function outbox($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		return $result;
	}
    
    /**
	 * Show Messenger New Message Window using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    public function new_message($vars){
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
	public function message_send($vars){
		$array=array();
		$result=$this->getLayout($array, "main_window_header", $vars);
		
		$nachricht=new Mitteilung();	//Neue Mitteilung erzeugen
		$errormessage=$nachricht->parse_html_imput($vars); //Formulareingaben überprüfen und zuweisen
		$result.=$errormessage;
		if ($errormessage=="Nachricht gesendet") {
			$nachricht->store();	//Nachricht in SQL-DB abspeichern
		} 
		else {
			$result.="<br>Die Nachricht wurde nicht gesendet!";		
		}
		return $result;
	}
  }
?>