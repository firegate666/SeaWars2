<?
	function getTemplateClasses($class='') {
		return "<option></option><option>Navigation</option><option>Insel</option>";
	}
	function getTemplateLayouts($class, $layout='') {
		if($class=="Insel") return "<option></option><option>Ressourcen</option><option>Baufelder</option>";
		if($class=="Navigation") return "<option></option><option>... new Template</option><option>Navigation</option>";
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
	<td align=center valigb=top>
		<a href="admin.php">Startseite</a><br>
		<a href="admin.php?template">Templates</a>
	</td>
	<td align=left valign=top>
		<?
			if(isset($template)) { ?>
				<form method="POST">
				<table>
					<tr>
						<td>Class</td>
						<td><select name="class" onchange="submit()"><?=getTemplateClasses($class);?></select></td>
					</tr>
					<tr>
						<td>Layout</td>
						<td><select name="layout" onchange="submit()"><?=getTemplateLayouts($class, $layout);?></select></td>
					</tr>
				</table>
				</form>
			<? }
		?>
	</td>
</tr>
</table>
</body>
</html>