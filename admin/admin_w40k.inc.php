<?
$adminlogin = Session::getCookie('adminlogin');
if(empty($adminlogin)) die("DENIED");

if (isset($_REQUEST['store'])) {
	$obj = new $_REQUEST['type']();
	$obj->set('name', $_REQUEST['name']);
	$obj->store();
}

?>

<h3>W40K Configuration</h3>
<table border="1" width="100%">
	<tr>
		<th><a href="?admin&w40k&type=Codex">Codices</a></th>
		<th><a href="?admin&w40k&type=Mission">Missionen</a></th>
	</tr>
<? if (isset($_REQUEST['type'])) { ?>
<tr>
	<td colspan="2">
		<form method="post"/>
			<input type="hidden" name="admin"/>
			<input type="hidden" name="w40k"/>
			<input type="hidden" name="type" value="<?=$_REQUEST['type']?>"/>
			Neuen anlegen (<?=$_REQUEST['type']?>)
			<input type="text" name="name"/>
			<input type="submit" name="store" value="Speichern"/>
		</form>
		<ul><b>Liste</b>
		<? $t = new $_REQUEST['type']();
		   $list = $t->getlist('', true, 'name', array('id', 'name'));
		   foreach($list as $item) {
				echo "<li>{$item['name']}</li>";
		   }
		?>
		</ul>
	</td>
</tr>
<? } ?>	
</table>
