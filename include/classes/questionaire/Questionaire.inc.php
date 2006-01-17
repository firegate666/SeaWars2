<?php
$template_classes[] = 'questionaire';
 
/**
 * This is questionaire
 */
class Questionaire extends AbstractClass {
	
	public function acl($method) {
		if (!$this->exists())
			return false;
		if ($method == 'show')
			return true;
		if ($method == 'submit')
			return true;
		else
			return parent::acl($method);
	}
	
	public function submit($vars) {
		print_a($vars);
	}
	
	public function show($vars) {
		$questiontpl = 'default';
		$array['id'] = $this->id;
		$questions = $this->getNextUnanswered();
		$array['questions'] = '';
		foreach ($questions as $question) {
			$question = new Question($question['qid']);
			$array['questions'] .= $question->show($vars);
		}
		return $this->getLayout($array, $this->id, $vars);
	}
	
	protected function getNextUnanswered() {
		$questions = $this->getAllUnanswered();
		$result = array();
		$qid = null;
		foreach($questions as $question) {
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
		return $result;
	}

	protected function getAllUnanswered() {
		global $mysql;
		$query = "SELECT q.id as qid, qa.id as qaid, q.blockname, q.groupname
					FROM question q, questionanswer qa
					WHERE q.questionaireid = ".($this->id)."
					AND qa.questionid = q.id
					AND qa.id NOT IN (SELECT questionanswerid FROM questionaireanswers)
					ORDER BY blockname ASC, groupname ASC, qid ASC;";
		return $mysql->select($query, true);
	}
}
?>