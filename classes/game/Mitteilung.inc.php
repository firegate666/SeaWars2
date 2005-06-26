<?
  class Mitteilung extends AbstractNoNavigationClass {
  	
  	/**
	 * all fields used in class
	 */
	function getFields() {
		$fields[] = array('name' => 'sender', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'empfaenger', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'datum', 'type' => 'timestamp', 'notnull' => true);
		$fields[] = array('name' => 'uhrzeit', 'type' => 'timestamp', 'notnull' => true);
		$fields[] = array('name' => 'betreff', 'type' => 'String', 'size' => 100, 'notnull' => false);
		$fields[] = array('name' => 'inhalt', 'type' => 'String', 'size' => 500, 'notnull' => false);
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
	function acl($method){
          return Login::isLoggedIn();
        }
    
    /**
	 * Show Messenger using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    function show($vars) {
    	return "test";
	}
	
	/**
	 * Converts Message to string 
	 * Only for Debugging
	 */
    function __toString() {
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
	 * 
	 * @param	String[]	$vars	request parameter
	 * @return  String		Beschreibung des aufgetretenen Fehlers oder den Text: "Nachricht gesendet"
	 *                      im Falle des Erfolges
	 */
	function parse_html_imput($vars) {
		
		return "Nachricht gesendet"; //no Error
	}
	
	
  	
  }
?>