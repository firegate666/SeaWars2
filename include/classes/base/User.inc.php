<?

$template_classes[]='user';
$__userrights[] = array('name'=>'useradmin', 'desc'=>'can edit users');
$__userrights[] = array('name'=>'disabled', 'desc'=>'denied login');

class User extends AbstractClass {
	
	/**
	* returns id of logged in user, 0 if no one is logged in
	*
	* @return	integer	userid
	*/
	public function loggedIn() {
		return Session::getCookie('user', false);
	}
	
	/**
	 * test rights for logged in user
	 */
	public function hasright($right) {
		$rights = Session::getCookie('userrights', array());
		return in_array($right, $rights);
	}
	
	public function acl($method) {
		if (($method=='logout')
				|| ($method == 'passwordreminder')
				|| ($method == 'resetpassword')
		)
			return true;
		if ($method=='edit')
			return $this->get('id') == $this->loggedIn();
		if ($method=='login')
			return true;
		if ($method=='register')
			return true;
		return false;
	}
	
	public function logout($vars){
		Session::cleanUpCookies();
		return redirect($vars['ref']);
	}
	
	public function login($vars) {
		if (empty($vars['login']) || empty($vars['password']))
			return error('Login or password not send', 'user', 'login', $vars);
		$ids = $this->search($vars['login'], 'login');
		if (count($ids) != 1)
			return error('Login does not exist', 'user', 'login', $vars);
		$u = new User($ids[0]['id']);
		if (myencrypt($vars['password']) != $u->get('password')) {
			$u->set('errorlogins', $u->get('errorlogins')+1);
			$u->store();
			return error('Password error', 'user', 'login', $vars);
		}
		Session::setCookie('user', $u->get('id'));
		Session::setCookie('usergroup', $u->get('groupid'));
		Session::setCookie('userrights', Usergroup::getUserrights($u->get('groupid')));
		if ($u->hasright('disabled')) {
			Session::cleanUpCookies();
			return error('User disabled', 'user', 'login', $vars);
		}
		$u->set('errorlogins', 0);
		$u->set('lastlogin', Date::now());
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
		$fields[] = array('name' => 'signature',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false);
		$fields[] = array('name' => 'show_email',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'password',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true,
                          'password' => true);
		$fields[] = array('name' => 'errorlogins',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'lastlogin',
                          'type' => 'date',
                          'notnull' => false);
		$fields[] = array('name' => 'hash',
                          'type' => 'string',
                          'notnull' => false);

		return $fields;
	}

	function parsefields($vars) {
		$err = false;
		if ((!isset($vars['password2'])) ||
			(!isset($vars['password'])) ||
				($vars['password2'] != $vars['password']))
			$err[] ='Passwords do not match';
		if (isset($vars['login']) && !empty($vars['login']))
			if (!($vars['login'] == $this->get('login')))
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
	
	public function resetpassword($vars) {
		if (isset($vars['hash'])) {
			if($this->get('hash') == $vars['hash']) {
				$array['newpass'] = randomstring(8);
				$this->set('hash', '');
				$this->set('password', myencrypt($array['newpass']));
				$this->store();
				$body = $this->show($vars, 'passwordmail', $array);
				$mailer = new Mailer();
				$mailer->simplesend(get_config('sender', 'marco@firegate.de'),
					$this->get('email'),
					'Password Reset', $body);
			}
		}
		if (isset($vars['destination']))
			return redirect($vars['destination']);
		return '';
	}
	
	public function passwordreminder($vars) {
		if (isset($vars['login'])) {
			$ids = $this->search($vars['login'], 'login');
			$u = new User($ids[0]['id']);
			$hash = randomstring(32);
			$array['hashlink'] = get_config('system', '').'/index.php?user/resetpassword/'.$u->get('id').'/hash='.$hash;
			$u->set('hash', $hash);
			$u->store();
			$body = $u->show($vars, 'remindermail', $array);
			$mailer = new Mailer();
			$mailer->simplesend(get_config('sender', 'marco@firegate.de'),
				$u->get('email'),
				'Password Reminder', $body);
		}
		if (isset($vars['destination']))
			return redirect($vars['destination']);
		return '';
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
	
	function edit(&$vars) {
		$array = array();
		if (isset($vars['submit'])) {
			$vars['groupid'] = $this->get('groupid');
			if (isset($vars['show_email']))
				$vars['show_email'] = 1;
			else
				$vars['show_email'] = 0;
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else {
				$this->store();
				$array['error'] = "Object saved";
			}
		}
		$array['show_email'] = '';
		if ($this->get('show_email') == 1)
			$array['show_email'] = 'CHECKED="CHECKED"';
		return parent::show($vars, 'edit', $array);
	}
	
	
}
?>