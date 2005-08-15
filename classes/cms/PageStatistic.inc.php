<?
/**
 * This class ist for counting page statistics
 */
 
class PageStatistic extends AbstractNoNavigationClass {

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'pagename', 'type' => 'string', 'notnull' => true);
		$fields[] = array('name' => 'timestamp', 'type' => 'timestamp', 'notnull' => true);
		$fields[] = array('name' => 'user', 'type' => 'integer', 'notnull' => false);
		return $fields;
	}	
	
	function store() {
		$userid = User::loggedIn();
		if($userid == 0)
			$userid = null;
		$this->set('user', $userid);
		$this->set('timestamp', Date::now());
		parent::store();
	}

}
?>