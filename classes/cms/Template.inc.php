<?
/**
 * Templatehandling
 */
class Template {
	var $layout;
	var $tags=array();
	
	function acl(){
		return false;
	}
	
	/**
	 * Remove all not substituted tags from $template
	 */
	function removeLostTags(&$template) {
		$suchmuster = '/\$\{.*\}/i';
		$template = preg_replace($suchmuster,'',$template);
	}
	
	/**
	 * parse $template for known tags and return them
	 */
	function parseTags($template){
		$result = array();
		$suchmuster = '/\$\{(\w*):(\w*)\}/i';
		$temp = array();
		preg_match_all($suchmuster, $template, $temp, PREG_SET_ORDER);
		foreach($temp as $item) {
			$result[$item[1].':'.$item[2]] = array('type' => $item[1], 'value' => $item[2]);
		}
		$this->tags = $result;
	}
	
	/**
	 * Delete template
	 * @class	category
	 * @layout	name
	 */
	function deleteTemplate($class, $layout) {
		global $mysql;
		$query = "DELETE FROM template WHERE class='$class' AND layout='$layout';";
		$mysql->update($query);
	}

	/**
	 * Create template
	 * @class	category
	 * @layout	name
	 */
	function createTemplate($class, $layout) {
		global $mysql;
		$query = "INSERT INTO template(class, layout) VALUES('$class', '$layout');";
		$mysql->insert($query);
	}
	
	function Template(){
	}
	
	/**
	 * get all template classes
	 */
	 function getClasses() {
		global $template_classes;
		if(!isset($template_classes) || empty($template_classes))
			$template_classes = array();
		sort($template_classes);
		return $template_classes;
	}
	
	/**
	 * get all layouts for $class
	 */
	function getLayouts($class) {
		$DB = new MySQL();
		$result = $DB->select("SELECT layout, id FROM template WHERE class='$class';");
		return $result;
	}
	
	/**
	 * return template from cache or false
	 * @class	category
	 * @layout	template name
	 * @return	false if not found or
	 * 			template as string
	 */
	function getLayoutCached($class, $layout) {
		return false;
	}
	
	/**
	 * Returns parsed template
	 * @class	category
	 * @layout	template name
	 * @array	array of elements to replace tags in template
	 * @noparse	if true, no replacement is made
	 * @return	template as string
	 */
	function getLayout($class, $layout,$array,$noparse=false){
		global $_CONFIG;
		// hier überprüfen, ob cache vorhanden
		//$string = Template::getLayoutCached($class, $layout);
		//if($string === false) {
			$DB = new MySQL();
			$result = $DB->select("SELECT content FROM template WHERE class='$class' AND layout='$layout'");
			$string = $result[0][0];
		//}
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