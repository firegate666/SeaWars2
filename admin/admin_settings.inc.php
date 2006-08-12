<?
$adminlogin = (User::hasright('admin') || User::hasright('settingsadmin'));
if(empty($adminlogin)) die("DENIED");
?>

<h3>Systemsettings</h3>
<?
	if (isset($_REQUEST['save'])) {
		Setting::write($_REQUEST['name'], $_REQUEST['value']);
		unset($_REQUEST['save']);
		unset($_REQUEST['name']);
		unset($_REQUEST['value']);
	}
  if(isset($_REQUEST['edit'])) { ?>
  	<div>
  		<form action="index.php" method="post">
  			<input type="hidden" name="admin"/>
  			<input type="hidden" name="settings"/>
  			<input type="hidden" name="save"/>
  			<input type="hidden" name="name" value="<?=$_REQUEST['name']?>"/>
  			<table class="adminedit">
  				<tr>
  					<th colspan="2"><h3>Setting bearbeiten</h3></th>
  				<tr>
  					<td><?=$_SESSION['settingdesc'][$_REQUEST['name']]?></td>
  					<td><input size="25" name="value" type="text" value="<?=$_SESSION['setting'][$_REQUEST['name']]?>"/></td>
  				</tr>
  				<tr>
  					<td></td>
  					<td><input type="submit"/></td>
  				</tr>
  			</table>
  		</form>
  	</div>
  <? }
?>
<table class="adminlist" width="100%">
  <tr>
    <th align="left">Name</th><th align="left">Value</th><th align="left">&nbsp;</th>
  </tr>
<?
	foreach($_SESSION['setting'] as $name=>$value) { ?>
		<tr>
		  <td><?=$_SESSION['settingdesc'][$name]?></td>
		  <td><?
		  		if ($value === true) echo "true";
		  		else if ($value === false) echo "false";
		  		else echo $value;
		  ?></td>
		  <td><a href="?admin&settings&edit&name=<?=$name?>"><img src="img/edit.gif" border="0"/></a></td>
		</tr>
	<? }
?></table>
