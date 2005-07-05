<?
	Setting::set('gamespeed', '1', 'Game Speed Faktor', false);
	$template_classes[] = 'seawars';

/**
 * Main Class of the game, only layout purpose
 * has to removed as soon as navigation
 */
class SeaWars extends AbstractClass {
	var $navigation = '';
	var $mainbody   = '';
	var $layoutname = '';

	/**
	 * return id of logged in player
	 * 
	 * @return	int	spieler id
	 */
	function player() {
		return Session::getCookie('spieler_id');
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
		if(Login::isLoggedIn())
                  $array['username'] = Session::getCookie("username");
                else
                  $array['username'] = "";
		return $this->getLayout($array, $this->layoutname, $vars);
	}
}
?>
