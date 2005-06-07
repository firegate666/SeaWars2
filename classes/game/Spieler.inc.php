<?	$template_classes[] = 'spieler';
/** * there he is, the player himself */class Spieler extends AbstractClass {

	/**	 * get all player ids	 * private function I guess	 */	function playerids() {		global $mysql;		$result = $mysql->select("SELECT id FROM spieler", true);		return $result;	}		/**	 * show highscore table	 */	function highscore(&$vars) {		$ids = $this->playerids();		$result = '';		foreach($ids as $id) {			$p = new Spieler($id['id']);			$array['spieler'] = $p->data['username']; 			$array['punkte'] = $p->data['punkte']; 			$result .= $this->getLayout($array, "highscore_row");		}		$array['rows'] = $result;		return $this->getLayout($array, "highscore");	}	function acl($method) {		if($method == 'highscore') return true;
		return false;
	}
}
?>