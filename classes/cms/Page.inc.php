<?
	$template_classes[] = 'page';
	
/**
 * This is a page
 */
class Page extends AbstractNoNavigationClass {
	
	var $name = '';
	var $tags = '';
	
	function acl($method) {
		return true;
	}
	
	function Page($name='') {
		if(empty($name)) 
			error("No page name given", "Page", "Constructor");
		$this->name = $name;
	}	
	
	/**
	 * if admin is logged in, show adminbar
	 */
	function adminbar($layout){
		$result = '';
		$result .= '<a href="';
		$result .= 'index.php?admin&template&tpl_class=page&tpl_layout='.$layout;
		$result .= '" TARGET="_BLANK">Edit Template</a> - <a href="index.php?admin&logout">Adminlogout</a><hr>';
		return $result;
	}
	
	function show(&$vars) {
		global $_CONFIG;
		if($this->name=='') return error("Pagename not given",get_class($this),"show");
		$output = $this->getLayout(array(),$this->name, $vars);
		if(isset($_COOKIE['adminlogin']) && $_CONFIG['quickedit'])
			$output = $this->adminbar($this->name).$output;
		return $output;
	}
}

class Varspage {

	var $attr = '';

	function Varspage($id='') {
		$this->attr = $id;
	}

	function show(&$vars){
		if(isset($vars[$this->attr]) && !empty($vars[$this->attr])) {
			$p = new Page($vars[$this->attr]);
			return $p->show($vars);
		} else
			return '';
	}
}
?>
