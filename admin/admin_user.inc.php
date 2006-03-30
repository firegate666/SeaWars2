<?
$adminlogin = Session::getCookie('adminlogin');
if(empty($adminlogin)) die("DENIED");
?>

<h3>User Configuration</h3>