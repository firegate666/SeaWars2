<?
class Insel extends AbstractTimestampClass {
	
	function acl($method) {
		$method=strtolower($method);
		if($method=='show') return true;
		else return false;
	}
	
	function show(){
		$o = "";
		$o .= "<h3>Inselansicht</h3>";
		return $o;
	}
	function setname($newname) {
	}
}
?>