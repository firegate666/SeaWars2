<?

abstract class AbstractClass extends AbstractNoNavigationClass {

	function getNavigation(&$vars) {
		$nav = new Navigation();
		return $nav->show($vars);
	}

}
?>
