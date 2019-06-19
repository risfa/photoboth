<?php

 // this will avoid mysql_connect() deprecation error.
 error_reporting( ~E_DEPRECATED & ~E_NOTICE );
 // but I strongly suggest you to use PDO or MySQLi.
 
  $hash = md5(md5(rand()."5Digit")).md5(md5(rand()."5Digit")).md5(md5(rand()."5Digit"));
 

 define('DBHOST', 'localhost');
 define('DBUSER', 'dapps_adminamp');
 define('DBPASS', 'admin5D');
 define('DBNAME', 'dapps_amplified');
 
 $conn = mysql_connect(DBHOST,DBUSER,DBPASS);
 $dbcon = mysql_select_db(DBNAME);
 
 if ( !$conn ) {
  die("Connection failed : " . mysql_error());
 }
 
 if ( !$dbcon ) {
  die("Database Connection failed : " . mysql_error());
 }


?>