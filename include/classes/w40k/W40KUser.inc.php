<?php
class W40KUser extends W40K {
	protected $user;

	function acl($method) {
		if ($method == 'view')
			return $this->loggedIn();
		return false;
	}

	function view($vars){
		$array = $this->user->getData();
		if ($this->user->get('show_email') == '0')
			$array['email'] = '';
		$ug = new Usergroup($array['groupid']);
		$array['groupname'] = $ug->get('name');
		
		
		return parent::show($vars, 'user_view', $array);
	}

	public function W40KUser($id='') {
		$this->user = new User($id);
		$this->data['id'] = $this->user->get('id');
	}
	
}
?>