<?
class Page extends AbstractNoNavigationClass {
	
	var $name = '';
	
	function acl($method) {
		return true;
	}
	
	function Page($name='') {
		$this->name = $name;
	}	
	
	function show(&$vars) {
		if($name=='') return error("Pagename not given does not exist",get_class($this),"show");
		$output = $this->get_template($this->name);
		// parse for embedded pages
		return $output;
	}
}
?>