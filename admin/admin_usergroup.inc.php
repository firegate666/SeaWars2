<?
$adminlogin = (User::hasright('admin') || User::hasright('useradmin'));
if(!$adminlogin) die("DENIED");

$error = array();

if(isset($_REQUEST['store']) && isset($_REQUEST['userright'])) {
		$ug = new Usergroup($_REQUEST['id']);
		$error = $ug->parsefields($_REQUEST);
		if (!$error) {
			$ug->store();
			$ug->setUserrights($_REQUEST['userright']);
			unset($_REQUEST['id']);
			unset($_REQUEST['usergroup']);
		}
		unset($_REQUEST['store']);
} else if(isset($_REQUEST['store']) && !isset($_REQUEST['userright']))
	$error[] = "You have to set a userright to create a group!";

if (!isset($_REQUEST['id'])) {
	$ug = new Usergroup();
?>
	<h3>Groups</h3>
	<form method="get" action="index.php">
		<input type="hidden" name="admin"/>
		<input type="hidden" name="id" value="0"/>
		<input type="submit" name="usergroup" value="Neue Gruppe anlegen"/>
	</form>
	<table class="adminlist" width="100%">
		<tr>
			<th align="left" width="100%">Name</th>
			<th></th>
		</tr>
	<?
		$u = new Usergroup();
		foreach($u->getlist('', true, 'name', array('*')) as $ug) { ?>
			<tr>
				<td><?=$ug['name']?></td>
				<td><a href="?admin&usergroup&id=<?=$ug['id']?>"><img src="img/edit.gif" border="0"/></a></td>
			</tr>
		<? } ?>
	</table>
<? }
	if (isset($_REQUEST['id'])) {
		global $__userrights;
		$ug = new Usergroup($_REQUEST['id']); ?>
		<a name="edit"></a>
		<form action="index.php" method="post">
			<input type="hidden" name="admin"/>
			<input type="hidden" name="usergroup"/>
			<input type="hidden" name="id" value="<?=$ug->get('id')?>"/>
			<table class="adminedit">
				<tr>
					<th colspan="2"><h3>Edit Group: <?=$ug->get('name')?></h3></th>
				<tr>
					<td colspan="2"><span class="error"><?=implode(' ', $error);?></span></td>
				</tr>
				<tr>
					<td>Name</td>
					<td><input type="text" name="name" value="<?=$ug->get('name')?>"/>
				</tr>
				</tr>
					<td></td>
					<td>
						<? $rights = $ug->getUserrights();
						  foreach ($__userrights as $priv) {
							$checked="";
							if (in_array($priv['name'], $rights))
								$checked = 'CHECKED="CHECKED"'; 
							?><input <?=$checked?> type="checkbox" name="userright[<?=$priv['name']?>]"> <?=$priv['name']?>, <?=$priv['desc']?></br><? 
						}?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="store" value="Speichern"/>
				</tr>
			</table>	
		</form>
	<?}
?>
