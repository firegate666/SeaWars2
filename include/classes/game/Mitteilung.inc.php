<?
  $template_classes[] = 'mitteilung';
  
  class mitteilung extends AbstractNoNavigationClass {
  	
  	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'sender', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'empfaenger', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'betreff', 'type' => 'String', 'size' => 100, 'notnull' => false);
		$fields[] = array('name' => 'inhalt', 'type' => 'String', 'size' => 500, 'notnull' => false);
		$fields[] = array('name' => 'art', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'gelesen', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'geloescht_sender', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'geloescht_empfaenger', 'type' => 'integer', 'notnull' => true);
		return $fields;
	}
	
  	/**
	 * check if method is allowed
	 * @param	String	$method	method to test
	 * @return	boolean	true/false
	 */
	public function acl($method){
          return Login::isLoggedIn();
        }
    
    /**
	 * Show Messenger using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    public function show($vars) {
    	return "test";
	}
	
	/**
	 * Converts Message to string 
	 * Only for Debugging
	 */
    public function __toString() {
    	if (!($this->exists())){	
	    	$keys   = array_keys($this->data);
	      	$values = array_values($this->data);
	      	$result="";
	      	for($i=0;$i<count($values);$i++) {
	      		$result.=" ".$keys[$i]."=".$values[$i];
	      	}
	    	return $result;
    	}
    	else
    		return "Nachricht nicht existent";
	}
	/**
	 * parst die Formulareingabe zum erzeugen einer neuen Nachricht
	 * liefert unbekannten Fehler!
	 * 
	 * @param	String[]	$vars	request parameter
	 * @return  String		Beschreibung des aufgetretenen Fehlers oder den Text: "Nachricht gesendet"
	 *                      im Falle des Erfolges
	 */
	public function parse_html_imput($vars) {
		if(!isset($vars["empfaenger"]) or empty($vars["empfaenger"])) return "kein Empfaenger angegeben!";
		if(!isset($vars["betreff"]) or empty($vars["betreff"])) return "kein Betreff angegeben!";
		if(!isset($vars["inhalt"]) or empty($vars["inhalt"])) return "Die Nachricht ist leer!";
		global $mysql;
		$query = "SELECT id FROM spieler WHERE username = '".$vars["empfaenger"]."';";
		$result = $mysql->select($query);
		return "Nachricht gesendet"; //no Error
	}
	
	/**
	 * Stellt sich als mit dem Template dar
	 * noch nicht fertig!
	 */
	
	public function show_as_table(){
		$n=array();
		$n["betreff"]=$this->data["betreff"];
		$n["inhalt"]=$this->data["inhalt"];
		$n["datum"]=$this->data["__createdon"];
		switch ($this->data["art"]) {
			case 0:
				$n["art"]="System";
				break;
			case 1:
				$n["art"]="User";
				break;	
			default:
				$n["art"]="Sonstiges";
		}
		return $this->getLayout($n, "table_messages_row", $vars);	
	}
	
	
  	
  }
?>