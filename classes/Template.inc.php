<?
class Template {
	protected $layout;
	
	function Template(){
	}
	
	function load($class){
	}
	
	function getLayout($class, $name, $vars){
		return "<h3>Überschrift ".$vars['title']."</h3>";
//		$this->load($class);
//		if(isset($this->layout[$name])) return $this->layout[$name];
//		else return "";
	}
}
?>