<?
$adminlogin = Session::getCookie('adminlogin');
if(empty($adminlogin)) die("DENIED");
?>

<h3>Questionaire Administration</h3>
<h4><a href="?questionaireimport/start">Import starten</a></h4>
<table border="1">
	<tr>
		<th>ID</th>
		<th>Titel</th>
		<th>Autor</th>
		<th>Kurzbeschreibung</th>
		<th>Erstellt am</th>
		<th>Veröffentlicht</th>
		<th>Geschlossen</th>
	</tr>
<?
$q = new Questionaire();
if (!isset($_REQUEST['id'])) {
	$list = $q->getlist();
} else {
	$list = array('id' => $_REQUEST['id']);
}	
	foreach($list as $id) {
		$q = new Questionaire($id['id']);
		?>
		<tr>
			<td><a href="?admin&questionaire&id=<?=$q->get('id');?>"><?=$q->get('id');?></a></td>
			<td><?=$q->get('name');?></td>
			<td><?=$q->get('author');?></td>
			<td><?=$q->get('shortdesc');?></td>
			<td><?=$q->get('__createdon');?></td>
			<td><?=($q->get('published')==0)?'nein':'ja';?></td>
			<td><?=($q->get('closed')==0)?'nein':'ja';?></td>
		</tr>
		<?		
	}
?></table><?
	if (isset($_REQUEST['id'])) {
		?>
			<p><b>Beschreibung:</b> <?=$q->get('longdesc');?></p>
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
		<div onClick="togglevisible('questions');"><b>Fragen <span id="questionslabel">anzeigen</span></b> 
			<span id="questions" style="visibility:hidden;display:none;">
			<?=HTML::table($qs->getlistbyquestionaire($_REQUEST['id']));?>
			</span>
		</div>
		<div onClick="togglevisible('answers');"><b>Antworten <span id="answerslabel">anzeigen</span></b> 
			<span id="answers" style="visibility:hidden;display:none;">
		<table border="1">
		<?
			foreach($q->getAnswerTable() as $row) {
				echo "<tr>";
				foreach($row as $column) {
					echo "<td align='center'>$column</td>";
				}
				echo "</tr>";
			}
		?>
		</table>
			</span>
		</div>
		<?
	}
?>
