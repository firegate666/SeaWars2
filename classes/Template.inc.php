<?
class Template {
	protected $layout;
	
	function Template(){
	}
	
	function load($class){
	}
	
	function getClasses() {
		$DB = new MySQL();
		$result = $DB->select("SELECT DISTINCT class FROM template;");
		return $result;
	}
	function getLayouts($class) {
		$DB = new MySQL();
		$result = $DB->select("SELECT layout, id FROM template WHERE class='$class';");
		return $result;
	}
	
	function getLayout($class, $layout){
		$DB = new MySQL();
		$result = $DB->select("SELECT content FROM template WHERE class='$class' AND layout='$layout'");
		return $result[0][0];
//		$this->load($class);
//		if(isset($this->layout[$name])) return $this->layout[$name];
//		else return "";
	}
}
?>