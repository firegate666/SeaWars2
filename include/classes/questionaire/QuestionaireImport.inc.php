<?php
class QuestionaireImport extends AbstractClass {
	
	function acl($method) {
		if ($method == 'start')
			return true;
		if ($method == 'verify')
			return true;
	}
	
	function verify($vars) {
		global $HTTP_POST_FILES;
		if (isset($HTTP_POST_FILES['importfile']['error']) && $HTTP_POST_FILES['importfile']['error']==0) {
			$csv = file($HTTP_POST_FILES['importfile']['tmp_name']);
			$result[] = array(
				'Semantische ID', 'Fragetext', 'Block', 'Gruppen', 'TYPE'
			);
			foreach($csv as $item)
				$result[] = explode(";", $item);
			$output = HTML::table($result);
			return $output;
		} else {
			error('Dateiupload fehlgeschlagen', 'Questionaire','import');
		}
	}
	
	public function start($vars) {
		$content[] = array('input' => '<h3>Fragebogenimport</h3>');
		$content[] = array('descr'=>'Input File (csv)', 'input' => HTML::input('file', 'importfile', ''));
		$content[] = array('descr'=>'Trennzeichen', 'input' => HTML::input('text', 'importseperator', ';'));
		$content[] = array('descr'=>'&nbsp;', 'input' => HTML::input('submit', 'submit', 'Import starten'));
		$form = $this->getForm($content, '', 'verify', 'importfile', $vars, 'multipart/form-data');
		return $form;
	}
	
	
	
}
?>