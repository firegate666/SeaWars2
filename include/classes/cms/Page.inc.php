<?
	$template_classes[] = 'page';
	$template_classes[] = 'admin';
	
/**
 * This is a page
 */
class Page extends AbstractClass {
	
	protected $name = '';
	protected $tags = '';
	
	function acl($method) {
		if ($method == 'show')
			return true;
		return false;
	}
	
	function Page($name='') {
		if(empty($name)) 
			error("No page name given", $this->class_name(), "Constructor");
		$this->name = $name;
	}	
	
	/**
	 * if admin is logged in, show adminbar
	 */
	function adminbar($layout){
		$result = '';
		$result .= '<a href="';
		$result .= 'index.php?admin&template&tpl_class=page&tpl_layout='.$layout;
		$result .= '" TARGET="_BLANK">Edit Template "'.$this->name.'"</a> - <a href="index.php?user/logout//">Adminlogout</a><hr>';
		return $result;
	}
	
	function show(&$vars) {
		if($this->name=='') return error("Pagename not given",$this->class_name(),"show");
		$output = $this->getLayout(array(),$this->name, $vars);
		if($this->hasright('templateadmin') && get_config('quickedit'))
			$output = $this->adminbar($this->name).$output;
		return $output;
	}
	
	function contenttype() {
		global $mysql;
		if($this->name=='') return error("Pagename not given",$this->class_name(),"show");
		$result = $mysql->select("SELECT contenttype FROM template WHERE class='page' AND layout='".$this->name."'");
		$contenttype = $result[0][0];
		return $contenttype;				
	}
}

class Varspage {

	protected $attr = '';

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
