<?
class AbstractClass extends AbstractNoNavigationClass {

	function getNavigation() {
		$nav = new Navigation();
		return $nav->show();
	}

}
?>