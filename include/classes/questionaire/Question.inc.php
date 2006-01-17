<?php

$template_classes[] = 'question';

/**
 * a group of questions contains questions
 */
class Question extends AbstractClass {

	protected function getAllAnswers() {
		global $mysql;
		$query = "SELECT id, answertype FROM `questionanswer` WHERE questionid=".($this->id).";";
		return $mysql->select($query, true);
	}

	public function show($vars) {
		$result = parent::show($vars, 'default');
		$answers = $this->getAllAnswers();
		foreach($answers as $answertype) {
			$at = new QuestionAnswertype($answertype['answertype']);
			$result .= $at->show($vars, $at->get('name'), array('qaid'=>$answertype['id']));
		}
		return $result;
	}

	public function getlistbyquestionaire($qrid) {
		global $mysql;
		$query = "SELECT q.id, q.sem_id, q.name, q.blockname, q.groupname
					FROM question q
					WHERE q.questionaireid = ".$qrid.";";
		return $mysql->select($query);
	}
}
?>