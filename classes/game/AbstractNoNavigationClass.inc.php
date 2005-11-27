<?
/**
 * The main features everyone should know
 */
abstract class AbstractNoNavigationClass extends AbstractClass{
	
	function getMainLayout() {
		return 'main';
	}

	function getNavigation(&$vars) {
		return "&nbsp;";
	}
}
?>