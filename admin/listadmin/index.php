<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);
$JudulHead = "List Admin";
$strCss = "initial";
$bodyid = "admin";
$hal = "listadmin";
$txtlist = "Admin";

$numakses = 24;

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
				directdeletepic("ms_admin","id",$_POST["cb"][$i],"");
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

//set level
$sqllevel = "";
$plevel = 2;
if (isset($_GET["level"]) && is_numeric($_GET["level"])){
	$plevel = $_GET["level"];
	if($plevel!=2){
		$sqllevel = " AND s.lvl=".$db->escape($plevel)." ";
	}
}
//end set level

//set order
$sqlorder = "";
$porder = 0;
if(isset($_GET["order"]) && is_numeric($_GET["order"])){
		$porder = $_GET["order"];
}
switch($porder){
	case 1 : $sqlorder = " ORDER BY s.username desc, s.id desc ";break;
	case 2 : $sqlorder = " ORDER BY s.name asc, s.id desc ";break;
	case 3 : $sqlorder = " ORDER BY s.name desc, s.id desc ";break;
	default : $sqlorder = " ORDER BY s.username asc, s.id desc ";
}
//end set order

//set divisi
$sqldiv = "";
$pdiv = 0;
if(isset($_GET["div"]) && is_numeric($_GET["div"])){
	$pdiv = $_GET["div"];
}
if($pdiv>=1 and $pdiv<=6){
	$sqldiv = " AND s.tdiv=".$db->escape($pdiv)." ";
}else{
	$sqldiv = "";
}
//end set divisi


$param = "&amp;level=".$plevel."&amp;order=".$porder."&amp;div=".$pdiv;


//ganti aktif
if(isset($_GET["job"]) && $_GET["job"]=="gantiaktif"){
	if(isset($_GET["id"]) && isset($_GET["aktif"]) && is_numeric($_GET["id"])  && is_numeric($_GET["aktif"])){
		if($_GET["aktif"]==1){
			$strsql = "UPDATE ms_admin SET taktif=0, modtime=NOW(), modby = '".$db->escape($_SESSION["ssusername"])."' WHERE id=".$db->escape($_GET["id"])."";
		}else{
			$strsql = "UPDATE ms_admin SET taktif=1, modtime=NOW(), modby = '".$db->escape($_SESSION["ssusername"])."' WHERE id=".$db->escape($_GET["id"])."";
		}
		$updateaktif = $db->query($strsql);
		header("Location: index.php?page=".$page.str_replace("&amp;","&",$param)."");
	}
}

//delete berita
del_query("ms_admin","id");
		
require_once("".$pathdir."layout_header.php");
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


</script>

<div class="wrap">
	<div id="icon-user-edit" class="icon32"><br />
	</div>
	<h2>List <?=$txtlist;?></h2>
		
		<?php
		
		//untuk pencarian
		$selpilih = 0;
		$pilihsql = search("s.name,s.username",2);		
		
		$strsql = "
			SELECT s.id, s.name, s.username, s.lvl, s.lastlogin, s.vip, s.taktif, p.vdiv 
			FROM ms_admin s JOIN ms_admin_permit p ON p.iddiv=s.tdiv 
			WHERE 1=1 ".$sqllevel.$sqldiv.$pilihsql.$sqlorder."
			LIMIT ".$rsFirst.",".$rowsPerPage;
		
		$result = $db->get_results($strsql);
		
		$maxPage = get_maxpage($rowsPerPage);		
		?>
		
		<!--menu atas-->
		<ul class="subsubsub">
			<li><a href='index.php'  class="current">List <?=$txtlist?> <span class="count">(<?=$_SESSION["allrows"];?> Record)</span></a> |</li>
			<li><a href='add.php' >Add Admin</a> |</li>
			<li><a href='../logout.php' >Logout</a></li>
		</ul>
		
		
		<div class="tablenav">			 			
			<div class="alignleft actions">								
				<select onchange="location='index.php?div=<?php echo $pdiv;?>&amp;level=<?php echo $plevel;?>&amp;order='+this.options[this.selectedIndex].value;">
          <option value='0'>Berdasarkan Username A-Z</option>
          <option value='1' <?php echo issel($porder,1,"selected","")?>>Berdasarkan Username Z-A</option>
          <option value='2' <?php echo issel($porder,2,"selected","")?>>Berdasarkan Nama A-Z</option>
          <option value='3' <?php echo issel($porder,3,"selected","")?>>Berdasarkan Nama Z-A</option>
        </select>
        
        <select onchange="location='index.php?level='+this.options[this.selectedIndex].value+'&amp;order=<?php echo $porder;?>&amp;div=<?php echo $pdiv;?>'">
          <option value='2' <?php echo issel($plevel,2,"selected","")?>>Semua Level</option>
          <option value='1' <?php echo issel($plevel,1,"selected","")?>>Level 1</option>
          <option value='0' <?php echo issel($plevel,0,"selected","")?>>Level 0</option>
        </select>			
        
        <select onchange="location='index.php?div='+this.options[this.selectedIndex].value+'&amp;order=<?php echo $porder;?>&amp;level=<?php echo $plevel;?>'">
          <option value='0' >Semua Divisi</option>
          <?php
          $strsql = "SELECT iddiv ,vdiv FROM ms_admin_permit";
          $resultdiv = $db->get_results($strsql);
          if($resultdiv){
            foreach($resultdiv as $rowdiv){
              echo "<option value=\"".$rowdiv->iddiv."\" ".issel($pdiv,$rowdiv->iddiv,"selected","").">".$rowdiv->vdiv."</option>";
            }
          }
          ?>
				</select>	
			</div>
			<div class="menuatas-search">		
				<form action="index.php?job=cari<?=$param;?>" method="post" >						
					<select name='selpilih' >
						<option value='0'>Nama</option>
						<option value="1">Username</option>
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
          <th>Nama</th>
          <th>Username</th>
          <th>Last Login</th>
          <th>Aktif</th>
          <th>Action</th>
        </tr>
      </thead>
      <tfoot>
      	<tr>
        	<th class="check-column" width="3%" ><input type="checkbox" onclick="checkall();" /></th>
          <th>No</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Last Login</th>
          <th>Aktif</th>
          <th>Action</th>
        </tr>
 	    </tfoot>      
      <tbody id="the-comment-list">
      	<?php
				$i=1;
				if($result){
					foreach ($result as $row) {
						$no = $i++ +(($page-1)*$rowsPerPage);
						
						if ($row->taktif==1) {
							$txttaktif = "<img border=\"0\" src=\"/Admin/images/sticky.png\" alt=\"Iya\" />";
						} else {
							$txttaktif = "-";
						}
						
						if(!is_null($row->vip) && $row->vip!=""){
							$txtip = "(".$row->vip.")";
						}else{
							$txtip = "";
						}
						
						echo "
							<tr id=\"data-".$no."\">
								<th class=\"check-column\"><input type=\"checkbox\" name=\"cb[]\" value=\"".$row->id."\" /></th>
								<td class=\"column-center\">".$no.".</td>
								<td class=\"column-center\">".$row->name."</td>
								<td class=\"column-center\">".$row->username."<Br />Level ".$row->lvl."<br />Divisi ".$row->vdiv."</td>
								<td class=\"column-center\">".$row->lastlogin."<br />".$txtip."</td>
								<td class=\"column-center\"><a href=\"index.php?job=gantiaktif&amp;page=".$page."&amp;aktif=".$row->taktif."&amp;id=".$row->id.$param."\">".$txttaktif."</a></td>
								<td class=\"column-center\"><a href=\"edit.php?id=".$row->id."\">Edit</a><Br /><br /><a href=\"#\" class=\"delete\" onclick=\"konfirmdel('".$row->username."',".$row->id.",".$_SESSION["ssLV"].")\">Delete</a></td>
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
