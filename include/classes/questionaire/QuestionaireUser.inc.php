<?php

/**
 * these are the users who can answer
 */

$template_classes[] = 'questionaireuser';

class QuestionaireUser extends AbstractClass {

	public function acl($method) {
		if (($method == 'register') || ($method == 'registerform') || ($method == 'loginform') || ($method == 'logout') || ($method == 'login'))
			return true;
		return parent :: acl($method);
	}

	public function registerform($vars) {
		$array = array ();
		if (isset ($vars['err']))
			$array['err'] = $vars['err'];

		return $this->show($vars, 'registerform', $array);
	}

	public function loginform($vars) {
		$array = array ();
		if (isset ($vars['err']))
			$array['err'] = $vars['err'];

		return $this->show($vars, 'loginform', $array);
	}

	public function register($vars) {
		$err = false;
		if (!isset ($vars['email'], $vars['password'], $vars['password2']))
			$err[] = 'Email or password not submitted';
		$search = $this->search($vars['email'], 'email');
		if (count($search) != 0) {
			$err[] = "Diese Email ist bereits vergeben";
		}

		if (!$err) {
			$this->set('email', $vars['email']);
			$this->set('password', myencrypt($vars['password']));
			$this->store();
			$this->dologin();
			return redirect($vars['ref']);
		}
		$vars['err'] = implode("\n", $err);
		return $this->registerform($vars);
	}

	public function dologin() {
		Session :: setCookie('questionaireuserid', $this->id);
	}

	public function dologout() {
		Session :: removecookie('questionaireuserid');
	}

	public function logout($vars) {
		$this->dologout();
		if (isset ($vars['ref']))
			return redirect($vars['ref']);
		return redirect('index.php');
	}

	public function login($vars) {
		$err = false;
		if (!isset ($vars['email'], $vars['password']))
			$err[] = 'Email or password wrong';
		$result = $this->search($vars['email'], 'email');
		if (count($result) != 1)
			$err[] = 'User not found';
		if (!$err) {
			$q = new QuestionaireUser($result[0]['id']);
			$q->dologin();
			$q->store();
			return redirect($vars['ref']);
		}
		$vars['err'] = implode("\n", $err);
		return $this->loginform($vars);
	}

	public function LoggedIn() {
		return Session :: getCookie('questionaireuserid', false);
		//return 1; // TODO userlogin
	}
}
?>