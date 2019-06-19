<?php
$JudulHead = "Web Tools Add Admin";
$strCss = "initial";
$bodyid = "Adminmenu";
$hal = "listsubmenuadd";
session_start();

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."layout_header.php");
?>


<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Add Admin Menu Setting</h2>
  <form name="frmadd" method="post" action="addsubmenu_.php" autocomplete="off">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="f0051ff1e3" />
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />-->
	<table class="form-table">
		
	<?php
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
			
		$_SESSION['cek'] = 0;
		$_SESSION['txt'] = 0;
		set_sessiontext(6,"");
		//echo set_listmenu("Kategori Berita","ts_topic","id,vnms","ttampil","Pilih Kategori Berita yang diinginkan");
		echo set_listmenu("Menu Utama","webtool_menu","idmenu,vmenu","ttampil","Menu utama Sub Menu ini");
		echo set_text("Nama Sub Menu","1",100,"Nama Sub Menu yang diinginkan");
		echo set_text("ID Halaman","1",100,"ID Halaman yang digunakan oleh menu");
		echo set_text("URL","1",100," URL Sub Menu");
		echo set_text("Posisi Sub Menu",";1;2",2," Posisi Sub Menu menggunakan angka");
		echo set_checkbox("Tampil","Centang jika ingin menampilkan menu");
	?>
				
	</table>
	<?=set_submit("Add Sub Menu")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
