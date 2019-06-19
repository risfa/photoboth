<?php
session_start();
require_once 'config.php';
$fb = new Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl("https://5dapps.com/amplified/rfid/photobooth/fb/proses.php",array('scope' => 'email'));
header("location: " . $loginUrl);