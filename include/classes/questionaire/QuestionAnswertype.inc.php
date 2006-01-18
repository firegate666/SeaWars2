<?php
	$template_classes[] = 'questionanswertype';

/**
 * every answer has a type containing a template
 */
class QuestionAnswertype extends AbstractClass {
	public function QuestionAnswertype($id='') {
		if(!$this->getFields()) error("No fields set",$this->class_name(),'Constructor');
		if(!is_numeric($id)) return;
		$this->id=$id;
		$this->load();
	}}
?>