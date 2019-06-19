<?php
session_start();
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
$opt = $db->query("SET character_set_results=utf8");

$JudulHead = "Member";
$strCss = "initial;function";
$bodyid = "member";
$hal = "member";
$numakses = 6;

$txtlist = "Member";

//setting paging
$page = set_page();

$rowsPerPage = 20;
$rsFirst = $rowsPerPage*($page-1);	
//end setting paging

//order
$vorder = 0;
if(isset($_GET["order"])){
	$vorder = $_GET["order"];
}
switch($vorder){
	case 1 : $sqlorder = " ORDER BY cretime ASC";break;
	case 2 : $sqlorder = " ORDER BY nama ASC ";break;
	case 3 : $sqlorder = " ORDER BY nama DESC ";break;
	default : $sqlorder = " ORDER BY cretime DESC ";
}

$param = "&amp;order=".$vorder;

require_once("".$pathdir."layout_header.php");

?>
<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?php //untuk pencarian
	$selpilih = 0;
	$pilihsql = search("nama",1);
	
	
	//sql buat tampil konfirmasi
	$strsql = "
		SELECT SQL_CALC_FOUND_ROWS * FROM ms_member WHERE 1=1  ".$pilihsql." ".$sqlorder." LIMIT ".$rsFirst.",".$rowsPerPage;
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

	<div class="tablenav">			 			
  	<div class="alignleft actions">								 
	
		<!--order by-->
    <select name="selkategori" onChange="location='index.php?order='+this.options[this.selectedIndex].value;">
      <option value='0'>Tanggal Terbesar</option>
      <option value='1' <?php echo issel(1,$vorder,"selected","")?>>Tanggal Terkecil</option>
			<option value='2' <?php echo issel(2,$vorder,"selected","")?>>Sort Nama A-Z</option>
      <option value='3' <?php echo issel(3,$vorder,"selected","")?>>Sort Nama Z-A</option>
    </select>
    <!--end order by-->
		
		</div>
		<div class="menuatas-search" >		
			<form action="index.php?job=cari<?php echo $param;?>" method="post" >					
				<select name='selpilih' >
        	<option value='0'>Nama</option>
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
					<th>Event</th>
          <th>RFID</th>
          <th>Data Diri</th>
          <th>Facebook</th>
          <th>Twitter</th>
          <th>Cretime</th>
          <th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
        	<th>No</th>
					<th>Event</th>
          <th>RFID</th>
          <th>Data Diri</th>
          <th>Facebook</th>
          <th>Twitter</th>
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
						
						$strsqlevent = "select nama_event from ms_event where id_event = ".$row->id_event;
						$rowevent = $db->get_row($strsqlevent);
						if($rowevent){
							$event = $rowevent->nama_event;
						}else{
							$event = "-";
						}
						
						$time = strtotime($row->cretime);
						$vartgl = date("d", $time);
						$varbln = date("M", $time);
						$varthn = date("Y", $time);
						$varjam = date("H", $time);
						$varmnt = date("i", $time);
						$vardtk = date("s", $time);
						$vartglcomplete=$vartgl." ".$varbln." ".$varthn."<br />".$varjam.":".$varmnt.":".$vardtk;
						
						if ($row->twitter_name ==""){
							$namatwitter="-";
						}else{
							$namatwitter="@".$row->twitter_name;
						}
						
						echo "<tr id=\"data-".$a."\">";
						echo "<td class=\"column-center\">".$a."</td>";
						echo "<td class=\"column-center\">".$event."</td>";
						echo "<td class=\"column-center\">".$row->id_rfid."</td>";
						echo "<td class=\"column-center\"><strong>Nama : </strong>".$row->nama."<br/><strong>Telp : </strong>".$row->telp."<br/><strong>HP : </strong>".$row->hp."<br/><strong>Alamat : </strong>".$row->alamat."<br/><strong>Email : </strong>".$row->email."</td>";
						echo "<td class=\"column-center\"><strong>FB ID : </strong>".$row->fb_id."<br/><strong>FB Email : </strong>".$row->fbemail."</td>";
						echo "<td class=\"column-center\"><strong>Twitter ID : </strong>".$row->twitter_id."<br/><strong>Twitter Name : </strong>".$namatwitter."<br/></td>";
						echo "<td class=\"column-center\">".$vartglcomplete."</td>";
						echo "<td class=\"column-center\"><a href=\"detail.php?id=".$row->id_member		."\">Detail</a></td>";
						
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
