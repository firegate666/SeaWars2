<?
class HTML {
	function tag($name, $content='', $attr = array(), $closing=true) {
		$adds = '';
		if(is_array($attr)) {
			foreach($attr as $item)
				$attr .= $item['name'].'="'.$item['value'].'" ';
		} else {
			$adds = $attr;
		}
		
		$tag = "<$name $adds>$content";
		if($closing) $tag .= "</$name>";
		return $tag;
	}	
	function tr($content) {
		return "<tr>$content</tr>\n";
	}
	
	function td($content) {
		return "<td>$content</td>\n";
	}
	
	function table($content) {
		if(!is_array($content)) {
			return '<table>'.$content.'</table>';
		} else {
			$rows = '';
			foreach($content as $row) {
				$cells = '';
				foreach($row as $item) {
					$cells .= HTML::td($item);
				}
				$rows .= HTML::tr($cells); 
			}
			return '<table>'.$rows.'</table>';
			
		}
	}	
}
?>