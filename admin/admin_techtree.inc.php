<?
$adminlogin = Session::getCookie('adminlogin');
if(empty($adminlogin)) die("DENIED");

if(isset($cat) && isset($create)) {
	$tc = new TTCategory();
	$tc->set('name', $vars['cat_name']);
	$tc->store();
	header("Location: index.php?admin&techtree&cat");
}
if(isset($cat) && isset($del)) {
	$tc = new TTCategory($vars['id']);
	$tc->delete();
	header("Location: index.php?admin&techtree&cat");
}
?>

<h3>Tech-Tree Management</h3>
<p><a href="?admin&techtree&cat">Kategorie</a> - <a href="?admin&techtree&entry">Eintrag</a></p>

<?
if(isset($cat)) { ?>
	<table>
		<tr>
			<th>Name</th>
			<th>&nbsp;</th>
		</tr>
		<? $result = TTCategory::getlist('ttcategory');
		   foreach($result as $item) {
		   	  $tc = new TTCategory($item['id']);
		   	  ?><tr>
		   	      <td><?=$tc->get('name')?></td>
		   	      <td><a href="?admin&techtree&cat&del&id=<?=$tc->get('id')?>">löschen</a></td>
		   	    </tr>
		   	  <?
		   }
		?>
	</table>
	<form>
		<input type="hidden" name="admin">
		<input type="hidden" name="techtree">
		<input type="hidden" name="cat">
		<input type="hidden" name="create">
		Kategoriename: <input type="text" name="cat_name" maxlength="100">
		<input type="submit" value="Erstellen">
	</form>
<? }
if(isset($entry)) { ?>
	
	
	
<? } ?>
