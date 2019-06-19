<?php
session_start();
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
$opt = $db->query("SET character_set_results=utf8");

$JudulHead = "Peserta Event";
$strCss = "initial;function";
$strJs = "jquery.min.1.8.2;highcharts;exporting";
$bodyid = "event";
$hal = "event";
$numakses = 1;

$txtlist = "Peserta Event";

if(!isset($_GET["id"])){
	header("location:index.php");
}else{
	$id = $_GET["id"];
}

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
	case 2 : $sqlorder = " ORDER BY nama_event ASC ";break;
	case 3 : $sqlorder = " ORDER BY nama_event DESC ";break;
	default : $sqlorder = " ORDER BY cretime DESC ";
}


//ganti tampil
if(isset($_GET["job"]) && $_GET["job"]=="gantitampil"){
	if(isset($_GET["id"]) && isset($_GET["ptampil"]) && is_numeric($_GET["id"])  && is_numeric($_GET["ptampil"])){

		if($_GET["ptampil"]==1){
			$strsql = "UPDATE ms_event SET aktif=0 WHERE id_event=".$db->escape($_GET["id"])."";
		}else{
			$strsql = "UPDATE ms_event SET aktif=1 WHERE id_event=".$db->escape($_GET["id"])."";
		}
		
		$updatetampil = $db->query($strsql);
		header("Location: index.php?page=".$page.str_replace("&amp;","&",$param)."");
	}
}

del_query("ms_event","id_event");

$param = "&amp;order=".$vorder;

require_once("".$pathdir."layout_header.php");

$rowdate = $db->get_results("SELECT DISTINCT CAST(cretime AS DATE) as cretime FROM ms_event_arsip where id_event = ".$id);
if($rowdate){
	$i = 1;
	foreach($rowdate as $rows){
		$date = strtotime($rows->cretime);
		$tgl = date("j",$date);
		$tgl2 = date("d",$date);
		$bln = date("m",$date);
		$thn = date("Y",$date);
		$arrdate[$i] = "'".$tgl."-"."".$bln."-".$thn."'";
		//$arrdate[$i] = $tgl."-".$bln."-".$thn;
		
		$tgllengkap = $thn."-".$bln."-".$tgl2;
		
		//$strpeople = "select count(location) as location, count(photobooth) as photo from ms_event_arsip where cretime between '".$rows->cretime." 00:00:00' and '".$rows->cretime." 23:59:59' ";
		$strpeople = "select count(id_member) as idm, sum(location) as location, sum(photobooth) as photo from ms_event_arsip where id_event = ".$id." and cretime between '".$rows->cretime." 00:00:00' and '".$rows->cretime." 23:59:59' ";
		$people = $db->get_results($strpeople);
		if($people){
			foreach($people as $rowpeople){
				$arridm[$i] = $rowpeople->idm;
				$arrloc[$i] = $rowpeople->location;
				$arrphoto[$i] = $rowpeople->photo;
			}
		}
		$i++;
	}
}

$rowevent = $db->get_row("select nama_event from ms_event where id_event = ".$id);
if($rowevent){
	$namaevent = $rowevent->nama_event;
}else{
	$namaevent = "";
}

if(isset($arrdate) && isset($arrloc) && isset($arrphoto) && isset($arridm)){
	$date = implode(",",$arrdate);
	$idm = implode(",",$arridm);
	$loc = implode(",",$arrloc);
	$photo = implode(",",$arrphoto);
}else{
	$date = "";
	$idm = "";
	$loc = "";
	$photo = "";
}

?>

<script language="javascript" type="text/javascript">
function konfirmdel(nama,id,mgLV) {
	if (mgLV==1){
		var answer = confirm("Yakin ingin menghapus admin '"+nama+"' ?");
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
		var answer = confirm("Yakin ingin menghapus Admin yang dipilih ? \n pilih 'OK' untuk lanjut \n pilih 'Cancel' untuk kembali ");
		if (answer==0) {
			return false;
		}else{
			document.tablelist.submit();
		}
	}else{
		document.tablelist.submit();
	}
}
$(function () {
		$('#container').highcharts({
				chart: {
						type: 'spline'
				},
				title: {
						text: 'Amplified'
				},
				subtitle: {
						text: 'Log Event <?php echo $namaevent; ?>'
				},
				xAxis: {
					categories: [ <?php echo $date;	?> ]
				},
				yAxis: {
						title: {
								text: 'Pengunjung'
						},
						labels: {
								formatter: function() {
										return this.value //+'°'
								}
						}
				},
				tooltip: {
						crosshairs: true,
						shared: true
				},
				plotOptions: {
						spline: {
								marker: {
										radius: 4,
										lineColor: '#666666',
										lineWidth: 1
								}
						}
				},
				series: [{
						name: 'Member',
						marker: {
								symbol: 'circle'
						},
						data: [<?php echo $idm; ?>]

				}, {
						name: 'Poin Added',
						marker: {
								symbol: 'diamond'
						},
						data: [<?php echo $loc; ?>]
				}, {
						name: 'Photobooth',
						marker: {
								symbol: 'square'
						},
						data: [<?php echo $photo; ?>]
				}]
		});
});

</script>


<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?php //untuk pencarian
	$selpilih = 0;
	$pilihsql = search("nama_event",1);
	
	
	//sql buat tampil Event
	$strsql = "
		SELECT SQL_CALC_FOUND_ROWS * FROM ms_event_arsip WHERE id_event = ".$id." order by cretime desc LIMIT ".$rsFirst.",".$rowsPerPage;
	$result = $db->get_results($strsql);
	$maxPage = get_maxpage($rowsPerPage);
	
	$rowcount = $db->get_row("select count(id_event_arsip) as count from ms_event_arsip where id_event = ".$id);
	if($rowcount){
		$peserta = $rowcount->count;
	}else{
		$peserta = 0;
	}

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

	<div class="tablenav">			 			

		<div class="alignleft actions">								
		
			<p style="float:left;margin:0 0 0 10px;padding:0;" class="submit"><a href="report.php?id=<?php echo $id; ?>" style="text-decoration:none;"><input type="button" name="submit" class="button-primary" value="Export" /></a> Export to Excel </p>
			
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
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		<table class="widefat" cellspacing="0" width="100%" style="margin-bottom:10px;">			
			<thead>
				<tr>
					<th>Total Peserta = <?php echo $peserta; ?></th>
				</tr>
			</thead>
		</table>
		<table class="widefat" cellspacing="0" width="100%">			
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Member</th>
          <th>Nama Event</th>
          <th>Poin Added</th>
          <th>Photobooth</th>
          <th>Sequence</th>
          <th>Cretime</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>No</th>
					<th>Nama Member</th>
          <th>Nama Event</th>
          <th>Poin Added</th>
          <th>Photobooth</th>
          <th>Sequence</th>
          <th>Cretime</th>
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
						$rownama = $db->get_row("select nama from ms_member where id_member = ".$row->id_member);
						if($rownama){
							$namamember = $rownama->nama;
						}else{
							$namamember = "-";
						}
						
						$rowevent = $db->get_row("select nama_event from ms_event where id_event = ".$row->id_event);
						if($rowevent){
							$namaevent = $rowevent->nama_event;
						}else{
							$namaevent = "-";
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
						echo "<td class=\"column-center\">".$namamember."</td>";
						echo "<td class=\"column-center\">".$namaevent."</td>";
						echo "<td class=\"column-center\">".$row->location."</td>";
						echo "<td class=\"column-center\">".$row->photobooth."</td>";
						echo "<td class=\"column-center\">".$row->sequence."</td>";
						echo "<td class=\"column-center\">".$vartglcomplete."</td>";
						
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
