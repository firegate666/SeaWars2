<?
	$template_classes[] = 'error';

/**
 * The Error-Class, gets pages for different errors
 */
class Error extends AbstractNoNavigationClass {

	var $class  = '';
	var $method = '';
	var $msg    = '';

	/**
	 * constructor
	 * @msg		message to be shown
	 * @class	who throws the error
	 * @method	when?
	 */
	function Error($msg,$class,$method){
		$this->msg = $msg;
		$this->class = $class;
		$this->method = $method;
	}

	function show(&$vars) {
		if(get_config('cms', false)) {
			$array = array(
						"message" => $this->msg,
						"class" => $this->class,
						"method" => $this->method
					);
			return $this->getLayout($array, "page", $vars);
		} else {
			$result = "Error ".$this->class."/".$this->method.": ".$this->msg;
			return $result;
		}
	}
}
?>