<?php
class Army extends W40K {

	public function acl($method) {
		if ($method == 'delimage')
				return ($this->get('userid')==User::loggedIn())
					|| $this->hasright('admin')
					|| $this->hasright('w40kadmin');
		if ($method == 'edit')
			if ($this->exists())
				return ($this->get('userid')==User::loggedIn())
					|| $this->hasright('admin')
					|| $this->hasright('w40kadmin');
			else
				return $this->hasright('w40kuser_intern')
					|| $this->hasright('w40kuser_extern')
					|| $this->hasright('w40kadmin');
		if ($method == 'view')
			return true;
		if ($method == 'showlist')
			return true;
		return false;
	}

	function delimage($vars) {
		if (isset($vars['image'])) {
			$image = new Image($vars['image']);
			if ($image->exists() && ($image->get('parentid') == $this->get('id')))
				$image->delete();
		}
		return redirect($vars['ref']);
	}

	protected function numImages($id = null) {
		if ($id == null)
			$id = $this->get('id');
		
		$i = new Image();
		$where[] = "parent='army'";
		$where[] = "parentid=".$id;
		return count($i->advsearch($where, array('id')));
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
			else {
				$this->store();
				$array['error'] = "Object saved";
			}
		}
		$codex = new Codex();
		$array['codexlist'] = $codex->getOptionList($this->data['codex'], false);
		$image = new Image();
		$ilist = $image->getlist('', true, 'name', array('*'));
		$array['imagelist'] = "";
		foreach($ilist as $iobj) {
			if (($iobj['parent'] == $this->class_name()) && ($iobj['parentid'] == $this->get('id')))
				$array['imagelist'] .= $this->show($vars, 'army_edit_image', $iobj); 			
		}
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
		
		$b = new Battle();
		$battles = $b->getListByArmy($this->get('id'));
		$battlerows = "";
		foreach($battles as $entry) {
			$mission = new Mission($entry['mission']);
			$entry['missionname'] = $mission->get('name');
			$bt = new BattleType($entry['battletypeid']);
			$entry['battletypename'] = $bt->get('name');
			$battlerows .= $b->show($vars, 'battle_list_row', $entry);
		}
		$array['battlerows'] = $battlerows;
		$image = new Image();
		$ilist = $image->getlist('', true, 'name', array('*'));
		$array['imagelist'] = "";
		foreach($ilist as $iobj) {
			if (($iobj['parent'] == $this->class_name()) && ($iobj['parentid'] == $this->get('id')))
				$array['imagelist'] .= $this->show($vars, 'army_view_image', $iobj); 			
		}
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
			$entry['codexname'] = $codex->get('name');
			$u = new User($entry['userid']);
			$entry['username'] = $u->get('login');
			if (!empty($entry['comment']))
				$entry['hastext'] = "T";
			$entry['icount'] = $this->numImages($entry['id']);
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
                          'size' => 100000,
                          'notnull' => false);

		return $fields;
	}

}
?>