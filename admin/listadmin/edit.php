<?php
session_start();
$JudulHead = "Web Tools Edit Admin";
$strCss = "initial";
$bodyid = "admin";
$hal = "listadmin";
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

if(isset($_GET["id"]) && is_numeric($_GET["id"])){
	$id = $_GET["id"];
}else{
	header("Location: index.php");
	exit;
}


//query	
$strsql = "SELECT id, name, username, lvl, tdiv , akses, vlastip, UNIX_TIMESTAMP(cretime) as cretime, creby, lastlogin, UNIX_TIMESTAMP(modtime) as modtime, modby, taktif FROM ms_admin WHERE id = ".$db->escape($id)."";
$row = $db->get_row($strsql);
if(!$row){header("Location: index.php");exit;};


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
  <h2>Edit Admin</h2>
  <form name="frmadd" method="post" action="edit_.php" autocomplete="off" onsubmit="getisifimage();">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="f0051ff1e3" />-->
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />
	<table class="form-table">

	
	<?php
		(isset($_SESSION["name"])) ? $txtname=$_SESSION["name"] : $txtname = $row->name;
		(isset($_SESSION["username"])) ? $txtusername=$_SESSION["username"] : $txtusername = $row->username;
		(isset($_SESSION["level"])) ? $txtsellevel=$_SESSION["level"] : $txtsellevel = $row->lvl;
		(isset($_SESSION["chkaktif"])) ? $chkaktif = $_SESSION["chkaktif"] : $chkaktif = $row->taktif;
		(isset($_SESSION["seldiv"])) ? $seldiv = $_SESSION["seldiv"] : $seldiv = $row->tdiv;
		(isset($_SESSION["chkakses"])) ? $chkakses = $_SESSION["chkakses"] : $chkakses = $row->akses;		
		$arrakses = explode(";",$chkakses);
		$modtime=$row->modtime;
		$modby=$row->modby;
		$cretime=$row->cretime;
		$creby=$row->creby;
		if(isset($_SESSION["chkaktif"])){
			$chkaktif = $_SESSION["chkaktif"];
		}else{
			if($row->taktif==1){
				$chkaktif = "on";
			}else{
				$chkaktif = "";
			}
		}
		
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		echo "<input type=\"hidden\" name=\"txtid\" value=\"".$id."\" />";
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
				<textarea name=\"txtakses\" cols=\"95\" rows=\"3\" onclick=\"document.frmadd.txtakses.select();\" readonly>".$chkakses."</textarea>
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
			
		echo lastupdate($cretime,$creby,$modtime,$modby);
	?>
		
	</table>
	<p class="submit"><input type="submit" name="submit" class="button-primary" value="Edit Admin" />
  <input type="submit" name="addnew" class="button-primary" value="Add as new" /></p>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
