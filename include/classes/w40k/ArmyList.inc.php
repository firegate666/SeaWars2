<?php
class ArmyList extends W40K {

	public function acl($method) {
		if ($method == 'show')
			return true;
		return false;
	}
}
?>