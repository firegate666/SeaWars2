<?php
class Army extends W40K {

	public function acl($method) {
		if ($method == 'edit')
			if ($this->exists())
				return $this->get('userid')==User::loggedIn();
			else
				return User::loggedIn();
		if ($method == 'view')
			return true;
		if ($method == 'showlist')
			return true;
		return false;
	}

	function parsefields($vars){
		if ($this->get('userid')==null)
			$vars['userid'] = User::loggedIn();
		else
			$vars['userid'] = $this->get('userid');
		return parent::parsefields($vars);
	}

	function edit(&$vars) {
		$array = array();
		if (isset($vars['submit'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else
				$this->store();
		}
		$codex = new Codex();
		$array['codexlist'] = $codex->getOptionList($this->data['codex'], false);
		return parent::show($vars, 'army_edit', $array);
	}

	function view(&$vars) {
		$array['battlecount'] = "battlecount";
		$array['win'] = "win";
		$array['deuce'] = "deuce";
		$array['lost'] = "lost";
		$array['gallery'] = "gallery";
		$array['battles'] = "battles";
		$codex = new Codex($this->get('codex'));
		$array['codexname'] = $codex->get('name');
		return parent::show($vars, 'army_view', $array);
	}

	function showlist(&$vars) {
		$orderby = "name";
		if (isset($vars['orderby']))
			$orderby = mysql_escape_string($vars['orderby']);
		$list = $this->getlist('', true, $orderby,
				array('id',
					'name',
					'commander',
					'codex',
					'userid',
					'comment',
				));
		$rows = '';
		foreach($list as $entry) {
			$codex = new Codex($entry['codex']);
			$entry['codex'] = $codex->get('name');
			$u = new User($entry['userid']);
			$entry['user'] = $u->get('login');
			if (!empty($entry['comment']))
				$entry['hastext'] = "T";
			$rows .= parent::show($vars, 'army_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'army_list', $array);
	}


	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'userid',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'points',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'commander',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'codex',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'comment',
                          'type' => 'string',
                          'size' => 10000,
                          'notnull' => true);

		return $fields;
	}

}
?>