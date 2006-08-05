<?php
Setting::write('w40kuser_defaultpagelimit', '', 'W40K User Default Pagelimit', false);

class W40KUser extends W40K {
	protected $user;
	protected $armies;

	function acl($method) {
		if ($method == 'view')
			return $this->loggedIn();
		if ($method == 'showlist')
			return $this->loggedIn();
		return false;
	}

	function view($vars){
		$array = $this->user->getData();
		if ($this->user->get('show_email') == '0')
			$array['email'] = '';
		$ug = new Usergroup($array['groupid']);
		$array['groupname'] = $ug->get('name');
		
		$armyrows = '';
		$army = new Army();
		foreach($this->armies as $entry) {
			$codex = new Codex($entry['codex']);
			$entry['codexname'] = $codex->get('name');
			$entry['username'] = $this->user->get('login');
			if (!empty($entry['comment']))
				$entry['hastext'] = "T";
			$entry['icount'] = $army->numImages($entry['id']);
			$armyrows .= parent::show($vars, 'army_list_row', $entry);
		}
		
		$array['armyrows'] = $armyrows;
		
		return parent::show($vars, 'user_view', $array);
	}

	public function W40KUser($id='') {
		$this->user = new User($id);
		$this->data['id'] = $this->user->get('id');
		
		$army = new Army();
		$this->armies = $army->getlist('', true, 'id', array('*'), '', '',
										array(array('key'=>'userid', 'value'=>$this->user->get('id'))));
	}

	function showlist(&$vars) {
		$orderby = "id";
		if (isset($vars['orderby']))
			$orderby = $this->escape($vars['orderby']);
		$limit = Setting::read('w40kuser_defaultpagelimit');
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = $this->escape($vars['limit']);
			$limitstart = $this->escape($vars['limitstart']);
		} else if (isset($vars['limit']))
			$limit = '';
		$list = $this->user->getlist('', true, $orderby,
				array('id',
					'login',
					'groupid',
				), $limitstart, $limit);
		$array['orderby'] = $orderby;
		$array['prevlimit'] = '';
		$array['nextlimit'] = '';
		$array['limit'] = '';
		$array['limitstart'] = '';
		if ($limit != '') {
			$array['prevlimit'] = $limitstart - $limit;
			if ($array['prevlimit'] < 0)
				$array['prevlimit'] = 0;
			$array['nextlimit'] = '';
			if (count($list)==$limit)
				$array['nextlimit'] = $limitstart + $limit;
			$array['limit'] = $limit;
			$array['limitstart'] = $limitstart;
		}
		$rows = '';
		foreach($list as $entry) {
			$ug = new Usergroup($entry['groupid']);
			$entry['groupname'] = $ug->get('name');
			$rows .= parent::show($vars, 'user_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'user_list', $array);
	}
	
}
?>