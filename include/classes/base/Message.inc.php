<?

$template_classes[]='message';

class Message extends AbstractClass {
	
	protected $receiverlist = array();
	protected $send = false;
	
	public function acl($method) {
		if (($method=='inbox') || ($method=='outbox')) 
			return true;
		if ($method=='edit')
			return $this->get('id') == '';
		if ($method=='view')
			return ($this->get('sender') == $this->loggedIn())
				|| ($this->get('receiver') == $this->loggedIn());
		return false;
	}
	

	public function getFields() {
		$fields[] = array('name' => 'sender',
                          'type' => 'integer',
                          'notnull' => true);

		$fields[] = array('name' => 'receiver',
                          'type' => 'integer',
                          'notnull' => false);

		$fields[] = array('name' => 'subject',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);

		$fields[] = array('name' => 'body',
                          'type' => 'string',
                          'notnull' => true);

		$fields[] = array('name' => 'unread',
                          'type' => 'integer',
                          'notnull' => false);

		$fields[] = array('name' => 'sender_deleted',
                          'type' => 'integer',
                          'notnull' => false);

		$fields[] = array('name' => 'receiver_deleted',
                          'type' => 'integer',
                          'notnull' => false);

		$fields[] = array('name' => 'readon',
                          'type' => 'date',
                          'notnull' => false);

		return $fields;
	}

	function parsefields($vars) {
		$err = false;
			
		$vars['sender'] = $this->loggedIn();
		
		$err = parent::parsefields($vars);
		$this->receivercheck($vars['receiver_list'], $err);
		
		return $err;
	}

	function receivercheck($receiver, &$err) {
		$receiverlist = explode(',', $receiver);
		if (empty($receiverlist))
			$err[] = "No receiver set";
		
		$u = new User();
		foreach($receiverlist as $user) {
			$result = $u->search(trim($user), 'login');
			if (empty($result))
				$err[] = "User $user not found";
			else
				$this->receiverlist[] = $result[0]['id'];
		}
		
	}
	
	function store() {
		if (!empty($this->receiverlist)) {
			foreach ($this->receiverlist as $receiver) {
				$message = new Message();
				$message->data = $this->data;
				$message->data['receiver'] = $receiver;
				$message->store();
			}
		} else
			parent::store();
	}
	
	function edit(&$vars) {
		$array = array();
		$array['receiver_list'] = "";
		if (isset($vars['receiver_list']))
			$array['receiver_list'] = $vars['receiver_list'];
		if (isset($vars['submit'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else {
				$this->store();
				return $this->outbox($vars);
			}
		}
		return parent::show($vars, 'edit', $array);
	}
	
	function inbox($vars) {
		$array = array();
		return parent::show($vars, 'inbox', $array);
	}

	function outbox($vars) {
		$array = array();
		return parent::show($vars, 'outbox', $array);
	}
}
?>