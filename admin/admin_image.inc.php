<?
<table width=100% border=1>
  <tr>
    <th colspan=2>Bilderverwaltung</th>
  <tr>
    <td  align=left valign=top><a href="index.php?admin&image&img_upload">Upload</a></td>
    <td  align=left valign=top><a href="index.php?admin&image&img_show">Anzeigen / L�schen</a></td>
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
         - <a href="index.php?admin&image&img_delete&id=<?=$item[0]?>">(delete)</a>
         </li><? 
     </ul></td>
     <td><img name="preview" src="">
     </td>
  <? } ?>
  </tr>
</table>

