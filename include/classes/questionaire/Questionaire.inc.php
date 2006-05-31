<?php
$template_classes[] = 'questionaire';
$__userrights[] = array('name'=>'questionaireadmin', 'desc'=>'can edit questionaires');

/**
 * This is questionaire
 */
class Questionaire extends AbstractClass {

	protected $stats = array();

	public function getAnswertypeIDs($questionaireid = null) {
		global $mysql;
		$id = $this->get('id');
		if ($questionaireid != null)
			$id = $questionaireid;
		$query = "SELECT distinct qat.id
					FROM
					  questionanswertype qat,
					  questionanswer qa,
					  question q
					WHERE qat.id = qa.answertype
					AND qa.questionid = q.id
					AND q.questionaireid = $id
					ORDER BY q.id";
		return $mysql->select($query, true);
	}

	/**
	 * get all given answers as assoc array
	 */
	public function getAnswerTable() {
		global $mysql;
		$query = "SELECT q.sem_id, qas.questionanswervalue, qas.quserid
						FROM question q, questionaireanswers qas, questionanswer qa
						WHERE qas.questionanswerid = qa.id
						AND qa.questionid = q.id
						AND q.questionaireid = ". ($this->id)." 
						ORDER BY qas.quserid, q.id;";
		$result = $mysql->select($query, true);
		$return = array ();
		foreach ($result as $answer) {
			$return[0][$answer['sem_id']] = $answer['sem_id'];
			$return[$answer['quserid']][$answer['sem_id']] = $answer['questionanswervalue'];
		}
		return $return;
	}

	public function acl($method) {
		if (!$this->exists())
			return false;
		if (($this->get('published') == 1) && ($this->get('closed') == 0)) {
			if ($method == 'show')
				return true;
			if ($method == 'submit')
				return true;
		}
		return parent :: acl($method);
	}

	/**
	 * submit answers
	 */
	public function submit($vars) {
		if (!QuestionaireUser :: LoggedIn())
			error('Um an Umfragen teilzunehmen, muss man eingelogged sein', 'questionaire', 'submit', $vars);
		if (!isset ($vars['question']) || !isset ($vars['questionanswer']))
			return $this->show(array ('err' => 'Es müssen alle Fragen beantwortet werden, bevor die Seite abgeschickt werden kann.'));
		// TODO überprüfen, ob dieser User diese Frage schon beantwortet hat
		$lastcc = Session::getCookie('questionaire_last_questioncount', null);
		if ($lastcc != count($vars['questionanswer']))
			return $this->show(array ('err' => 'Es müssen alle Fragen beantwortet werden, bevor die Seite abgeschickt werden kann.'));
		foreach ($vars['question'] as $qid) {
			if (isset ($vars['questionanswer'][$qid])) { // there are questions with no answers
				foreach ($vars['questionanswer'][$qid] as $qaid => $value) {
					$qas = new QuestionaireAnswers();
					$qas->set('questionanswerid', $qaid);
					$qas->set('questionanswervalue', $value);
					$qas->set('quserid', QuestionaireUser :: LoggedIn());
					$qas->store();
				}
			}
		}
		return $this->show(array ());
	}

	public function sendmail($quserid) {
		$qu = new QuestionaireUser($quserid);
		$from = $this->get('email');
		$to = $this->get('email');
		$subject = "Fragebogen abgeschlossen";
		$body = "Benutzer ".$qu->get('id')." (".$qu->get('email').")".
			" hat die Beantwortung des Fragebogens ".$this->get('id')." (".$this->get('name').")".
			" abgeschlossen.";		
		$m = new Mailer();
		$m->simplesend($from, $to, $subject, $body);
		
	}
	
	public function show($vars) {
		$qu = new QuestionaireUser();
		if (!$qu->loggedin()) {
			$vars['questionaireid'] = $this->get('id');
			return $qu->loginform($vars);
		}

		$questiontpl = 'default';
		if (($this->get('layout_question')!="") && ($this->get('layout_question')!=0)) {
			$t = new Template($this->get('layout_question'));
			$questiontpl = $t->get('layout');
		}
		$array['id'] = $this->id;
		if (isset ($vars['err']))
			$array['error'] = $vars['err'];
		$questions = $this->getNextUnanswered();
		if (count($questions) == 0) {
			$this->sendmail(QuestionaireUser::LoggedIn());
			QuestionaireUser :: dologout();
			$layoutend = $this->id.'end';
			if (($this->get('layout_end')!="") && ($this->get('layout_end')!=0)) {
				$t = new Template($this->get('layout_end'));
				$layoutend = $t->get('layout');
			}
			return $this->getLayout($array, $layoutend, $vars);
		}
		Session::setCookie('questionaire_last_questioncount', count($questions));
		$array['questions'] = '';
		$even = false;
		foreach ($questions as $question) {
			$question = new Question($question['qid']);
			$layout = $this->get('layout_question');
			if ($even) {
				$layout = $this->get('layout_question_alt');
			}
			$even = !$even;
			$array['questions'] .= $question->show($vars, $layout);
		}
		$layoutmain = $this->id;
		if (($this->get('layout_main')!="") && ($this->get('layout_main')!=0)) {
			$t = new Template($this->get('layout_main'));
			$layoutmain = $t->get('layout');
		}
		$array = array_merge($array, $this->stats);
		$array = array_merge($array, $this->data);
		return $this->getLayout($array, $layoutmain, $vars);
	}

	protected function getNextRandomPageFromBlock($questions) {
	}

	protected function getQuestioncount() {
		if (Session::getCookie('questionaire_abs_questions', false))
			return Session::getCookie('questionaire_abs_questions');
		$question = new Question();
		$count = count($question->getlistbyquestionaire($this->get('id')));
		Session::setCookie('questionaire_abs_questions', $count);
		return $count;
	}

	/**
	 * get all unanswered questions for logged in user but only next page
	 */
	protected function getNextUnanswered($random = false) {
		$questions = $this->getAllUnanswered();
		$this->stats['abs_unanswered'] = count($questions);
		$this->stats['abs_questions'] = $this->getQuestioncount();
		$this->stats['abs_answered'] = $this->stats['abs_questions'] - $this->stats['abs_unanswered'];
		if ($random)
			return getNextRandomPageFromBlock($questions);
		$result = array ();
		$qid = null;
		foreach ($questions as $question) {
			if ($qid == null) {
				$result[] = $question;
				$qid = $question['groupname'];
			} else {
				if ($question['groupname'] == $qid)
					$result[] = $question;
				else
					break;
			}
		}
		$this->stats['abs_unanswered_in_page'] = count($result);
		$this->stats['pc_unanswered'] = ($this->stats['abs_unanswered'] / $this->stats['abs_questions'])*100;
		$this->stats['pc_answered'] = 100 - $this->stats['pc_unanswered'];
		return $result;
	}



	/**
	 * get all unanswered questions for logged in user
	 */
	protected function getAllUnanswered() {
		global $mysql;
		$quserid = QuestionaireUser :: loggedin();
		$query = "SELECT q.id as qid, qa.id as qaid, q.blockname, q.groupname
							FROM question q, questionanswer qa
							WHERE q.questionaireid = ". ($this->id)."
							AND qa.questionid = q.id
							AND qa.id NOT IN (SELECT questionanswerid FROM questionaireanswers WHERE quserid=$quserid)
							ORDER BY blockname ASC, groupname ASC, qid ASC;";
		return $mysql->select($query, true);
	}
}
?>