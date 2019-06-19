<?php
session_start();
error_reporting(error_reporting() & ~E_STRICT);
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

$judul = "Edit Frame";
$JudulHead = "Admin ".$judul."";
$strCss = "initial;function";
$strJs = "jquery.min.1.8.2";
$bodyid = "bodyframe";
$hal="frame";
$numakses = 3;


if(isset($_GET["id"]) && is_numeric($_GET["id"])){
	$id = $_GET["id"];
}else{
	header("Location: index.php");
	exit;
}


require_once("".$pathdir."layout_header.php");

?>
<script type="text/javascript">

function submitform(){
	document.frmadd.action='edit_.php';
	document.frmadd.target='_self'	
	document.frmadd.submit();
}

function submitform2(){
	document.frmadd.action='edit.php?id=<?php echo $id; ?>';
	document.frmadd.target='_self'	
	document.frmadd.submit();
}

function preview(){
	document.frmadd.action='edit_preview.php';
	document.frmadd.target='_blank'
}

</script>


<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Edit Event</h2>
  <form name="frmadd" id="frmadd" method="post" action="edit_.php?id=<?php echo $id?>" autocomplete="off" enctype="multipart/form-data">
		<input type='hidden' name='option_page' value='general' />
		<input type="hidden" name="action" value="update" />
    <input type="hidden" name="id" value="<?php echo $id;?>" />
		<table class="form-table">
			
		<?php
			$strsql = "select * from ms_frame where id_frame=".fcekpetik($db->escape($id))."";
			$row = $db->get_row($strsql);			
			
			if (isset($_SESSION["peringatan"])) {
				echo "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$_SESSION["peringatan"]."</ul><br />";				
				unset($_SESSION['peringatan']); 
			}
			(isset($_SESSION["chkaktif"])) ? $chkaktif = $_SESSION["chkaktif"] : $chkaktif = $row->aktif;
			
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
							"<img style=\"width:100px;height:100px;\" src=\"/rfid/admin".str_replace(".","l.",$row->image)."\"".
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
	
		<p class="submit"><input type="submit" name="btnsubmit"  onclick="submitform();" class="button-primary" value="Edit Frame" />
  </form>
</div>
<div class="clear"></div>
<?php 
set_session();
require_once("../layout_footer.php");
?>
