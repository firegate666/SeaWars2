<?
	if(isset($content)) {
		$DB = new MySQL();
		$query = "UPDATE template SET content='$content' WHERE class='$class' AND layout = '$layout';";
		$DB->update($query);
		//unset($class);
		unset($layout);
	}
	if(isset($addlayout)) {
		Template::createTemplate($class, $layoutname);
	}
	if(isset($delete)) {
		Template::deleteTemplate($class, $layout);
		unset($delete);
		unset($layout);
	}
?>
<html>
<body>
<table width=100%>
<tr>
	<td align=center valign=absmiddle width=100>Sea Wars 2</td>
	<td align=center valign=absmiddle><h3>Administration</h3></td>
</tr>
<tr>
	<td align=center valign=top>
		<a href="index.php?admin">Startseite</a><br>
		<a href="index.php?admin&template">Templates</a>
	</td>
	<td align=left valign=top>
		<?
			if(isset($template)) { ?>
				<?
					?>
					<table border=0>
					    <tr>
					      <th align="left" valign="top" colspan><a href="index.php?admin&template">Templateklasse</a>
					      <? if(isset($class)) { ?>
					      	/ Class: <?=$class?></th> 
					      	  <td align="left" valign="top"><form>
					      	    <input type="hidden" name="addlayout">
					      	    <input type="hidden" name="admin">
					      	    <input type="hidden" name="template">
					      	    <input type="hidden" name="class" value="<?=$class?>">
					      	    <input type="text"   name="layoutname">
					      	    <input type="submit" value="Add Layout">
					      	  </form></td>
					      <? } else echo("</th>"); ?>
					    </tr>
					<?
					$array = array();
					//if(!isset($class)) {
						$link = "index.php?admin&template&&class=";
						$array = Template::getClasses();
						
						$options = '<option></option>';
						foreach($array as $items) {
							$options .= '<option>'.$items.'</option>';
						}
						?>
						<tr><td>
						<form name="selectclass">
						<input type="hidden" name="admin">
						<input type="hidden" name="template">
						<select name="class" onChange="this.form.submit()"><?=$options?></select>
						</form></td>
						<?
					//}
					if(isset($class)) {
						echo("<td align=left valign=top><table>");
						$link = "index.php?admin&template&class=$class&layout=";
						$array = Template::getLayouts($class);
						foreach($array as $items) {
							?><td>- <?=$items[0]?>
							<a href="<?=$link?><?=$items[0]?>">(edit)</a>
							<a href="<?=$link?><?=$items[0]?>&delete">(delete)</a>
							</td></tr><?
						}
						echo("</table></td>");
					} else echo("</tr>");
					?></table><?
				if(isset($layout)) {
					?>
					<form action="index.php" method="post">
					<p><input type="submit" value="Änderungen speichern"></p>
					<textarea name=content cols=80 rows=25><?=Template::getLayout($class, $layout,array(),true);?></textarea>
					<p><input type="submit" value="Änderungen speichern"></p>
					<input type="hidden" name="template" value="">
					<input type="hidden" name="admin" value="">
					<input type="hidden" name="class" value="<?=$class?>">
					<input type="hidden" name="layout" value="<?=$layout?>">
					</form>
				<? }
			}
		?>
	</td>
</tr>
</table>
</body>
</html>
