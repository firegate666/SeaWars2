<?
$adminlogin = (User::hasright('admin') || User::hasright('useradmin'));
if(!$adminlogin) die("DENIED");

if(isset($_REQUEST['store']) && isset($_REQUEST['userid'])) {
	$error = false;
	$u = new User($_REQUEST['userid']);
	if (!empty($_REQUEST['password']))
		if ($_REQUEST['password'] != $_REQUEST['password2'])
			$error = "Passwords do not match. ";
		else
			$u->set('password', myencrypt($_REQUEST['password']));
	$u->set('login', $_REQUEST['login']);	
	$u->set('email', $_REQUEST['email']);	
	$u->set('groupid', $_REQUEST['groupid']);	
	if ($error === false) {
		$u->store();
		unset($_REQUEST['userid']);
		unset($_REQUEST['store']);
		unset($_REQUEST['password']);
	} else
		echo $error."Error while saving";
}

if(isset($_REQUEST['store']) && isset($_REQUEST['userright'])) {
	$ug = new Usergroup($_REQUEST['id']);
	$ug->set('name', $_REQUEST['name']);
	$ug->store();
	$ug->setUserrights($_REQUEST['userright']);
	unset($_REQUEST['id']);
	unset($_REQUEST['store']);
	unset($_REQUEST['usergroup']);
}
?>
<?
if ((!isset($_REQUEST['usergroup'])) && (!isset($_REQUEST['userid']))) {
	$ug = new Usergroup();
?>
	<h3>User</h3>
	<form method="post">
		<input type="hidden" name="admin"/>
		<input type="hidden" name="user"/>
		<select name="usergroupid" onChange="this.form.submit()">
			<option value="">- Gruppenfilter aus -</option>
			<?=$ug->getOptionList($_REQUEST['usergroupid'], false, 'name', true, 'name')?>
		</select>
	</form>

	<form method="get" action="index.php">
		<input type="hidden" name="admin"/>
		<input type="hidden" name="user"/>
		<input type="submit" name="userid" value="Neuen User anlegen"/>
	</form>
	
	<table class="adminlist" width="100%">
		<tr>
			<th align="left" width="20%">Login</th>
			<th align="left" width="30%">Email</th>
			<th align="left" width="5%">Group</th>
			<th align="left" width="20%">seit</th>
			<th align="left" width="5%">Fehler</th>
			<th align="left" width="20%">leztes Login</th>
			<th>&nbsp;</th>
		</tr>
	<?
		$u = new User();
		$where = array();
		if (!empty($_REQUEST['usergroupid']))
			$where[] = array('key'=>'groupid', 'value'=>$_REQUEST['usergroupid']);
		$ul = $u->getlist('', true, 'login', array('*'), '', '', $where);
		foreach($ul as $myuser) { 
			$ug = new Usergroup($myuser['groupid']);
			//if (!empty($_REQUEST['usergroupid']) && ($user['groupid']!=$_REQUEST['usergroupid']))
			//	continue;
		?>
			<tr>
				<td><?=$myuser['login']?></td>
				<td><?=$myuser['email']?></td>
				<td><?=$ug->get('name')?></td>
				<td><?=$myuser['__createdon']?></td>
				<td><?=$myuser['errorlogins']?></td>
				<td><?=$myuser['lastlogin']?></td>
				<td><a href="?admin&user&userid=<?=$myuser['id']?>"><img src="img/edit.gif" border="0"/></a></td>
			</tr>
		<? }
	?>
	</table>
<?}
if (isset($_REQUEST['userid'])) {
	$u = new User($_REQUEST['userid']);
	$ug = new Usergroup($u->get('groupid'));
?>
	<a name="edit"></a>
	<form action="index.php" method="POST">
		<input type="hidden" name="admin"/>
		<input type="hidden" name="user"/>
		<input type="hidden" name="userid" value="<?=$u->get('id')?>"/>
		<table class="adminedit">
			<tr>
				<th colspan="2"><h3>Edit User: <?=$u->get('login')?></h3></th>
			</tr>
			<tr>
				<td>Login</td>
				<td><input type="text" name="login" value="<?=$u->get('login')?>"/></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="email" value="<?=$u->get('email')?>"/></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="text" name="password" value=""/>
					<input type="text" name="password2" value=""/>
				</td>
			</tr>
			<tr>
				<td>Usergroup</td>
				<td><select name="groupid"><?=$ug->getOptionList($u->get('groupid'),true)?></select></td>
			</tr>
			<tr>
				<td>&nbsp;</td><td><input type="submit" name="store" value="Speichern"/></td>
			</tr>
	</form>
<? }
 ?>
