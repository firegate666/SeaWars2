<?php
$template_classes[] = 'w40k';
$__userrights[] = array('name'=>'codexadmin', 'desc'=>'can edit codices'); 
$__userrights[] = array('name'=>'missionadmin', 'desc'=>'can edit missions');
$__userrights[] = array('name'=>'w40kuser_extern', 'desc'=>'can use W40K');
$__userrights[] = array('name'=>'w40kuser_intern', 'desc'=>'can use W40K');
$__userrights[] = array('name'=>'w40kadmin', 'desc'=>'can edit codices & missions');

class W40K extends AbstractClass {
	protected $layoutclass = "w40k";
	protected $image;
	
	public function parsefields($vars) {
		$err = parent::parsefields($vars);
		if ($err !== false)
			return $err;
		if (isset($vars['__files']['filename']) && ($this->get('id') != '')&& ($this->get('id') != 0)) {
			$err = $this->image->parsefields($vars['__files']['filename'], $this->class_name(), $this->get('id'));
			if ($err === false)
				$this->image->store();
		} else if (isset($vars['__files']['filename']) && ($this->get('id') == ''))
			$err[] = "Upload failed, Object is new";
		if ($err !== false)
			return $err;
		
		return false;
	}
	
	public function W40K($id='') {
		parent::AbstractClass($id);
		$this->image = new Image();
	}
	
}
?>
