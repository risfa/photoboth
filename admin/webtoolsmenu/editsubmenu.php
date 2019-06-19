<?php
$JudulHead = "Web Tools Edit Admin";
$strCss = "initial";
$bodyid = "Adminmenu";
$hal = "listsubmenu";
session_start();

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."layout_header.php");
?>


<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Edit Admin Sub Menu Setting</h2>
  <form name="frmadd" method="post" action="editsubmenu_.php" autocomplete="off" onsubmit="getisifimage();">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="f0051ff1e3" />
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />-->
	<table class="form-table">

	
	<?php
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		$_SESSION['txt'] = 0;
		
		if(isset($_GET["id"]) && is_numeric($_GET["id"])){
			$id = $_GET["id"];
		}else{
			header("Location: indexsubmenu.php");
			exit;
		}
		
		$strsql = "select idmenu, vsubmenu,  vhal,vurl, tposisi, ttampil from webtool_submenu where idsubmenu = ".$db->escape($id);
		set_sessiontext(6,$strsql);
//		echo $strsql ;
//		exit;
		
		echo set_hiddenid($id);
		echo set_listmenu("Nama Menu","webtool_menu","idmenu,vmenu","ttampil","Pilih Nama Menu yang di inginkan");
		echo set_text("Nama Sub Menu","1",100,"Nama Sub Menu yang di inginkan");
		echo set_text("ID Halaman","1",100,"ID Halaman yang digunakan oleh sub menu");
		echo set_text("URL","1",100,"URL menu");
		echo set_text("Posisi Sub Menu","1",100,"Posisi sub menu menggunakan angka");
		echo set_checkbox("Tampil","Centang jika ingin menampilkan menu");
	?>
				
	</table>
	<?=set_submit("Edit Sub Menu")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
