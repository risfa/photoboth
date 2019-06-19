<?php
include_once "core.php";
include_once "mysql.php";
require 'SafeSQL.class.php';
$db = new ezSQL_mysql('root','','smskudb','localhost');

$safesql = new SafeSQL_MySQL;
?>