<?
	Setting::write('gamespeed', '1', 'Game Speed Faktor', false);
	Setting::write('baseurl', 'http://www.sea-wars.de/game2/index.php', 'System Base Url', false);//
	$template_classes[] = 'seawars';

/**
 * Main Class of the game, only layout purpose
 * has to removed as soon as navigation
 */
class SeaWars extends AbstractClass {
	protected $navigation = '';
	protected $mainbody   = '';
	protected $layoutname = '';

	/**
	 * return id of logged in player
	 * 
	 * @return	int	spieler id
	 */
	function player() {
		$playerid = Session::getCookie('spieler_id');
		if(empty($playerid))
			$playerid = 0;
		return $playerid;
	}
	
	function SeaWars($layout='main'){
		$this->layoutname = $layout;
	}
	
	function setNavigation($string){
		$this->navigation = $string;
	}
	function setMainBody($string){
		$this->mainbody = $string;
	}
	
	/**
	 * Show game frame
	 * @param	String[]	$vars	request parameter
	 */
	function show(&$vars){
		$array = array("navigation" => $this->navigation, "content" => $this->mainbody);
		$spieler = new Spieler(Session::getCookie("spieler_id"));
		if(Login::isLoggedIn()) {
			$array['username'] = $spieler->get('username');
			$array['punkte'] = $spieler->getPunkte();
		}
		return $this->getLayout($array, $this->layoutname, $vars);
	}
}
?>
