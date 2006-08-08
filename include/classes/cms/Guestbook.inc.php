<?php
$template_classes[] = 'guestbook';
$__userrights[] = array('name'=>'guestbookadmin', 'desc'=>'can edit guestbook'); 

	Setting::write('moderated_guestbook',
		'1',
		'Moderated Guestbook? (1=true, 2=false)',
		false);
	
	Setting::write('email_guestbookadmin',
		'false',
		'Email Guestbookadmin',
		false);

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
		else if (($action == 'togglestate') || ($action == 'del'))
			return $this->hasright('guestbookadmin');
		else
			return parent::acl($action);
	}
	
	function del($vars) {
		if ($this->exists()) {
			$this->delete();
		}
		if (isset($vars['ref']))
			return redirect($vars['ref']);
		else
			return redirect($_SERVER['HTTP_REFERER']);
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
		$gb->set('ip', getClientIP());
		$gb->set('deleted', Setting::read('moderated_guestbook', 1));
		$gb->store();

		if (Setting::read('email_guestbookadmin', false)) {
			$m = new Mailer();
			$from = Setting::read('email_guestbookadmin');
			$to = Setting::read('email_guestbookadmin');
			$subject = 'Neuer Gästebucheintrag';
			$body = 'Ein neuer Gästebucheintrag von "'.$gb->get('name'). '" wurde erstellt.';
			$body .= "\n\n".$gb->get('content');
			$m->simplesend($from, $to, $subject, $body);
		}

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
			if (($gb->get('deleted') == 1) && (!$this->hasright('guestbookadmin')))
				continue;
			$array = array();
			$array['name'] = $gb->get('name');
			$array['email'] = $gb->get('email');
			$array['subject'] = $gb->get('subject');
			$array['body'] = $gb->get('content');
			$array['timestamp'] = $gb->get('__createdon');
			if ($this->hasright('guestbookadmin')) {
				$link = '<a href="index.php?guestbook/togglestate/'.($gb->id).'">';
				$dellink = '<a href="index.php?guestbook/del/'.($gb->id).'">Delete</a>';
				if ($gb->get('deleted'))
					$output .= '<div>'.$link.'Show</a> - '.$dellink.'</div>';
				else
					$output .= '<div>'.$link.'Hide</a> - '.$dellink.'</div>';
			}
			$output .= $this->getLayout($array, $this->layout, $vars);
		}
		return $output;
	}
}
?>