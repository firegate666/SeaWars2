<?
	$template_classes[] = 'page';
	
class Page extends AbstractNoNavigationClass {
	
	var $name = '';
	var $tags = '';
	
	function acl($method) {
		return true;
	}
	
	function Page($name='') {
		if(empty($name)) $name='index';
		$this->name = $name;
	}	
	
	function adminbar($layout){
		$result = '';
		$result .= '<a href="';
		$result .= 'http://localhost/seawars2/index.php?admin&template&tpl_class=page&tpl_layout='.$layout;
		$result .= '" TARGET="_BLANK">Edit Template</a> - <a href="index.php?admin&logout">Adminlogout</a><hr>';
		return $result;
	}
	
	function show(&$vars) {
		if($this->name=='') return error("Pagename not given",get_class($this),"show");
		$output = $this->getLayout(array(),$this->name);
		if(isset($_COOKIE['adminlogin']))
			$output = $this->adminbar($this->name).$output;
		return $output;
	}
}
?>