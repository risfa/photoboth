<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

If (!isset($_SESSION["ssid"]) || CekDiv($_SESSION["ssid"],24)==false || strlen($_POST["txtid"])==0 || !is_numeric($_POST["txtid"])) {
	header("Location: ".$pathdir ."index.php");
	exit();
}else{
	$id = $_POST["txtid"];
	if(isset($_POST["addnew"])){
		$txtaddnew = $_POST["addnew"];
	}else{
		$txtaddnew = "";
	}
}

function ceknama($varnama){
	global $db;
	$ceknama = $db->get_row("SELECT name FROM ms_admin WHERE name ='".$db->escape($varnama)."'");
	if($ceknama){
		return true;
	}else{
		return false;
	}
}

function cekusn($varusn){
	global $db;
	$cekusn = $db->get_row("SELECT username FROM ms_admin WHERE username ='".$db->escape($varusn)."'");
	if($cekusn){
		return true;
	}else{
		return false;
	}
}

$peringatan = "";

//nama admin
if ( strlen($_POST["txtnama"])==0 ) {
	$peringatan = $peringatan . "<li> Nama admin : tidak boleh kosong</li>";
} elseif (!CekValidAsc2($_POST["txtnama"])) {
	$peringatan = $peringatan . "<li> Nama admin : Harap menggunakan huruf a~z dan angka 0~9</li>";
}
if($txtaddnew!=""){
	//nama admin
	if ( ceknama($_POST["txtnama"]) ) {
		$peringatan = $peringatan . "<li> Add admin : Nama admin : sudah dipakai</li>";
	}
}

//username
if ( strlen($_POST["txtusername"])==0 ) {
	$peringatan = $peringatan . "<li> Username : tidak boleh kosong</li>";
} elseif (!CekValidAsc($_POST["txtusername"])) {
	$peringatan = $peringatan . "<li> Username : Harap menggunakan huruf a~z dan angka 0~9</li>";
}
if($txtaddnew!=""){
	//nama admin
	if ( cekusn($_POST["txtusername"]) ) {
		$peringatan = $peringatan . "<li> Add admin : Username admin : sudah dipakai</li>";
	}
}

//password
if($txtaddnew!=""){
	//validasi add news
	if ( strlen($_POST["txtpwd"])==0 ) {
		$peringatan = $peringatan . "<li> Password : tidak boleh kosong</li>";
	} elseif (!CekValidAsc($_POST["txtpwd"])) {
		$peringatan = $peringatan . "<li> Password : Harap menggunakan huruf a~z dan angka 0~9</li>";
	} elseif (strcmp($_POST["txtpwd"], $_POST["txtpwd2"])!=0) {
		$peringatan = $peringatan . "<li> Password : Harap diulangi dengan password yang sama</li>";
	}

}else{
	//validasi edit normal
	if (strlen($_POST["txtpwd"])!=0 ) {
		if (!CekValidAsc($_POST["txtpwd"])) {
			$peringatan = $peringatan . "<li> Password : Harap menggunakan huruf a~z dan angka 0~9</li>";
		}elseif (strcmp($_POST["txtpwd"], $_POST["txtpwd2"])!=0) {
			$peringatan = $peringatan . "<li> Password : Harap diulangi dengan password yang sama</li>";
		}
	}
}

//level
if ( strlen($_POST["sellevel"])==0 ) {
	$peringatan = $peringatan . "<li> Level : tidak boleh kosong</li>";
} elseif ($_POST["sellevel"] < 0 || $_POST["sellevel"] > 1) {
	$peringatan = $peringatan . "<li> Level : tidak valid</li>";
}

//divisi
if ( strlen($_POST["seldivisi"])==0 ) {
	$peringatan = $peringatan . "<li> Divisi : tidak boleh kosong</li>";
} elseif (!is_numeric($_POST["seldivisi"])) {
	$peringatan = $peringatan . "<li> Divisi : tidak valid</li>";
}

//akses	
if(isset($_POST["chkakses"])){
	$arrakses = $_POST["chkakses"];
	$selakses = ";";
	foreach($arrakses as $akses){
		$checkpos = strpos($selakses,";".$akses.";");
		if(!is_numeric($checkpos)){
			$selakses .= $akses.";"; 
		}
	}
}else{
	$selakses = ";99;100;"; 
}


if (strlen($peringatan) > 0) {
	$_SESSION["peringatan"] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$peringatan."</ul>";
	$_SESSION["name"] = $_POST["txtnama"];
	$_SESSION["username"] = $_POST["txtusername"];
	$_SESSION["level"] = $_POST["sellevel"];
	if(isset($_POST["seldivisi"])){
		$_SESSION["seldiv"] = $_POST["seldivisi"];
	}
	if(isset($_POST["chkaktif"])){
		$_SESSION["chkaktif"] = $_POST["chkaktif"];
	}
	if(isset($_POST["chkakses"])){
		$_SESSION["chkakses"] = $selakses;
	}
	header("Location: edit.php?id=".$id."");
	exit();

} else {
		
	$passchange = "";
	if(isset($_POST["txtpwd"]) && $_POST["txtpwd2"]!=""){
		$passchange = ",password = '".md5($db->escape($_POST["txtpwd"]))."'";
	}
	
	if (isset($_POST["chkaktif"]) && strcasecmp($_POST["chkaktif"],"on")==0) {
		$chkaktif = 1;
	} else {
		$chkaktif = 0;
	}
	
	if($txtaddnew!=""){
		$id = getnewid("ms_admin","id");
		$strsql = "insert into ms_admin (id, name, username, password, lvl, tdiv, taktif, akses, cretime, creby) values (".
		"".$id.", ".
		"'".$db->escape($_POST["txtnama"])."', ".
		"'".$db->escape($_POST["txtusername"])."', ".
		"'".md5($db->escape($_POST["txtpwd"]))."', ".
		"".$db->escape($_POST["sellevel"]).", ".
		"".$db->escape($_POST["seldivisi"]).", ".
		"".$db->escape($chkaktif).", ".
		"'".$db->escape($selakses)."', ".
		"NOW(), '".$db->escape($_SESSION["ssusername"])."' ".
		")";
	}else{
		$strsql = "
			UPDATE ms_admin
			SET 
			name = '".$db->escape($_POST["txtnama"])."'
			,username = '".$db->escape($_POST["txtusername"])."'
			".$passchange."
			,lvl = ".$db->escape($_POST["sellevel"])."
			,akses = '".$db->escape($selakses)."'
			,tdiv = '".$db->escape($_POST["seldivisi"])."'
			,modtime = NOW()
			,modby = '".$_SESSION["ssusername"]."'
			WHERE id=".$db->escape($id)."
		";
	}

	$result = $db->query($strsql);
	header("Location: index.php");
	exit;
}
?>