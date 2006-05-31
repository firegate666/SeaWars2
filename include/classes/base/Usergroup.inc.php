<?

class Usergroup extends AbstractClass {
	
	public function acl($method) {
		return false;
	}
	
	public function getFields() {
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		return $fields;
	}
	
	public function setUserrights($array) {
		global $mysql;
		if (!$this->exists())
			return;
		if (!is_array($array))
			return;
		$mysql->update("DELETE FROM userrights WHERE usergroupid = ".$this->get('id').";");
		foreach($array as $priv=>$value) {
			$ur = new Userrights();
			$ur->set('userright', $priv);
			$ur->set('usergroupid', $this->get('id'));
			$ur->store(); 
		}
	}
	
	public function hasright($right) {
		$rights = $this->getUserrights();
		return in_array($right, $rights);
	}
	
	public function getUserrights($id = null) {
		if ($id == null)
			$id = $this->get('id');
		if (empty($id))
			return array();
		global $mysql;
		$list = $mysql->select("SELECT userright FROM userrights WHERE usergroupid=".$id.";", true);
		$result = array();
		foreach($list as $item)
			$result[] = $item['userright'];
		return $result;
	}
}

class Userrights extends AbstractClass {
	public function acl($method) {
		return false;
	}
}
?>