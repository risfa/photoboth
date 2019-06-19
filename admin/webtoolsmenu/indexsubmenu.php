<?php
ini_set('display_errors', 1);
$JudulHead = "Web Tools Forum";
$strCss = "initial;function";
$bodyid = "Adminmenu";
$hal = "listsubmenu";
$linkpage = "indexsubmenu.php";
session_start();

$pathdir2 = "../../";
$curdir = getcwd ();
chdir($pathdir2);
//require_once('global.php');
chdir ($curdir);
//require_once("../../query/function.php");

$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."layout_header.php");
?>
<script language="javascript" type="text/javascript">
function konfirmdel(nama,id,mgLV) {
	if (mgLV==1){
		var answer = confirm("Yakin ingin menghapus SubMenu '"+nama+"' ?");
		if (answer!=0) {
			window.location = "indexsubmenu.php?job=delete&id="+id+"";
		} else {
			window.location = "indexsubmenu.php";
		}
	}else{
		alert("Admin Level 0 tidak dapat menghapus data.");
	}
}
</script>
<!--start warp-->
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div> 
	<?	
		//setting paging
		$page = set_page();
	
		$rowsPerPage = 20;
		$rsFirst = $rowsPerPage*($page-1);
		//end setting paging
		
		(!isset($_GET["tampil"]) || !is_numeric($_GET["tampil"])) ? $ptampil = 2 : $ptampil = $_GET["tampil"];
		($ptampil == 2) ? $sqltampil = "" : $sqltampil = " and s.ttampil=".$db->escape($ptampil)." ";
		
		(!isset($_GET["cre"]) || !is_numeric($_GET["cre"])) ? $pcre = 0 : $pcre = $_GET["cre"];
		switch ($pcre) {
			case 1 :
				$sqlcre = " order by s.idsubmenu desc ";
				break;
			case 2 :
				$sqlcre = " order by s.vsubmenu asc ";
				break;
			case 3 :
				$sqlcre = " order by s.vsubmenu desc ";
				break;
			default :
				$sqlcre = " order by s.idsubmenu asc ";
		}
		
		//delete sub menu
		del_query("webtool_submenu","idsubmenu");
		
		//ganti status tampil
		update_query("webtool_submenu","ttampil","idsubmenu",1);
		
		//ganti status shortcut
	//	update_query("webtool_menu","tshortcut","idmenu",2);
		
		//untuk pencarian
		$selpilih = 0;
		$pilihsql = search("vsubmenu,vhal",2);	
		
		$param = "&amp;tampil=".$ptampil."&amp;cre=".$pcre;
		
		$strsql = "
				SELECT SQL_CALC_FOUND_ROWS m.idmenu, m.vmenu, s.idsubmenu, s.vsubmenu, s.vhal, s.vurl, s.ttampil, s.tposisi
				FROM webtool_submenu s INNER JOIN webtool_menu m ON s.idmenu = m.idmenu
				WHERE 1=1 "
				.$pilihsql
				.$sqltampil
				.$sqlcre
				."LIMIT ".$rsFirst.",".$rowsPerPage;
		
				
		$result = $db->get_results($strsql);
		
		//echo $strsql;
		
		$maxPage = get_maxpage($rowsPerPage);	
		
	?>
	
	<h2>List Admin SubMenu Setting</h2>
	<!--filter menu-->
	<ul class="subsubsub">
		<li><a href='indexsubmenu.php'>List SubMenu <span class="count">(<?=$_SESSION["allrows"];?> Record)</span></a> |</li>
		<li><a href='addsubmenu.php' >Add SubMenu</a> |</li>
		<li><a href='../logout.asp' >Logout</a></li>
	</ul>
	<!--end menu-->
	
	<!--filter menu-->
	<div class="tablenav">			 			
		<div class="alignleft actions">								
			<select onchange="location='indexsubmenu.php?tampil=<?=$ptampil; ?>&amp;cre='+this.options[this.selectedIndex].value;">
				<option value='0'>Berdasarkan Posisi Menu terkecil</option>
				<option value='1' <?=issel($pcre,1,"selected","")?>>Berdasarkan Posisi Menu terbesar</option>
				<option value='2' <?=issel($pcre,2,"selected","")?>>Berdasarkan Nama Menu A-Z</option>
				<option value='3' <?=issel($pcre,3,"selected","")?>>Berdasarkan Nama Menu Z-A</option>
			</select>
			
			<select onchange="location='indexsubmenu.php?cre=<?=$pcre?>&amp;tampil='+this.options[this.selectedIndex].value;">
				<option value='2' <?=issel($ptampil,2,"selected","")?>>Semua Menu</option>
				<option value='1' <?=issel($ptampil,1,"selected","")?>>Menu yang tampil</option>
				<option value='0' <?=issel($ptampil,0,"selected","")?>>Menu tidak tampil</option>
			</select>
		</div>
		<div class="menuatas-search">		
			<form action="indexsubmenu.php?job=cari<?=$param;?>" method="post" >					
				<select name='selpilih' >
					<option value='0'>SubMenu</option>
					<option value="1">ID Halaman</option>
				</select>										
				<input type="text" class="search-input" id="post-search-input" size="20" name="txtcari" value="" />								
				<input type="submit" id="post-query-submit" value="Search" class="button-secondary" />
			</form>
		</div>
	</div>
	<!--end filter menu-->
	
	<!--start form action-->
	<form id="tablelist" name="tablelist" action="indexsubmenu.php" method="post">
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
					<th>Nama Sub Menu</th>
					<th>ID Halaman</th>
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
					<th>Nama Sub Menu</th>
					<th>ID Halaman</th>
					<th>URL</th>
					<th>Posisi</th>
					<th>Tampil</th>
					<th>Action</th>
				</tr>
			</tfoot>
			<tbody id="the-comment-list">
			<?
				if ($result) {
					// untuk keluarin nomor
					if ($page ==1) {
						$a=1;
					}else{
						$a=$page*$rowsPerPage-($rowsPerPage-1);
					}
					foreach($result as $row){	
						
						if ($row->ttampil==1){
							$txttampil="<a href=\"indexsubmenu.php?job=gantitampil1&amp;flag=".$row->ttampil."&amp;id=".$row->idsubmenu.$param."\"><img src=\"/Admin/images/sticky.png\"></a>";
						}else{
							$txttampil="<a href=\"indexsubmenu.php?job=gantitampil1&amp;flag=".$row->ttampil."&amp;id=".$row->idsubmenu.$param."&amp;tampil=0\"><img src=\"/Admin/images/unsticky.png\" alt=\"tidak tampil\" /></a>";
						}
						
						//if ($row->tshortcut==1){
//							$txtshortcut="<a href=\"indexsubmenu.php?job=gantitampil2&amp;flag=".$row->tshortcut."&amp;id=".$row->idmenu.$param."\"><img src=\"/Admin/images/sticky.png\"></a>";
//						}else{
//							$txtshortcut="<a href=\"indexsubmenu.php?job=gantitampil2&amp;flag=".$row->tshortcut."&amp;id=".$row->idmenu.$param."&amp;tampil=0\"><img src=\"/Admin/images/unsticky.png\" alt=\"tidak tampil\" /></a>";
//						}
						
						echo "<tr id=\"data-".$a."\">";
						echo "<td class=\"column-center\">".$a.".</td>";
						echo "<td class=\"column-center\">".$row->vmenu."</td>";
						echo "<td class=\"column-center\">".$row->vsubmenu."</td>";
						echo "<td class=\"column-center\">".$row->vhal."</td>";
						echo "<td class=\"column-center\">".$row->vurl."</td>";
						echo "<td class=\"column-center\">".$row->tposisi."</td>";
						echo "<td class=\"column-center\">".$txttampil."</td>";
						echo "<td class=\"column-center\"><a href=\"editsubmenu.php?id=".$row->idsubmenu."\">Edit</a> | <a href=\"#\" onclick=\"konfirmdel('".$row->vsubmenu."',".$row->idsubmenu.",".$_SESSION["ssLV"].")\" class=\"delete\">Delete</a></td>";
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
//set_session();
require_once("../layout_footer.php");
?>