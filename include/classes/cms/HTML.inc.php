<?
/**
 * HTML Wrappers, much improved has to done
 */
class HTML {

	public function convert_specialchars($string) {
		$string = str_replace('&', '&amp;', $string);
		$string = str_replace('ä', '&auml;', $string);
		$string = str_replace('ö', '&ouml;', $string);
		$string = str_replace('ü', '&uuml;', $string);
		$string = str_replace('Ä', '&Auml;', $string);
		$string = str_replace('Ö', '&Ouml;', $string);
		$string = str_replace('Ü', '&Uuml;', $string);
		$string = str_replace('ß', '&szlig;', $string);
		return $string;
	}
	
	function input($type, $name, $value, $length=false) {
		$attr[] = array('name' => 'name', 'value' => $name);
		$attr[] = array('name' => 'type', 'value' => $type);
		$attr[] = array('name' => 'value', 'value' => $value);
		if($length !== false)
			$attr[] = array('name' => 'maxlength', 'value' => $length);
		return HTML::tag('input', '', $attr, false);
	}
	
	function textarea($name, $value, $cols=50, $rows=10) {
		$attr[] = array('name' => 'name', 'value' => $name);
		$attr[] = array('name' => 'cols', 'value' => $cols);
		$attr[] = array('name' => 'rows', 'value' => $rows);
		return HTML::tag('textarea', $value, $attr, true);
	}

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
				$adds .= $item['name'].'="'.$item['value'].'" ';
		}
		
		$tag = "<$name $adds";
		if($closing) $tag .= ">$content</$name>";
		else $tag .= " />$content";
		return $tag."\n";
	}	
	
	function tr($content) {
		return HTML::tag('tr', $content);
	}
	
	function td($content) {
		return HTML::tag('td', $content);
	}
	
	function table($content, $border = 0, $header = "", $footer = "") {
		if(!is_array($content)) {
			return HTML::tag('table', $header.$content.$footer);
		} else {
			$rows = '';
			foreach($content as $row) {
				$cells = '';
				foreach($row as $item) {
					$cells .= HTML::td($item);
				}
				$rows .= HTML::tr($cells); 
			}
			$rows = $header.$rows.$footer;
			return HTML::tag('table', $rows, array(array('name'=>'border','value'=>$border)));
			
		}
	}	
}
?>