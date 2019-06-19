<?php
$JudulHead = "Admin Ubah Password";
$strCss = "initial";
$bodyid = "ubahpass";
$hal = "ubahpass";
$pathdir = "";
$numakses = 99;
session_start();
error_reporting(error_reporting() & ~E_STRICT);

require_once("".$pathdir."../assets/db/db.php");
require_once("../includes/function.php");
require_once("../layout_header.php");

//update password
if(isset($_POST["submit"])){
	
	if(validate_input() <> ""){
		header("location:gantipass.php");
		exit;
	}else{
		if(isset($_POST["txt"][1]) && $_POST["txt"][1]!=""){
			$strsql = "
				UPDATE ms_admin
				SET 
				password = '".md5($db->escape($_POST["txt"][1]))."'
				,modtime = NOW()
				WHERE id=".$db->escape($_SESSION["ssid"])."
			";
			
			$result = $db->query($strsql);
			if($result){
				$_SESSION["peringatan"]="<div align=\"center\" style=\"color:#2683AE;font-weight:bold;\">Password telah berhasil di ganti</div>";
			}
		}
	}

}


?>

<div class="wrap">
  <div id="icon-tools" class="icon32"><br />
  </div>
  <h2>Ubah Password</h2>
  <form name="frmadd" method="post" action="gantipass.php" enctype="multipart/form-data" autocomplete="off" onsubmit="getisifimage();">
	
	<table class="form-table">

	
	<?php
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		$_SESSION['txt'] = 0;
				
		$strsql = "select username as value FROM ms_admin where id = ".$db->escape($_SESSION["ssid"]);
		set_sessiontext(1,$strsql);
		
		echo set_label("Username",$strsql);
		echo set_passtext("Password","1,5",20);
	?>
				
	</table>
	<?=set_submit("Ubah Password")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
