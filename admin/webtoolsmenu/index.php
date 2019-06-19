<?php
session_start();
error_reporting(error_reporting() & ~E_STRICT);
$JudulHead = "Admin Menu";
$strCss = "initial;function";
$bodyid = "adminmenu";
$hal = "listmenu";
$pathdir2 = "../../";
$curdir = getcwd ();
chdir($pathdir2);
//require_once('global.php');
chdir ($curdir);
//require_once("../../query/function.php");
$numakses = 26;
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");

//delete berita
del_query("ms_admin_menu","idmenu");

//ganti status tampil
update_query("ms_admin_menu","ttampil","idmenu",1);

//ganti status shortcut
//update_query("ms_admin_menu","tshortcut","idmenu",2);
require_once("".$pathdir."layout_header.php");
?>
<script language="javascript" type="text/javascript">
function konfirmdel(nama,id,mgLV) {
	if (mgLV==1){
		var answer = confirm("Yakin ingin menghapus Menu '"+nama+"' ?");
		if (answer!=0) {
			window.location = "index.php?job=delete&id="+id+"";
		} else {
			window.location = "index.php";
		}
	}else{
		alert("Admin Level 0 tidak dapat menghapus data.");
	}
}
</script>
<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?php	
		//setting paging
		$page = set_page();
	
		$rowsPerPage = 20;
		$rsFirst = $rowsPerPage*($page-1);
		//end setting paging
		
		(!isset($_GET["tampil"]) || !is_numeric($_GET["tampil"])) ? $ptampil = 2 : $ptampil = $_GET["tampil"];
		($ptampil == 2) ? $sqltampil = "" : $sqltampil = " and m.ttampil=".$db->escape($ptampil)." ";
		
		(!isset($_GET["level"]) || !is_numeric($_GET["level"])) ? $plevel = 0 : $plevel = $_GET["level"];
		($plevel == 0) ? $sqllevel = "" : $sqllevel = " and m.ilevel=".$db->escape($plevel)." ";
		
		
		//untuk pencarian
		$selpilih = 0;
		$pilihsql = search("m.vmenu",1);	
		
		$param = "&amp;tampil=".$ptampil;
		
		$strsql = "
				SELECT SQL_CALC_FOUND_ROWS m.idmenu, m.vmenu, m.ilevel, m.numakses , m.vurl, m.tposisi, m.ttampil, m2.vmenu as vparent
				FROM ms_admin_menu m LEFT OUTER JOIN ms_admin_menu m2 ON m2.idmenu = m.idparent 
				WHERE (1 = 1) ".$pilihsql.$sqltampil.$sqllevel." 
				ORDER BY m.ilevel, m.tposisi
				LIMIT ".$rsFirst.",".$rowsPerPage;		

		$result = $db->get_results($strsql);
		
		$maxPage = get_maxpage($rowsPerPage);	
	?>
	
	<h2>List Admin Menu Setting</h2>
	<!--filter menu-->
	<ul class="subsubsub">
		<li><a href='index.php'>List Menu <span class="count">(<?=$_SESSION["allrows"];?> Record)</span></a> |</li>
		<li><a href='add.php' >Add Menu</a> |</li>
		<li><a href='../logout.asp' >Logout</a></li>
	</ul>
	<!--end menu-->
	
	<!--filter menu-->
	<div class="tablenav">			 			
		<div class="alignleft actions">								
			<select onchange="location='index.php?tampil=<?php echo $ptampil;?>&amp;level='+this.options[this.selectedIndex].value;">
				<option value='0' <?=issel($plevel,0,"selected","")?>>Semua Level</option>
				<option value='1' <?=issel($plevel,1,"selected","")?>>Level 1</option>
				<option value='2' <?=issel($plevel,2,"selected","")?>>Level 2</option>
			</select>
      			
			<select onchange="location='index.php?level=<?php echo $plevel;?>&amp;tampil='+this.options[this.selectedIndex].value;">
				<option value='2' <?=issel($ptampil,2,"selected","")?>>Semua Menu</option>
				<option value='1' <?=issel($ptampil,1,"selected","")?>>Menu yang tampil</option>
				<option value='0' <?=issel($ptampil,0,"selected","")?>>Menu tidak tampil</option>
			</select>
		</div>
		<div class="menuatas-search">		
			<form action="index.php?job=cari<?=$param;?>" method="post" >					
				<select name='selpilih' >
					<option value='0'>Menu</option>
				</select>										
				<input type="text" class="search-input" id="post-search-input" size="20" name="txtcari" value="" />								
				<input type="submit" id="post-query-submit" value="Search" class="button-secondary" />
			</form>
		</div>
	</div>
	<!--end filter menu-->
	
	<!--start form action-->
	<form id="tablelist" name="tablelist" action="index.php" method="post">
		<input type="hidden" name="goaction" value="goaction" />
		<!--start action-->
		<div class="tablenav">
			
			<!--pagging-->
			<?php						
				//print paging			
				echo paging($maxPage);
			?>
			<!--end pagging-->

			<div class="clear"></div>
		</div>
		<!--end action-->		
		<div class="clear"></div>
		
		<table class="widefat" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Menu</th>
					<th>Level</th>
					<th>Parent</th>
          <th>Kode Akses</th>
					<th>URL</th>
					<th>Posisi</th>
					<th>Tampil</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>No</th>
					<th>Nama Menu</th>
					<th>Level</th>
					<th>Parent</th>
          <th>Kode Akses</th>
					<th>URL</th>
					<th>Posisi</th>
					<th>Tampil</th>
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
						$a=$page*$rowsPerPage-1;
					}
					foreach($result as $row){	
						
						
						if ($row->ttampil==1){
							$txttampil="<a href=\"index.php?job=gantitampil1&amp;flag=".$row->ttampil."&amp;id=".$row->idmenu.$param."\"><img src=\"../images/sticky.png\"></a>";
						}else{
							$txttampil="<a href=\"index.php?job=gantitampil1&amp;flag=".$row->ttampil."&amp;id=".$row->idmenu.$param."&amp;tampil=0\"><img src=\"../images/unsticky.png\" alt=\"tidak tampil\" /></a>";
						}
						
					
						
						if(is_null($row->vparent) || $row->vparent==""){
							$txtparent = "<td class=\"column-center\">-</td>";
						}else{
							$txtparent = "<td>".$row->vparent."</td>";
						}
						
						echo "<tr id=\"data-".$a."\">";
						echo "<td class=\"column-center\">".$a.".</td>";
						echo "<td>".$row->vmenu."</td>";
						echo "<td class=\"column-center\">Level ".$row->ilevel."</td>";
						echo $txtparent;
						echo "<td class=\"column-center\">".$row->numakses."</td>";
						echo "<td>".$row->vurl."</td>";
						echo "<td class=\"column-center\">".$row->tposisi."</td>";
						echo "<td class=\"column-center\">".$txttampil."</td>";
						echo "<td class=\"column-center\"><a href=\"edit.php?id=".$row->idmenu."\">Edit</a> | <a href=\"#\" onclick=\"konfirmdel('".$row->vmenu."',".$row->idmenu.",".$_SESSION["ssLV"].")\" class=\"delete\">Delete</a></td>";
						echo "</tr>";
						$a++;
					}
				}else{
					echo "<td class=\"column-center\" colspan=\"20\">Data Belum Tersedia</td>";
				}
				
			?>
				
			</tbody>
		</table>
		<!--start action2-->
			<div class="tablenav">  
			<!--pagging-->

			
			<!--end pagging-->
			</div>
		
		<!--end action2-->
	</form>	
	<!--end form action-->
	
	<br class="clear" />
</div>
<!--end warp-->

<?php 
set_session();
require_once("../layout_footer.php");
?>