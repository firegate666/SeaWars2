<?
	if(isset($content)) {
		$DB = new MySQL();
		$query = "UPDATE template SET content='$content' WHERE class='$class' AND layout = '$layout';";
		$DB->update($query);
		unset($class);
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
					      <th><a href="index.php?admin&template">Templateklasse</a>
					      (add class)
					      <? if(isset($class)) { echo("/ $class (add layout to class)"); } ?>
					      </th>
					    </tr>
					<?
					$array = array();
					if(!isset($class)) {
						$link = "index.php?admin&template&&class=";
						$array = Template::getClasses();
						foreach($array as $items) {
							?><tr><td>- <a href="<?=$link?><?=$items[0]?>"><?=$items[0]?></a></td></tr><?
						}
					}
					else {
						$link = "index.php?admin&template&class=$class&layout=";
						$array = Template::getLayouts($class);
						foreach($array as $items) {
							?><tr><td>- <?=$items[0]?>
							<a href="<?=$link?><?=$items[0]?>">(edit)</a>
							(delete)
							</td></tr><?
						}
					} 
					?></table><?
				if(isset($layout)) {
					?>
					<form action="index.php" method="post">
					<p><input type="submit" value="Änderungen speichern"></p>
					<textarea name=content cols=80 rows=25><?=Template::getLayout($class, $layout);?></textarea>
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
