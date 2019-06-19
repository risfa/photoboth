<?php

session_start();
$JudulHead = "Web Tools Update Divisi";
$strCss = "initial";
$bodyid = "divisi";
$hal = "listdivisi";
$pathdir = "../";
$numakses = 25;
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

if(isset($_GET["id"]) && is_numeric($_GET["id"])){
	$id = $_GET["id"];
}else{
	header("Location: index.php");
	exit;
}

$strsql = "select a.username, a.name, a.akses, a.taktif, a.id, d.vdiv, d.tpermit from ms_admin a INNER JOIN ms_admin_permit d ON d.iddiv = a.tdiv where a.tdiv=".$db->escape($id)." ORDER BY a.id";
$results = $db->get_results($strsql);
if(!$results){header("Location: ".$pathdir."index.php");exit;};

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

function disabledchkbox(nmform,nmchk,varinputcek){		
	var theForm = nmform;
	if (varinputcek.value!=''){
		for (i=0; i<theForm.elements.length; i++) {
			if (theForm.elements[i].name==nmchk)
					theForm.elements[i].disabled = true;
		}
	}else{
		for (i=0; i<theForm.elements.length; i++) {
			if (theForm.elements[i].name==nmchk)
					theForm.elements[i].disabled = false;
		}
	}
}

</script>
<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Update Divisi</h2>
  <form name="frmadd" method="post" action="update_.php" autocomplete="off">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="f0051ff1e3" />-->
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />
	<table class="form-table">

	
	<?php
		(isset($_SESSION["chkakses"])) ? $chkakses = $_SESSION["chkakses"] : $chkakses = $results[0]->tpermit;
		$arrakses = split(";",$chkakses);
				
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		echo "<input type=\"hidden\" name=\"txtid\" value=\"".$id."\" />";
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt1\">Nama Divisi</label></th>
			<td>
				<strong>".$results[0]->vdiv."</strong>
				</td>
			</tr>";
		
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt1\">List Admin</label></th>
			<td>";
		
		echo "<ul class=\"listitems\" style=\"width:420px;\">";
		foreach($results as $row){
			
			//validasi klo adminnya ga aktif = merah
			if($row->taktif==1){
				$varcolorstatus = "";
			}else{
				$varcolorstatus = " style=\"color:red;\" ";
			}
			
			//validasi aksesnya sama dengan akses divisi / engga
			if($row->akses==$row->tpermit){
				$aksessel = " checked";
			}else{
				$aksessel = "";
			}
			
			echo "<li><input type=\"checkbox\" id=\"admin".$row->id."\" name=\"chkadmin[]\" value=\"".$row->id."\" ".$aksessel." /> <a href=\"/Admin/listadmin/edit.asp?id=".$row->id."\" target=\"_blank\">[edit]</a> <label for=\"admin".$row->id."\" ".$varcolorstatus.">".$row->username." (<em>".$row->name."</em>)</label></li>";
			
		}
		echo "</ul>";
		
		echo "
			<ul class=\"listitems\">
				<li><strong>Options</strong></li>
				<li><input type=\"button\" class=\"inputbtn\" name=\"btncheckall\" value=\"Check All\" onclick=\"CheckAll(document.frmadd,'chkadmin[]',1)\" /></li>
				<li><input type=\"button\" class=\"inputbtn\" name=\"btnuncheckall\" value=\"Uncheck All\" onclick=\"CheckAll(document.frmadd,'chkadmin[]',0)\" /></li>
			</ul>
		";
		
		echo"		
				</td>
			</tr>";
		
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt7\">Akses Halaman</label></th>
			<td>
				<textarea name=\"txtakses\" cols=\"95\" rows=\"3\" onkeyup=\"disabledchkbox(document.frmadd,'chkakses[]',document.frmadd.txtakses);\"></textarea>
				<span class=\"setting-description\"><Br /> *jika diisi maka checkbox akses halaman akan di disabled</span></td>
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
	<?=set_submit("Update Divisi")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
