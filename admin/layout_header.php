<?php 

	if(!isset($_SESSION["ssname"])){header("Location: /rfid/admin/index.php");exit;}

	//get menu yang punya akses
	$getmenuwakses = $db->get_results("SELECT DISTINCT numakses FROM ms_admin_menu WHERE (ilevel = 1) AND (ttampil = 1) AND (numakses!=99) AND (numakses!=100) ORDER BY numakses");
	$varmenuwakses = " numakses=99 OR numakses=100 ";
	if($getmenuwakses){
		foreach($getmenuwakses as $rsmenu){
			if(isset($numakses) && is_numeric($numakses)){
				if($numakses == $rsmenu->numakses){
					if(CekDiv($_SESSION["ssid"],$rsmenu->numakses)==false){
						//redirect ke halaman yang dia ada akses
						$strsql = "SELECT vurl, numakses FROM ms_admin_menu WHERE ttampil=1 AND idparent=0 ORDER BY tposisi";
						$result = $db->get_results($strsql);
						if($result){
							foreach($result as $row){
								if(CekDiv($_SESSION["ssid"],$row->numakses)){
									header("Location: ".$row->vurl."");
									exit;
								}
							}
						}
					}//end if(CekDiv($_SESSION["ssid"],$row->numakses)==false){
				}//end if($numakses == $rsmenu->numakses){
			}//end if(isset($numakses) && is_numeric($numakses)){
			
			if(CekDiv($_SESSION["ssid"],$rsmenu->numakses)){
				$varmenuwakses .= " OR numakses=".$rsmenu->numakses." ";
			}
			
		}//end foreach($getmenuwakses as $rsmenu){
	}//end if($getmenuwakses){

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $JudulHead;?></title>
<?php

if(isset($strCss)) {
	$arrCss = explode(";",$strCss);
	if (is_array($arrCss)) {
		foreach ($arrCss as $value) {
			print("<link rel=\"stylesheet\" href=\"../css/".$value.".css\" type=\"text/css\" media=\"screen\" />");
		}
		unset($value);
	}
}
if(isset($strJs)) {
	$arrJs = explode(";",$strJs);
	if (is_array($arrJs)) {
		foreach ($arrJs as $value) {
			print("<script type=\"text/javascript\" src=\"../js/".$value.".js\"></script>");
		}
		unset($value);
	}
}

?>
<!--[if gte IE 6]>
<link rel='stylesheet' href='/Admin/css/ie.css' type='text/css' media='all' />
<![endif]-->
<script type='text/javascript' src='../js/jquery/jquery.js?ver=1.2.6'></script>
<script type='text/javascript'>
// ini buat ingetin menu pilihan. settingan cookies
/* <![CDATA[ */
	userSettings = {
		url: "/",
		uid: "1",
		time: "1231833068"
	}
/* ]]> */

function getfimage(a){
	document.getElementById('txt'+a).value='valid';
}

</script>

<script type='text/javascript' src='../js/common.js'></script>
<link rel="stylesheet" href="../css/ie6nomore.css" type="text/css" media="all" />
</head>

<body id="live" class="wp-admin">

<?php
require_once($pathdir."includes/ie6nomore.php"); 
?>

<div id="wpwrap">
	<div id="wpcontent">
	<?php require_once($pathdir."includes/head.php"); ?>
		<div id="wpbody">
		<?php require_once($pathdir."includes/menu.php"); ?>	
			<div id="wpbody-content">	
			<?php require_once($pathdir."includes/screen-meta.php"); ?>
