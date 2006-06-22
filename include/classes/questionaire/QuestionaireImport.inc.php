<?php
class QuestionaireImport extends AbstractClass {

	public function acl($method) {
		if ($method == 'start')
			return true;
		if ($method == 'verify')
			return true;
		if ($method == 'finish')
			return true;
		return parent :: acl($method);
	}

	function finish($vars) {
		if (!Session :: getCookie('questionaireimport', false))
			return $this->start($vars);

		$questions = Session :: getCookie('questionaireimport', array ());
		unset ($questions[0]);

		$questionaire = new Questionaire();
		$questionaire->set('name', $vars['name']);
		$questionaire->set('author', $vars['author']);
		$questionaire->set('email', $vars['email']);
		$questionaire->set('shortdesc', $vars['shortdesc']);
		$questionaire->set('longdesc', $vars['desc']);
		$questionaire->set('userid', User::loggedIn());
		$questionaire_id = $questionaire->store();
		$at_translation_table = array();
		foreach ($questions as $item) {
			$question = new Question();
			$question->set('sem_id', $item[0]);
			unset ($item[0]);
			$question->set('name', $item[1]);
			unset ($item[1]);
			$question->set('blockname', $item[2]);
			unset ($item[2]);
			$question->set('groupname', $item[3]);
			unset ($item[3]);
			$question->set('questionaireid', $questionaire_id);
			$check = $question->get('sem_id');
			if (empty($check))
				continue;
			$question->store();
			$at = $item[4];
				$at = trim($at);
				$answer = new QuestionAnswer();
				if (!isset($at_translation_table["$at"])) {
					$newAT = new QuestionAnswertype();
					$newAT->set('name', $question->get('sem_id'));
					$at_translation_table["$at"] = $newAT->store();
				}
				$answer->set('answertype', $at_translation_table["$at"]);
				$answer->set('questionid', $question->get('id'));
				$answer->store();

		}
		return redirect('?admin&questionaire');
	}

	function verify($vars) {
		global $HTTP_POST_FILES;
		if (isset ($HTTP_POST_FILES['importfile']['error']) && $HTTP_POST_FILES['importfile']['error'] == 0) {
			$csv = file($HTTP_POST_FILES['importfile']['tmp_name']);
			$result[] = array ('Semantische ID', 'Fragetext', 'Block', 'Gruppen', 'TYPE');
			foreach ($csv as $item)
				$result[] = explode(";", $item);
			Session :: setCookie('questionaireimport', $result);
			$content[] = array ('input' => '<h3>Fragebogenimport Schritt 2/3</h3>');
			$content[] = array ('input' => '<p>Daten vervollständigen:</p>');
			$content[] = array ('descr' => 'Name', 'input' => HTML :: input('text', 'name', '', 100));
			$content[] = array ('descr' => 'Autor', 'input' => HTML :: input('text', 'author', '', 100));
			$content[] = array ('descr' => 'Email', 'input' => HTML :: input('text', 'email', '', 100));
			$content[] = array ('descr' => 'Kurzbeschreibung', 'input' => HTML :: input('text', 'shortdesc', '', 100));
			$content[] = array ('descr' => 'Beschreibung', 'input' => HTML :: textarea('desc', ''));
			$content[] = array ('descr' => '&nbsp;', 'input' => HTML :: input('submit', 'submit', 'Import abschließen'));
			$form1 = $this->getForm($content, '', 'finish', 'createquestionaire', $vars, '');
			$content2[] = array ('descr' => '&nbsp;', 'input' => HTML :: input('submit', 'submit', 'Import abbrechen'));
			$form2 = $this->getForm($content2, '', 'start', 'stopimport', $vars, '');
			$output = $form1.$form2.HTML :: table($result);
			return $output;
		} else {
			error('Dateiupload fehlgeschlagen', 'Questionaire', 'import');
		}
	}

	public function start($vars) {
		Session :: unsetCookie('questionaireimport');
		$content[] = array ('input' => '<h3>Fragebogenimport Schritt 1/3</h3>');
		$content[] = array ('descr' => 'Input File (csv)', 'input' => HTML :: input('file', 'importfile', ''));
		$content[] = array ('descr' => 'Trennzeichen', 'input' => HTML :: input('text', 'importseperator', ';'));
		$content[] = array ('descr' => '&nbsp;', 'input' => HTML :: input('submit', 'submit', 'Import starten'));
		$form = $this->getForm($content, '', 'verify', 'importfile', $vars, 'multipart/form-data');
		return $form;
	}

}
?>