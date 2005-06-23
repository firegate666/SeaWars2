<?
	$template_classes[] = 'messenger';

  class Messenger extends AbstractClass {
  	var $mitteilungen;
  	
  	/**
	 * check if method is allowed
	 * @param	String	$method	method to test
	 * @return	boolean	true/false
	 */
	function acl($method){
          return Login::isLoggedIn();
        }
    
    /**
	 * Show Messenger using template messenger/page 
	 * @param	String[]	$vars	request parameter
	 */
    function show(&$vars) {
    	
    	return "test";
		//return $this->getLayout($array, "page", $vars);
	}
    
  }
?>