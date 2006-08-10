<?$adminlogin = (User::hasright('admin') || User::hasright('templateadmin'));if(empty($adminlogin)) die("DENIED");
if (isset ($tpl_content)) {
	global $mysql;	$tpl_content = html_entity_decode($tpl_content);	$tpl_class = $mysql->escape($tpl_class);	$tpl_layout = $mysql->escape($tpl_layout);
	$contenttype = $mysql->escape($contenttype);	$tpl_query = "UPDATE template SET content='$tpl_content', contenttype='$contenttype' WHERE class='$tpl_class' AND layout = '$tpl_layout';";
	$mysql->update($tpl_query);
	if (!isset($_REQUEST['submitandstay']))		unset ($tpl_layout);
}
if (isset ($tpl_addlayout)) {
	if ($tpl_layoutname != "") {		Template::createTemplate($tpl_class, $tpl_layoutname);		$tpl_layout = $tpl_layoutname;	}
}
if (isset ($tpl_delete)) {
	Template::deleteTemplate($tpl_class, $tpl_layout);
	unset ($tpl_delete);
	unset ($tpl_layout);
}
?>
<table border=0 width="100%">
  <tr>
    <th colspan="4" align="left" valign="top"><a href="index.php?admin&template">Templateklasse</a></th>
  </tr>
  <?

$array = array ();
$link = "index.php?admin&template&tpl_class=";
$array = Template::getClasses();
$options = '<option></option>';
foreach ($array as $items) {
	if($items == $tpl_class) $options .= '<option selected>'.$items.'</option>';	else $options .= '<option>'.$items.'</option>';
}
?>
  <tr>    <form name="selectclass" action="index.php" method="get">      <input type="hidden" name="admin">	  <input type="hidden" name="template">      <td align="left" valign="top">Template Klasse</td>
      <td align="left" valign="top">
	    <select name="tpl_class" onChange="this.form.submit()"><?=$options?></select>
      </td>
    </form><? if (isset ($tpl_class) && !empty($tpl_class)) { ?>	<form name="showtemplate" action="index.php" method="get">		<input type="hidden" name="admin">		<input type="hidden" name="template">		<input type="hidden" name="tpl_class" value="<?=$tpl_class?>">		<input type="hidden" name="admin">    	<td align=left valign=top>Template</td>    	<td align=left valign=top>			<select name="tpl_layout" size="1" onChange="this.form.submit()"><option></option><?
$array = Template::getLayouts($tpl_class);
foreach ($array as $items) {	$selected = '';	if($items[0] == $tpl_layout)		$selected ='SELECTED="SELECTED"';	?><option <?=$selected?>><?=$items[0]?></option><?
}?>
			</select>			<input type='submit' value='Bearbeiten'/>			<input type='submit' value='Löschen' name='tpl_delete'/>		</td>	</form></tr><tr>    <form action="index.php" method="get">       	<input type="hidden" name="tpl_addlayout">		<input type="hidden" name="admin">		<input type="hidden" name="template">		<input type="hidden" name="tpl_class" value="<?=$tpl_class?>">    	<td colspan="2">&nbsp;</td>    	<td align="left" valign="top">Neues Template</td>    	<td align="left" valign="top">			<input type="text"   name="tpl_layoutname">			<input type="submit" value="Add Layout">    	</td>	</form></tr><?} else
	echo ("<td colspan='2'></td></tr>");
?></table><?

if (isset ($tpl_layout)) {
?>
  <script>
    function insertTag(tagname) {
       name = prompt('Referenzname','hier Inhalte/Name eingeben');
       if(tagname == 'image') myValue = '<img src="${'+tagname+':'+name+'}">';       else if(tagname == 'plink') myValue = '<a href="${'+tagname+':'+name+'}">linktext</a>';       else if(tagname == 'page')  myValue = '${'+tagname+':'+name+'}';       else myValue = '<'+tagname+'>'+name+'</'+tagname+'>';		if (document.selection) {			document.edittpl.tpl_content.focus();			sel = document.selection.createRange();			sel.text = myValue;		} //MOZILLA/NETSCAPE support		else if (document.edittpl.tpl_content.selectionStart || document.edittpl.tpl_content.selectionStart == '0') {			var startPos = document.edittpl.tpl_content.selectionStart;			var endPos = document.edittpl.tpl_content.selectionEnd;			document.edittpl.tpl_content.value = document.edittpl.tpl_content.value.substring(0, startPos)			+ myValue			+ document.edittpl.tpl_content.value.substring(endPos, document.edittpl.tpl_content.value.length);		} else {			document.edittpl.tpl_content.value += myValue;		}    }
  </script>
  <form action="index.php" method="post" name="edittpl">
    <p>Template '<?=$tpl_layout?>' bearbeiten</p>    <input type="submit" name="submit" value="Speichern und schließen">    <input type="submit" name="submitandstay" value="Nur Speichern">    <table>
      <tr>
        <td>
          <input type="button" value="Fett" onClick="insertTag('b')">          <input type="button" value="Kursiv" onClick="insertTag('i')">          <input type="button" value="Unterstrichen" onClick="insertTag('u')">          <input type="button" value="ImageTag" onClick="insertTag('image')">
          <input type="button" value="PageLink" onClick="insertTag('plink')">
          <input type="button" value="PageInclude" onClick="insertTag('page')">
        </td>
      </tr>
      <tr>
        <td>
          <textarea name=tpl_content cols=80 rows=50><?=htmlentities(Template::getLayout($tpl_class, $tpl_layout,array(),true, array(), true));?></textarea>
        </td>
        <td align="left" valign="top">Mögliche Tags: (nicht vergessen, dass Tags immer ein $ vorangestellt werden muss)
          <ul>
            <li>{plink:seitenname} erstellt einen Hyperlink zur Page "seitenname"
            <li>{page:seitenname} bindet die Seite "seitenname" an dieser Stelle ins Dokument ein
          </ul>
        </td>
      </tr>      <tr>      	<td>      		<select name="contenttype">				<?=Template::contenttypeoptionlist($tpl_class, $tpl_layout);?>      		</select>      	</td>      </tr>    </table>
    <input type="submit" name="submit" value="Speichern und schließen">    <input type="submit" name="submitandstay" value="Nur Speichern">    <input type="hidden" name="template" value="">
    <input type="hidden" name="admin" value="">
    <input type="hidden" name="tpl_class" value="<?=$tpl_class?>">
    <input type="hidden" name="tpl_layout" value="<?=$tpl_layout?>">
  </form>
<? } ?>