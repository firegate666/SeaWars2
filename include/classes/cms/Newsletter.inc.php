<?
class Newsletter extends AbstractClass {

	public function getFields() {
		$fields[] = array('name' => 'subject', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'body', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'from', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'replyto', 'type' => 'String', 'notnull' => true);
		return $fields;
	}

	public function acl($method){
		if ($method=='subscribe')
			return true;
		if ($method=='unsubscribe')
			return true;
		return false;
	}

	function send() {
		// alle subscriber auslesen
		$subscribers = array();
		// better fetch this from any settings
		$headers = 'From: '.$this->data['from']."\r\n" .
					'Reply-To: '.$this->data['replyto']."\r\n" .
					'X-Mailer: PHP/' . phpversion();
		foreach($subscribers as $to)
			mail($to, $this->data['subject'], $this->data['body'], $headers);		
	}

	/**
	 * subsribe to newsletter
	 */
	function subscribe(&$vars) {
	}
	
	/**
	 * unsubscribe from newsletter
	 */
	function unsubscribe(&$vars) {
	}
}

class NewsletterSubscription extends AbstractClass {
	function getFields() {
		$fields[] = array('name' => 'userid', 'type' => 'integer', 'notnull' => true);
		return $fields;
	}
}
?>