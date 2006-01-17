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
		echo HTML::table($qs->getlistbyquestionaire($_REQUEST['id']));
	}
?>
