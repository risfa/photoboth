<?php
session_start();
ob_start();
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
$opt = $db->query("SET character_set_results=utf8");

$JudulHead = "Field";
$strCss = "initial;function";
$bodyid = "bodyfield";
$hal = "field";
$numakses = 4;

$txtlist = "Field";

//setting paging
$page = set_page();

$rowsPerPage = 30;
$rsFirst = $rowsPerPage*($page-1);	
//end setting paging

require_once("".$pathdir."layout_header.php");

?>
<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?php //untuk pencarian
	$selpilih = 0;
	$pilihsql = search("nama",1);
	
	
	//sql buat tampil konfirmasi
	$strsql = "DESCRIBE ms_field";
	$result = $db->get_results($strsql);
	$maxPage = get_maxpage($rowsPerPage);
	?>
	<h2>List <?php echo $txtlist;?></h2>

<!--filter menu-->
	<?php
			if (isset($_SESSION["peringatan"])) {
				echo "<ul id=\"peringatan\" style = \"width = 100%;clear:both;\"><li class=\"info\">Peringatan :</li>".$_SESSION["peringatan"]."</ul>";				
				unset($_SESSION['peringatan']); 
			}
	?>
	<ul class="subsubsub" style="clear:both;">
		<li><a href='index.php' class="current">List <?php echo $txtlist?> <span class="count">(<?php echo $_SESSION["allrows"];?> Record)</span></a> |</li>
    <li><a href='../logout.php' >Logout</a></li>
	</ul>
		<form id="tablelist" name="tablelist" action="index.php" method="post">
		<input type="hidden" name="goaction" />	
		<table class="widefat" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Field</th>
					<th>Status</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
        	<th>No</th>
					<th>Field</th>
					<th>Status</th>	
				</tr>
			</tfoot>
			<tbody id="the-comment-list">
			<?php
				if ($result) {
					// untuk keluarin nomor
					if ($page ==1) {
						$a=1;
					}else{
						$a=$page*$rowsPerPage-($rowsPerPage-1);
					}
			
					foreach($result as $row){			
						$field = $row->Field;
						echo "<tr id=\"data-".$a."\">";
						echo "<td class=\"column-center\">".$a."</td>";
						echo "<td class=\"column-center\">".$row->Field."</td>";
						
						$strsql2 = $db->get_results("select * from ms_field");
						if($strsql2){
							foreach($strsql2 as $row2){
								if(isset($_GET["job"]) && $_GET["job"]=="gantitampil"){
									if(isset($_GET[$field]) && isset($_GET["ptampil"]) && is_numeric($_GET[$field])  && is_numeric($_GET["ptampil"])){

										if($_GET["ptampil"]==1){
											$strsql = "UPDATE ms_field SET ".$field." = 0";
										}else{
											$strsql = "UPDATE ms_field SET ".$field." = 1";
										}

										$updatetampil = $db->query($strsql);
										header("Location: index.php");
										exit;
									}
								}
								
								if($row2->$field == 1){
									$txtaktif="<a href=\"index.php?job=gantitampil&amp;".$field."=".$row2->$field."&amp;ptampil=".$row2->$field."\"><img src=\"../images/sticky.png\"></a>";
								}else{
									$txtaktif="<a href=\"index.php?job=gantitampil&amp;".$field."=".$row2->$field."&amp;ptampil=".$row2->$field."\"><img src=\"../images/unsticky.png\" alt=\"tidak tampil\" /></a>";
								}
								echo "<td class=\"column-center\">".$txtaktif."</td>";
							}
						}
						
						
						
						$a++;
					}
				}else{
					echo "<td class=\"column-center\" colspan=\"7\"><strong style=\"color:red;\">There's no data</strong></td>";
				}
				echo "</tr>";
			?>
			</tbody>
		</table>
		</form>
	
	<br class="clear" />
</div>
<div class="clear"></div>

<script type="text/javascript">

function checkall() {
		
	if (<?php echo $_SESSION["allrows"]?>==1) {
		document.tablelist.cb.checked=document.getElementById('cball').checked;
	} else {
		for (i=0;i<<?php echo $_SESSION["allrows"]?>;i++) {
			document.tablelist.cb.checked=document.getElementById('cball').checked;
		}
	}
	
}
</script>

<?php 
set_session();
require_once("../layout_footer.php");
?>
