<?
	function login($user, $pass) {
		
		$adminuser = get_config('adminuser');
		$adminpassword = get_config('adminpassword');
		
		if(empty($adminuser) || empty($adminpassword))
			error("No adminuser and/or password set. Update config file", 'admin', 'login');
		
		if(($user==$adminuser) && ($pass==$adminpassword)) {
			Session::setCookie('adminlogin', 'true', NULL);
			return true;
		} else return false;
	}

	if(isset($logout)) {
		Session::setCookie('adminlogin','',0);
		header("Location: index.php");
	}

	if(isset($login_name) && isset($login_pass)) {
		if(!login($login_name, $login_pass)) $error = "Wrong username or password";
		else header("Location: index.php?admin");
	}

	$adminlogin = Session::getCookie('adminlogin');
	if(empty($adminlogin)) { ?>
		<h3>Adminlogin</h3>
		<font color="#FF0000"><?=$error?></font>
		<form>
		  <table>
		    <tr><td>Benutzername</td><td><input type="text" name="login_name"></td></tr>
		    <tr><td>Passwort</td><td><input type="password" name="login_pass"></td></tr>
		  </table>
		  <input type="submit" value="login">
		  <input type="hidden" name="admin">
		</form>
		<a href="index.php">Zurück zur Startseite</a>

	<?  die();
	}
?>
<html>
<script language="javascript">
function dialog_confirm(question, dest) 
{
  if (confirm(question)) location = dest;
}
</script>
<body>
<table width=100%>
  <tr>
    <td align=center valign=absmiddle width=100>CMS Manager</td>
    <td align=center valign=absmiddle><h3>Administration</h3></td>
  </tr>
  <tr>
    <td align=center valign=top>
      <a href="index.php?admin">Startseite</a><br>
      <a href="index.php?admin&template">Templates</a><br>
      <? if(get_config("cms", false)) { ?>
	      <a href="index.php?admin&image">Images</a><br>
      <? } ?>
      <? if(get_config("game", false)) { ?>
      	<a href="index.php?admin&techtree">Tech-Tree</a><br>
      <? } ?>
      <br><a href="index.php?admin&logout">Logout</a>
    </td>
    <td align=left valign=top>
    <?		if (isset ($template)) {
			include ('admin/admin_template.inc.php');
		} else if (isset ($image)) {
			include ('admin/admin_image.inc.php');
		} else if (isset ($techtree)) {
			include ('admin/admin_techtree.inc.php');
		} else {
	?>
        <h3>CMS Administration</h3>
        <p>Bitte im Menü links eine Aktion wählen.
          <ul>
            <li>Template: Editieren und verwalten von HTML Templates, Seiten etc...
            <li>Images: Upload und löschen von Bildern
          </ul>
        </p>
      <? 

	}
?>
    </td>
  </tr>
</table>
</body>
</html>