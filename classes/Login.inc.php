<?
class Login extends AbstractNoNavigationClass {
		
	function denied(){
		$o = '';
		$o .= '<h1>Denied.</h1>';
		$o .= '<a href="index.php?class=Login">Zurück</a>';
		return $o;
	}

	function login(&$vars){
		$DB = new MySQL();
		// Passwort überprüfen
		$username = $vars['username'];
		$password = $vars['password'];
		$array = $DB->executeSql("SELECT id FROM spieler WHERE username='$username' AND password='$password'");
		$result['content']="URL";
		if(isset($array['id'])) {
			$session = new Session($array['id']);
			setcookie("username",$username,3600);
			$result['target']="index.php?class=Insel";
		} else {
			$result['target']="index.php?class=Login&method=denied";
		}
		return $result;
	}
	
	function show(&$vars){
		$o = "";
		$o .= "<h3>Login</h3>";
		$form[] = array('input' => '<input type="text" name="username">', 'descr' => 'Benutzername');
		$form[] = array('input' => '<input type="password" name="password">', 'descr' => 'Passwort');
		$form[] = array('input' => '<input type="submit">', 'descr' => '&nbsp;');
		$o .= $this->getForm($form,'login','login','submit_login','GET');
		return $o;
	}
}
?>