<html>
<form action="upload.php" method="post">
 <p>Username : @<input type="text" name="name" /></p>
 <p>Password :<input type="password" name="password" /></p>
 <p>Caption :<input type="text" name="caption" /></p>
 <p><input type="submit" /></p>
</form>


</html>




<?php

require_once 'functions.php';
require_once 'instaW.php';



$username_html = $_POST['name'];
$password_html = $_POST['password'];
$caption_html = $_POST['caption'];

// echo 'this is username : ' .  $username_html;
// echo 'this is password : </br>' . $password_html;

$username = $username_html;
$password = $password_html;
// $filename = __DIR__.'/400x400.jpg';
$filename = __DIR__.'/test12332131.jpg';
$caption = $caption_html;

$client = new instaW();
$login = $client->login($username,$password);
if($login){
    $media_id = $client->upload_image($filename);
    if($media_id){
        $manipulate = $client->configure_image($media_id,$caption);
        if($manipulate){
            echo 'Your image has been uploaded, to @'.$username_html.' Image id: '.$manipulate->id;
            $username_html = null;
            $password_html = null;
            exit;
        }
    }
}

die_pre($client->printError());

