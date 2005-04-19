<?
class Spieler extends AbstractClass {

      function acl($method) {
                            	if($method=='register') return true;
                            	if($method=='register2') return true;
                            	else return false;
      }

      function playerExists($username) {
                              	global $mysql;
                              	$array = $mysql->executeSql("SELECT id, username FROM spieler WHERE username='".$username."';");
                              	return (!empty($array));
      }
      function emailExists($email) {
                              	global $mysql;
                              	$array = $mysql->executeSql("SELECT id, username FROM spieler WHERE email='".$email."';");
                              	return (!empty($array));
      }

    function register2(&$vars) {
        if(isset($vars['username']))  Session::setCookie('register_username',  $vars['username']);
        if(isset($vars['password']))  Session::setCookie('register_password',  $vars['password']);
        if(isset($vars['password2'])) Session::setCookie('register_password2', $vars['password2']);
        if(isset($vars['email']))     Session::setCookie('register_email',     $vars['email']);

        if($vars['password'] != $vars['password2']) $error .= 'Passwörter nicht gleich<br>';
        if($this->playerExists($vars['username']))  $error .= 'Benutzername bereits vergeben<br>';
        if($this->emailExists($vars['email']))  $error .= 'Email bereits vergeben<br>';

        if(isset($error)) $target = "index.php?class=spieler&method=register&error=$error";
        else {
             	$spieler = new Spieler();
             	$spieler->data["username"] = $vars['username'];
             	$spieler->data["password"] = $vars['password'];
             	$spieler->data["email"] = $vars['email'];
             	$spieler->store();
             	$target = "index.php?class=login";
        }
        return redirect($target);
    }

    function register(&$vars) {
        $array['username']  = Session::getCookie("register_username");
        $array['password']  = Session::getCookie("register_password");
        $array['password2'] = Session::getCookie("register_password2");
        $array['email']     = Session::getCookie("register_email");
        $array['error']     = $vars['error'];
	return $this->getLayout($array, "register");
    }

}
?>
