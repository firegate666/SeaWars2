<?
	Setting::set('ttpointsfaktor', 0.1, 'Faktor mit denen erforschte Techs in die Punktzahl eingehen', false);

	$template_classes[] = 'spieler';

/**
 * there he is, the player himself
 */
class Spieler extends AbstractClass {

	function ttpoints($spieler_id = '') {
		global $mysql;
		if(empty($spieler_id))
			$spieler_id = $this->id;
		$query = "SELECT sum(ttentry.aufwand) as punkte FROM ttentry, ttexplored
				WHERE techtree_entry_id = ttentry.id AND spieler_id=".$spieler_id.";";
		$result = $mysql->executeSql($query);
		
		return $result['punkte'] * Setting::get('ttpointsfaktor', 1);
	}
	
	/**
	 * get all player ids
	 * private function I guess
	 */
	function playerids() {
		global $mysql;
		$result = $mysql->select("SELECT id FROM spieler", true);
		return $result;
	}
	
	/**
	 * show highscore table
	 * @param	String[]	$vars	request parameter
	 */
	function highscore(&$vars) {
		$ids = $this->playerids();
		$result = '';
		foreach($ids as $id) {
			$p = new Spieler($id['id']);
			$array['spieler'] = $p->data['username']; 
			$array['punkte'] = $p->data['punkte']+$p->ttpoints(); 
			$result .= $this->getLayout($array, "highscore_row", $vars);
		}
		$array['rows'] = $result;
		return $this->getLayout($array, "highscore", $vars);
	}

	function acl($method) {
		if($method == 'highscore') return true;
		return false;
	}
}
?>
