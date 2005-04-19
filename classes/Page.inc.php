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

	function show(&$vars) {
		if($this->name=='') return error("Pagename not given",get_class($this),"show");
		$output = $this->getLayout(array(),$this->name);
		$this->parseTags($output);
		if(empty($output)) return error("Page ".$this->name." is empty",get_class($this),"show");
                $array = array();
                foreach($this->tags as $key=>$item) {
                      $p = new $item['type']($item['value']);
                      $array[$key] = $p->show(&$vars);
                }
		$output = $this->getLayout($array,$this->name);
		$this->removeLostTags($output);
		return $output;
	}
}
?>
