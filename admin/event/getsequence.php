<?php
$pathdir = "../";
require_once("".$pathdir."includes/function.php");
$id = $_POST["id"];
(isset($_POST["selseq"])) ? $selseq = trim($_POST["selseq"]) : $selseq = "";

if($id == 1){
	echo 
		"<div id='selsequence'>".
					"<select name='selseq' id='selseq1'>";
						for ($i = 0; $i <= 6; $i++) {
								echo "<option value=".$i." ".issel($selseq,$i,"selected","").">".$i."</option>";
							}	
	echo "</select>
					<span class=\"setting-description\"> Pilih Jumlah Sequence</span>";
	echo "</div><div id='txtseq'></div>";
}else{
	echo "<div id='selsequence'></div>";
}
	
?>