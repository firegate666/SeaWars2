<?php
$template_classes[] = 'questionanswer';

/**
 * every questions has 1 to n answers to answer
 */
class QuestionAnswer extends AbstractClass {
	protected function acl($method) {
		return false;
	}
}
?>