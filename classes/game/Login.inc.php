<?	// if not exists set pwdstyle	Setting::set('pwdstyle', '(\w|\d){5,}', 'Password complexity (regex)', false);	$template_classes[] = 'login';/** * Userlogin, maybe this can be moved to spieler sometime */class Login extends AbstractNoNavigationClass {	function getMainLayout() {		return 'login_logout';	}	/**	 * is there a player with that name?	 * @username	username	 * @return		true/false	 */	function playerExists($username) {		global $mysql;		$username = mysql_real_escape_string($username);		$array = $mysql->executeSql("SELECT id, username FROM spieler WHERE username='".$username."';");		return (!empty ($array));	}		/**	 * is there a player with that email?	 * @email	email	 * @return	true/false	 */	function emailExists($email) {		global $mysql;		$email = mysql_real_escape_string($email);		$array = $mysql->executeSql("SELECT id, username FROM spieler WHERE email='".$email."';");		return (!empty ($array));	}	/**	* check passwordstyle of given password	* @param	String	$password	password as String	* @return	boolean	true if correct, false if not	*/	function checkpasswordstyle($password) {        $pattern = Setting::get('pwdstyle', '');        if (!empty($pattern))            if (!preg_match('/'.$pattern.'/', $password))            	return false;            else            	return true;		return true;	}	/**	 * register player	 * redirect to login or show error	 */	function register2(& $vars) {		if (isset ($vars['username']))			Session :: setCookie('register_username', $vars['username']);		if (isset ($vars['password']))			Session :: setCookie('register_password', $vars['password']);		if (isset ($vars['password2']))			Session :: setCookie('register_password2', $vars['password2']);		if (isset ($vars['email']))			Session :: setCookie('register_email', $vars['email']);		if ($vars['password'] != $vars['password2'])			$error .= 'Passw�rter nicht gleich<br>';		if (!$this->checkpasswordstyle($vars['password']))			$error .= 'Passwort zu einfach<br>';		if ($this->playerExists($vars['username']))			$error .= 'Benutzername bereits vergeben<br>';		if ($this->emailExists($vars['email']))			$error .= 'Email bereits vergeben<br>';		if (isset ($error))			$target = "index.php?class=login&method=register&error=$error";		else {			$spieler = new Spieler();			$spieler->data["username"] = $vars['username'];			$spieler->data["password"] = $vars['password'];			$spieler->data["email"] = $vars['email'];			$spieler->store();			$spieler_id = $spieler->id;			$inseln = Insel :: getStartIslands();			$anzahl = count($inseln);			$insel = new Insel($inseln[rand(0, $anzahl)][0]);			$insel->data['spieler_id'] = $spieler_id;			$insel->store();			$target = "index.php?class=login";		}		return redirect($target);	}		/**	 * show register dialog	 */	function register(& $vars) {		$array['username'] = Session :: getCookie("register_username");		$array['password'] = Session :: getCookie("register_password");		$array['password2'] = Session :: getCookie("register_password2");		$array['email'] = Session :: getCookie("register_email");		$array['error'] = $vars['error'];		return $this->getLayout($array, "register", $vars);	}		function acl($method) {		$method = strtolower($method);		if ($method == 'register') {			return true;		} else if ($method == 'register2') {			return true;		} else if ($method == 'logout') {			return true;		} else if ($method == 'login') {			return true;		} else if ($method == 'show') {			return true;		} else {			return false;		}	}		/**	 * is there a logged in player?	 */	function isLoggedIn() {		return isset ($_COOKIE["username"]);	}		/**	 * show denied if login fails	 * bad style, has to be improved	 */	function denied() {		return error("Zugang verweigert", $this->class_name, "denied");	}		/**	 * logout player, reset session has to be 	 * implemented in session	 */	function logout(& $vars) {		setcookie("username", "", 0);		return redirect("index.php");	}		/**	 * login player	 */	function login(& $vars) {		if ($this->isLoggedIn())			$this->logout($vars);		$DB = new MySQL();		// Passwort �berpr�fen		$username = mysql_real_escape_string($vars['username']);		$password = mysql_real_escape_string($vars['password']);		$array = $DB->select("SELECT id FROM spieler WHERE username='$username' AND password='$password'");		$result['content'] = "URL";		$target = '';		if (count($array) == 1) {			setcookie("username", $username, NULL);			setcookie("spieler_id", $array[0][0], NULL);			$result['target'] = "index.php?class=inselliste&mode=OWN";			$target = "index.php?class=Inselliste";		} else {			$result['target'] = "index.php?class=Login&method=denied";			$target = "index.php?class=Login&method=denied";		}		return redirect($target);	}		function show(& $vars) {		$array = array ("title" => "Login", "lbl_username" => "Benutzername", "lbl_password" => "Passwort", "lbl_login" => "Anmelden");		return $this->getLayout($array, "login_window", $vars);	}}?>