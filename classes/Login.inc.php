<?
class Login extends AbstractNoNavigationClass {
		
	function getMainLayout() {
		return 'login_logout';
	}

	function acl($method) {
		$method=strtolower($method);
		if($method=='logout') return true;
		else if($method=='login') return true;
		else if($method=='show') return true;
		else return false;
	}
	
	function isLoggedIn() {
		return isset($_COOKIE["username"]);
	}
	
	function denied(){
		return error("Zugang verweigert",get_class($this),"denied");
//		$o = '';
//		$o .= '<h1>Denied.</h1>';
//		$o .= '<a href="index.php?class=Login">Zurück</a>';
//		return $o;
	}

	function logout(&$vars) {
		setcookie("username","",0);
		//$result['content']="URL";
		//$result['target']="index.php";
		return redirect("index.php");
	}

	function login(&$vars){
		if($this->isLoggedIn()) $this->logout(&$vars);
		$DB = new MySQL();
		// Passwort überprüfen
		$username = $vars['username'];
		$password = $vars['password'];
		$array = $DB->select("SELECT id FROM spieler WHERE username='$username' AND password='$password'");
		$result['content']="URL";
		$target = '';
		if(count($array)==1) {
			setcookie("username",$username, NULL);
			$result['target']="index.php?class=Insel";
			$target = "index.php?class=Insel";
		} else {
			$result['target']="index.php?class=Login&method=denied";
			$target = "index.php?class=Login&method=denied";
		}
		return redirect($target);
	}
	
	function show(&$vars){
		$array = array("title" => "Login", "lbl_username" => "Benutzername", "lbl_password" => "Passwort", "lbl_login" => "Anmelden");
		return $this->getLayout($array, "login_window");
	}
}
?>