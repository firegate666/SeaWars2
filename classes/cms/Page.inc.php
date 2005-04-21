<?
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
	
	function show(&$vars) {
		if($this->name=='') return error("Pagename not given",get_class($this),"show");
		$output = $this->getLayout(array(),$this->name);
		return $output;
	}
}
?>
