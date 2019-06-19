<?php
error_reporting(0);
ob_start();
require_once("../admin/assets/db/db.php");
require_once ('src/codebird.php');
\Codebird\Codebird::setConsumerKey('Vu36hAFuwKNQrBIfmuZrQ','JjrBajTX1ml77JxbFuuUa2dloPvileO4rasPsX4rlYA');
$cb = \Codebird\Codebird::getInstance();
session_start();
?>
<script src="/rfid/admin/assets/js/jquery.js" type="text/javascript" ></script>
<?php


if (! isset($_SESSION['oauth_token'])) {
		
    // get the request token
    $reply = $cb->oauth_requestToken(array(
        //'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
        'oauth_callback' =>'http://5dapps.com/amplified/rfid/twitter/index.php'
        
        
    ));
		

    // store the token
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
    $_SESSION['oauth_verify'] = true;
		
    // redirect to auth website
    $auth_url = $cb->oauth_authorize();
		
		header('Location: ' . $auth_url);
		exit;
    die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
		
    // verify the token
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    unset($_SESSION['oauth_verify']);

    // get the access token
    $reply = $cb->oauth_accessToken(array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
	
    // store the token (which is different from the request token!)
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
		
    // send to same URL, without oauth GET parameters
    header('Location: index.php');
		exit;
    die();
}elseif ( isset($_SESSION['oauth_token'] ) &&  isset($_SESSION['oauth_verify']) ){
		 // get the request token
    $reply = $cb->oauth_requestToken(array(
        //'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
        'oauth_callback' =>'http://5dapps.com/amplified/rfid/twitter/index.php'
    ));
		 $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
		  // redirect to auth website
    $auth_url = $cb->oauth_authorize();

		header('Location: ' . $auth_url);
		exit;
    die();
}



if (isset($_SESSION["twitterregis"]) && $_SESSION["twitterregis"]=="yes" && isset($_SESSION["twitter_id"]) && $_SESSION["twitter_id"] !="" && isset($_SESSION["twitcek"]) && $_SESSION["twitcek"] !="yes" ){
	$_SESSION["peringatan"]="kamu telah melakukan registrasi twitter";
	?>	
		<script>
		$( document ).ready(function() {
		self.close();
		window.opener.location.replace('/amplified/rfid/permision.php');
		});
		</script>
<?php	
	exit;
}


// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$twitteruser = $cb->account_verifyCredentials();
$_SESSION["twitter_id"] = $twitteruser->id;
$_SESSION["twitter_name"] = $twitteruser->screen_name;




if  ($twitteruser->id !=""){
	$strsqlcek="select  * from ms_member where twitter_id= '".$twitteruser->id."'  ";
		$rowcek=$db->get_row($strsqlcek);
		
		if ($rowcek){
			$_SESSION["peringatan"]="ID Twitter telah melakukan registrasi";
			$_SESSION["twittcek"]="yes";
			$_SESSION["regis"]="regis_";
			unset ($_SESSION['oauth_verify']);
			unset ($_SESSION['oauth_token']);
	?>	
		<script>
		window.opener.location.replace('/amplified/rfid/permision.php');
		self.close();

		</script>
<?php	
  	exit;
	
		}else{
			$_SESSION["regis"]="regis_";	
			$_SESSION["twitterregis"]="yes";
			$_SESSION["peringatan"]="Selamat kamu telah melakukan registrasi Twitter";
	?>	
		<script>
		window.opener.location.replace('/amplified/rfid/permision.php');
		self.close();

		</script>
<?php	
	exit;
		}	
}else{
	$_SESSION["peringatan"]="Kamu gagal melakukan registrasi Twitter. Harap untuk menghubungi CS di 021xxxx";
	?>	
	?>	
		<script>
		window.opener.location.replace('/amplified/rfid/permision.php');
		self.close();

		</script>
<?php	
	exit;
}	



?>