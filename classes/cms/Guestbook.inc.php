<?php
	$template_classes[] = 'guestbook';
	
class Guestbook extends AbstractTimestampClass {
	
	function Guestbook($id='') {
		$this->layout = $id;
		AbstractTimestampClass::AbstractTimestampClass($id);
	}
	
	function acl($action) {
		if ($action == 'newentry')
			return true;
		else
			return parent::acl($action);
	}
	
	function newentry($vars) {
		// error handling
		if (!isset($vars['name']))
			$error[] = "Name not set";
		if (!isset($vars['subject']))
			$error[] = "Subject not set";
		if (!isset($vars['content']))
			$error[] = "Content not set";
		if (isset($error)) {
			if (isset($vars['onerror']))
				return redirect($vars['onerror']);
			$error = implode(" / ", $error);
			$this->error($error, 'newentry');
		}
		
		$gb = new Guestbook();
		$gb->set('name', $vars['name']);
		$gb->set('subject', $vars['subject']);
		$gb->set('content', $vars['content']);
		$gb->set('email', $vars['email']);
		$gb->store();

		if (isset($vars['onok']))
			return redirect($vars['onok']);
		else
			return redirect('index.php');
	}
	
	function show($vars) {
		$result = $this->getlist('guestbook', false);
		$output = '';
		foreach($result as $entry) {
			$gb = new Guestbook($entry['id']);
			if ($gb->get('deleted') == 1)
				continue;
			$array = array();
			$array['name'] = $gb->get('name');
			$array['email'] = $gb->get('email');
			$array['subject'] = $gb->get('subject');
			$array['body'] = $gb->get('content');
			$array['timestamp'] = $gb->get('timestamp');
			$output .= $this->getLayout($array, $this->layout, $vars);
		}
		return $output;
	}
}
?>