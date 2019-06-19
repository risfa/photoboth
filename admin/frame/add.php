<?php
session_start();
error_reporting(error_reporting() & ~E_STRICT);
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

$judul = "Add Frame";
$JudulHead = "Frame ".$judul."";
$strCss = "initial;function";
$strJs = "jquery.min.1.8.2";
$bodyid = "bodyframe";
$hal="frame";
$numakses = 3;


require_once("".$pathdir."layout_header.php");

?>
<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Add Event</h2>
  <form name="frmadd" id="frmadd" method="post" action="add_.php" autocomplete="off" enctype="multipart/form-data">
		<input type='hidden' name='option_page' value='general' />
		<input type="hidden" name="action" value="update" />
		<table class="form-table">
			
		<?php			
			if (isset($_SESSION["peringatan"])) {
				echo "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$_SESSION["peringatan"]."</ul><br />";				
				unset($_SESSION['peringatan']); 
			}
			
			(isset($_SESSION["chkaktif"])) ? $chkaktif = $_SESSION["chkaktif"] : $chkaktif = "";
			
			//checked=\"checked\"
			if($chkaktif==1){
				$chkaktif = "checked=\"checked\"";
			}else{
				$chkaktif = "";
			}				
			
				echo "
					<tr valign=\"top\">".
						"<th scope=\"row\"><label for=\"fimage\">Themes</label></th>".
						"<td>".		
							"<input type=\"file\" name=\"fimage\" size=\"100\" /><br/>".
						"</td>".
					"</tr>";		

	
					
			echo "
				<tr valign=\"top\">".
					"<th scope=\"row\"><label for=\"chkaktif\">Aktif</label></th>".
					"<td>".
						"<input type=\"checkbox\"  id=\"chkaktif\"  name=\"chkaktif\"  ".$chkaktif."/>".
						"<span class=\"setting-description\"> Centang jika ingin menampilkan Frame</span>".
					"</td>".
				"</tr>";
		?>	
		</table>
		<p class="submit"><input type="submit" name="btnsubmit"  onclick="submitform();" class="button-primary" value="Add Frame" />
  </form>
</div>
<div class="clear"></div>
<?php 
set_session();
require_once("../layout_footer.php");
?>
