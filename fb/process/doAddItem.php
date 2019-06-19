<?php

  include("config.php");

$token = $_GET['token'];
$query = "  update user set alamat = '$_POST[alamat]', no_handphone = '$_POST[no_handphone]' where token = '$token'  ";
$result = mysql_query($query) or die(mysql_error());

if ($result) {
	header("location:https://5dapps.com/pertaction/fb4/");}
else {
	echo "proses simpan gagal !.";
}
?>




