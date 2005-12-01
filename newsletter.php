<?
// TODO better handling in newsletter class
	require_once dirname(__FILE__).'/config/All.inc.php';
	require_once dirname(__FILE__).'/include/All.inc.php';
 	require_once dirname(__FILE__).'/classes/All.inc.php';

if(!empty($email)) {
                  $mysql = new MySQL();
                  $query = "INSERT INTO newsletter(email) VALUES('$email');";
                  $id = $mysql->insert($query);
                  $to = 'marco@firegate.de';
                  $email = $_REQUEST['email'];
                  $subject = "Newslettereintrag";
                  $body = "Eine neue Newsletteranmeldung liegt vor von $email";
                  $headers = 'From: '.$to."\r\n" .
                  	'Reply-To: '.$to."\r\n" .
                   	'X-Mailer: PHP/' . phpversion();
                  mail($to, $subject, $body, $headers);
                  header("Location: ".$_REQUEST['ref']);
} else
  header("Location: ".$_REQUEST['referr']);


?>
