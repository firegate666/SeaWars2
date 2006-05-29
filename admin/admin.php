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

	if(empty($adminlogin)) {
		header("Location: ?admin/show/login");
	}
?>
<html>
  <head>
    <link href="?admin/show/css" rel="stylesheet" type="text/css"/>
	<script>
		function dialog_confirm(question, dest) 
		{
  			if (confirm(question)) location = dest;
		}
	</script>
  </head>
<body>
<table width="100%"">
  <tr>
    <td align="left" valign="absmiddle" width="200">CMS Manager</td>
    <td align="left" valign="absmiddle">
    <?
    	$admin = new Admin('topframe');
    	$vars = array();
    	echo $admin->show($vars);
    ?>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" width="100">
      <a href="index.php?admin">Startseite</a>
      <br><a href="index.php?admin&template">Templates</a>
      <? if(get_config("cms", false)) { ?>
	      <br><a href="index.php?admin&image">Images</a>
      <? } ?>
      <? if(get_config("game", false)) { ?>
      	<br>a href="index.php?admin&techtree">Tech-Tree</a>
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
    <td align="left" valign="top">
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
        <h3>Startseite</h3>
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