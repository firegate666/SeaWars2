<?php

	Setting::set('timestampformat', '%Y-%m-%d %H:%M:%S', 'Timestamp Format', false);


class Date {
	
	public function now($formatstring = '') {
		if(empty($formatstring)) {
			$formatstring = Setting::get("timestampformat");
		}
		return strftime("$formatstring", time());
	}
	
}
?>