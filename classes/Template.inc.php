<?
class Template {
	protected $layout;
	
	function Template(){
	}
	
	function load($class){
	}
	
	function getLayout($class, $name){
		$this->load($class);
		if(isset($this->layout[$name])) return $this->layout[$name];
		else return "";
	}
}
?>