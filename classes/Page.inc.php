<?
class Page extends AbstractNoNavigationClass {
	
	var $name = '';
	
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
		if(empty($output)) return error("Page ".$this->name." is empty",get_class($this),"show");
		// parse for embedded pages
		return $output;
	}
}
?>