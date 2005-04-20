<?
class Link {
             var $value;
             function show(&$vars) {
                return 'index.php?class='.$this->value;
             }

             function Link($value) {
                                    	$this->value = $value;
             }
}
?>