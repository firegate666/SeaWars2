<?
class AbstractNoNavigationClass {
	function getNavigation() {
		return "&nbsp;";
	}
	function getForm($content='', $class='', $method='',$name='MyForm') {
		if(empty($class)) $class = $_REQUEST['class'];
		if(empty($method)) $method = $_REQUEST['method'];
		$o = '';
		$o .= '<form action="index.php" name="'.$name.'" METHOD="POST">';
		$o .= '<input type="hidden" name="class" value="'.$class.'">';
		$o .= '<input type="hidden" name="method" value="'.$method.'">';
		if(is_string($content))
			$o .= $content;
		else {
			$o .= '<table>';
			foreach($content as $input) {
				if($input['descr']=='') $o .= $input['input'];
				else $o .= HTML::tr('<td>'.$input['descr'].'</td>' .
							'<td>'.$input['input'].'</td>');
			}
			$o .= '</table>';
		}
		$o .= '</form>';
		return $o;
	}
}
?>