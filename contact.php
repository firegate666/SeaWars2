<?
// TODO better handling in contact class

$to = 'marco@firegate.de';
$from = $_REQUEST['email'];
$subject1 = $_REQUEST['subject'];
$subject2 = $_REQUEST['subject2'];
$subject = "$subject1 ($subject2)";
$body = $_REQUEST['body'];
$name = $_REQUEST['name'];
$body = "Nachricht von $name:\n$body";
$headers = 'From: '.$from."\r\n" .
	'Reply-To: '.$from."\r\n" .
	'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $body, $headers);
header("Location: ".$_REQUEST['ref']);
?>
