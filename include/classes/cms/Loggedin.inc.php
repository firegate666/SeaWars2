<?/** * real links */class Loggedin {
	protected $loggedin = "";	protected $loggedout = "";	
	public function acl($method){		if ($method=='show')			return true;		return false;	}	function show(& $vars) {
		if (User::loggedIn()) {			if (empty($this->loggedin))				return "";			$p = new Page($this->loggedin);			return $p->show($vars);		} else {			if (empty($this->loggedout))				return "";			$p = new Page($this->loggedout);			return $p->show($vars);		} 
	}

	function Loggedin($value) {		$value = explode("|", $value);		if(isset($value[0]))			$this->loggedin = $value[0];
		if(isset($value[1]))			$this->loggedout = $value[1];	}
}
?>