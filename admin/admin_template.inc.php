<?
if (isset ($tpl_content)) {
	$DB = new MySQL();
	$tpl_query = "UPDATE template SET content='$tpl_content' WHERE class='$tpl_class' AND layout = '$tpl_layout';";
	$DB->update($tpl_query);
	unset ($tpl_layout);
}
if (isset ($tpl_addlayout)) {
	Template :: createTemplate($tpl_class, $tpl_layoutname);
}
if (isset ($tpl_delete)) {
	Template :: deleteTemplate($tpl_class, $tpl_layout);
	unset ($tpl_delete);
	unset ($tpl_layout);
}
?>
<table border=0>
  <tr>
    <th align="left" valign="top" colspan><a href="index.php?admin&template">Templateklasse</a>
    <? if(isset($tpl_class)) { ?>
      / Class: <?=$tpl_class?></th>
    <td align="left" valign="top">
      <form>
        <input type="hidden" name="tpl_addlayout">
	<input type="hidden" name="admin">
	<input type="hidden" name="template">
	<input type="hidden" name="tpl_class" value="<?=$tpl_class?>">
	<input type="text"   name="tpl_layoutname">
	<input type="submit" value="Add Layout">
      </form>
    </td>
    <? } else echo("</th>"); ?>
  </tr>
  <?

$array = array ();
$link = "index.php?admin&template&tpl_class=";
$array = Template :: getClasses();
$options = '<option></option>';
foreach ($array as $items) {
	$options .= '<option>'.$items.'</option>';
}
?>
  <tr>
    <td>
      <form name="selectclass">
        <input type="hidden" name="admin">
	<input type="hidden" name="template">
	<select name="tpl_class" onChange="this.form.submit()"><?=$options?></select>
      </form>
    </td>
    <?

if (isset ($tpl_class)) {
	echo ("<td align=left valign=top><table>");
	$link = "index.php?admin&template&tpl_class=$tpl_class&tpl_layout=";
	$array = Template :: getLayouts($tpl_class);
	foreach ($array as $items) {
?><td>- <?=$items[0]?>
	<a href="<?=$link?><?=$items[0]?>">(edit)</a>
	<a href="<?=$link?><?=$items[0]?>&tpl_delete">(delete)</a>
	<a href="index.php?class=<?=$tpl_class?>&method=show&id=<?=$items[0]?>" target="_blank">(show)</a>
	</td></tr><?

	}
	echo ("</table></td>");
} else
	echo ("</tr>");
?></table><?

if (isset ($tpl_layout)) {
?>
  <script>
    function insertTag(tagname) {
       name = prompt('Referenzname','hier Name eingeben');
       if(tagname == 'image') document.edittpl.tpl_content.value += '<img src="${'+tagname+':'+name+'}">';
       if(tagname == 'plink') document.edittpl.tpl_content.value += '<a href="${'+tagname+':'+name+'}">linktext</a>';
       if(tagname == 'page') document.edittpl.tpl_content.value += '${'+tagname+':'+name+'}';
    }
  </script>
  <form action="index.php" method="post" name="edittpl">
    <input type="submit" value="Änderungen speichern">
    <table>
      <tr>
        <td>
          <input type="button" value="IMG" onClick="insertTag('image')">
          <input type="button" value="Link" onClick="insertTag('plink')">
          <input type="button" value="Page" onClick="insertTag('page')">
        </td>
      </tr>
      <tr>
        <td>
          <textarea name=tpl_content cols=80 rows=25><?=Template::getLayout($tpl_class, $tpl_layout,array(),true);?></textarea>
        </td>
        <td align="left" valign="top">Mögliche Tags: (nicht vergessen, dass Tags immer ein $ vorangestellt werden muss)
          <ul>
            <li>{plink:seitenname} erstellt einen Hyperlink zur Page "seitenname"
            <li>{page:seitenname} bindet die Seite "seitenname" an dieser Stelle ins Dokument ein
          </ul>
        </td>
      </tr>
    </table>
    <input type="submit" value="Änderungen speichern">
    <input type="hidden" name="template" value="">
    <input type="hidden" name="admin" value="">
    <input type="hidden" name="tpl_class" value="<?=$tpl_class?>">
    <input type="hidden" name="tpl_layout" value="<?=$tpl_layout?>">
  </form>
<? } ?>

