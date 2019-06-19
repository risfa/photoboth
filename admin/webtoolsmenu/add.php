<?php
session_start();
$JudulHead = "Web Tools Add Admin";
$strCss = "initial";
$bodyid = "Adminmenu";
$hal = "listmenuadd";
$numakses = 26;
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
require_once("".$pathdir."layout_header.php");
?>


<div class="wrap">
  <div id="icon-tools" class="icon32"><br />
  </div>
  <h2>Add Admin Menu Setting</h2>
  <form name="frmadd" method="post" action="add_.php" autocomplete="off">
	<input type='hidden' name='option_page' value='general' />
	<input type="hidden" name="action" value="update" />
	<!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="f0051ff1e3" />
	<input type="hidden" name="_wp_http_referer" value="/admincp/options-general.php" />-->
	<table class="form-table">
		
	<?php
		if (isset($_SESSION["peringatan"])) {
			echo $_SESSION["peringatan"];
		}
		
		(isset($_SESSION["vmenu"])) ? $vmenu = $_SESSION["vmenu"] : $vmenu = "";
		(isset($_SESSION["selparent"])) ? $selparent = $_SESSION["selparent"] : $selparent = "";
		(isset($_SESSION["selnumakses"])) ? $selnumakses = $_SESSION["selnumakses"] : $selnumakses = "";
		(isset($_SESSION["txtbodyid"])) ? $txtbodyid = $_SESSION["txtbodyid"] : $txtbodyid = "";
		(isset($_SESSION["txturl"])) ? $txturl = $_SESSION["txturl"] : $txturl = "";
		(isset($_SESSION["selposisi"])) ? $selposisi = $_SESSION["selposisi"] : $selposisi = "";
		(isset($_SESSION["tampil"])) ? $tampil = $_SESSION["tampil"] : $tampil = "";
		
		$plevel = 1;
		if(isset($_GET["level"]) && is_numeric($_GET["level"])){
			$plevel=$_GET["level"];
		}
		if(isset($_SESSION["level"]) && is_numeric($_SESSION["level"])){
			$plevel = $_SESSION["level"];
		}
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt1\">Menu Level</label></th>
			<td>
				<select name=\"sellevel\" id=\"txt1\" onchange=\"window.location = 'add.php?level='+this.options[this.selectedIndex].value;\">";
			
		for($i=1;$i<=2;$i++){
			echo "<option value=\"".$i."\" ".issel($i,$plevel,"selected","").">Level ".$i."</option>";
		}
		
		echo "
				</select>		
				<span class=\"setting-description\"> Level Menu</span></td>
			</tr>";
			
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt2\">Parent</label></th>
			<td>";
		
		switch($plevel){
			case 2 : $sqllevel = " AND ilevel=1 ";break;
			default : $sqllevel = "";
		}
		
		if($sqllevel!=""){
			$getparent = $db->get_results("SELECT vmenu, idmenu, numakses FROM ms_admin_menu WHERE 1=1 ".$sqllevel." ORDER BY vmenu, tposisi");
			if($getparent){
				echo "<select name=\"selparent\" id=\"txt2\">";
				foreach($getparent as $rsparent){
					echo "<option value=\"".$rsparent->idmenu."\" ".issel($rsparent->idmenu,$selparent,"selected","").">".$rsparent->vmenu." (numakses : ".$rsparent->numakses.")</option>";
				}
				echo "</select>";
			}
		}else{
			echo "<strong>-</strong>";
		}
		
		echo "	
			</td>
			</tr>";
		
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt3\">Nama Menu</label></th>
			<td>
				<input id=\"txt3\" class=\"regular-text\"  name=\"vmenu\" value=\"".$vmenu."\" />
				<span class=\"setting-description\">Nama Menu</span></td>
			</tr>";
		
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt4\">Kode Akses (numakses)</label></th>
			<td>
				<select name=\"selnumakses\" id=\"txt4\">";
		
		for($i=1;$i<=100;$i++){
			$cekava = $db->get_row("SELECT vmenu FROM ms_admin_menu WHERE numakses=".$db->escape($i)."");
			if($plevel==1){				
				if(!$cekava){
					echo "<option value=\"".$i."\" ".issel($selnumakses,$i,"selected","").">".$i."</option>";
				}
			}else{
				if($cekava){
					echo "<option value=\"".$i."\" ".issel($selnumakses,$i,"selected","").">".$i." (".$cekava->vmenu.")</option>";
				}else{
					echo "<option value=\"".$i."\" ".issel($selnumakses,$i,"selected","").">".$i."</option>";
				}
			}
		}
		
		echo "
				</select>
				<span class=\"setting-description\">Variabel numakses halaman</span>	
			</td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt5\">Body ID</label></th>
			<td>
				<input id=\"txt5\" class=\"regular-text\"  name=\"txtbodyid\" value=\"".$txtbodyid."\" />
				<span class=\"setting-description\">variabel bodyid halaman</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt6\">URL</label></th>
			<td>
				<input id=\"txt6\" class=\"regular-text\"  name=\"txturl\" value=\"".$txturl."\" />
				<span class=\"setting-description\"> co :<strong> /rfid/admin/member/add.php</strong></span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt7\">Posisi</label></th>
			<td>
				<select name=\"selposisi\" id=\"txt7\">";
		
		for($i=1;$i<=100;$i++){
			echo "<option value=\"".$i."\" ".issel($i,$selposisi,"selected","").">".$i."</option>";
		}
		
		echo "
				</select>
				<span class=\"setting-description\">Posisi Menu</span>	
			</td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt10\">Tampil</label></th>
			<td>
				<input type=\"checkbox\" id=\"txt10\"  name=\"chktampil\" ".issel($tampil,"on","checked","")." />
				<span class=\"setting-description\">Tampilkan Menu</span></td>
			</tr>";
		
		echo "
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt11\">Add Lagi?</label></th>
			<td>
				<input type=\"checkbox\" id=\"txt11\"  name=\"chklagi\"  />
				<span class=\"setting-description\">*klo dicentang balik kehalaman ini lagi</span></td>
			</tr>";
		
	?>
				
	</table>
	<?=set_submit("Add Menu")?>
  </form>
</div>
<div class="clear"></div>

<?php 
set_session();
require_once("../layout_footer.php");
?>
