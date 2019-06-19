<?php
session_start();
error_reporting(error_reporting() & ~E_STRICT);
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

$judul = "Edit Event";
$JudulHead = "Admin ".$judul."";
$strCss = "initial;function";
$strJs = "jquery.min.1.8.2";
$bodyid = "bodyevent";
$hal="event";
$numakses = 1;


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
			$strsql = "select * from ms_event where id_event=".fcekpetik($db->escape($id))."";
			$row = $db->get_row($strsql);
			if($row){
				$idevent = $row->id_event;
			}else{
				$idevent = 0;
			}
			$txtteks=explode(";",$row->desk);
			$selteks=count($txtteks)-1;
			$txtsequence = explode(";",$row->txtsequence);
			$seq = count($txtsequence)-1;
			
				$k=0;			
			for($m=1;$m<=$selteks;$m++){
				$datateks[$m]=$txtteks[$k];
				$k++;
			}
			
			$l=0;
			for($n=1;$n<=$seq;$n++){
				$dataseq[$n]=$txtsequence[$l];
				$l++;
			}
			
			
			if (isset($_SESSION["peringatan"])) {
				echo "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$_SESSION["peringatan"]."</ul><br />";				
				unset($_SESSION['peringatan']); 
			}
			(isset($_SESSION["txtjudul"])) ? $txtjudul = $_SESSION["txtjudul"] : $txtjudul = $row->nama_event;
			(isset($_SESSION["chkaktif"])) ? $chkaktif = $_SESSION["chkaktif"] : $chkaktif = $row->aktif;
			(isset($_SESSION["lokasi"])) ? $lokasi = $_SESSION["lokasi"] : $lokasi = $row->lokasi;
			(isset($_SESSION["selteks"])) ? $selteks = $_SESSION["selteks"] : $selteks = $selteks;
			(isset($_SESSION["sequence"])) ? $sequence = $_SESSION["sequence"] : $sequence = $row->sequence;
			(isset($_SESSION["selseq"])) ? $selseq = $_SESSION["selseq"] : $selseq = $row->sequence;

			if(isset($selteks) && $selteks != ""){
				for($x=1;$x<=$selteks;$x++){
					(isset($_SESSION["txtteks".$x.""])) ?	$txtteks[$x]=$_SESSION["txtteks".$x.""] : $txtteks[$x]= $datateks[$x];
				}
			}
			
			if(isset($selseq) && $selseq != ""){
				for($y=1;$y<=6;$y++){					
					if($y > $selseq){
						(isset($_SESSION["txtseq".$y.""])) ?	$txtseq[$y]=$_SESSION["txtseq".$y.""] : $txtseq[$y] = "";
					}else{
						(isset($_SESSION["txtseq".$y.""])) ?	$txtseq[$y]=$_SESSION["txtseq".$y.""] : $txtseq[$y] = $dataseq[$y];
					}
					echo "<input name=\"txtseq".$y."\" id=\"txtseq".$y."\" type=\"hidden\" maxlength=\"150\" value=\"".$txtseq[$y]."\" class=\"regular-text\" />";
				}
			}
			
			//checked=\"checked\"
			if($chkaktif==1){
				$chkaktif = "checked=\"checked\"";
			}else{
				$chkaktif = "";
			}
			
			if($selseq > 0){
				$sequence = "checked=\"checked\"";
			}else{
				$sequence = "";
			}
			
			if(isset($_POST["selteks"])){
				$selteks = $_POST["selteks"];
				for($b=1;$b<=$_POST["selteks"];$b++){
				
					(isset($_POST["txtteks".$b.""])) ?	$txtteks[$b]=$_POST["txtteks".$b.""] : $txtteks[$b]= "";
					
				}
				
				$txtjudul = $_POST["txtjudul"];
				$txtlokasi =  $_POST["txtlokasi"];

				if (isset($_POST["chkaktif"]) && strcasecmp($_POST["chkaktif"],"on")==0) {
					$chkaktif = "checked=\"checked\"";
				} else {
					$chkaktif = "";
				}	

			}
			
			echo "
				<tr valign=\"top\">".
					"<th scope=\"row\"><label for=\"txtjudul\"> Nama Event </label></th>".
					"<td>".
						"<input name=\"txtjudul\" type=\"text\" maxlength=\"150\" value=\"".$txtjudul."\" class=\"regular-text\" />".
					"</td>".
				"</tr>";
				
				
				
			echo "
				<tr valign=\"top\">
					<th scope=\"row\"><label for=\"txt1\">Jumlah</label></th>
					<td>
						<select name=\"selteks\" onchange=\"submitform2();\" >
							<option value=0>Select</option>";
							for ($i = 1; $i <= 10; $i++) {
								echo "<option value=".$i." ".issel($selteks,$i,"selected","").">".$i."</option>";
							}	
									echo "</select>
					<span class=\"setting-description\"> Pilih Jumlah Teks Event
					</span>
				</td>
			</tr>";	
				
			if( isset($selteks) && $selteks > 0 ){
				$y=0;
				for($z=1;$z<=$selteks;$z++){
						
							echo "
					<tr valign=\"top\">".
						"<th scope=\"row\"><label for=\"txtteks[$z]\">Teks Ke ".$z."</label></th>".
						"<td>".
							"<input name=\"txtteks".$z."\" type=\"text\" maxlength=\"150\" value=\"".$txtteks[$z]."\" class=\"regular-text\" />".
						"</td>".
					"</tr>";
					$y++;
  			}

			}
		
			echo "
				<tr valign=\"top\">".
					"<th scope=\"row\"><label for=\"txtlokasi\">Lokasi</label></th>".
					"<td>".
						"<input name=\"txtlokasi\" type=\"text\" maxlength=\"150\" value=\"".$lokasi."\" class=\"regular-text\" />".
					"</td>".
				"</tr>";
				
				
					echo "
					<tr valign=\"top\">".
						"<th scope=\"row\"><label for=\"fimage\">Themes</label></th>".
						"<td>".		
							"<input type=\"file\" name=\"fimage\" size=\"100\" /><br/>".
							"<img style=\"width:100px;height:100px;\" src=\"/rfid/admin".$row->background."\"".
						"</td>".
					"</tr>";		

	
					
			echo "
				<tr valign=\"top\">".
					"<th scope=\"row\"><label for=\"chkaktif\">Aktif</label></th>".
					"<td>".
						"<input type=\"checkbox\"  id=\"chkaktif\"  name=\"chkaktif\"  ".$chkaktif."/>".
						"<span class=\"setting-description\"> Centang jika ingin menampilkan Event</span>".
					"</td>".
				"</tr>";
				
			echo "
				<tr valign=\"top\">".
					"<th scope=\"row\"><label for=\"sequence\">Sequence</label></th>".
					"<td>".
						"<input type=\"checkbox\"  id=\"sequence\"  name=\"sequence\"  ".$sequence."/>".
						"<input type=\"hidden\"  id=\"selseq2\"  name=\"selseq\"  value=\"".$selseq."\" />".
						"<span class=\"setting-description\"> Centang jika ada Sequence<br/><div id='selsequence'></div></span>".
					"</td>".
				"</tr>";

		?>	
		</table>
	
		<p class="submit"><input type="submit" name="btnsubmit"  onclick="submitform();" class="button-primary" value="Edit Event" />
  </form>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function(){		
		if($("#sequence").is(":checked")) {
			var selseq = $("#selseq2").val();
			$.ajax({
				type: "POST",
				url: "getsequence.php",
				data: "id=1&selseq="+selseq,
				success: function(html) {
					$("#selsequence").html(html);
				}
			});
	} else {
			$.ajax({
				type: "POST",
				url: "getsequence.php",
				data: "id=0",
				success: function(html) {
					$("#selsequence").html(html);
				}
			});
		}

		if($("#sequence").is(":checked")){
			var selseq = $("#selseq2").val();
			var txtseq1 = $("#txtseq1").val();
			var txtseq2 = $("#txtseq2").val();
			var txtseq3 = $("#txtseq3").val();
			var txtseq4 = $("#txtseq4").val();
			var txtseq5 = $("#txtseq5").val();
			var txtseq6 = $("#txtseq6").val();
			$.ajax({
				type: "POST",
				url: "gettxtsequence.php",
				data: "selseq="+selseq+"&txtseq1="+txtseq1+"&txtseq2="+txtseq2+"&txtseq3="+txtseq3+"&txtseq4="+txtseq4+"&txtseq5="+txtseq5+"&txtseq6="+txtseq6,
				success: function(html) {
					$("#txtseq").html(html);
				}
			});
		}
});

//start combobox sequence
$("#selseq1").live("change",function(){
	var selseq = $("#selseq1").val();
	var txtseq1 = $("#txtseq1").val();
	var txtseq2 = $("#txtseq2").val();
	var txtseq3 = $("#txtseq3").val();
	var txtseq4 = $("#txtseq4").val();
	var txtseq5 = $("#txtseq5").val();
	var txtseq6 = $("#txtseq6").val();
	$.ajax({
		type: "POST",
		url: "gettxtsequence.php",
		data: "selseq="+selseq+"&txtseq1="+txtseq1+"&txtseq2="+txtseq2+"&txtseq3="+txtseq3+"&txtseq4="+txtseq4+"&txtseq5="+txtseq5+"&txtseq6="+txtseq6,
		success: function(html) {
			//alert(html);
			$("#txtseq").html(html);
		}
	});
});
//end combobox sequence

//start sequence
$("#sequence").live("click", function(){
		if($(this).is(":checked")) {
				var selseq = $("#selseq").val();
				$.ajax({
					type: "POST",
					url: "getsequence.php",
					data: "id=1&selseq="+selseq,
					success: function(html) {
						$("#selsequence").html(html);
					}
				});
		} else {
				$.ajax({
					type: "POST",
					url: "getsequence.php",
					data: "id=0",
					success: function(html) {
						$("#selsequence").html(html);
					}
				});
		}
});
//end sequence
</script>
<?php 
set_session();
require_once("../layout_footer.php");
?>
