<?
  if(isset($img_delete)) {
    echo("noch nicht implementiert");                         	
  }
  if(isset($img_upload) && isset($img_file) && isset($img_name)) {
    global $_CONFIG;
    if (is_uploaded_file($HTTP_POST_FILES['img_file']['tmp_name'])) {
      if($HTTP_POST_FILES['img_file']['type']=="image/gif") $extension = ".gif";
      else $extension = ".jpg";
      $newname = randomstring(25).$extension;

      if (($HTTP_POST_FILES['img_file']['type']=="image/gif") || ($HTTP_POST_FILES['img_file']['type']=="image/pjpeg") || ($HTTP_POST_FILES['img_file']['type']=="image/jpeg")) {
        if (file_exists($path . $newname)) {
          $msg .= "Fehler, weil der Dateiname bereits vorhanden ist.";
        }
        $res = copy($HTTP_POST_FILES['img_file']['tmp_name'], $_CONFIG["uploadpath"].$newname);
        if (!$res) {
          $msg .= "Upload fehlgeschlagen!";
        } else {
          // Datenbankreferenz erstellen
          $img = new Image();
          $img->data['name'] = $img_name;
          $img->data['url']  = $_CONFIG["uploadpath"].$newname;
          $img->store();
        }
        $msg .=  "Dateigröße: ".$HTTP_POST_FILES['img_file']['size']." bytes<br>\n";
        $msg .=  "Dateityp: ".$HTTP_POST_FILES['img_file']['type']."<br>\n";
      } else {
        $msg .=  "Wrong file type<br>\n";
        exit;
      }
    }
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
       $array = Image::getImageList();
       foreach($array as $item) {
         ?><li><a href="javascript:show('<?=$item[2]?>')"><?=$item[1]?></a>
         - <a href="index.php?admin&image&img_delete&id=<?=$item[0]?>">(delete)</a>
         </li><?
       } ?>
     </ul></td>
     <td><img name="preview" src="">
     </td>
  <? } ?>
  </tr>
</table>
