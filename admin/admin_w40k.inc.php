<?
$adminlogin = (User::hasright('admin') || User::hasright('w40kadmin'));
if(empty($adminlogin)) die("DENIED");

if (isset($_REQUEST['store'])) {
	$obj = new $_REQUEST['type']($_REQUEST['id']);
	$err = $obj->parsefields($_REQUEST);
	if ($err===false) {
		$obj->store();
		unset($_REQUEST['id']);
	} else
		echo(implode(",", $err));
}

?>

<h3>W40K Configuration</h3>
<table border="1" width="100%">
	<tr>
		<th><a href="?admin&w40k&type=Codex">Codices</a></th>
		<th><a href="?admin&w40k&type=Mission">Missionen</a></th>
		<th><a href="?admin&w40k&type=BattleType">BattleType</a></th>
		<th><a href="?admin&w40k&type=GameSystem">Spielsystem</a></th>
	</tr>
<? if (isset($_REQUEST['type'])) { ?>
<tr>
	<td colspan="4">
		<ul><b>Liste</b>
		<? $t = new $_REQUEST['type']();
		   $list = $t->getlist('', true, 'name', array('id', 'name'));
		   foreach($list as $item) {
				echo "<li>{$item['name']} (<a href='?admin&w40k&type={$_REQUEST['type']}&id={$item['id']}#edit'><img src='img/edit.gif' border='0'/></a> / <img src='img/delete.gif' border='0'/></a>)</li>";
		   }
		?>
		</ul><a name="edit">
			<table>
			<form method="post" action="index.php"/>
				<?$obj = new $_REQUEST['type']($_REQUEST['id']);?>
				<input type="hidden" name="admin"/>
				<input type="hidden" name="w40k"/>
				<input type="hidden" name="id" value="<?=$obj->get('id')?>"/>
				<input type="hidden" name="type" value="<?=$_REQUEST['type']?>"/>
				<tr>
					<th colspan="2">Bearbeiten/Anlegen (<?=$_REQUEST['type']?>)</th>
				</tr>
				<?
					foreach($obj->getFields() as $field) { ?>
						<tr>
							<td><?=$field['desc']?></td>
							<td><?=$obj->getInputField($field)?></td>
						</tr>
					<?}
				?>
				<!--tr>
					<td>Neu anlegen </td>
					<td><input size="50" type="text" name="name" value="<?=$obj->get('name')?>"/></td>
				</tr>
				<tr>
					<td>Beschreibung</td>
					<td><textarea name="comment" cols="50" rows="4"><?=$obj->get('comment')?></textarea></td>
				</tr-->
				<tr>
					<td colspan="2"><input type="submit" name="store" value="Speichern"/></td>
				</tr>
			</form>
			</table>
	</td>
</tr>
<? } ?>	
</table>
