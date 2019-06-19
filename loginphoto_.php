<?php
ob_start();
session_start();ini_set('display_errors', 1);error_reporting(error_reporting() & ~E_STRICT);
error_reporting(error_reporting() & ~E_STRICT);
error_reporting(E_ALL);
$pathdir = "/";
require_once "admin/assets/db/db.php";

if(isset($_POST["rfid"]) && $_POST["rfid"] != ""){
	$rfid = $_POST["rfid"];
}else{
	echo "Tempelkan RFID Anda";
	exit;
}
$peringatan="";

if(!is_numeric($rfid)){
  $peringatan = $peringatan . "<li> RFID : harus menggunakan angka</li>";
}

if (strlen($peringatan) >0){
	$_SESSION["peringatan"] = "<ul id=\"peringatan\">".$peringatan."</ul>";
	header("Location: loginphoto.php");
	exit();
}else{
	if($rfid == '123'){
		$_SESSION["rfid"] = $rfid;
		header("location:index.php");
		exit;
	}
	$strsql = "select * from ms_peserta where rfid_number = ".$rfid;
	$rs = $db->get_row($strsql);
	if($rs){
		$_SESSION["rfid"] = $rfid;
		header("location:index.php");
		exit;	
	}else{
		$_SESSION["peringatan"] = "<ul id=\"peringatan\">RFID anda belum terdaftar</ul>";
		header("Location: loginphoto.php");
		exit();
	}
}

?>
