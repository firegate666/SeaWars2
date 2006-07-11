<?
$adminlogin = (User::hasright('admin') || User::hasright('questionaireadmin'));
if(empty($adminlogin)) die("DENIED");

if (isset($_REQUEST['id']) && isset($_REQUEST['field']) && (isset($_REQUEST['value']) || is_array($_REQUEST['field']))) {
	$q = new Questionaire($_REQUEST['id']);
	if ($q->exists()) {
		if (!is_array($_REQUEST['field']))
			$q->set($_REQUEST['field'], $_REQUEST['value']);
		else
			foreach($_REQUEST['field'] as $key=>$value) {
				$q->set($key, $value);
			}
		$q->store();
	}
	if (isset($_REQUEST['atlayout']))
		foreach($_REQUEST['atlayout'] as $key=>$value) {
			$at = new QuestionAnswertype($key);
			$at->set('layout', $value);
			$at->store();
		}
	if (!isset($_REQUEST['nolist']))
		unset($_REQUEST['id']);
	unset($_REQUEST['field']);
	unset($_REQUEST['value']);
	unset($_REQUEST['atlayout']);
}

if (isset($_REQUEST['delete'])) {
	$q = new Questionaire($_REQUEST['delete']);
	if ($q->exists()) {
		$q->set('deleted', 1);
		$q->store();
	}
	unset($_REQUEST['delete']);
}
?>

<h3>Questionaire Administration</h3>
<h4><a href="?questionaireimport/start">Import starten</a></h4>
<table border="0">
	<tr>
		<th align="left">ID</th>
		<th align="left">Titel</th>
		<th align="left">Autor</th>
		<th align="left">Email</th>
		<th align="left">Kurzbeschreibung</th>
		<th align="left">Erstellt am</th>
		<th align="left">Fertig</th>
		<th align="left"><img src="img/shuffle.png" border="0" title="Zufällige Seitenreihenfolge"/></th>
		<th align="left"><img src="img/publish.jpg" border="0" title="Fragebogen veröffentlichen"/></th>
		<th align="left"><img src="img/locked.gif" border="0" title="Fragebogen schließen"/></th>
		<th align="left">&nbsp;</th>
	</tr>
<?
$q = new Questionaire();
if (!isset($_REQUEST['id'])) {
	$list = $q->getlist('', true, 'id', array('id'),
			'', '', array(array('key'=>'deleted', 'value'=>0), array('key'=>'userid', 'value'=>User::loggedIn())));
} else {
	$list[] = array('id' => $_REQUEST['id']);
}	
	$rowclass[0] = "adminrow";
	$rowclass[1] = "adminrowalt";
	$count = 0;
	foreach($list as $id) {
		$q = new Questionaire($id['id']);
		?>
		<tr class="<?=$rowclass[$count]?>">
			<td align="right"><a href="?admin&questionaire&id=<?=$q->get('id');?>"><?=$q->get('id');?></a></td>
			<td><?=$q->get('name');?></td>
			<td><?=$q->get('author');?></td>
			<td><?=$q->get('email');?></td>
			<td><?=$q->get('shortdesc');?></td>
			<td><?=$q->get('__createdon');?></td>
			<td align="right"><?=$q->getAnswerCount();?></td>
			<?
				$qid = $q->get('id');
				$qp1 = ($q->get('published')==0)?'1':'0';
				$qp2 = ($q->get('published')==0)?'<img src="img/notverified.gif" border="0" title="Veröffentlichen?"/>':'<img src="img/verified.gif" border="0" title="Veröffentlichung beenden?"/>';
				$qc1 = ($q->get('closed')==0)?'1':'0';
				$qc2 = ($q->get('closed')==0)?'<img src="img/notverified.gif" border="0" title="Schließen?"/>':'<img src="img/verified.gif" border="0" title="Wiedereröffnen?"/>';
				$qr1 = ($q->get('randompages')==0)?'1':'0';
				$qr2 = ($q->get('randompages')==0)?'<img src="img/notverified.gif" border="0" title="Zufällige Seitenreihenfolge?"/>':'<img src="img/verified.gif" border="0" title="keine zufällige Seitenreihenfolge?"/>';
			?>
			<td><a href="?admin&questionaire&id=<?=$qid?>&field=randompages&value=<?=$qr1?>">
					<?=$qr2?>
				</a>
			</td>
			<td><a href="?admin&questionaire&id=<?=$qid?>&field=published&value=<?=$qp1?>">
					<?=$qp2?>
				</a>
			</td>
			<td><a href="?admin&questionaire&id=<?=$qid?>&field=closed&value=<?=$qc1?>">
					<?=$qc2?>
				</a>
			</td>
			<td>
				<a href="?admin&questionaire&id=<?=$q->get('id');?>"><img src="img/edit.gif" border="0" title="details Bearbeiten"/></a>
				
				<a href="javascript:dialog_confirm('Wirklich löschen?', '?admin&questionaire&delete=<?=$q->get('id');?>');">
					<img src="img/delete.gif" border="0" title="Löschen"/>
				</a>
				
				<a href="?questionaire/csv/<?=$q->get('id');?>"><img src="img/export.png" border="0" title="CSV Export Antworten"/></a>
				<a href="?questionaire/csv_emails/<?=$q->get('id');?>"><img src="img/users.png" border="0" title="CSV Export Benutzer"/></a>
			</td>
		</tr>
		<?	
		($count==0)?$count=1:$count=0;	
	}
?></table><?
	if (isset($_REQUEST['id'])) {
		?>
			<p><b>Beschreibung:</b> <?=$q->get('longdesc');?></p>
			<div><b>Layouts</b> <u><span id="layoutslabel" onClick="togglevisible('layouts');">anzeigen</span></u>
			<span id="layouts" style="visibility:hidden;display:none;"><table>
				<form method="get" action="index.php">
					<input type="hidden" name="admin">
					<input type="hidden" name="questionaire">
					<input type="hidden" name="nolist">
					<input type="hidden" name="id" value="<?=$q->get('id')?>">
					<tr>
						<td>Questionaire (Frageseiten)</td>
						<td><select name="field[layout_main]"><?=Template::getLayoutOptions('questionaire', $q->get('layout_main'))?></select></td>
					</tr>
					<tr>
						<td>Questionaire (letzte Seite)</td>
						<td><select name="field[layout_end]"><?=Template::getLayoutOptions('questionaire', $q->get('layout_end'))?></select></td>
					</tr>
					<tr>
						<td>Question</td>
						<td><select name="field[layout_question]"><?=Template::getLayoutOptions('question', $q->get('layout_question'))?></select></td>
					</tr>
					<tr>
						<td>Question (alt)</td>
						<td><select name="field[layout_question_alt]"><?=Template::getLayoutOptions('question', $q->get('layout_question_alt'))?></select></td>
						<td><input type="submit" value="Questionairelayouts speichern"/></td>
					</tr>
		<?
			$atlist = $q->getAnswertypeIDs();
		?>
				<tr>
					<td colspan="2"><i><?=count($atlist)?> verschiedene Answertypes im Fragebogen</i></td>
				</tr>
		<?
			foreach($atlist as $atid) {
				$at = new QuestionAnswertype($atid['id']);
				echo "<tr>\n";
				echo "<td>Typ {$at->get('id')} (Bsp. SEM_ID {$at->get('name')})</td>\n";
				echo "<td><select name='atlayout[{$at->get('id')}]'>".Template::getLayoutOptions('questionanswertype', $at->get('layout'))."</select></td>\n";
				echo "</tr>\n";
			}
		?>
				</form>
			</span></table>
			</div>
		<?
			$qs = new Question();
		?>
		<script language="javascript">
			function togglevisible(id) {
				if (document.getElementById(id).style.visibility == 'hidden') {
					document.getElementById(id).style.display='block';
					document.getElementById(id).style.visibility='visible';
					document.getElementById(id+'label').innerHTML = 'verstecken';
				} else {
					document.getElementById(id).style.visibility='hidden';
					document.getElementById(id).style.display='none';
					document.getElementById(id+'label').innerHTML = 'anzeigen';
				}
			}
		</script>
		<div><b>Fragen</b> <u><span id="questionslabel" onClick="togglevisible('questions');">anzeigen</span></u> 
			<span id="questions" style="visibility:hidden;display:none;">
			<?$header = "<tr><th>ID</th><th>SEM_ID</th><th>Frage</th><th>Block</th><th>Gruppe</th></tr>";?>
			<?=HTML::table($qs->getlistbyquestionaire($_REQUEST['id']), 0, $header);?>
			</span>
		</div>
		<div><b>Antworten</b> <u><span id="answerslabel" onClick="togglevisible('answers');">anzeigen</span></u> 
			<span id="answers" style="visibility:hidden;display:none;">
		<table border="1">
		<?
			$list = $q->getAnswerTable();
			if (empty($list))
				echo("<tr><td>Keine Ergebnisse</td></tr>\n");
			foreach($list as $key=>$row) {
				echo "<tr>";
				if ($key == 0)
					echo "<td>User</td>";
				else
					echo "<td>$key</td>";
				foreach($row as $column) {
					echo "<td align='center'>$column</td>";
				}
				echo "</tr>\n";
			}
		?>
		</table>
			</span>
		</div>
		<?
	}
?>
