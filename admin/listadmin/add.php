<?php
session_start();
$JudulHead = "Web Tools Add Admin";
$strCss = "initial";
$bodyid = "admin";
$hal = "editadmin";
$pathdir = "../";
$numakses = 24;
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
  <h2>Add Admin</h2>
  <form name="frmadd" method="post" action="add_.php" autocomplete="off">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />
	<table class="form-table">
		
	<?php
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		(isset($_SESSION["name"])) ? $txtname=$_SESSION["name"] : $txtname = "";
		(isset($_SESSION["username"])) ? $txtusername=$_SESSION["username"] : $txtusername = "";
		(isset($_SESSION["level"])) ? $txtsellevel=$_SESSION["level"] : $txtsellevel = "";
		(isset($_SESSION["chkaktif"])) ? $chkaktif = $_SESSION["chkaktif"] : $chkaktif = "";
		(isset($_SESSION["seldiv"])) ? $seldiv = $_SESSION["seldiv"] : $seldiv = "";
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

		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt1\">Nama Admin</label></th>
			<td>
				<input type=\"text\" class=\"regular-text\" id=\"txt1\" name=\"txtnama\" value=\"".$txtname."\"  />
				<span class=\"setting-description\"> Nama Admin</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt2\">Username</label></th>
			<td>
				<input type=\"text\" class=\"regular-text\" id=\"txt2\" name=\"txtusername\" value=\"".$txtusername."\"  />
				<span class=\"setting-description\"> Username Admin</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt3\">Password</label></th>
			<td>
				<input type=\"password\" class=\"regular-text\" id=\"txt3\" name=\"txtpwd\"  />
				<span class=\"setting-description\"> Password Admin</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt4\">Password</label></th>
			<td>
				<input type=\"password\" class=\"regular-text\" id=\"txt4\" name=\"txtpwd2\"  />
				<span class=\"setting-description\"> Ulangi Password</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt5\">Admin Level</label></th>
			<td>
				<select name=\"sellevel\" id=\"txt5\">
					<option value=\"0\">Level 0</option>
					<option value=\"1\" ".issel($txtsellevel,1,"selected","").">Level 1</option>
				</select>
				<span class=\"setting-description\"> Level Authoritas Admin</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt6\">Divisi</label></th>
			<td>
				<select name=\"seldivisi\" id=\"txt6\">";
		
		$resultdiv = $db->get_results("SELECT vdiv,iddiv FROM ms_admin_permit");
		if($resultdiv){
			foreach($resultdiv as $rowdiv){
				echo "<option value=\"".$rowdiv->iddiv."\" ".issel($seldiv,$rowdiv->iddiv,"selected","").">".$rowdiv->vdiv."</option>";	
			}
		}		
			
		echo "
				</select>
				<span class=\"setting-description\"> Divisi Admin</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt7\">Aktif</label></th>
			<td>
				<input id=\"txt7\" name=\"chkaktif\" type=\"checkbox\" ".issel($chkaktif,"on","checked","")." />
				<span class=\"setting-description\"> Apakah Admin aktif atau tidak</span></td>
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
<?php	echo set_submit("Add Admin")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
