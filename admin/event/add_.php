<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
$numakses = 1;

$tempp="";
$peringatan="";
$validasi="True";

(isset($_POST["txtjudul"])) ? $txtjudul = $_POST["txtjudul"] : $txtjudul = "";
(isset($_POST["selteks"])) ? $selteks = $_POST["selteks"] : $selteks = "";
(isset($_POST["fimage1"])) ? $themes = $_POST["fimage1"] : $themes = "";
(isset($_POST["txtlokasi"])) ? $lokasi = $_POST["txtlokasi"] : $lokasi = "";
(isset($_POST["selseq"])) ? $selseq = $_POST["selseq"] : $selseq = "";

for($b=1;$b<=$selteks;$b++){
	$teks[$b]=$_POST["txtteks".$b.""];
}

for($c=1;$c<=$selseq;$c++){
	$txtseq[$c] = $_POST["txtseq".$c.""];
}

if (isset($_POST["chkaktif"]) && strcasecmp($_POST["chkaktif"],"on")==0) {
	$chkaktif = 1;
} else {
	$chkaktif = 0;
}

if (isset($_POST["sequence"]) && strcasecmp($_POST["sequence"],"on")==0) {
	$sequence = 1;
} else {
	$sequence = 0;
}


//judul
if($_POST["txtjudul"]==""){
	$peringatan = $peringatan . "<li> Nama Event : Tidak boleh kosong</li>";
	$validasi = "false";	
}

//jumlah content
if($_POST["selteks"]==0){
	$peringatan = $peringatan . "<li> Jumlah Teks : pilih salah satu</li>";
	$validasi = "false";	
}

//image
if($_FILES["fimage"] ==""){
	$peringatan = $peringatan . "<li> Themes : Tidak boleh kosong</li>";
	$validasi = "false";	
}


if(!isset($_FILES["fimage"])){   
  if(!empty($_FILES['fimage']['name'])) {
    $errors = array(1 => 'php.ini max file size exceeded', 
                    2 => 'html form max file size exceeded', 
                    3 => 'file upload was only partial', 
                    4 => 'masukkan file foto yang ingin di upload');
    if ($_FILES["fimage"]["error"]) {
      $peringatan = $peringatan . "<li> Themes : ".$errors[$_FILES["fimage"]["error"]].".</li>";
    } elseif ($_FILES["fimage"]['size'] > (2*1048576)) {
   $peringatan = $peringatan. "<li> Themes : tidak boleh lebih dari 2MB</li>";
    } else {
      //cek tipe file
      //1:IMG_GIF --  2:IMG_JPG -- 4:IMG_PNG -- 8:IMG_WBMP -- 0:IMG_XPM
      $img_size = getimagesize($_FILES["fimage"]["tmp_name"]);

      if ($img_size[2]!=1 && $img_size[2]!=2 && $img_size[2]!=4 && $img_size[2]!=8) {
        $peringatan = $peringatan . "<li> Themes : format gambar salah</li>";
      }
    }
  }
}  


if($_POST["txtlokasi"]==""){
	$peringatan = $peringatan . "<li> Lokasi : Tidak boleh kosong</li>";
	$validasi = "false";	
}


//Teks
for ($a = 1; $a <= $selteks ; $a++){
	if($teks[$a]==""){
		$peringatan = $peringatan . "<li> Link Teks $a : Tidak boleh kosong</li>";
		$validasi = "false";	
	}
}

//Teks sequece
for ($x = 1; $x <= $selseq ; $x++){
	if($txtseq[$x]==""){
		$peringatan = $peringatan . "<li> Teks Sequence $x : Tidak boleh kosong</li>";
		$validasi = "false";	
	}
}

if($validasi == "false"){
	$_SESSION["peringatan"]= $peringatan ;
	$_SESSION["txtjudul"] = $_POST["txtjudul"];
	$_SESSION["selteks"] = $_POST["selteks"];
	$_SESSION["lokasi"] = $_POST["txtlokasi"];
	$_SESSION["selseq"] = $_POST["selseq"];
	
	for ($a = 1; $a <= $selteks ; $a++){
		$_SESSION["txtteks".$a] = $_POST["txtteks".$a];
	}
	
	for ($q = 1; $q <= $selseq ; $q++){
		$_SESSION["txtseq".$q] = $_POST["txtseq".$q];
	}
	
	if(isset($_POST["chkaktif"])){
		$_SESSION["chkaktif"] = 1;
	}else{
		$_SESSION["chkaktif"] = 0;
	}
	
	if(isset($_POST["sequence"])){
		$_SESSION["sequence"] = 1;
	}else{
		$_SESSION["sequence"] = 0;
	}
	
	header("location:add.php");
	exit;
}else{	
	$id = getnewid("ms_event","id_event");
	
	$desk = "";
	for ($a = 1; $a <= $selteks ; $a++){
		$desk = $desk.$teks[$a].";";
	}
	$txtsequence = "";
	for ($w = 1; $w <= $selseq ; $w++){
		$txtsequence = $txtsequence.$txtseq[$w].";";
	}

	 $themesupload = "/".UploadImage($id,"",0);
	isset($selseq) ? $selseq = $sequence : $selseq = 0;	
	
	$strsql = "insert into ms_event (id_event, nama_event, desk, lokasi, background,aktif ,sequence, txtsequence, creby, cretime) values (".
		"".fcekpetik($id).", ".
		"'".fcekpetik($db->escape($txtjudul))."', ".
		"'".fcekpetik($db->escape($desk))."', ".
		"'".fcekpetik($db->escape($lokasi))."', ".
		"'".$db->escape($themesupload)."', ".
		"".fcekpetik($db->escape($chkaktif)).", ".	
		"".fcekpetik($db->escape($selseq)).", ".	
		"'".fcekpetik($db->escape($txtsequence))."', ".	
		"'".fcekpetik($_SESSION["ssusername"])."', ".
		"NOW()".
		")";

	$result = $db->query($strsql);

	header("Location: index.php");
	exit;
}
?>