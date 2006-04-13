<?php
class Mailer {
	function simplesend($from, $to, $subject, $body){
		$headers = "From: $from";
		@mail($to, $subject, $body, $headers);
	}
}
?>