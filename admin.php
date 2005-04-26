<html>
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
      <a href="index.php?admin&image">Images</a>
    </td>
    <td align=left valign=top>
    <?
      if(isset($template)) {
        include('admin/admin_template.inc.php');
      } else if(isset($image)) {
        include('admin/admin_image.inc.php');
      } else { ?>
        <h3>CMS Administration</h3>
        <p>Bitte im Menü links eine Aktion wählen.
          <ul>
            <li>Template: Editieren und verwalten von HTML Templates, Seiten etc...
            <li>Images: Upload und löschen von Bildern
          </ul>
        </p>
      <? }
    ?>
    </td>
  </tr>
</table>
</body>
</html>
