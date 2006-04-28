<?
/**
 * This class ist for counting page statistics
 */
/*SELECT template, count( id ) as aufrufe
FROM pagestatistic
GROUP  BY template
ORDER BY aufrufe DESC*/
class PageStatistic extends AbstractClass {

	public function acl($method) {
		if ($method == 'show')
			return true;
		else
			return parent::acl($method);
	}
	
	public function show($vars) {
		global $mysql;
       	$result = $mysql->select("SELECT template, count( id ) as aufrufe
									FROM pagestatistic
									GROUP  BY template
									ORDER BY aufrufe DESC", true);
		$o = '<html><head><title>Page Statistics</title></head><body>';
		$o .= '<table><tr><th>Seitenname</th><th>Anzahl Aufrufe</th></tr>';
		foreach($result as $page) {
			$o .= '<tr><td align="center">'.$page['template'].'</td>';
			$o .= '<td align="center">'.$page['aufrufe'].'</td></tr>';
		}
		$o .= '</table></body></html>';
		return $o;
	}

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'pagename', 'type' => 'string', 'notnull' => true);
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