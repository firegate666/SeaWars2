<?
	$template_classes[] = 'navigation';

/**
 * Navigation bar... someday there this class won't be anymore
 * as it is useless
 */
class Navigation extends AbstractClass {
	
	function show(&$vars) {
		$o = $this->getLayout(array(), "main_bar", $vars);
		return $o;
	}
	
	function Navigation() {
	}
}
?>