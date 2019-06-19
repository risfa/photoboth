<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Bangkok");
ini_set('max_execution_time', 300);
require_once("includes/functions.php");
require_once 'fb/facebook.php';
require_once ('twitter/src/codebird.php');
\Codebird\Codebird::setConsumerKey('Vu36hAFuwKNQrBIfmuZrQ','JjrBajTX1ml77JxbFuuUa2dloPvileO4rasPsX4rlYA');
$cb = \Codebird\Codebird::getInstance();

$message = file_get_contents("messagephotothanks.txt");
$desc = file_get_contents("descphotothanks.txt");

$pathdir = "/";
$ismobile = check_user_agent('mobile');
if($ismobile) {
	$strcss = "global,reset,bootstrap.min,rfidregis";
	$bodyid = "bodyregis";
} else {
	$strcss = "global,reset,bootstrap.min,rfidregis2";
	$bodyid = "bodythanks";
}
$strjs = "jquery,html5";


$judul_head = "RFID";

(isset($_SESSION["rfid"])) ? $rfid = $_SESSION["rfid"] : $rfid = "";
	if($rfid == ""){
		$_SESSION["peringatan"] = "<ul id=\"peringatan\">Harap Login terlebih dahulu</ul>";
		header("location:loginphoto.php");
		exit;
	}
require_once("includes/top.php");



$dir = date("d M Y");

$rowcek = $db->get_row("select * from ms_member where id_rfid = ".$rfid);
if($rowcek){
	$rowevent = $db->get_row("select nama_event from ms_event where id_event = ".$rowcek->id_event);
	if($rowevent){
		$date2 = date("Y-m-d");
		$getidfoto = $db->get_row("select photobooth from ms_event_arsip where cretime between '".$date2." 00:00:00' and '".$date2." 23:59:59' and id_member = ".$rowcek->id_member." ");
		if($getidfoto){
			$idfoto = $getidfoto->photobooth;
		}else{
			$idfoto = 0;
		}
		if($rowcek->fb_id != 0){
			$graph_url= "https://graph.facebook.com/".$rowcek->fb_id."/photos?";
			$photo = "images/".$rowevent->nama_event."/".$dir."/".$rfid."/".$rfid."1foto".$idfoto.".png";
			$postData = array("method" => "POST",
																	"access_token" => $rowcek->fbtoken,
																	"message"=> $message,
																	"description"=> $desc
												);
			$postData[basename($photo)] = '@'.realpath($photo);
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $graph_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$output = curl_exec($ch);

			curl_close($ch);
		}
		if($rowcek->twitter_id != 0){
			$cb->setToken($rowcek->twitteroauth_key, $rowcek->twitteroauth_secret);		
			$params = array(
					'status' => $message,
					'media[]' => 'images/'.$rowevent->nama_event.'/'.$dir.'/'.$rfid.'/'.$rfid.'1foto'.$idfoto.'.png'
			);
			// echo '../images/'.$rowevent->nama_event.'/'.$dir.'/'.$rfid.'/'.$rfid.'1foto'.$idfoto.'.png';die;
			$reply = $cb->statuses_updateWithMedia($params);
			// echo "<pre>";print_r($reply);die;
		}
	}
}	

$tglskrg= date("Y-m-d");
$rowmember=$db->get_row("select id_member from ms_member where id_rfid=".$rfid);
if ($rowmember){

	/*Tambahan dari Heros*/
	$string_aktif = "select * from ms_aktif where id_member = '".$rowmember->id_member."' ";
	$query_aktif = mysql_query($string_aktif);
	
	if($rs = mysql_fetch_array($query_aktif))
	{
		$string_aktif_update = "update ms_aktif set total_aktif = total_aktif + 1 where id_member = '".$rowmember->id_member."'";
		$query_aktif_update = mysql_query($string_aktif_update);
	}
	else
	{
		$string_aktif_insert = "insert into ms_aktif(id_member, total_aktif) values('".$rowmember->id_member."', 1)";
		$query_aktif_insert = mysql_query($string_aktif_insert);
	}
	
	/*End Tambahan dari Heros*/
	
	$rowtgl=$db->get_row("select * from ms_event_arsip where id_member=".$rowmember->id_member." and cretime between '".$tglskrg." 00:00:00' and '".$tglskrg." 23:59:59' ");
	$strsqlevent = "select * from ms_event where aktif = 1";
	$rowevent = $db->get_row($strsqlevent);
	if($rowevent){
		if ($rowtgl){
			$strupdate="update ms_event_arsip set photobooth=photobooth+1 where id_event_arsip=".$rowtgl->id_event_arsip;
			$result=$db->query($strupdate);
			echo "<meta http-equiv=\"refresh\" content=\"5; url=loginphoto.php\" />";
		}else{
			$newid = $db->get_row("select max(id_event_arsip) as id from ms_event_arsip");
			(is_null($newid->id)) ? $id = 1 : $id = $newid->id + 1;
			$strinsert="insert into ms_event_arsip (id_event_arsip,id_member,id_event,location,photobooth,cretime) values(".$id.",".$rowmember->id_member.",".$rowevent->id_event.",0,1,now())";

			$result=$db->query($strinsert);
			echo "<meta http-equiv=\"refresh\" content=\"5; url=loginphoto.php\" />";
		}
	}
}
?>
<script type="text/javascript">
try{document.getElementById('rfid').focus();}catch(e){}
</script>
<?php
unset($_SESSION["rfid"]);
unset($_SESSION["savefoto"]);
header("location:loginphoto.php");
?>