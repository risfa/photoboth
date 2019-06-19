<?php
function check_user_agent ( $type = NULL ) {
	$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
	if ( $type == 'bot' ) {
			// matches popular bots
			if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
					return true;
					// watchmouse|pingdom\.com are "uptime services"
			}
	} else if ( $type == 'browser' ) {
			// matches core browser types
			if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
					return true;
			}
	} else if ( $type == 'mobile' ) {
			// matches popular mobile devices that have small screens and/or touch inputs
			// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
			// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
			if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
					// these are the most common
					return true;
			} else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
					// these are less common, and might not be worth checking
					return true;
			}
	}
	return false;
}


function issel($a,$b,$c,$d) {
	if($a==$b) 
		return $c;
	else
		return $d;	

}
function sendmail($emailtujuan,$emailsubject,$content){
	global $pathdir;
	require_once("includes/class.phpmailer.php");
	try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 587;                    // set the SMTP server port
	$mail->Host       = "smtp.googlemail.com"; // SMTP server
	$mail->Username   = "explore@limadigit.com";     // SMTP server username
	$mail->Password   = "5d3xpl0r3";            // SMTP server password

	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->SMTPDebug = 1;
	$mail->SMTPSecure = 'tls';
	$mail->AddReplyTo("explore@limadigit.com","Explore");

		$mail->From       = "explore@limadigit.com";
		$mail->FromName   = "Explore";

	$to = $emailtujuan;

	$mail->AddAddress($to);

	$mail->Subject  = $emailsubject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	return 1;
	}catch (phpmailerException $e){
		echo $mail->ErrorInfo;
		echo $e->errorMessage();
		exit;
	}
}
?>