<?
/**
* These classes have a navigation bar
*/
abstract class AbstractNavigationClass extends AbstractNoNavigationClass {

	/**
	* get Navigation bar set in index.php
	*/
	function getNavigation(&$vars) {
		$nav = new Navigation();
		return $nav->show($vars);
	}

}
?>
