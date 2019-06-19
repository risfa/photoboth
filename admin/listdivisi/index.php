<?php

session_start();
error_reporting(error_reporting() & ~E_STRICT);
$JudulHead = "Admin List Divisi";
$strCss = "initial";
$bodyid = "divisi";
$hal = "listdivisi";
$txtlist = "Divisi";
$numakses = 25;
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
//set all command
//doaction

if(isset($_POST["goaction"])){
	if($_POST["action"]!=0){
		//delete
		switch($_POST["action"]){
			case "1" : $job = "delete";break;
			default : $job = "";
		}
	}elseif($_POST["action2"]!=0){
		//delete
		switch($_POST["action2"]){
			case "1" : $job = "delete";break;
			default : $job = "";	
		}
	}else{
		$job = "";
	}		
	
	//delete job
	if($job=="delete"){
		if(isset($_POST["cb"])){
			for($i=0;$i<count($_POST["cb"]);$i++){
				directdeletepic("ms_admin_permit","iddiv",$_POST["cb"][$i],"vbanner");
			}
		}
	}
	
}		
//end set all command


//setting paging
$page = set_page();

$rowsPerPage = 20;
$rsFirst = $rowsPerPage*($page-1);
//end setting paging


(!isset($_GET["cre"]) || !is_numeric($_GET["cre"])) ? $pcre = 0 : $pcre = $_GET["cre"];
switch ($pcre) {
	case 1 :
		$sqlcre = " order by vdiv desc ";
		break;
	case 2 :
		$sqlcre = " order by cretime asc ";
		break;
	case 3 :
		$sqlcre = " order by cretime desc ";
		break;
	default :
		$sqlcre = " order by vdiv asc ";
}

$param = "&amp;cre=".$pcre;

//delete berita
del_query("ms_admin_permit","iddiv");
		
require_once("".$pathdir."layout_header.php");
?>
<script language="javascript" type="text/javascript">
function konfirmdel(nama,id,mgLV) {
	if (mgLV==1){
		var answer = confirm("Yakin ingin menghapus Divisi '"+nama+"' ?");
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
		var answer = confirm("Yakin ingin menghapus Divisi yang dipilih ? \n pilih 'OK' untuk lanjut \n pilih 'Cancel' untuk kembali ");
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

<div class="wrap">
	<div id="icon-user-edit" class="icon32"><br />
	</div>
	<h2>List <?=$txtlist;?></h2>
		
		<?php
		
		//untuk pencarian
		$selpilih = 0;
		$pilihsql = search("vdiv",1);		
		/*	$pilihsql
			.$sqllevel
			.$sqlcre."*/
		$strsql = "
			SELECT SQL_CALC_FOUND_ROWS 
				iddiv as id
				, vdiv 
				, UNIX_TIMESTAMP(cretime) as cretime
			FROM ms_admin_permit
			WHERE 1=1 ".
			"".$pilihsql."".
			"".$sqlcre."".
			"LIMIT ".$rsFirst.",".$rowsPerPage;
		
		$result = $db->get_results($strsql);
		
		$maxPage = get_maxpage($rowsPerPage);		
		?>
		
		<!--menu atas-->
		<ul class="subsubsub">
			<li><a href='index.php'  class="current">List <?=$txtlist?> <span class="count">(<?=$_SESSION["allrows"];?> Record)</span></a> |</li>
			<li><a href='add.php' >Add Divisi</a> |</li>
			<li><a href='../logout.php' >Logout</a></li>
		</ul>
		
		
		<div class="tablenav">			 			
			<div class="alignleft actions">								
				<select onchange="location='index.php?cre='+this.options[this.selectedIndex].value">
					<option value='0'>Nama Divisi A-Z</option>
					<option value='1' <?=issel($pcre,1,"selected","")?>>Nama Divisi Z-A</option>
					<option value='2' <?=issel($pcre,2,"selected","")?>>Addtime terbaru </option>
					<option value='3' <?=issel($pcre,3,"selected","")?>>Addtime terlama </option>
				</select>				
			</div>
			<div class="menuatas-search">		
				<form action="index.php?job=cari<?=$param;?>" method="post" >						
					<select name='selpilih' >
						<option value='0'>Nama</option>
					</select>								
					<input type="text" class="search-input" id="post-search-input" size="10" name="txtcari" value="" />								
					<input type="submit" id="post-query-submit" value="Search" class="button-secondary" />
				</form>
			</div>
		</div>
			
		<form id="tablelist" name="tablelist" action="index.php" method="post">
		<input type="hidden" name="goaction" />
		<div class="tablenav">		
			<div class="clear menuatas-doaction">
				<select name="action">
					<option value="0" selected="selected">Actions</option>
					<option value="1">Delete</option>
				</select>
				<input type="button" value="Apply" name="doaction" id="doaction" class="button-secondary action" onclick="konfirmdel2();" />
			</div>	
			
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
        	<th id="cb" class="check-column" width="3%" ><input id="cball" name="cball" type="checkbox" onclick="checkall();" /></th>
          <th>No</th>
          <th>Nama Divisi</th>
          <th>Create Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tfoot>
      	<tr>
        	<th id="cb" class="check-column" width="3%" ><input id="cball" name="cball" type="checkbox" onclick="checkall();" /></th>
          <th>No</th>
          <th>Nama Divisi</th>
          <th>Create Time</th>
          <th>Action</th>
        </tr>
 	    </tfoot>      
      <tbody id="the-comment-list">
      	<?php
				$i=1;
				if($result){
					foreach ($result as $row) {
						$no = $i++ +(($page-1)*$rowsPerPage);
												
						echo "
							<tr id=\"data-".$no."\">
								<th class=\"check-column\"><input type=\"checkbox\" name=\"cb[]\" value=\"".$row->id."\" /></th>
								<td class=\"column-center\">".$no.".</td>
								<td class=\"column-center\">".$row->vdiv."</td>
								<td class=\"column-center\">".date('j F Y',$row->cretime)."</td>								
								<td class=\"column-center\"><a href=\"edit.php?id=".$row->id."\">Edit</a> | <a href=\"update.php?id=".$row->id."\">Update</a> | <a href=\"#\" class=\"delete\" onclick=\"konfirmdel('".$row->vdiv."',".$row->id.",".$_SESSION["ssLV"].")\">Delete</a></td>
							</tr>
						";
						
					}
				}
				?>
      	
      </tbody>    
    </table>
		
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action2">
					<option value="0" selected="selected">Actions</option>
					<option value="1">Delete</option>
				</select>
				<input type="button" value="Apply" name="doaction2" id="doaction2" class="button-secondary action" onclick="konfirmdel2();"  />
			</div>
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
		
	if (<?=$_SESSION["allrows"]?>==1) {
		document.tablelist.cb.checked=document.getElementById('cball').checked;
	} else {
		for (i=0;i<<?=$_SESSION["allrows"]?>;i++) {
			document.tablelist.cb.checked=document.getElementById('cball').checked;
		}
	}
	
}
</script>

<?php 
set_session();
require_once("../layout_footer.php");
?>
