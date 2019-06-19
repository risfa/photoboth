<?php
session_start();
error_reporting (E_ALL); 
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
$numakses = 3;

$tempp="";
$peringatan="";
$validasi="True";

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

if(!isset($_FILES["fimage"])){   
  if(!empty($_FILES['fimage']['name'])) {
    $errors = array(1 => 'php.ini max file size exceeded', 
                    2 => 'html form max file size exceeded', 
                    3 => 'file upload was only partial', 
                    4 => 'masukkan file foto yang ingin di upload');
    if ($_FILES["fimage"]["error"]) {
      $peringatan = $peringatan . "<li> Frame : ".$errors[$_FILES["fimage"]["error"]].".</li>";
    } elseif ($_FILES["fimage"]['size'] > (2*1048576)) {
   $peringatan = $peringatan. "<li> Frame : tidak boleh lebih dari 2MB</li>";
    } else {
      //cek tipe file
      //1:IMG_GIF --  2:IMG_JPG -- 4:IMG_PNG -- 8:IMG_WBMP -- 0:IMG_XPM
      $img_size = getimagesize($_FILES["fimage"]["tmp_name"]);

      if ($img_size[2]!=1 && $img_size[2]!=2 && $img_size[2]!=4 && $img_size[2]!=8) {
        $peringatan = $peringatan . "<li> Frame : format gambar salah</li>";
      }
    }
  }
}  

if($validasi == "false"){
	$_SESSION["peringatan"]= $peringatan ;
	if(isset($_POST["chkaktif"])){
		$_SESSION["chkaktif"] = 1;
	}else{
		$_SESSION["chkaktif"] = 0;
	}
	
	header("location:add.php");
	exit;	
}else{	
	$id = getnewid("ms_frame","id_frame");

	
	$frameupload = "/".UploadImage3($id,"",0);
	//$frameupload = "/".UploadImage2($id,"",0);

	
	$strsql = "insert into ms_frame (id_frame, image, aktif , creby, cretime) values (".
		"".fcekpetik($id).", ".
		"'".$db->escape($frameupload)."', ".
		"".fcekpetik($db->escape($chkaktif)).", ".	
		"'".fcekpetik($_SESSION["ssusername"])."', ".
		"NOW()".
		")";
	$result = $db->query($strsql);

	header("Location: index.php");
	exit;
}
?>