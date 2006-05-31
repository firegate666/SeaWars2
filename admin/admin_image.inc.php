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
if (isset ($_REQUEST['img_upload']) && isset($HTTP_POST_FILES['img_file']) && isset($_REQUEST['img_name'])) {
	if (is_uploaded_file($HTTP_POST_FILES['img_file']['tmp_name'])) {
                $upload_allowed = true;
		if ($HTTP_POST_FILES['img_file']['type'] == "image/gif") {
			$extension = ".gif";
		} else {
			$extension = ".jpg";
		}
		$newname = randomstring(25).$extension;

		if (($HTTP_POST_FILES['img_file']['type'] == "image/gif") || ($HTTP_POST_FILES['img_file']['type'] == "image/pjpeg") || ($HTTP_POST_FILES['img_file']['type'] == "image/jpeg")) {
			$msg .= "Upload wird gestartet.<br>";
                        $res = copy($HTTP_POST_FILES['img_file']['tmp_name'], get_config("uploadpath").$newname);
			if (!$res) {
				$msg .= "Upload fehlgeschlagen!";
			} else {
				// Datenbankreferenz erstellen
				$img = new Image();
				$img->set('name', $_REQUEST['img_name']);
				$img->set('url', get_config("uploadpath").$newname);
				$img->store();
			}
			$msg .= "Dateigröße: ".$HTTP_POST_FILES['img_file']['size']." bytes<br>\n";
			$msg .= "Dateityp: ".$HTTP_POST_FILES['img_file']['type']."<br>\n";
		} else {
			$msg .= "Wrong file type<br>\n";
		}
	} else {
		$msg = "Error while uploading";
	}
} else {
	$msg = "Form misconfigured";
}
?>
<table width=100% border=1>
  <tr>
    <th colspan=2>Bilderverwaltung</th>
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
         <tr><td align=left valign=top>Dateiname</td><td align=left valign=top><input type="file" name="img_file"></td></tr>
         <tr><td align=left valign=top>Bildname </td><td align=left valign=top><input type="text" name="img_name"></td></tr>
         <tr><td  align=left valign=top colspan=2><input type="submit" value="Upload"></td></tr>
       </form></table>
       <?=$msg?>
     </td>
  <? } ?>
  <? if(isset($img_show)) { ?>
     <script>
       function show(url) {
         document.preview.src = url;
       }
     </script>
     <td align="left" valign="top"><ul><?



$array = Image :: getImageList();
foreach ($array as $item) {
?><li><a href="javascript:show('<?=$item[2]?>')"><?=$item[1]?></a>
         - <a href="javascript:dialog_confirm('Wirklich löschen?', 'index.php?admin&image&img_show&img_delete&id=<?=$item[0]?>');">(<img src="img/delete.gif" border="0"/>)</a>
         </li><? 

}
?>
     </ul></td>
     <td><img name="preview" src="">
     </td>
  <? } ?>
  </tr>
</table>


