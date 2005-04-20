<?
class PLink {
             var $value;
             function show(&$vars) {
                return 'index.php?class=page&id='.$this->value;
             }

             function PLink($value) {
                                    	$this->value = $value;
             }
}
?>