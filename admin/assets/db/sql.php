<?php
$pathdir = "../";
require_once($pathdir."includes/function.php");
require_once($pathdir."db/db.php");

$id = $_REQUEST['id'];
//if(empty($id) || !is_numeric($id)) { header("Location: asdfs.php");}

$strsql = "SELECT 
	UNIX_TIMESTAMP(cretime) AS cretime, 
	kdnews, 
	kdgames, 
	vjudnews,
	kdconsole,
	ffoto, 
	ffotob, 
	tinter,
	creby, 
	vnews, 
	vkeyword 
FROM 
	tt_news 
WHERE 
	ttampil = 1 
	AND 
	kdnews = ".fcekpetik($db->escape($id))." 
ORDER BY 
	cretime DESC 
LIMIT 0,1";
//echo $strsql;
//exit;			
$result = $db->get_row($strsql);
								
if ($result) {
	echo $result->vjudnews;
}	
?>	




