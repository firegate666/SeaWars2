<?php

	Setting::set('timestampformat', '%Y-%m-%d %H:%M:%S', 'Timestamp Format', false);


class Date {
	
	/**
	* get actual date formatted
	*
	* @param	String	$formatstring	format date, see php doc
	* @return	String	formatted date
	*/
	public function now($formatstring = '') {
		if(empty($formatstring)) {
			$formatstring = Setting::get("timestampformat");
		}
		return strftime("$formatstring", time());
	}
	
}
?>