<?
/**
 * HTML Wrappers, much improved has to done
 */
class HTML {
	
	/**
	* build html tag
	* 
	* @param	String	$name	name of tag
	* @param	String	$content	contents of tag
	* @param	String	$attr	attributes of tag array('name', 'value')
	* @param	boolean	$closing	if true, closing tag is added, else a single tag is created
	* @return	String	build tag
	*/
	function tag($name, $content='', $attr = array(), $closing=true) {
		$adds = '';
		if(is_array($attr)) {
			foreach($attr as $item)
				$attr .= $item['name'].'="'.$item['value'].'" ';
		} else {
			$adds = $attr;
		}
		
		$tag = "<$name $adds";
		if($closing) $tag .= ">$content</$name>";
		else $tag .= " />$content";
		return $tag;
	}	
	
	function tr($content) {
		return $this->tag('tr', $content);
	}
	
	function td($content) {
		return $this->tag('td', $content);
	}
	
	function table($content) {
		if(!is_array($content)) {
			return $this->tag('table', $content);
		} else {
			$rows = '';
			foreach($content as $row) {
				$cells = '';
				foreach($row as $item) {
					$cells .= HTML::td($item);
				}
				$rows .= HTML::tr($cells); 
			}
			return $this->tag('<table>', $rows);
			
		}
	}	
}
?>