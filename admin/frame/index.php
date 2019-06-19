<?php
session_start();
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
$opt = $db->query("SET character_set_results=utf8");

$JudulHead = "Frame";
$strCss = "initial;function";
$bodyid = "bodyframe";
$hal = "frame";
$numakses = 3;

$txtlist = "Frame";

//setting paging
$page = set_page();

$rowsPerPage = 20;
$rsFirst = $rowsPerPage*($page-1);	
//end setting paging

//ttampil
(!isset($_GET["tampil"]) || !is_numeric($_GET["tampil"])) ? $ptampil = 2 : $ptampil = $_GET["tampil"];
($ptampil == 2) ? $sqltampil = "" : $sqltampil = " and aktif=".$db->escape($ptampil)." ";	

//order
$vorder = 0;
if(isset($_GET["order"])){
	$vorder = $_GET["order"];
}
switch($vorder){
	case 1 : $sqlorder = " ORDER BY cretime ASC";break;
	default : $sqlorder = " ORDER BY cretime DESC ";
}


//ganti tampil
if(isset($_GET["job"]) && $_GET["job"]=="gantitampil"){

	if(isset($_GET["id"]) && isset($_GET["ptampil"]) && is_numeric($_GET["id"])  && is_numeric($_GET["ptampil"])){


		if($_GET["ptampil"]==1){
			$strsql = "UPDATE ms_frame SET aktif=0 WHERE id_frame=".$db->escape($_GET["id"])."";
		}else{
			$strsql = "UPDATE ms_frame SET aktif=1 WHERE id_frame=".$db->escape($_GET["id"])."";
		}
		
		$updatetampil = $db->query($strsql);
		header("Location: index.php?page=".$page.str_replace("&amp;","&",$param)."");
	}
}

del_query("ms_frame","id_frame");

$param = "&amp;order=".$vorder;

require_once("".$pathdir."layout_header.php");

?>

<script language="javascript" type="text/javascript">
function konfirmdel(nama,id,mgLV) {
	if (mgLV==1){
		var answer = confirm("Yakin ingin menghapus gambar '"+nama+"' ?");
		if (answer!=0) {
			window.location = "index.php?job=delete&id="+id+"";
		} else {
			window.location = "index.php";
		}
	}else{
		alert("Admin Level 0 tidak dapat menghapus data.");
	}
}

function konfirmdel2(){
	if(document.tablelist.action.value=="1" || document.tablelist.action2.value=="1"){
		var answer = confirm("Yakin ingin menghapus gambar yang dipilih ? \n pilih 'OK' untuk lanjut \n pilih 'Cancel' untuk kembali ");
		if (answer==0) {
			return false;
		}else{
			document.tablelist.submit();
		}
	}else{
		document.tablelist.submit();
	}
}


</script>


<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?php //untuk pencarian
	$selpilih = 0;
	$pilihsql = search("dir",1);
	
	
	//sql buat tampil Event
	$strsql = "
		SELECT SQL_CALC_FOUND_ROWS * FROM ms_frame WHERE 1=1  ".$pilihsql." ".$sqlorder." LIMIT ".$rsFirst.",".$rowsPerPage;
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
		 <li><a href='add.php' >Add <?php echo $txtlist?></a> |</li>
    <li><a href='../logout.php' >Logout</a></li>
	</ul>

	<div class="tablenav">			 			
  	<div class="alignleft actions">								 
	
		<!--order by-->
    <select name="selkategori" onChange="location='index.php?order='+this.options[this.selectedIndex].value;">
      <option value='0'>Tanggal Terbesar</option>
      <option value='1' <?php echo issel(1,$vorder,"selected","")?>>Tanggal Terkecil</option>
    </select>
    <!--end order by-->
		
		</div>
		
		<div class="menuatas-search" >		
			<form action="index.php?job=cari<?php echo $param;?>" method="post" >					
				<select name='selpilih' >
        	<option value='0'> Nama Image</option>
				</select>										
				<input type="text" class="search-input" id="post-search-input" size="20" name="txtcari" value="" />								
				<input type="submit" id="post-query-submit" value="Search" class="button-secondary" />
			</form>
		</div>
  </div>

	<!--end filter menu-->
	
		<form id="tablelist" name="tablelist" action="index.php" method="post">
		<input type="hidden" name="goaction" />
		<div class="tablenav">		
		
			<!--paging-->
			<?php						
			//print paging
			echo paging($maxPage);
			
			?>
			<!--end paging-->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<!--menu atas-->
		
		<table class="widefat" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Image</th>
					<th>Status</th>
          <th>Cretime</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>No</th>
					<th>Image</th>
					<th>Status</th>
          <th>Cretime</th>
					<th>Action</th>
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
						if ($row->aktif==1){
							$txtaktif="<a href=\"index.php?job=gantitampil&amp;id=".$row->id_frame."&amp;ptampil=".$row->aktif.$param."\"><img src=\"../images/sticky.png\"></a>";
						}else{
							$txtaktif="<a href=\"index.php?job=gantitampil&amp;id=".$row->id_frame."&amp;ptampil=".$row->aktif.$param."&amp;tampil=0\"><img src=\"../images/unsticky.png\" alt=\"tidak tampil\" /></a>";
						}
						
						
						$time = strtotime($row->cretime);
						$vartgl = date("d", $time);
						$varbln = date("M", $time);
						$varthn = date("Y", $time);
						$varjam = date("H", $time);
						$varmnt = date("i", $time);
						$vardtk = date("s", $time);
						$vartglcomplete=$vartgl." ".$varbln." ".$varthn."<br />".$varjam.":".$varmnt.":".$vardtk;
						
						echo "<tr id=\"data-".$a."\">";
						echo "<td class=\"column-center\">".$a."</td>";
						echo "<td class=\"column-center\"><img style=\"width:400px;height:200px;\" src=\"/rfid/admin".str_replace(".","l.",$row->image)."\" /><br/>".$row->image."</td>";
						echo "<td class=\"column-center\">".$txtaktif."</td>";
						echo "<td class=\"column-center\">".$vartglcomplete."</td>";
						echo "<td class=\"column-center\"><a href=\"edit.php?id=".$row->id_frame."\">Edit</a> | <a href=\"#\" class=\"delete\" onclick=\"konfirmdel('".$row->image."',".$row->id_frame.",".$_SESSION["ssLV"].")\">Delete</a></td>";

						
						$a++;
					}
				}else{
					echo "<td class=\"column-center\" colspan=\"7\"><strong style=\"color:red;\">There's no data</strong></td>";
				}
				echo "</tr>";
			?>
			</tbody>
		</table>
		<div class="tablenav">
			<!--paging-->
			<?php
			//print paging			
			echo paging($maxPage);
			?>
		
			<br class="clear" />
		</div>
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
