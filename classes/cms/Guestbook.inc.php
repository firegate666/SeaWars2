<?php
	$template_classes[] = 'guestbook';
	
class Guestbook extends AbstractClass {
	
	function Guestbook($id='') {
		$this->layout = $id;
		parent::AbstractClass($id);
	}
	
	function togglestate($vars) {
		if ($this->get('deleted'))
			$this->set('deleted', 0);
		else
			$this->set('deleted', 1);
		$this->store(false);
		if (isset($vars['destination']))
			return redirect($vars['destination']);
		else
			return redirect($_SERVER['HTTP_REFERER']);
		
	}
	
	function acl($action) {
		if ($action == 'newentry')
			return true;
		else if ($action == 'togglestate')
			return (Session::getCookie('adminlogin', false) !== false);
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
			if (($gb->get('deleted') == 1) && (Session::getCookie('adminlogin', false) == false))
				continue;
			$array = array();
			$array['name'] = $gb->get('name');
			$array['email'] = $gb->get('email');
			$array['subject'] = $gb->get('subject');
			$array['body'] = $gb->get('content');
			$array['timestamp'] = $gb->get('__createdon');
			if (Session::getCookie('adminlogin', false) != false) {
				$link = '<a href="index.php?class=guestbook&method=togglestate&id='.($gb->id).'">';
				if ($gb->get('deleted'))
					$output .= '<div>'.$link.'Show</a></div>';
				else
					$output .= '<div>'.$link.'Hide</a></div>';
			}
			$output .= $this->getLayout($array, $this->layout, $vars);
		}
		return $output;
	}
}
?>