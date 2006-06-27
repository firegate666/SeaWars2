<?php
$template_classes[] = 'w40k';
$__userrights[] = array('name'=>'codexadmin', 'desc'=>'can edit codices'); 
$__userrights[] = array('name'=>'missionadmin', 'desc'=>'can edit missions');
$__userrights[] = array('name'=>'battletypeadmin', 'desc'=>'can edit battle types');
$__userrights[] = array('name'=>'w40kuser_extern', 'desc'=>'can use W40K');
$__userrights[] = array('name'=>'w40kuser_intern', 'desc'=>'can use W40K');
$__userrights[] = array('name'=>'w40kadmin', 'desc'=>'can edit codices & missions');

abstract class W40K extends AbstractClass {
	protected $layoutclass = "w40k";
	protected $image;
	
	protected function numImages($id = null) {
		if ($id == null)
			$id = $this->get('id');
		
		$i = new Image();
		$where[] = "parent='".$this->class_name()."'";
		$where[] = "parentid=".$id;
		return count($i->advsearch($where, array('id')));
	}

	public function acl($method) {
		if ($method == 'delimage')
				return ($this->get('userid')==User::loggedIn())
					|| $this->hasright('admin')
					|| $this->hasright('w40kadmin');
		if ($method == 'prioimage')
				return ($this->get('userid')==User::loggedIn())
					|| $this->hasright('admin')
					|| $this->hasright('w40kadmin');
		return false;
	}

	function delimage($vars) {
		if (isset($vars['image'])) {
			$image = new Image($vars['image']);
			if ($image->exists() && ($image->get('parentid') == $this->get('id')))
				$image->delete();
		}
		return redirect("?".$this->class_name()."/edit/".$this->get('id'));
	}

	function prioimage($vars) {
		if (!empty($vars['prio']))
			foreach($vars['prio'] as $id=>$value) {
				$image = new Image($id);
				if ($image->exists() && ($image->get('parentid') == $this->get('id'))) {
					$image->set('prio', $value);
					$image->store();
				}
			}
		return redirect($vars['ref']);
	}
	
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