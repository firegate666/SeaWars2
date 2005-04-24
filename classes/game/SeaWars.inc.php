<?
	$template_classes[] = 'seawars';

class SeaWars extends AbstractClass {
	var $navigation = '';
	var $mainbody   = '';
	var $layoutname = '';

	function SeaWars($layout='main'){
		$this->layoutname = $layout;
	}
	
	function setNavigation($string){
		$this->navigation = $string;
	}
	function setMainBody($string){
		$this->mainbody = $string;
	}
	
	function show(){
		$array = array("navigation" => $this->navigation, "content" => $this->mainbody);
		if(Login::isLoggedIn())
                  $array['username'] = Session::getCookie("username");
                else
                  $array['username'] = "";
		return $this->getLayout($array, $this->layoutname);
	}	
}
?>
