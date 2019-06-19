<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once("../admin/assets/db/db.php");
require_once ('src/codebird.php');
\Codebird\Codebird::setConsumerKey('JLPoz2ZwPpqh43EA5Vig', 'do0Ca5gTmaMsImzX8envIXYKHMGg7tuOKukWaba1o');
$cb = \Codebird\Codebird::getInstance();

$strsql = "select * from users where id = 3";
$row = $db->get_row($strsql);

$cb->setToken($row->twitter_oauth_token, $row->twitter_oauth_token_secret); // see above
$strsqlstatus = "select desk from ms_event where aktif = 1";
$row2 = $db->get_row($strsqlstatus);
if($row2){
	$desk = explode(";",$row2->desk);
	$count = count($desk);
	$rand = rand(1,$count-1);
}

$reply = $cb->statuses_update('status='.$desk[$rand]);
print_r($reply);
?>