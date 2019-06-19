<?php
session_start();
$JudulHead = "Admin Add Divisi";
$strCss = "initial";
$bodyid = "divisi";
$hal = "adddivisi";
$numakses = 25;
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
require_once("".$pathdir."layout_header.php");
?>

<script type="text/javascript">
function CheckAll(nmform,nmchk,opt) {
	var theForm = nmform;
	for (i=0; i<theForm.elements.length; i++) {
		if (theForm.elements[i].name==nmchk)
				theForm.elements[i].checked = opt;
	}
}
</script>

<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Add Divisi</h2>
  <form name="frmadd" method="post" action="add_.php" autocomplete="off">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />
	<table class="form-table">
		
	<?php
		(isset($_SESSION["name"])) ? $txtname=$_SESSION["name"] : $txtname = "";
		(isset($_SESSION["chkakses"])) ? $chkakses = $_SESSION["chkakses"] : $chkakses = "";
		
		if($chkakses!=""){
			$varakses = ";";
			foreach($chkakses as $iakses){
				$varakses .= $iakses.";";
			}
			$arrakses = explode(";",$varakses);
		}else{
			$arrakses = "";
		}
		
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt1\">Nama Divisi</label></th>
			<td>
				<input type=\"text\" class=\"regular-text\" id=\"txt1\" name=\"txtnama\" value=\"".$txtname."\"  />
				<span class=\"setting-description\"> Nama Divisi</span></td>
			</tr>";
			
		echo "
			<tr valign=\"top\">
				<th scope=\"row\"><label>Akses Halaman</label></th>
				<td>";
		
		$result = $db->get_results("SELECT vmenu, numakses FROM ms_admin_menu WHERE ilevel=1 ORDER BY tposisi");
		if($result){
			echo "<ul class=\"listitems\">";
			echo "<li><strong>Menu Level 1</strong></li>";
			foreach($result as $row){
				$aksessel = "";
				if($arrakses != ""){
					foreach($arrakses as $akses){
						if(isset($akses) && is_numeric($akses)){
							if($row->numakses==$akses){
								$aksessel=" checked";
							}
						}
					}
				}
				if($row->numakses==99 || $row->numakses==100){
					$aksessel = " checked";
				}
				echo "<li><input type=\"checkbox\" name=\"chkakses[]\" value=\"".$row->numakses."\" ".$aksessel." /> ".$row->vmenu."</li>";			
			}
			echo "</ul>";
		}
		
		$result = $db->get_results("
			SELECT a.vmenu, a.numakses, a2.vmenu as parent 
			FROM ms_admin_menu a INNER JOIN ms_admin_menu a2 ON a2.idmenu = a.idparent 
			WHERE a.ilevel=2 
			ORDER BY a.idparent, a.tposisi");
		if($result){
			echo "<ul class=\"listitems\" style=\"width:400px;\">";
			echo "<li><strong>Menu Level 2</strong></li>";
			foreach($result as $row){
				$aksessel = "";
				if($arrakses != ""){
					foreach($arrakses as $akses){
						if(isset($akses) && is_numeric($akses)){
							if($row->numakses==$akses){
								$aksessel=" checked";
							}
						}
					}
				}
				echo "<li><input type=\"checkbox\" name=\"chkakses[]\" value=\"".$row->numakses."\" ".$aksessel." />[<em><strong>".$row->parent."</strong></em>] ".$row->vmenu."</li>";			
			}
			echo "</ul>";
		}
		
		echo "
			<ul class=\"listitems\">
				<li><strong>Options</strong></li>
				<li><input type=\"button\" class=\"inputbtn\" name=\"btncheckall\" value=\"Check All\" onclick=\"CheckAll(document.frmadd,'chkakses[]',1)\" /></li>
				<li><input type=\"button\" class=\"inputbtn\" name=\"btnuncheckall\" value=\"Uncheck All\" onclick=\"CheckAll(document.frmadd,'chkakses[]',0)\" /></li>
			</ul>
		";
				
		echo "
				</td>
			</tr>";
	?>
	</table>
  <?php echo set_submit("Add Divisi");?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
