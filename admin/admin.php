<?
	if(isset($logout)) {
		Session::cleanUpCookies();
		header("Location: index.php");
	}

//	if(isset($login_name) && isset($login_pass)) {
//		if(!login($login_name, $login_pass)) $error = "Wrong username or password";
//		else header("Location: index.php?admin");
//	}
	$adminlogin = User::loggedIn();
?>
<html>
  <head>
    <link href="?page/show/css_w40k" rel="stylesheet" type="text/css"/>
  </head>
<body>
<?	if(empty($adminlogin)) { ?>
		<h3>Adminlogin</h3>
		<font color="#FF0000"><?=$error?></font>
		<form action="index.php" method="POST">
		  <table>
		    <tr><td>Benutzername</td><td><input type="text" name="login"></td></tr>
		    <tr><td>Passwort</td><td><input type="password" name="password"></td></tr>
		  </table>
		  <input type="submit" value="login">
		  <input type="hidden" name="class" value="user">
		  <input type="hidden" name="method" value="login">
		  <input type="hidden" name="ref" value="?admin">
		</form>
		<a href="index.php">Zurück zur Startseite</a>
		</body>
		</html>
	<?  die();
	}
?>
<script type="javascript/text" language="javascript">
function dialog_confirm(question, dest) 
{
  if (confirm(question)) location = dest;
}
</script>
<table width=100%>
  <tr>
    <td align=center valign=absmiddle width=100>CMS Manager</td>
    <td align=center valign=absmiddle><h3>Administration</h3></td>
  </tr>
  <tr>
    <td align=center valign=top>
      <a href="index.php?admin">Startseite</a>
      <br><a href="index.php?admin&template">Templates</a>
      <? if(get_config("cms", false)) { ?>
	      <br><a href="index.php?admin&image">Images</a>
      <? } ?>
      <? if(get_config("game", false)) { ?>
      	<<br>a href="index.php?admin&techtree">Tech-Tree</a>
      <? } ?>
      <? if(get_config("questionaire", false)) { ?>
      	<br><a href="index.php?admin&questionaire">Questionaire</a>
      <? } ?>
      <? if(get_config("w40k", false)) { ?>
      	<br><br><a href="index.php?admin&w40k">W40K</a>
      	<? if (isset($_REQUEST['w40k'])) { ?>
      		<br>=&gt; Codices
      		<br>=&gt; Missionen
      		<br>=&gt; BattleTypes
      <? }} ?>
      </br></br><a href="index.php?admin&user">User</a>
      </br><a href="index.php?admin&settings">Settings</a>
      </br><a href="index.php?admin&config">Configuration</a>
      </br><a href="index.php?user/logout//ref=index.php">Logout</a>
    </td>
    <td align=left valign=top>
    <?		if (isset ($_REQUEST['template'])) {
			include ('admin/admin_template.inc.php');
		} else if (isset ($_REQUEST['image'])) {
			include ('admin/admin_image.inc.php');
		} else if (isset ($_REQUEST['techtree'])) {
			include ('admin/admin_techtree.inc.php');
		} else if (isset ($_REQUEST['settings'])) {
			include ('admin/admin_settings.inc.php');
		} else if (isset ($_REQUEST['config'])) {
			include ('admin/admin_config.inc.php');
		} else if (isset ($_REQUEST['questionaire'])) {
			include ('admin/admin_questionaire.inc.php');
		} else if (isset ($_REQUEST['user'])) {
			include ('admin/admin_user.inc.php');
		} else if (isset ($_REQUEST['w40k'])) {
			include ('admin/admin_w40k.inc.php');
		} else {
	?>
        <h3>CMS Administration</h3>
        <p>Willkommen in der Administrationszentrale von smallCMS. Weiter geht es mit den Menüpunkten links.</p>
        </p>
      <? 

	}
?>
    </td>
  </tr>
</table>
</body>
</html>