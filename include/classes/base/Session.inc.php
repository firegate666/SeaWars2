<?
class Session extends AbstractClass {
	protected $sid;

	/**
	* Return value from session, if not set return default
	*
	* @param	String	$name	name of value
	* @param	String	$default	default value
	* @return	String	value
	*/
	function getCookie($name, $default = '') {
		if (isset ($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return $default;
	}

	/**
	* Unset value
	*
	* @param	String	$name	name of value
	*/
	function unsetCookie($name) {
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}

	/**
	* set value
	*
	* @param	String	$name	name of value
	* @param	String	$value	value
	*/
	function setCookie($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	/**
	* empty value
	* 
	* @param	String	$name	name of value
	*/
	function removeCookie($name) {
		$_SESSION[$name] = '';
	}
	
	/**
	* delete all values from session
	*/
	function cleanUpCookies() {
		$_SESSION = array ();
	}

	function Session($userid='') {
		$this->data["uid"] = $userid;
		$this->data["client_ip"] = getClientIP();
		// in Datenbank speichern und ID übergeben
		$this->sid = $this->store();
	}

	function isRegistered() {
		return (($this->id != NULL) && ($this->username != NULL));
	}
}
?>