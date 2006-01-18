<?php
/**
 * these are the users who can answer
 */
class QuestionaireUser extends AbstractClass {
	
	public function acl($method) {
		if (($method == 'register') ||
			($method == 'login'))
			return true;
		return parent::acl($method);
	}
	
	public function register($vars) {
	}
	
	public function login($vars) {
	}
	
	public function LoggedIn() {
		return 1; // TODO userlogin
	}
}
?>