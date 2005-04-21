<?
class Template {
	var $layout;
	var $tags=array();
	
	function acl(){
		return false;
	}
	
	function removeLostTags(&$template) {
		$suchmuster = '/\$\{.*\}/i';
		$template = preg_replace($suchmuster,'',$template);
	}
	
	function parseTags($template){
		$result = array();
		$suchmuster = '/\$\{(.*):(.*)\}/i';
		$temp = array();
		preg_match_all($suchmuster, $template, $temp, PREG_SET_ORDER);
		foreach($temp as $item) {
			$result[$item[1].':'.$item[2]] = array('type' => $item[1], 'value' => $item[2]);
		}
		$this->tags = $result;
	}
	
	function deleteTemplate($class, $layout) {
		global $mysql;
		$query = "DELETE FROM template WHERE class='$class' AND layout='$layout';";
		$mysql->update($query);
	}

	function createTemplate($class, $layout) {
		global $mysql;
		$query = "INSERT INTO template(class, layout) VALUES('$class', '$layout');";
		$mysql->insert($query);
	}
	
	function Template(){
	}
	
	function getClasses() {
		$DB = new MySQL();
		//$result = $DB->select("SELECT DISTINCT class FROM template;");
		$result = $DB->select("SHOW tables;");
		return $result;
	}
	function getLayouts($class) {
		$DB = new MySQL();
		$result = $DB->select("SELECT layout, id FROM template WHERE class='$class';");
		return $result;
	}
	
	function getLayout($class, $layout,$array,$noparse=false){
		$DB = new MySQL();
		$result = $DB->select("SELECT content FROM template WHERE class='$class' AND layout='$layout'");
		$string = $result[0][0];
		if($noparse) return $string;
		$keys = array_keys($array);
		foreach($keys as $key) {
			$string = str_replace('${'.$key.'}',$array[$key],$string);
		}
		$this->parseTags($string);
                $array = array();
                foreach($this->tags as $key=>$item) {
                      $p = new $item['type']($item['value']);
                      $array[$key] = $p->show(&$vars);
                }
		$keys = array_keys($array);
		foreach($keys as $key) {
			$string = str_replace('${'.$key.'}',$array[$key],$string);
		}
		$this->removeLostTags($string);
		return $string;
	}
}
?>