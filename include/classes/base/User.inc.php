<?

	$template_classes[]='user';

class User extends AbstractClass {
	
	/**
	* returns id of logged in user, 0 if no one is logged in
	*
	* @return	integer	userid
	*/
	public function loggedIn() {
		return Session::getCookie('user', false);
	}
	
	public function acl($method) {
		if ($method=='logout')
			return true;
		if ($method=='login')
			return true;
		if ($method=='register')
			return true;
		return false;
	}
	
	public function logout($vars){
		Session::unsetCookie('user');
		return redirect($vars['ref']);
	}
	
	public function login($vars) {
		if (empty($vars['login']) || empty($vars['password']))
			return error('Login or password not send', 'user', 'login', $vars);
		$ids = $this->search($vars['login'], 'login');
		if (count($ids) != 1)
			return error('Login does not exist', 'user', 'login', $vars);
		$u = new User($ids[0]['id']);
		if (myencrypt($vars['password']) != $u->get('password'))
			return error('Password error', 'user', 'login', $vars);
		Session::setCookie('user', $u->get('id'));
		$u->store();
		return redirect($vars['ref']);
	}

	public function getFields() {
		$fields[] = array('name' => 'login',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'email',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'password',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);

		return $fields;
	}

	function parsefields($vars) {
		$err = false;
		if ((!isset($vars['password2'])) ||
			(!isset($vars['password'])) ||
				($vars['password2'] != $vars['password']))
			$err[] ='Passwords do not match';
		if (isset($vars['login']) && !empty($vars['login']))
			if(count($this->search($vars['login'], 'login'))>0)
				$err[] = 'Username already exists';
		if(!empty($vars['password']))
			$vars['password'] = myencrypt($vars['password']);
		$return = parent::parsefields($vars);
		if ($return && $err)
			return array_merge($err, $return);
		else if ($return)
			return $return;
		else if ($err)
			return $err;
		return false;
	}
	
	public function register($vars){
		$array = array();
		if (isset($vars['submit'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else {
				$this->store();
				$m = new Mailer();
				$from = get_config('sender', 'no reply');
				$to = $this->get('email');
				$subject = "User registration at ".get_config('system', 'smallCMS');
				$body = "User: ".$this->get('login')."\nPassword: ".$vars['password2'];
				$m->simplesend($from, $to, $subject, $body);
				return redirect($vars['ref']);
			}
		}
		return parent::show($vars, 'register', $array);
	}
}
?>