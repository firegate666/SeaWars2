<?
$adminlogin = (User::hasright('admin') || User::hasright('templateadmin'));
$msg = "";
if(empty($adminlogin)) die("DENIED");

if (isset ($_REQUEST['img_delete'])) {
	if(isset($_REQUEST['id'])) {
	        $i = new Image($_REQUEST['id']);
	        $i->delete();
	}
}
if (isset ($_REQUEST['img_upload']) && isset($HTTP_POST_FILES['filename'])) {
	$image = new Image();
	$result = $image->parsefields($HTTP_POST_FILES['filename']);
	if ($result === false) {
		$msg .= "Dateigröße: ".$HTTP_POST_FILES['filename']['size']." bytes<br>\n";
		$msg .= "Dateityp: ".$HTTP_POST_FILES['filename']['type']."<br>\n";
		if (!empty($_REQUEST['img_name']))
			$image->set('name', $_REQUEST['img_name']);
		$image->store();
	} else {
		$msg .= implode($result);
	}
} else {
	$msg = "ready for upload";
}
?>
<table width=100% border=1>
  <tr>
    <th colspan=2>Dateiverwaltung</th>
  <tr>
    <td  align=left valign=top><a href="index.php?admin&image&img_upload">Upload</a></td>
    <td  align=left valign=top><a href="index.php?admin&image&img_show">Anzeigen / Löschen</a></td>
  </tr>
  <tr>
  <? if(isset($img_upload)) { ?>
     <td colspan=2 align=left valign=top>
       <table><form enctype="multipart/form-data" method="post">
         <input type="hidden" name="img_upload">
         <input type="hidden" name="image">
         <input type="hidden" name="admin">
         <tr>
           <th colspan=2>Bild hochladen</th>
         </tr>
         <tr><td align=left valign=top>Dateiname</td><td align=left valign=top><input type="file" name="filename"></td></tr>
         <tr><td align=left valign=top>Bildname </td><td align=left valign=top><input type="text" name="img_name"></td></tr>
         <tr><td  align=left valign=top colspan=2><input type="submit" value="Upload"></td></tr>
       </form></table>
       <?=$msg?>
     </td>
  <? } ?>
  <? if(isset($img_show)) {
  		$image = new Image();
  		$default = $_REQUEST['filter_type'];
		$optionlist = $image->getTypeOptionList($default, false);
?>
     <td colspan=2 align=left valign=top>
<form method="get">
	<input type="hidden" name="admin"/>
	<input type="hidden" name="image"/>
	<input type="hidden" name="img_show"/>
<table>
	<tr>
		<th align=left valign=top>Bildname</th>
		<th align=left valign=top>Größe</th>
		<th align=left valign=top><select name="filter_type" onChange="this.form.submit();"><option value="">Dateityp</option><?=$optionlist?></select></th>
		<th/>
		<th align=left valign=top>URL</th>
	</tr>
<?
$where[] = 'parentid=0';
if (!empty($_REQUEST['filter_type']))
	$where[] = "type='{$_REQUEST['filter_type']}'";
$array = Image::getImageList($where);
foreach ($array as $item) {
?>
<tr>
	<td><a href="<?=$item['url']?>" target="_blank"><?=$item['name']?></a></td>
	<td><?=$item['size']?></td>
	<td><?=$item['type']?></td>
    <td><a href="javascript:dialog_confirm('Wirklich löschen?', 'index.php?admin&image&img_show&img_delete&id=<?=$item['id']?>');"><img src="img/delete.gif" border="0"/></a></td>
	<td><?=$item['url']?></td>
</tr>
<? 
}
?>
</table>
</form>
     </td>
  <? } ?>
  </tr>
</table>


