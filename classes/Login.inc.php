<?
	class Login extends AbstractNoNavigationClass {
		function login(&$vars){
			// Passwort überprüfen
			$result['content']="URL";
			$result['target']="index.php?class=Insel";
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