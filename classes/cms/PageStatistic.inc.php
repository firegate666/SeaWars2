<?
/**
 * This class ist for counting page statistics
 */
/*SELECT template, count( id ) as aufrufe
FROM pagestatistic
GROUP  BY template
ORDER BY aufrufe DESC*/
class PageStatistic extends AbstractTimestampClass {

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'pagename', 'type' => 'string', 'notnull' => true);
		$fields[] = array('name' => 'timestamp', 'type' => 'timestamp', 'notnull' => true);
		$fields[] = array('name' => 'user', 'type' => 'integer', 'notnull' => false);
		$fields[] = array('name' => 'ip', 'type' => 'string', 'notnull' => true);
		return $fields;
	}	
	
	function store() {
		$userid = User::loggedIn();
		if($userid == 0)
			$userid = null;
		$this->set('user', $userid);
		$this->set('ip', getClientIP());
		parent::store();
	}

}
?>