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
<h3>Dateiverwaltung</h3>
<a href="index.php?admin&image&img_upload">Upload</a> /
<a href="index.php?admin&image&img_show">Anzeigen</a>
  <? if(isset($img_upload)) { ?>
       <table class="adminedit"><form enctype="multipart/form-data" method="post">
         <input type="hidden" name="img_upload">
         <input type="hidden" name="image">
         <input type="hidden" name="admin">
         <tr>
           <th colspan=2><h3>Bild hochladen</h3></th>
         </tr>
         <tr><td>Dateiname</td><td><input type="file" name="filename"></td></tr>
         <tr><td>Bildname </td><td><input type="text" name="img_name"></td></tr>
         <tr><td><td><input type="submit" value="Upload"></td></tr>
       </form></table>
       <?=$msg?>
  <? } ?>
  <? if(isset($img_show)) {
  		$image = new Image();
  		$default = $_REQUEST['filter_type'];
		$optionlist = $image->getTypeOptionList($default, false);
?>
<form method="get">
	<input type="hidden" name="admin"/>
	<input type="hidden" name="image"/>
	<input type="hidden" name="img_show"/>
<table class="adminlist">
	<tr>
		<th>Bildname</th>
		<th>Größe</th>
		<th><select name="filter_type" onChange="this.form.submit();"><option value="">Dateityp</option><?=$optionlist?></select></th>
		<th>URL</th>
		<th/>
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
	<td><?=$item['url']?></td>
    <td><a href="javascript:dialog_confirm('Wirklich löschen?', 'index.php?admin&image&img_show&img_delete&id=<?=$item['id']?>');"><img src="img/delete.gif" border="0"/></a></td>
</tr>
<? 
}
?>
</table>
</form>
  <? } ?>