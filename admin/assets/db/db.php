<?php
ini_set('display_errors', '0');    
error_reporting(E_ALL | E_STRICT);  
include_once "core.php";
include_once "mysql.php";
require 'SafeSQL.class.php';
$db = new ezSQL_mysql('dapps','admin5D','dapps_dapps_Hut60PertaminaDago','localhost');

$safesql = new SafeSQL_MySQL;
?>
