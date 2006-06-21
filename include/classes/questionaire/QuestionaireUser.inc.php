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
		$array['questionaireid'] = '';
		if (!empty($vars['questionaireid']))
			$array['questionaireid'] = $vars['questionaireid'];
		if (isset ($vars['err']))
			$array['err'] = $vars['err'];

		return $this->show($vars, 'registerform', $array);
	}

	public function loginform($vars) {
		$array = array ();
		$array['questionaireid'] = '';
		if (!empty($vars['questionaireid']))
			$array['questionaireid'] = $vars['questionaireid'];
		if (isset ($vars['err']))
			$array['err'] = $vars['err'];

		return $this->show($vars, 'loginform', $array);
	}

	public function sendmail($email, $password, $from='') {
		if (empty($from))
			$from = $this->get('email');
		$to = $this->get('email');
		$subject = "Benutzerdaten";
		$body = "Ihre Daten für das Fragebogensystem lauten:\n\nEmail: $email\nPassort: $password";		
		$m = new Mailer();
		$m->simplesend($from, $to, $subject, $body);
		
	}

	public function register($vars) {
		$err = false;
		if (empty($vars['email']) || empty($vars['password']) || empty($vars['password2']))
			$err[] = 'Email or password not submitted';

		$search = $this->search($vars['email'], 'email');
		if (count($search) != 0)
			$err[] = "Diese Email ist bereits vergeben";

		if ($vars['password'] != $vars['password2'])
			$err[] = "Die Passwörter müssen übereinstimmen";

		if (!$err) {
			$this->set('email', $vars['email']);
			$this->set('password', myencrypt($vars['password']));
			$this->store();
			$sender = '';
			if (!empty($vars['questionaireid'])) {
				$questionaire = new Questionaire($vars['questionaireid']);
				if ($questionaire->exists())
					$sender = $questionaire->get('email');
			}
			$this->sendmail($vars['email'], $vars['password'], $sender);
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
		if (empty($vars['email']) || empty($vars['password']))
			$err[] = 'Email or password wrong';
		if (!$err)
			$result = $this->search($vars['email'], 'email');
		if (count($result) != 1)
			$err[] = 'User not found';
		if (!$err) {
			$q = new QuestionaireUser($result[0]['id']);
			if ($q->get('password') == myencrypt($vars['password'])) {
				$q->dologin();
				$q->store();
				if ($q->get('lastquestionaire') != 0)
					return redirect('index.php?questionaire/show/'.$q->get('lastquestionaire'));
				return redirect($vars['ref']);
			} else
				$err[] = 'Password wrong';
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