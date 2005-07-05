<?php

	Setting::set('timestampformat', 'Timestamp Format', '%Y-%m-%d %H:%M:%S', false);


class Date {
	
	public function now($formatstring = '') {
		if(empty($formatstring)) {
			$formatstring = Setting::get("timestampformat");
		}
		strftime("$formatstring", time());
	}
}
?>