<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
$numakses = 3;

$tempp="";
$peringatan="";
$validasi="True";

(isset($_POST["id"])) ? $txtid = $_POST["id"] : $txtid = 0;

if($txtid == 0){
	header("Location: index.php");
	exit;
}

if (isset($_POST["chkaktif"]) && strcasecmp($_POST["chkaktif"],"on")==0) {
	$chkaktif = 1;
} else {
	$chkaktif = 0;
}

//image
if($_FILES["fimage"] ==""){
	$peringatan = $peringatan . "<li> Frame : Tidak boleh kosong</li>";
	$validasi = "false";	
}

if($validasi == "false"){
	$_SESSION["peringatan"]= $peringatan ;
	if(isset($_POST["chkaktif"])){
		$_SESSION["chkaktif"] = 1;
	}else{
		$_SESSION["chkaktif"] = 0;
	}
	
	header("location:edit.php?id=".$txtid);
	exit;
}else{	
	if ($_FILES['fimage']['name'] =="" ){
		$isiframes="";
	}	else{
	
		$themesupload = "/".UploadImage3($txtid,"",0);
		//$themesupload = "/".UploadImage2($txtid,"",0);
		$isiframes="image= '".$db->escape($themesupload)."',";
	}
	
		$strsql = "UPDATE ms_frame SET 
							".$isiframes."	
							aktif='".$db->escape($chkaktif)."',
						WHERE id_frame=".$db->escape($txtid)."";
	$result = $db->query($strsql);
	header("Location: index.php");
	exit;
}
?>