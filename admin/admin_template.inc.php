<?$adminlogin = (User::hasright('admin') || User::hasright('templateadmin'));if(empty($adminlogin)) die("DENIED");
if (isset ($tpl_content)) {
	global $mysql;	$tpl_content = htmlentities($tpl_content);	$tpl_class = $mysql->escape($tpl_class);	$tpl_layout = $mysql->escape($tpl_layout);
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
<table class="adminlist" width="100%">
<tr>	<th colspan="4"><h3>Templates bearbeiten</h3></th></tr>  <?

$array = array ();
$link = "index.php?admin&template&tpl_class=";
$array = Template::getClasses();
$options = '<option></option>';
foreach ($array as $items) {
	if($items == $tpl_class) $options .= '<option selected>'.$items.'</option>';	else $options .= '<option>'.$items.'</option>';
}
?>
<tr>	<td>Template Klasse</td>
	<td>
    	<form name="selectclass" action="index.php" method="get">      		<input type="hidden" name="admin">	  		<input type="hidden" name="template">	    	<select name="tpl_class" onChange="this.form.submit()"><?=$options?></select>
    	</form>    </td>
<? if (isset ($tpl_class) && !empty($tpl_class)) { ?>    <td>Template</td>    <td>		<form name="showtemplate" action="index.php" method="get">			<input type="hidden" name="admin">			<input type="hidden" name="template">			<input type="hidden" name="tpl_class" value="<?=$tpl_class?>">			<input type="hidden" name="admin">			<select name="tpl_layout" size="1" onChange="this.form.submit()"><option></option><?
$array = Template::getLayouts($tpl_class);
foreach ($array as $items) {	$selected = '';	if($items[0] == $tpl_layout)		$selected ='SELECTED="SELECTED"';	?><option <?=$selected?>><?=$items[0]?></option><?
}?>
			</select>			<input type='submit' value='Bearbeiten'/>			<input type='submit' value='Löschen' name='tpl_delete'/>		</form>	</td></tr><tr>	<td>Neues Template</td>    <td>    	<form action="index.php" method="get">       		<input type="hidden" name="tpl_addlayout">			<input type="hidden" name="admin">			<input type="hidden" name="template">			<input type="hidden" name="tpl_class" value="<?=$tpl_class?>">			<input type="text"   name="tpl_layoutname">			<input type="submit" value="Add Layout">		</form>    </td>    <td colspan="2"></td></tr><?} else
	//echo ("<td colspan='2'></td></tr>");
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
          <textarea name="tpl_content" cols="80" rows="50"><?=(Template::getLayout($tpl_class, $tpl_layout,array(),true, array(), true));?></textarea>
        </td>
      </tr>      <tr>      	<td>      		<select name="contenttype">				<?=Template::contenttypeoptionlist($tpl_class, $tpl_layout);?>      		</select>      	</td>      </tr>    </table>
    <input type="submit" name="submit" value="Speichern und schließen">    <input type="submit" name="submitandstay" value="Nur Speichern">    <input type="hidden" name="template" value="">
    <input type="hidden" name="admin" value="">
    <input type="hidden" name="tpl_class" value="<?=$tpl_class?>">
    <input type="hidden" name="tpl_layout" value="<?=$tpl_layout?>">
  </form>
<? } ?>