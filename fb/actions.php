<?php
require_once "../admin/assets/db/db.php";
//include 'library.php';
(isset($_GET["from"])) ? $from = $_GET["from"] : $from = "";
$action = $_REQUEST["action"];
switch($action){
	case "fblogin":
	include 'facebook.php';
	$appid 		= "730214766997657";
	$appsecret  = "6982585a9c6660b02d48372e37d4eba3";
	$facebook   = new Facebook(array(
  		'appId' => $appid,
  		'secret' => $appsecret,
  		'cookie' => TRUE,
	));
	$fbuser = $facebook->getUser();
	
if (isset($_SESSION["fbregis"]) && $_SESSION["fbregis"]=="yes" &&  isset($_SESSION["user_fbid"]) &&	$_SESSION["user_fbid"]  !="" ) {
		$_SESSION["peringatan"]="Kamu telah melakukan registrasi facebook";
		header("location:../permision.php");
		exit;
}
	
	if ($fbuser) {
		try {
		    $user_profile = $facebook->api('/me');
				$facebook->setExtendedAccessToken();
				$tokenaccess = $facebook->getAccessToken();
				/*$token_url = "https://graph.facebook.com/oauth/access_token?client_id=730214766997657&client_secret=6982585a9c6660b02d48372e37d4eba3&grant_type=fb_exchange_token&fb_exchange_token=$tokenaccess ";
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);*/
				
				//cek permission
				$permissions = $facebook->api("/me/permissions");
				$logouturl = $facebook->getLogoutUrl();
 
				
		}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
		
		$user_fbid	= $fbuser;
		$user_email = $user_profile["email"];
		//$user_fnmae = $user_profile["first_name"];
		$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
		
		
		if ($user_fbid !=""){	
				$strsqlcek="select  * from ms_member where fb_id= '".$user_fbid."'  ";
				$rowcek=$db->get_row($strsqlcek);
				
				if ($rowcek){				
					if(	$_SESSION["ceklogout"]!="yes"){
						$_SESSION["peringatan"]="ID facebook telah melakukan registrasi";
						$_SESSION["ceklogout"]="yes";
					
					 header('Location: '.$logouturl);
					 exit;
			
					}else{
						if($from == ""){
							header("location:../permision.php");
							exit;
						}else{
							header("location:../renew.php");
							exit;
						}	
					}
				}else{
						
						if( array_key_exists('publish_stream', $permissions['data'][0]) !=1) {
									header( "Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream")) );
									exit;
						} 
						
				
					$_SESSION["user_fbid"] = $user_fbid;
					$_SESSION["user_email"] = $user_email;
					$_SESSION["user_image"] = $user_image;
					$_SESSION["tokenaccess"] = $tokenaccess;
					$_SESSION["peringatan"]="Selamat kamu telah berhasil melakukan registrasi facebook";
					$_SESSION["fbregis"]="yes"; 
					if($from == ""){
						header("location:../permision.php");
						exit;
					}else{
						header("location:../renew.php");
						exit;
					}
				}	
		}else{
			if($from == ""){
				$_SESSION["peringatan"]="Kamu gagal melakukan registrasi facebook. Harap untuk menghubungi CS di 021xxxx";
				header("location:../permision.php");
				exit;
			}else{
				$_SESSION["peringatan"]="Kamu gagal melakukan registrasi facebook. Harap untuk menghubungi CS di 021xxxx";
				header("location:../renew.php");
				exit;
			}
		}

		
	/*	$strsqlcek="select  * from ms_member where fb_id= '".$user_fbid."'  ";
		$rowcek=$db->get_row($strsqlcek);
		
		if ($rowcek){
			$_SESSION["peringatan"]="ID facebook telah melakukan registrasi";
			header("location:../permision.php");
			exit;			
		}else{
		
				
		
				$newid = $db->get_row("select max(id_member) as id from ms_member");
				(is_null($newid->id)) ? $id = 1 : $id = $newid->id + 1;
				
				$rowevent = $db->get_row("select * from ms_event where aktif = 1");
				if($rowevent){
					$strsql = "insert into ms_member (id_member, id_event, id_rfid, fb_id, nama, alamat, telp, hp, email, 	fbemail, fbpicture, fbtoken, cretime) values (".
						" '".$db->escape($id)."' , ".
						" ".$db->escape($rowevent->id_event)." ,".
						"'',".
						" ".$db->escape($user_fbid)." ,".
						" '".$db->escape($_SESSION['vnama'])."' , ".
						" '".$db->escape($_SESSION['valamat'])."' , ".
						" ".$db->escape($_SESSION['vtelp'])." , ".
						" ".$db->escape($_SESSION['vhp'])." , ".
						" '".$db->escape($_SESSION['vemail'])."' , ".
						" '".$db->escape($user_email)."' , ".
						" '".$db->escape($user_image)."' , ".
						" '".$db->escape($tokenaccess)."' , ".
						" NOW() ".
						")";
						
						$result = $db->query($strsql);
					
						
						if ($result){
								$_SESSION["user_fbid"] = $user_fbid;
								$_SESSION["user_email"] = $user_email;
								$_SESSION["user_image"] = $user_image;
								$_SESSION["tokenaccess"] = $tokenaccess;
									$_SESSION["peringatan"]="Selamat kamu telah berhasil melakukan registrasi facebook";
								$_SESSION["fbregis"]="yes";
								header("location:../permision.php");
								exit;
						}
				}else{
					$_SESSION["peringatan"]="Kamu gagal melakukan registrasi facebook. Harap untuk menghubungi CS di 021xxxx";
					header("location:../permision.php");
					exit;
				}
				
		}//end cek data ms_member	*/
		
	}
	//break;
	exit;
}
?>