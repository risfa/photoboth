<?php
require_once 'src/Facebook/autoload.php'; // load file autoload dari facebook

$appId     = "1745823622387024"; // replace dengan app id yang kamu dapatkan di facebook developer
$appSecret = "d2ea7600dd2ca0e84cc0aa8f48fce7f1"; // replace dengan secret key yang kamu dapatkan di facebook developer

class db {

  function __construct() {
    $dbhost = "localhost"; // replace dengan database host kamu
    $dbuser = "dapps"; // replace dengan databae user kamu
    $dbpass = "admin5D"; // replace dengan database pass kamu
    $dbname = "dapps_amplified"; // replace dengan database name kamu
    $this->mysqli = new mysqli($dbhost,$dbuser,$dbpass,$dbname );
    if(mysqli_connect_error()) {
      die("Tidak Bisa Konek Ke Database Karena : ". mysqli_connect_errno());
    }
  }

  function redirect($url) {
    echo "<script type='text/javascript'>window.top.location='$url';</script>";
  }
} 