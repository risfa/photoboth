<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="Digital Agency, creativity, experience. specialize in developing digital matters and social media management."/>
		<meta name="description" content="Digital Agency, creativity, experience. specialize in developing digital matters and social media management."/>
		<link rel="shortcut icon" href="http://static.wixstatic.com/ficons/bcc5ac_3f6862dec7e742beaa998ce75ab2ace3_fi.ico" type="image/png"/>
		<title><?=$judul_head;?></title>
		<?php
		//error_reporting (E_ALL);
		require_once($pathdir."admin/assets/db/db.php");		
		//css
		$pisahcss = strtok($strcss,",");
		while($pisahcss) {
			echo("<link rel=\"stylesheet\" href=\"".$pathdir."admin/assets/css/".$pisahcss.".css\" type=\"text/css\" media=\"screen\" />\n");
			$pisahcss=strtok(",");
		}
		//end css

		//js
		$pisahjs = strtok($strjs,",");
		while($pisahjs) {
			echo("<script type=\"text/javascript\" src=\"".$pathdir."admin/assets/js/".$pisahjs.".js\"></script>\n");
			$pisahjs=strtok(",");
		}
		
		
		$strsql="select * from ms_event where aktif=1 order by id_event desc";
		$rowsql=$db->get_row($strsql);
		if($rowsql){
			$imgbg=$rowsql->background;
		}
		if($bodyid == "bodyhome" || $bodyid == "bodythanks"){
			$size = "background-attachment:fixed;background-position:center;";
		}else{
			$size = "background-size:100% 100%";
		}
		?>
		<style>
			body{
				background:url("<?php echo "/amplified/rfid/admin".$imgbg; ?>") no-repeat;
				background-color:#fff;
				background-size:cover;
				<?php echo $size ?>;
			}
			
			.bgLogin
			{
				background:url("admin/assets/images/login-photobooth.png");
				height:600px;
				width:500px;
				background-repeat: no-repeat;
				background-attachment: relative;
				display:block;
				background-position: center;
			}
			
			.inputLogin
			{
				position:absolute;
				bottom:28px;
				left:200px;
				width:740px;
				font-size:40px;
			}
			
			@media (min-width:768px)
			{
				.btn-lg
				{
					width:400px;
				}
			}
			
			@media (max-width:768px)
			{
				.btn-lg
				{
					width:100%;
				}
			}
		</style>
	</head>
	<body>
	