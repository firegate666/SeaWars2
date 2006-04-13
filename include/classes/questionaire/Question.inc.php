<?php
$template_classes[] = 'question';

/**
 * a group of questions contains questions
 */
class Question extends AbstractClass {

	public function acl($method) {
		return false;
	}

	protected function getAllAnswers() {
		global $mysql;
		$query = "SELECT id, answertype FROM `questionanswer` WHERE questionid=". ($this->id).";";
		return $mysql->select($query, true);
	}

	public function show($vars, $qlayout = '') {
		$questionlayout = 'default';
		if (($qlayout!="") && ($qlayout!=0)) {
			$t = new Template($qlayout);
			$questionlayout = $t->get('layout');
		}
		$result = "";
		$answers = $this->getAllAnswers();
		foreach ($answers as $answertype) {
			$at = new QuestionAnswertype($answertype['answertype']);
			$atlayout = $at->get('name');
			if (($at->get('layout')!="") && ($at->get('layout')!=0)) {
				$t = new Template($at->get('layout'));
				$atlayout = $t->get('layout');
			}
			$result .= $at->show($vars, $atlayout, array ('qid' => $this->id, 'qaid' => $answertype['id']));
		}
		$result = parent::show($vars, $questionlayout, array('answers'=>$result));
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
