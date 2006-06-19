<?php

/**
 * these are the given answers
 */
class QuestionaireAnswers extends AbstractClass {
	public function acl($method) {
		return false;
	}
	
	public function finalize($q_id, $qu_id) {
		$q = new Question();
		$q_list = $q->advsearch(array('questionaireid='.$q_id));
	}
}
?>