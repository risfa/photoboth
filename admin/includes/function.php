<?php

function issel($a,$b,$c,$d){
	if($a==$b){
		return $c;
	}else{
		return $d;
	}
}

function iscek($a){
	if($a=="on"){
		return " checked";
	}
}


function CekValidAsc($parStr) {
	$valid = true;
	$arr1 = str_split($parStr);
	for ($i=0; $i < count($arr1); $i++) {
		if ( !((ord($arr1[$i])>=48 && ord($arr1[$i])<=57) || (ord($arr1[$i])>=65 && ord($arr1[$i])<=90) || (ord($arr1[$i])>=97 && ord($arr1[$i])<=122)) &&  ord($arr1[$i])!=32 ) {
			$valid = false;
		}
	}
	return $valid;
}

/*
validasi ini digunakan untuk memeriksa string
string hanya boleh terdiri dari angka A-Z a-z
*/
//cek special char
function CekValidAsc2($parStr) {
	$valid = true;
	$parStr = str_replace(" ","",$parStr);
	$arr1 = str_split($parStr);
	for ($i=0; $i < count($arr1); $i++) {
		if ( !((ord($arr1[$i])>=49 && ord($arr1[$i])<=57) || (ord($arr1[$i])>=65 && ord($arr1[$i])<=90) || (ord($arr1[$i])>=97 && ord($arr1[$i])<=122)) ) {
			$valid = false;
		}
	}
	return $valid;
}


//cek string
function CekValidStr($parStr) {
	$valid = true;
	$arr1 = str_split($parStr);
	for ($i=0; $i < count($arr1); $i++) {
		if (!((ord($arr1[$i])>=65 && ord($arr1[$i])<=90) || (ord($arr1[$i])>=97 && ord($arr1[$i])<=122) || ord($arr1[$i])==32) ) {
			$valid = false;
		}
	}

	return $valid;
}

function set_sessiontext($bnyktext,$strsql) {
	global $db,$input;
	if($strsql == "") {
		for($i=1 ; $i<=$bnyktext ; $i++) {
			(isset($_SESSION['frm'.$i])) ? $input[$i] = $_SESSION['frm'.$i] : $input[$i] = "";
		}
	} else {
		$result = $db->get_row($strsql);
		if($result) {
			//buat ambil field
			$i = 1;
			foreach($db->get_col_info("name") as $name) {
				$arr[$i] = $name;
				$i++;
			}
			$k=1;
			foreach($db->get_col_info("type") as $tipe) {
				$arrtipe[$k] = $tipe;
				$k++;
			}
									
			$k=1;
			for($j=1 ; $j<$i ; $j++) {
				if(isset($arrtipe[$j])) {
					$tipe = $arrtipe[$j];
				} else {
					$tipe = "";
				}
				if(strcmp($tipe,"datetime") == 0) {
					$dateTime = new DateTime($result->$arr[$j]); 
					$valued = intval($dateTime->format("d"));
					$input[$k] = $valued;
					$k++;
					$valuef = intval($dateTime->format("m"));
					$input[$k] = $valuef;
					$k++;
					$valuey = $dateTime->format("Y");
					$input[$k] = $valuey;
					$k++;
				} else {
					(isset($_SESSION['frm'.$k])) ? $input[$k] = $_SESSION['frm'.$k] : $input[$k] = $result->$arr[$j];
					$k++;
				}
			}
		}

		if (isset($_SESSION["txt".$bnyktext])) {
			$tampil = $_SESSION["txt".$bnyktext];
		} else {
			if(isset($result->ttampil)){
				if ($result->ttampil == 1) {
					$tampil = "on";
				} else {
					$tampil = "";
				}
			}else{
				$tampil = "";
			}
		}
		
	}
}

function set_session() {
	$mgName = $_SESSION["ssname"];
	$mgUsername = $_SESSION["ssusername"];
	$mgID = $_SESSION["ssid"];
	$mgLv = $_SESSION["ssLV"];
	$mgAkses = $_SESSION["ssakses"];
	session_unset();
	$_SESSION["ssname"] = $mgName;
	$_SESSION["ssusername"] = $mgUsername;
	$_SESSION["ssid"] = $mgID;
	$_SESSION["ssLV"] = $mgLv;
	$_SESSION["ssakses"] = $mgAkses;
}

function validate_page($numakses) {
	if (!isset($_SESSION["ssid"])) {
		header("Location: ../index.php");
		exit();
	} else if (!cekAkses($_SESSION["ssakses"], $numakses) && $_SESSION["ssLV"]!=1) {
		header("Location: ../gmpage.php?err=$numakses");
		exit();
	}
}

function set_page() {
	$page = 1;
	if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
		$page = $_GET["page"];
	}
	return $page;
}
/* 
	digunakan untuk menampilkan data [ Grid ]
	parameter yg dikirim :
	query = query yang ingin ditampilkan
	act = kolom brapa yang ingin menjadi link
	act2 = 
		1 = edit & delete
		2 = edit
		3 = detail & delete
		4 = detail 
*/
function set_list($query,$act,$act2) {
	global $db,$param,$edit,$maxPage,$page;
	
	$page = set_page();
	
	if($maxPage>0){
		if ($page > $maxPage) {header("Location: ".$edit.".php"); exit(); } 
	}
	
	$tableclass = "widefat";
	$thclass = "check-column";
	$tbodyid = "the-comment-list";
	$trid = "comment-";
	$traction = "column-edit";
	$dateclass = "column-date";
	$centerclass = "column-center";
	$imgtampil = "/Admin/images/sticky.png";
	$imgnontampil = "/Admin/images/unsticky.png";
	
	$tempact = "";
	//untuk membagi kolom yang mana yang ingin di link
	$arract = explode(",",$act);
	$i=0;
	
	$result = $db->get_results($query);
	if($result) {
		$temp = "<table class=\"".$tableclass."\" cellspacing=\"0\">";
		$temp = $temp."<thead>";
		$temp = $temp."<tr>";
		$i=0;
		
		//untuk mengambil nama field
		$temp = $temp."<th id=\"cb\" class=\"".$thclass."\" width=\"3%\" ><input id=\"cball\" name=\"cball\" type=\"checkbox\" onclick=\"checkall();\" /></th>";
		
		foreach($db->get_col_info("name") as $name) {
			if($i>1) {
				$temp = $temp."<th>".$name."</th>";
			}
			$arr[$i] = $name;
			$i++;
		}
		$i=0;
		foreach($db->get_col_info("type") as $tipe) {
			$arrtipe[$i] = $tipe;
			$i++;
		}
		
		$temp = $temp."<th>Action</th>";
		$temp = $temp."</tr>";
		$temp = $temp."</thead>";		
		$temp = $temp."<tfoot>";
		$temp = $temp."<tr>";
		$i=0;
		
		//untuk mengambil nama field
		$temp = $temp."<th class=\"".$thclass."\"><input type=\"checkbox\" /></th>";
		
		foreach($db->get_col_info("name") as $name) {
			if($i>1) {
				$temp = $temp."<th>".$name."</th>";
			}
			$arr[$i] = $name;
			$i++;
		}
		$i=0;
		foreach($db->get_col_info("type") as $tipe) {
			$arrtipe[$i] = $tipe;
			$i++;
		}
		
		$temp = $temp."<th>Action</th>";
		$temp = $temp."</tr>";
		$temp = $temp."</tfoot>";
		
		//untuk menampilkan value dari database
		$i=1;
		$judul = "";
		
		$temp = $temp."<tbody id=\"".$tbodyid."\">";
		foreach($result as $rs) {
			$temp = $temp."<tr id=\"".$trid.$i."\">";
			$temp = $temp."<th class=\"".$thclass."\"><input type=\"checkbox\" name=\"cb[]\" value=\"".$rs->id."\"  /></th>";
			$j = 0;
			$k = 0;
			$cek = 0;
			foreach($arr as $val) {
				if($j==0) {
					$kdpk = $rs->$val;
					$j++;
				}
				
				$faddclass = "";
				
				//untuk membuat datetime
				if(strcmp($arrtipe[$k],"datetime") == 0) {
					$faddclass = "class=\"".$dateclass."\"";
					if(!is_null($rs->$val)){
						$dateTime = new DateTime($rs->$val); 
						$value = $dateTime->format("d F Y \n h:i:s");
					}else{
						$value = "-";
					}
				} else {
					if($rs->$val == "") { 
						$faddclass = "class=\"".$centerclass."\"";
						$value = "-"; 
					}
					else { $value = $rs->$val; }
				}
				
				//cek image atau bukan 
				$cekfile = explode(".",$rs->$val);
				$ceklast = count($cekfile);

				if($cekfile[$ceklast-1] == "jpg" or $cekfile[$ceklast-1] == "gif" or $cekfile[$ceklast-1] == "jpeg" or $cekfile[$ceklast-1] == "png") {
					$faddclass = "class=\"".$centerclass."\"";
					if($rs->$val <> "") {
						$value = "<a href=\"/".$rs->$val."\" rel=\"lightbox[gallery".$i."]\" title=\"".$rs->$val."\" ><img src=\"/".$rs->$val."\" width=\"100%\" /></a>";
					} else {
						$value = "<strong>-</strong>";
					}
				}
				//untuk mengetahui field mana yang akan menjadi link dari hasil variable split diatas
				foreach($arract as $arracts) {
					if($cek <= $k) {
						if($arracts == $k) {
							$faddclass = "class=\"".$centerclass."\"";
							if ($value==1) {
								$txttampil = "<img src=\"".$imgtampil."\" alt=\"iya\" />";
							} else {
								$txttampil = "<img src=\"".$imgnontampil."\" alt=\"no\" />";
							}
							$value = "<a href=\"".$edit.".php?job=gantitampil".$arracts."&amp;page=".$page."&amp;id=".$kdpk.$param."&amp;flag=".$value."\">".$txttampil."</a>";
							$cek++;
						}
					}
				}
				
				//tidak mencetak ID dan title
				if($k > 1) {
					//klo jumlah huruf kurang dari X dijadiin center
					if(strlen($value)<20){$faddclass="class=\"".$centerclass."\"";}
					$temp = $temp."<td ".$faddclass.">".$value."</td>";
				}
				$k++;
			}
			
			//untuk memilih action apa yang ingin digunakan
			switch($act2) {
				case 1 : $temp = $temp."<td class=\"".$traction."\"><a href=\"edit".str_replace("index","",$edit).".php?id=".$kdpk."\" >Edit</a><br /><br /> <a href=\"#\" onclick=\"konfirmdel('".$rs->title."',".$kdpk.",".$_SESSION["ssLV"].")\" class=\"delete\">Delete</a>"; break;
				case 2 : $temp = $temp."<td class=\"".$traction."\"><a href=\"edit".str_replace("index","",$edit).".php?id=".$kdpk."\">Edit</a></td>"; break;
				case 3 : $temp = $temp."<td class=\"".$traction."\"><a href=\"#\" onclick=\"konfirmdel('".$kdtitle."',".$kdpk.",".$_SESSION["ssLV"].")\"  class=\"delete\" >Delete</a>"; break;
				case 4 : $temp = $temp."<td class=\"".$traction."\"><a href=\"".str_replace("index","",$edit).".php\">Detail</a> | <a href=\"#\" onclick=\"konfirmdel('".$kdtitle."',".$kdpk.",".$_SESSION["ssLV"].")\"  class=\"delete\">Delete</a>"; break;
				default :  $temp = $temp."<td class=\"".$traction."\"><a href=\"edit".$edit.".php?id=".$kdpk."\">Detail</td>"; break;
			}
			
			$temp = $temp."</tr>";
			$i++;
		}
		$temp = $temp."</table>";
		return $temp;
	} else {
		$temp = "<div align=\"center\">Belum ada Data</div>";
		return $temp;
	}
}

function del_query($namatable,$fieldpk) {
	global $db,$param,$page;
	if (isset($_GET["job"]) && strcasecmp($_GET["job"],"delete")==0) {
		if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
			header("Location: index.php");
			exit();
		}else{
			$id = $_GET["id"];
		}
		$strsql = "delete from ".$db->escape($namatable)." where ".$db->escape($fieldpk)." = ".$db->escape($id);

		$del = $db->query($strsql);
		
		header("Location: index.php?page=".$page.str_ireplace("&amp;","&",$param));
		exit();
	}
}

function update_query($namatable,$fieldupdate,$pk,$act2) {
	global $db,$page,$param;
	$bagian = explode(",",$act2);
	foreach($bagian as $bg)	{
		if (isset($_GET["job"]) && strcasecmp($_GET["job"],"gantitampil".$bg)==0) {
			if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
				header("Location: index.php");
				exit();
			}else{
				$id = $_GET["id"];
			}
			if (!isset($_GET["flag"]) || !is_numeric($_GET["flag"])) {
				header("Location: index.php");
				exit();
			}else{
				$flag = $_GET["flag"];			
			}
			
			if ($flag==1) {
				$strsql = "update ".$db->escape($namatable)." set ".$db->escape($fieldupdate)."=0, modtime=NOW(), modby=".$db->escape($_SESSION["ssid"])." where ".$db->escape($pk)."=".$db->escape($id);
			}else{
				$strsql = "update ".$db->escape($namatable)." set ".$db->escape($fieldupdate)."=1, modtime=NOW(), modby=".$db->escape($_SESSION["ssid"])." where ".$db->escape($pk)."=".$db->escape($id);
			}
			
			$update = $db->query($strsql);
			header("Location: index.php?page=".$page.str_ireplace("&amp;","&",$param));
			exit();
	
		//cari berita
		}
	}
}

function search($field,$bnyk) {
	global $db,$param,$edit;
	if (isset($_GET["job"]) && strcasecmp($_GET["job"],"cari")==0) {
		if ( (isset($_POST["selpilih"]) && isset($_POST["txtcari"])) || (isset($_GET["selpilih"]) && isset($_GET["txtcari"])) ) {
			(isset($_POST["selpilih"])) ? $selpilih = $_POST["selpilih"] : $selpilih = $_GET["selpilih"];
			(isset($_POST["txtcari"])) ? $txtcari = $_POST["txtcari"] : $txtcari = $_GET["txtcari"];
		} else {
			header("Location: ".$edit.".php");
			exit();
		}
		
		$fld = explode(",",$field);
		if (strlen($txtcari) < 3) { $nopaging=1; }
		$param = $param."&amp;job=cari&amp;txtcari=".$txtcari."&amp;selpilih=".$selpilih;
		$i=0;
		$pilihsql = " and ".$db->escape($fld[$selpilih])." like '%".$db->escape($txtcari)."%'";
	} else { $pilihsql = ""; }
	return $pilihsql;
}

function get_maxpage($rowsPerPage) {
	global $db;
	$found = $db->get_row("SELECT FOUND_ROWS() as maxpg");
	$allrows = $found->maxpg;
	$_SESSION["allrows"] = $allrows;
	$maxPage = ceil($allrows/$rowsPerPage);
	return $maxPage;
}

function paging ($maxPage) {
	global $param,$rsFirst,$rowsPerPage,$linkpage;
	$allrows = $_SESSION["allrows"];
	
	$pagenowstart = $rsFirst+1;
	$pagenowend = $rsFirst+$rowsPerPage;
	if($pagenowend>$allrows){$pagenowend = $allrows;}
			
	$page = 1;
	if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
		$page = $_GET["page"];
	}
	
	if(!isset($linkpage) || $linkpage==""){
		$linkpage = "index.php";
	}
		
	//print paging
	if ($maxPage > 1) {
		echo "<div class=\"tablenav-pages\">";
		echo "<span class=\"displaying-num\">Page ".$pagenowstart."-".$pagenowend." of ".$allrows."</span>";
		
		//previous + first page
		if($page!=1){
			echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".($page-1).$param."\">&lt;</a>";
			echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=1".$param."\">1</a>";
		}		
		
		//page - 3
		for($i=($page-5); $i<$page; $i++) {
			if($i>1){
				echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".$i.$param."\">".$i."</a>";
			}
		}
		
		//current page
		echo "<span class=\"page-numbers current\">".$page."</span>";
		
		//page + 3
		for($i=($page+1); $i<=($page+5); $i++) {
			if($i<$maxPage){
				echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".$i.$param."\">".$i."</a>";
			}
		}
		
		//mid page (...)
		if(($maxPage/2)>$page+5){
			echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".($maxPage/2).$param."\">...</a>";
		}
		
		//last page & next		
		if($page!=$maxPage){
			echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".$maxPage.$param."\">".$maxPage."</a>";
			echo "<a class=\"page-numbers\" href=\"".$linkpage."?page=".($page+1).$param."\">&gt;</a>";
		}		
		echo "</div>";
	}
	//print paging END
}

//input
/*
1. validasi kosong / engga
2. validasi cuman boleh a-z
3. validasi cuman angka 0-9
4. validasi cuman boleh angka 0-9 & a-z
5. validasi tanggal
6. validasi email
7. validasi password harus sama (buat jnsform 6)
*/
function set_text($nama,$valid,$maxlength,$desk) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
			<td>
				<input name=\"txt[".$_SESSION['txt']."]\" type=\"text\" id=\"txt".$_SESSION['txt']."\" maxlength=\"".$maxlength."\" value=\"".$input[$_SESSION['txt']]."\"  class=\"regular-text\" />
				<input type=\"hidden\" name=\"val[".$_SESSION['txt']."]\" value=\"".$valid."\" />
				<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
		";
		
	if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}
	
	$temp = $temp."</td>
	  </tr>
	";
	return $temp;
	
}

function set_passtext($nama,$valid,$maxlength){
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
			<td>
				<input name=\"txt[".$_SESSION['txt']."]\" type=\"password\" id=\"txt".$_SESSION['txt']."\" maxlength=\"".$maxlength."\"  class=\"regular-text\" style=\"width:318px;\" />
				<input type=\"hidden\" name=\"val[".$_SESSION['txt']."]\" value=\"".$valid."\" />
				<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
		";
		
	//if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}
	
	$temp = $temp."</td>
	  </tr>
	";
	
	$temp = $temp."
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txtp".$_SESSION['txt']."\">".$nama." (ulangi)</label></th>
			<td>
				<input name=\"txtp[".$_SESSION['txt']."]\" type=\"password\" id=\"txtp".$_SESSION['txt']."\" maxlength=\"".$maxlength."\"  class=\"regular-text\"  style=\"width:318px;\" />
				<input type=\"hidden\" name=\"nmbagp".$_SESSION['txt']."\" value=\"".$nama." (ulangi)\" />
		";
		
	//if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}
	
	$temp = $temp."</td>
	  </tr>
	";
	
	return $temp;
}

function set_deskeditor($nama,$valid) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
		
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
			<td>
				<div class=\"editorcontainer\">
					<textarea id=\"txt".$_SESSION['txt']."\" name=\"txt[".$_SESSION['txt']."]\" rows=\"10\" cols=\"80\">".$input[$_SESSION['txt']]."</textarea>
				</div>
				<input type=\"hidden\" name=\"val[".$_SESSION['txt']."]\" value=\"".$valid."\" />
				<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
		";
	
	$temp = $temp."
	  </tr>
	";
	return $temp;
	
}


function set_submit($nama) {
	return "<p class=\"submit\"><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".$nama."\" /></p>";
}

function set_inputfile($nama,$valid,$desk) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
			<td>";
	
	if(isset($input[$_SESSION['txt']]) && $input[$_SESSION['txt']!=""]){
		$temp = $temp."<img src=\"/".$input[$_SESSION['txt']]."\" width=\"200\" style=\"float:left;margin-right:10px;\" />";
	}
	
	$temp = $temp."<input type=\"file\" name=\"fimage".$_SESSION['txt']."\" onchange=\"getfimage(".$_SESSION['txt'].");\" />
				<input type=\"hidden\" id=\"txt".$_SESSION["txt"]."\" name=\"txt[".$_SESSION['txt']."]\" />
				<input type=\"hidden\" name=\"val[".$_SESSION['txt']."]\" value=\"".$valid."\"  />
				<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
		";
			
	if($desk!=""){$temp = $temp."<Br /><span class=\"setting-description\">".$desk."</span>";}
	
	if(isset($input[$_SESSION['txt']]) && $input[$_SESSION['txt']!=""]){
		$temp = $temp."<br /><span class=\"setting-description\">Pilih Gambar Lain jika ingin mengganti</span>";
	}
	
	$temp = $temp."
			</td>
	  </tr>
	";
	
	return $temp;
}

function set_textarea($nama,$valid,$maxlength) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	$temp = "";
	$temp = "<div class=\"fieldbungkus clearfix\">".
				"<div class=\"field1s\" style=\"height:230px\">".
					"<strong>".$nama."</strong>".
				"</div>".
				"<div class=\"field2s\" style=\"height:230px\">".
					"<textarea name=\"txt[".$_SESSION['txt']."]\" cols=\"95\" rows=\"6\" onkeyup=\"sisaubah('txt[".$_SESSION['txt']."]','sisa".$_SESSION['txt']."',".$maxlength.")\">".$input[$_SESSION['txt']]."</textarea>".
					"<br /><br />Jika pakai <strong>IMG</strong>, jangan lupa pakai <strong>ALT</strong> dan tanda <strong>slash</strong> untuk tutup!!<br />CO:<br /> &lt;img src=&quot;http://www.namawebsite.com/gambar.gif&quot; <strong>alt</strong>=&quot;Gambarku&quot; <strong>/</strong>&gt;<br /><br />Jangan lupa format bener <strong>&lt;br /&gt; </strong>".
					"<br /><br /><br />Sisa huruf :<input name=\"sisa".$_SESSION['txt']."\" type=\"text\" value=\"5000\" size=\"4\" readonly=\"readonly\" />
					<input type=\"hidden\" name=\"val[".$_SESSION['txt']."]\" value=\"".$valid."\" />
					<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
				</div>".
			"</div>
			<script type=\"text/javascript\">
				sisaubah('txt[".$_SESSION['txt']."]','sisa".$_SESSION['txt']."',".$maxlength.");
			</script>";
	return $temp;
}

function set_radio($nama,$input,$valid) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	$arr = explode(",",$input);
	$temp = "";
	$temp = "<tr valign=\"top\">
				<td>".$nama."</td>
				<td>";
					foreach($arr as $arrinput) {
						$temp = $temp."<input type=\"radio\" name=\"txt".$_SESSION['txt']."\" /> " . $arrinput . "<br />";
					}
	$temp = $temp.	"	
						<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
					</td>
			 	   </tr>";
	return $temp;
}

function set_tanggal($nama,$desk) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	$namabulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label>".$nama."</label></th>
			<td>
	";

	$temp = $temp."".
					"<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".$_SESSION['txt']."]','txt[".($_SESSION['txt']+1)."]','txt[".($_SESSION['txt']+2)."]');\">";
						for ($i=1; $i <= 31; $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$i</option>";
						}
				$temp = $temp."</select> ";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				
				$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".($_SESSION['txt']-1)."]','txt[".($_SESSION['txt'])."]','txt[".($_SESSION['txt']+1)."]');\">";
						for ($i=1; $i <= 12; $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$namabulan[$i]</option>";
						}
				$temp = $temp."</select> ";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".($_SESSION['txt']-2)."]','txt[".($_SESSION['txt']-1)."]','txt[".$_SESSION['txt']."]');\">";
						for ($i=2008; $i <= (date('Y')+2); $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$i</option>";
						}
				$temp = $temp."</select> 
						<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
					";
					
	if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}
		
	$temp = $temp."</td></tr>";
	return $temp;
}

function set_tanggaljam($nama,$desk) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	$namabulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label>".$nama."</label></th>
			<td>
	";

	$temp = $temp."".
					"<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".$_SESSION['txt']."]','txt[".($_SESSION['txt']+1)."]','txt[".($_SESSION['txt']+2)."]');\">";
						for ($i=1; $i <= 31; $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$i</option>";
						}
				$temp = $temp."</select> ";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				
				$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".($_SESSION['txt']-1)."]','txt[".($_SESSION['txt'])."]','txt[".($_SESSION['txt']+1)."]');\">";
						for ($i=1; $i <= 12; $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$namabulan[$i]</option>";
						}
				$temp = $temp."</select> ";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\" onchange=\"chkLastDayperMonth('txt[".($_SESSION['txt']-2)."]','txt[".($_SESSION['txt']-1)."]','txt[".$_SESSION['txt']."]');\">";
						for ($i=2008; $i <= (date('Y')+2); $i++) {
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">$i</option>";
						}
				$temp = $temp."</select> ";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\" style=\"margin-left:10px;\">";
						for ($i=1; $i <= 24; $i++) {
							if($i<=9){
								$txti = "0".$i;
							}else{
								$txti = $i;
							}
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">".$txti."</option>";
						}
				$temp = $temp."</select>";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				$temp = $temp.":<select name=\"txt[".$_SESSION['txt']."]\">";
						for ($i=0; $i <= 60; $i++) {
							if($i<=9){
								$txti = "0".$i;
							}else{
								$txti = $i;
							}
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">".$txti."</option>";
						}
				$temp = $temp."</select>";
				$_SESSION['txt'] = $_SESSION['txt'] + 1;
				$temp = $temp.":<select name=\"txt[".$_SESSION['txt']."]\" style=\"margin-right:5px;\">";
						for ($i=0; $i <= 60; $i++) {
							if($i<=9){
								$txti = "0".$i;
							}else{
								$txti = $i;
							}
							$temp = $temp."<option value=\"".$i."\" ".issel($input[$_SESSION['txt']],$i,"selected","").">".$txti."</option>";
						}
				$temp = $temp."</select>";
				
				$temp = $temp."<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />";
					
	if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}
		
	$temp = $temp."</td></tr>";
	return $temp;
}

function set_checkbox($nama,$desk) {
	global $input;
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	$temp = "";
	if (isset($input[$_SESSION['txt']])) {
		$in = $input[$_SESSION['txt']];
		if($input[$_SESSION['txt']]=="1"){$in="on";}
	} else {
		$in = "";
	}
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
			<td>
				<input type=\"checkbox\"  id=\"txt".$_SESSION['txt']."\"  name=\"txt[".$_SESSION['txt']."]\" ".iscek($in)." />
				<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$nama."\" />
		";
	
	if($desk!=""){$temp = $temp."<span class=\"setting-description\">".$desk."</span>";}	
	
	$temp = $temp."
			</td>
	  </tr>
	";
	
	return $temp;
}

function set_checkbox2($nama,$field) {
	global $db,$input;
	$temp = "<div class=\"fieldbungkus clearfix\">".
				"<div class=\"field1a\">".
					"<strong>".$nama."</strong>".
				"</div>".
				"<div class=\"field2a\" style=\"height:auto;\">";
					$arr_akses = explode(",",$field);
					foreach($arr_akses as $arr) {
						$_SESSION['txt'] = $_SESSION['txt'] + 1;
						$temp = $temp."<input name=\"txt[".$_SESSION['txt']."]\" type=\"checkbox\" style=\"vertical-align:middle;\" /> ".$arr."<br />";
					}
	$temp = $temp."</div>".
			"</div>";
	return $temp;
}

function set_listmenu($nama,$table,$field,$tampil,$desk) {
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	global $db,$input;
	$arr = explode(",",$field);
	$temp = "";
	
	$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
				<td>
					<select id=\"txt".$_SESSION['txt']."\" name=\"txt[".$_SESSION['txt']."]\">
					";
					
					if($tampil <> "") {
						$query = " where ".$db->escape($tampil);
					} else {
						$query = "";
					}
															
					$strsql = "select ".$db->escape($field)." from ".$db->escape($table).$query;
					$topicdb = $db->get_results($strsql);
					if ($topicdb) {
						foreach ($topicdb as $row) {
							$temp = $temp."<option value=\"".$row->$arr[0]."\" ".issel($input[$_SESSION['txt']],$row->$arr[0]," selected","").">".$row->$arr[1]."</option>";
						}
					}
	$temp = $temp."</select>";	
	if($desk!=""){
		$temp = $temp." <span class=\"setting-description\">".$desk."</span>";	
	}
	$temp = $temp."
			</td>
		</tr>";
	
	return $temp;
}

function set_listmenu2($nama,$field) {
	$_SESSION['txt'] = $_SESSION['txt'] + 1;
	global $db,$input;
	$temp = "<tr valign=\"top\">
			<th scope=\"row\"><label for=\"txt".$_SESSION['txt']."\">".$nama."</label></th>
				<td>";
					$arr_akses = explode(",",$field);
					$temp = $temp."<select name=\"txt[".$_SESSION['txt']."]\">";
					$i = 0;
					foreach($arr_akses as $arr) {
						$temp = $temp."<option value=\"".$i."\"  ".issel($input[$_SESSION['txt']],$i," selected","").">".$arr."</option>";
						$i++;
					}
					
	$temp = $temp."</select>
			</td>
		</tr>";
	return $temp;
}


function set_checkboxdb($nama,$sql) {
	global $db, $input;
	$temp = "";
	$_SESSION['txt'] = $_SESSION['txt'] + 1;	
	$resultdb = $db->get_results($sql);
	if($resultdb){
		$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label>".$nama."</label></th>
			<td>
		";
		
		foreach($resultdb as $rs){
			$in = "";
			if (isset($input[$_SESSION['txt']])) {
				$inputses = explode(";",$input[$_SESSION['txt']]);
				foreach($inputses as $inputdb){
					if($inputdb == $rs->id){
						$in = "on";
					}
				}
			} 
				
			$temp = $temp."
				<div class=\"cbinput\">
					<input type=\"checkbox\" name=\"txt[".$_SESSION['txt']."][]\" ".iscek($in)." value=\"".$rs->id."\" />
					".$rs->nama."
					<input type=\"hidden\" name=\"nmbag".$_SESSION['txt']."\" value=\"".$rs->nama."\" />
				</div>";
		}
		
		$temp = $temp."
				</td>
			</tr>
		";
	}
	
	return $temp;
}


function set_hiddenid($val) {
	return "<input type=\"hidden\" name=\"txtid\" value=\"".$val."\" />";
}

function set_hiddeninput($nama,$val) {
	return "<input type=\"hidden\" name=\"".$nama."\" value=\"".$val."\" />";
}


//function buat cek format e-mail
function CekValidEmail($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
		return false;
		}
	}

	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}


//validasi
/*
1. validasi tidak boleh kosong
2. validasi cuman boleh angka 0-9 & a-z
3. validasi email
4. hanya boleh angka
5. validasi tanggal
*/

function validate_input() {
	$validate = "";
	for($i=1 ; $i<=count($_POST['txt']) ; $i++) {
		if(isset($_POST['txt'][$i])) {
			if(is_array($_POST['txt'][$i])){
				$sesfrmsplit = $_POST['txt'][$i];
				$txtsesfrm = "";
				foreach($sesfrmsplit as $sesfrm){
					$txtsesfrm = $txtsesfrm.$sesfrm.";";
				}
				$_SESSION['frm'.$i] = ";".$txtsesfrm;
			}		
		}else{
			$_SESSION['frm'.$i] = $_POST['txt'][$i];
		}
		if(isset($_POST['val'][$i])) {
			$arr = explode(",",$_POST['val'][$i]);
		
			foreach($arr as $cek) {
				//validasi tidak boleh kosong
				if($cek == 1) {
					if( strlen($_POST['txt'][$i])==0 ) {
						$validate = $validate."<li>Bagian <strong>".$_POST["nmbag".$i.""]."</strong> tidak boleh kosong</li>";
					}
				}
				//validasi hanya boleh a-z dan 0-9
				if($cek == 2) {
					if(!CekValidAsc($_POST['txt'][$i])) {
						$validate = $validate."<li>Bagian <strong>".$_POST["nmbag".$i.""]."</strong> Harap menggunakan huruf a~z dan angka 0~9</li>";
					}
				}
				
				//validasi e-mail
				if($cek == 3) {
					if(!CekValidEmail($_POST['txt'][$i])  && $_POST['txt'][$i]!="") {
						$validate = $validate."<li>Bagian <strong>".$_POST["nmbag".$i.""]."</strong> email format salah</li>";
					}
				}
				
				//validasi angka
				if($cek == 4) {
					if(!is_numeric($_POST['txt'][$i]) && $_POST['txt'][$i]!="") {
						$validate = $validate."<li>Bagian <strong>".$_POST["nmbag".$i.""]."</strong> harus angka</li>";
					}
				}
				

				
				
				//validasi password
				if($cek == 5) {
					if($_POST['txt'][$i] != $_POST['txtp'][$i]) {
						$validate = $validate."<li>Bagian <strong>".$_POST["nmbag".$i.""]."</strong> dan <strong>".$_POST["nmbagp".$i.""]."</strong> harus sama</li>";
					}
				}
			}
			//end for each
		}
	}
	//end for
	
	if($validate!=""){
		$_SESSION['peringatan'] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$validate."</ul>";
	}
	return $validate;
}

function getnewid($table,$kolom){
	global $db;
	$newid = $db->get_row("select max(".$kolom.") as id from ".$table."");
	(is_null($newid->id)) ? $id = 1 : $id = $newid->id + 1;
	return $id;
}

function directdeletepic($table,$id,$idpass,$fieldpic){
	global $db;
	if($fieldpic!="")
	$getpic = $db->get_row("SELECT ".$fieldpic." as filefoto FROM ".$table." WHERE  ".$id." = ".$idpass);
	if(isset($getpic->filefoto)){	
		$filefoto = "../../".$getpic->filefoto;
		if (is_file($filefoto)) {
			clearstatcache();
			unlink($filefoto);
		}
	}		
	$strsql = "DELETE FROM ".$table." WHERE ".$id." = ".$idpass." ";
	$del = $db->query($strsql);
	return $del;
}

function set_label($nama,$sql){
	global $db;
	$getvar = $db->get_row($sql);
	if($getvar){
		$temp = "
		<tr valign=\"top\">
			<th scope=\"row\"><label>".$nama."</label></th>
			<td>
		";
		
		$temp = $temp."<span class=\"isilabel\">".$getvar->value."</span>";
		
		$temp = $temp."
			</td>
		</tr>	
		";
		
		return $temp;
	}
}

function set_label2($nama,$visi){
	$temp = "
	<tr valign=\"top\">
		<th scope=\"row\"><label>".$nama."</label></th>
		<td>
	";
	
	$temp = $temp."<span class=\"isilabel\">".$visi."</span>";
	
	$temp = $temp."
		</td>
	</tr>	
	";
	
	return $temp;
}


//BBcode 2 HTML, harus include function.css di strcss
function BBCode($Text)
	 {
		// Replace any html brackets with HTML Entities to prevent executing HTML or script
		// Don't use strip_tags here because it breaks [url] search by replacing & with amp
		$Text = str_replace("<", "&lt;", $Text);
		$Text = str_replace(">", "&gt;", $Text);

		// Convert new line chars to html <br /> tags
		$Text = nl2br($Text);

		// Set up the parameters for a URL search string
		$URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
		// Set up the parameters for a MAIL search string
		$MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";

		// Perform URL Search
		$Text = preg_replace("/\[url\]([$URLSearchString]*)\[\/url\]/", '<a href="$1" target="_blank">$1</a>', $Text);
		$Text = preg_replace("(\[url\=([$URLSearchString]*)\](.+?)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);
 //$Text = preg_replace("(\[url\=([$URLSearchString]*)\]([$URLSearchString]*)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);

		// Perform MAIL Search
		$Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $Text);
		$Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $Text);
 
		// Check for bold text
		$Text = preg_replace("(\[b\](.+?)\[\/b])is",'<span class="bold">$1</span>',$Text);

		// Check for Italics text
		$Text = preg_replace("(\[i\](.+?)\[\/i\])is",'<span class="italics">$1</span>',$Text);

		// Check for Underline text
		$Text = preg_replace("(\[u\](.+?)\[\/u\])is",'<span class="underline">$1</span>',$Text);

		// Check for strike-through text
		$Text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span class="strikethrough">$1</span>',$Text);

		// Check for over-line text
		$Text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span class="overline">$1</span>',$Text);

		// Check for colored text
		$Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style=\"color: $1\">$2</span>",$Text);

		// Check for sized text
		// edit by andri, ganti size value
		for($itext=1;$itext<=7;$itext++){
			$Text = str_replace("SIZE=".$itext."","SIZE=".(($itext*2)+12)."",$Text);
		}
		$Text = preg_replace("(\[SIZE=(.+?)\](.+?)\[\/SIZE\])is","<span style=\"font-size: $1px\">$2</span>",$Text);
		
		// Check for list text
		$Text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$Text);
		$Text = str_replace("[*]", "<li>", $Text);

		// Check for font change text
		$Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style=\"font-family: $1;\">$2</span>",$Text);

		// Declare the format for [code] layout
		$CodeLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
														<td class="quotecodeheader"> Code:</td>
												</tr>
												<tr>
														<td class="codebody">$1</td>
												</tr>
									 </table>';
		// Check for [code] text
		$Text = preg_replace("/\[code\](.+?)\[\/code\]/is","$CodeLayout", $Text);
		// Declare the format for [php] layout
		$phpLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
														<td class="quotecodeheader"> Code:</td>
												</tr>
												<tr>
														<td class="codebody">$1</td>
												</tr>
									 </table>';
		// Check for [php] text
		$Text = preg_replace("/\[php\](.+?)\[\/php\]/is",$phpLayout, $Text);

		// Declare the format for [quote] layout
		$QuoteLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
														<td class="quotecodeheader"> Quote:</td>
												</tr>
												<tr>
														<td class="quotebody">$1</td>
												</tr>
									 </table>';
						 
		// Check for [quote] text
		$Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$QuoteLayout", $Text);
 
		// Images
		// [img]pathtoimage[/img]
		$Text = preg_replace("/\[IMG\](.+?)\[\/IMG\]/", '<img src="$1">', $Text);
 
		// [img=widthxheight]image source[/img]
		$Text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $Text);
		
		
		
		
		//add by andri 25 nov 2009
		//br
		$Text = str_replace("<br />","<br style=\"margin:0 0 -10px 0;padding:0;\" />",$Text);
		
		//left
		$Text = str_replace("[/LEFT]","</div>",str_replace("[LEFT]","<div align=\"left\">",$Text));
		
		//right
		$Text = str_replace("[/RIGHT]","</div>",str_replace("[RIGHT]","<div align=\"right\">",$Text));
		
		//center
		$Text = str_replace("[/CENTER]","</div>",str_replace("[CENTER]","<div align=\"center\">",$Text));
 
	 	return $Text;
} 


//ganti tulisan smile list 

function smiletext($Text){

	if(!is_null($Text) && $Text!=""){
		global $db;
		$fstrsql = "SELECT title, smilietext, smiliepath FROM smilie";
		$fresult = $db->get_results($fstrsql);
		if ($fresult){
			foreach($fresult as $frow){
				$Text = str_replace($frow->smilietext,"<img src=\"/".$frow->smiliepath."\" alt=\"".$frow->title."\" />",$Text);
			}
		}
	}
	return $Text;
}

/*function getMonth()
buat ngubah angka ke nama bulan
*/
function getMonth($m=0) {
	return (($m==0 ) ? date("F") : date("F", mktime(0,0,0,$m)));
}


//function cekakses
function cekAksesMenu($bodyid,$sessionMgAkses){
	$vcekAksesMenu = 0;
	if(isset($bodyid) && $bodyid!="" && !is_null($bodyid) && isset($sessionMgAkses) && $sessionMgAkses!="" && !is_null($sessionMgAkses)){
		
		global $db;
		
		//get id menu
		$getbodyid = $db->get_row("SELECT idmenu FROM webtool_menu WHERE vbodyid='".$db->escape($bodyid)."'");
		if($getbodyid){
			$bodyid = $getbodyid->idmenu;
			
			//cek session
			$arrAkses = explode(";",$sessionMgAkses);
			$bnykAkses = count($arrAkses);
			if($bnykAkses>0){$bnykAkses = $bnykAkses-1;}
			for($iakses=0;$iakses<=$bnykAkses;$iakses++){
				if($arrAkses[$iakses]==$bodyid){$vcekAksesMenu=1;}
			}
		}
	}
	return $vcekAksesMenu;
}

//function cekakses
function cekPermit($bodyid,$MgID){
	//di set false
	$vCekpermit = false;
	$vCekvarpermit = false;
	global $db;

	//get id menu
	$getbodyid = $db->get_row("SELECT idmenu FROM webtool_menu WHERE vbodyid='".$db->escape($bodyid)."'");
	if($getbodyid){
		$bodyid = $getbodyid->idmenu;
	}
	if (isset($bodyid) && $bodyid !=""){
		$getdiv = $db->get_row("select tdiv, akses from ms_admin where id=".$db->escape($MgID)."");
		if ($getdiv){
			$getpermit=$db->get_row("select tpermit from ms_admin_permit where iddiv=".$db->escape($getdiv->tdiv)."");
			if ($getpermit){
				$strpermit=$getpermit->tpermit;
				$arrpermit = explode(";",$strpermit);
				foreach($arrpermit as $idpermit){
					if (isset($idpermit) && $idpermit!=""){
						if ($idpermit!=0){
							if ($idpermit==$bodyid){
								$vCekvarpermit= true;
							}
						}
					}
				}
				
				if ($vCekvarpermit == 1 ){
					$arrakses = explode(";",$getdiv->akses);
					foreach($arrakses as $intakses){
						if (isset($intakses) && $intakses!=""){
							if ($intakses==$bodyid){
								$vCekpermit = true;
							}
						}
					}
				}
				
			}
		}
		
	}

	return $vCekpermit;
}

//function cek divisi
//buat cek akses divisi
//$mgid = id yang login biasa di session["mgid"]
//$parcek = kode halaman, biasa variabel $numakses
function CekDiv($mgid,$parcek){
	global $db;
	$varcek = false;
	
	$cekdiv = $db->get_row("SELECT tdiv, akses FROM ms_admin WHERE id=".$db->escape($mgid)."");
	if($cekdiv){
		if(is_numeric($cekdiv->tdiv)){
			$cekpermit = $db->get_row("SELECT tpermit FROM ms_admin_permit WHERE iddiv=".$db->escape($cekdiv->tdiv)."");
			if($cekpermit){
				//cek akses list divisi
				$getpermit = strpos($cekpermit->tpermit,";".$parcek.";");
				if (is_numeric($getpermit)){
					//cek akses list admin
					$getpermitadmin = strpos($cekdiv->akses,";".$parcek.";");
					if(is_numeric($getpermitadmin)){
						$varcek =  true;
					}
				}else{
					$varcek = false;
				}
			}//end if($cekpermit){
		}// end if(is_numeric($cekdiv->tdiv)){
	}//end if($cekdiv){
	
	return $varcek;
	
}
//end function cek divisi


function CekDiv2($mgid,$parcek){
	global $db;
	$varcek = false;
	
	$cekdiv = $db->get_row("SELECT tdiv, vakses FROM t_admin WHERE kdadmin=".$db->escape($mgid)."");
							
	if($cekdiv){
		if(is_numeric($cekdiv->tdiv)){
			$cekpermit = $db->get_row("SELECT tpermit FROM t_admin_permit WHERE iddiv=".$db->escape($cekdiv->tdiv)."");
			if($cekpermit){
				//cek akses list divisi
				$getpermit = strpos($cekpermit->tpermit,";".$parcek.";");
				if (is_numeric($getpermit)){
					//cek akses list admin
					$getpermitadmin = strpos($cekdiv->vakses,";".$parcek.";");
					if(is_numeric($getpermitadmin)){
						$varcek =  true;
					}
				}else{
					$varcek = false;
				}
			}//end if($cekpermit){
		}// end if(is_numeric($cekdiv->tdiv)){
	}//end if($cekdiv){

	return $varcek;
	
}


//function lastupdate
//function buat print cretime dan lastupdate
function lastupdate($cretime,$creby,$modtime,$modby){
	global $db;
	$varlastupdate = "";
	//cek jika cretime + creby ga kosong = cetak create time
	if(isset($cretime) && isset($creby)){		
		if(!is_null($cretime) && $cretime!="" && !is_null($creby) && $creby!=""){
			$varlastupdate .= "
				<tr valign=\"top\">
					<th scope=\"row\"><label>Created</label></th>
					<td>
						<strong>".$creby." (".date('j F Y, H:i:s',$cretime).")</strong>
					</td>
				</tr>";
		}
	}
	//end cek jika cretime + creby ga kosong = cetak create time
	
	//cek jika modtime + modby ga kosong = cetak create time
	if(isset($modtime) && isset($modby)){		
		if(!is_null($modtime) && $modtime!="" && !is_null($modby) && $modby!=""){
			$varlastupdate .= "
				<tr valign=\"top\">
					<th scope=\"row\"><label>Last Update</label></th>
					<td>
						<strong>".$modby." (".date('j F Y, H:i:s',$modtime).")</strong>
					</td>
				</tr>";
		}
	}
	//end cek jika modtime + modby ga kosong = cetak create time
	
	return $varlastupdate;
	
}

function CekIPLocal(){
	$varip = $_SERVER['REMOTE_ADDR'];
	$varcekiplocal = false;
	
	//ip yang boleh
	//ip kedoya
	$arrip[0] = "192.168.2";
	$arrip[1] = "192.168.88";
	
	for($i=1;$i<=count($arrip);$i++){
		if($arrip[$i-1]==substr($varip,0,strlen($arrip[$i-1])) ){
			$varcekiplocal = true;
		}
	}
	
	return $varcekiplocal;
}

function kirimNewsletter($emailtujuan,$emailsubject,$content){
	global $pathdir;
	require_once($pathdir."/includes/class.phpmailer.php");
	try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "192.168.88.21"; // SMTP server
	$mail->Username   = "newsletter@newsletter.gameweb.co.id";     // SMTP server username
	$mail->Password   = "s4fC5aqMiTMmWA6";            // SMTP server password

	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->SMTPDebug = 1;
	$mail->SMTPSecure = 'tls';
	$mail->AddReplyTo("newsletter@newsletter.gameweb.co.id","Gameweb");

	$mail->From       = "newsletter@newsletter.gameweb.co.id";
	$mail->FromName   = "Gameweb";

	$to = $emailtujuan;

	$mail->AddAddress($to);

	$mail->Subject  = $emailsubject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	return 1;
}catch (phpmailerException $e){
		return $e->errorMessage();
	}
}

function kirimJoblist($emailtujuan,$emailsubject,$content){
	global $pathdir;
	require_once($pathdir."/includes/class.phpmailer.php");
	try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "mail.lyto.net"; // SMTP server
	$mail->Username   = "wenby@lyto.net";     // SMTP server username
	$mail->Password   = "chabie";            // SMTP server password

	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->SMTPDebug = 1;
	$mail->SMTPSecure = 'tls';
	$mail->AddReplyTo("joblist@lyto.net","Joblist");

	$mail->From       = "joblist@lyto.net";
	$mail->FromName   = "Joblist";

	$to = $emailtujuan;

	$mail->AddAddress($to);

	$mail->Subject  = $emailsubject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	return 1;
}catch (phpmailerException $e){
		return $e->errorMessage();
	}
}

/*function kirimNewsletter2($emailtujuan,$emailsubject,$content){
	global $pathdir;
	require_once($pathdir."/includes/class.phpmailer.php");
	try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "192.168.88.21"; // SMTP server
	$mail->Username   = "newsletter@newsletter.lytogame.com";     // SMTP server username
	$mail->Password   = "s4fC5aqMiTMmWA6";            // SMTP server password

	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->SMTPDebug = 1;
	$mail->SMTPSecure = 'tls';
	$mail->AddReplyTo("newsletter@newsletter.lytogame.com","Lytogame");

	$mail->From       = "newsletter@newsletter.lytogame.com";
	$mail->FromName   = "Lytogame";

	$to = $emailtujuan;

	$mail->AddAddress($to);

	$mail->Subject  = $emailsubject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	return 1;
}catch (phpmailerException $e){
		return $e->errorMessage();
	}
}*/




function fcekpetik($value){
	// Stripslashes
	if (get_magic_quotes_gpc())
		{
		$value = stripslashes($value);
		}
	// Quote if not a number
	if (!is_numeric($value))
		{
		$value = mysql_real_escape_string($value);
		}
	return $value;
}


function kirimEmailPartner($emailtujuan,$emailsubject,$content,$emailcc){
	global $pathdir;
	require_once($pathdir."/includes/class.phpmailer.php");
	try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "mail.lyto.net"; // SMTP server
	$mail->Username   = "wenby@lyto.net";     // SMTP server username
	$mail->Password   = "chabie";            // SMTP server password

	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->SMTPDebug = 1;
	$mail->SMTPSecure = 'tls';
	$mail->AddReplyTo("staff@kotakgame.com","KotakGame");

	$mail->From       = "staff@kotakgame.com";
	$mail->FromName   = "KotakGame";
	
	
	//emailcc
	if(isset($emailcc))
	{
		$arrcc = explode(";",$emailcc);
		for($j=1;$j<count($arrcc)-1;$j++){	
			$mail->AddCC($arrcc[$j], $arrcc[$j]);
		}			
	}
	//end emailcc

	$to = $emailtujuan;

	$mail->AddAddress($to);

	$mail->Subject  = $emailsubject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	return 1;
}catch (phpmailerException $e){
		return $e->errorMessage();
	}
}

function UploadImage($idevent,$img_old,$nofimage) {
   $foldpic = "themes";
   $cekdir="../";
  //make the folder if doesn't exist ,buat cek foleder yg namanya imeges, kalo blm ada di buat folder namanya images
  if (!is_dir($cekdir."assets/")) mkdir($cekdir."assets/");
  $foldfoto = "assets/";
  
   if (!is_dir($cekdir.$foldfoto."img/")) mkdir($cekdir.$foldfoto."img/");
    $foldfoto = $foldfoto."img/";  
		
	if (!is_dir($cekdir.$foldfoto.$foldpic."/")) mkdir($cekdir.$foldfoto.$foldpic."/");
    $foldfoto = $foldfoto.$foldpic."/";
		
	if (!is_dir($cekdir.$foldfoto.$idevent."/")) mkdir($cekdir.$foldfoto.$idevent."/");
    $foldfoto = $foldfoto.$idevent."/";
	
  $imgname = strtolower($_FILES["fimage"]["name"]);

    $getext = explode(".",$imgname);
    $ext = ".".$getext[(count($getext)-1)];

  //$ext = strtolower(strstr($imgname, "."));
  $src = $_FILES["fimage"]["tmp_name"];
  $vdir = $cekdir.$foldfoto;
  $ffoto = $foldfoto.$idevent.$ext;
  $dest = $cekdir.$foldfoto.$idevent.$ext;

    $arr_dst_width = array(1024, 800, 234, 90);
    $arr_dst_height = array(768, 600, 175, 67);
    $arr_img_size = array("xl","l", "m", "s");

     $banyakfotoupload = 1;   

  //buat hapus file sblmnya
  if (is_file($dest)) {
    clearstatcache();
    unlink($dest);
  }

  //(*buat edit)cek ada gambar lama nya apa ga, kalo ada gambar lamanya di del
  if (strlen($img_old)!=0) {
    for ($i=0; $i <= $banyakfotoupload; $i++) {
      if ( is_file($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old)) ) {
        clearstatcache();
        unlink($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old));
      }
    }
  }
  
  //jika berhasil pindahin ke files berikutnya
  if ( move_uploaded_file($src, $dest) ) {

    //Array ( [0] => 365 [1] => 480 [2] => 2 [3] => width="365" height="480" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
    $img_size = getimagesize($dest);
    if ($img_size[0] > $img_size[1]) {
      $img_type = 0; //landscape
    }else{
      $img_type = 1; //portrait
    }

      //buat nentuin format imagenya (gif,jpeg,png,wbmp)      
      switch ($img_size[2]) {
        case 1 :
          $im_src = imagecreatefromgif($dest);
          break;
        case 2 :
          $im_src = imagecreatefromjpeg($dest);
          break;
        case 3 :
          $im_src = imagecreatefrompng($dest);
          break;
        case 4 :
          $im_src = imagecreatefromwbmp($dest);
          break;
        case 8 :
          $im_src = imagecreatefromwbmp($dest);
          break;
        default :
          return false;
      }      
            
      $src_width = imagesx($im_src);
      $src_height = imagesy($im_src);

       $dst_width = $src_width;
       $dst_height = $src_height;
       //Copy and resize part images 
      $im = imagecreatetruecolor($dst_width,$dst_height);
      imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
      
        switch ($img_size[2]) {
          case 1 :
            imagegif($im, $vdir.$idevent.$arr_img_size[$i].$ext);
            break;
          case 2 :
           imagejpeg($im, $vdir.$idevent.$ext);
           
            break;
          case 3 :
            imagepng($im, $vdir.$idevent.$ext);
            break;
          case 4 :
            imagewbmp($im, $vdir.$idevent.$arr_img_size[$i].$ext);
            break;
          default :
            return false;
        }

    imagedestroy($im_src);
    imagedestroy($im);

    return $ffoto;

  }else{
    //failed to move_uploaded_file
    return false;
  }

} //end upload

function UploadImage2($idframe,$img_old,$nofimage) {
   $foldpic = "frames";
   $cekdir="../";
  //make the folder if doesn't exist ,buat cek foleder yg namanya imeges, kalo blm ada di buat folder namanya images
  if (!is_dir($cekdir."assets/")) mkdir($cekdir."assets/");
  $foldfoto = "assets/";
  
  if (!is_dir($cekdir.$foldfoto."img/")) mkdir($cekdir.$foldfoto."img/");
    $foldfoto = $foldfoto."img/";  
		
	if (!is_dir($cekdir.$foldfoto.$foldpic."/")) mkdir($cekdir.$foldfoto.$foldpic."/");
    $foldfoto = $foldfoto.$foldpic."/";
		
	if (!is_dir($cekdir.$foldfoto.$idframe."/")) mkdir($cekdir.$foldfoto.$idframe."/");
    $foldfoto = $foldfoto.$idframe."/";
	
  $imgname = strtolower($_FILES["fimage"]["name"]);

    $getext = explode(".",$imgname);
    $ext = ".".$getext[(count($getext)-1)];

  //$ext = strtolower(strstr($imgname, "."));
  $src = $_FILES["fimage"]["tmp_name"];
  $vdir = $cekdir.$foldfoto;
  $ffoto = $foldfoto.$idframe.$ext;
  $dest = $cekdir.$foldfoto.$idframe.$ext;

    $arr_dst_width = array(1024, 800, 234, 90);
    $arr_dst_height = array(768, 600, 175, 67);
    $arr_img_size = array("xl","l", "m", "s");

     $banyakfotoupload = 1;   

  //buat hapus file sblmnya
  if (is_file($dest)) {
    clearstatcache();
    unlink($dest);
  }

  //(*buat edit)cek ada gambar lama nya apa ga, kalo ada gambar lamanya di del
  if (strlen($img_old)!=0) {
    for ($i=0; $i <= $banyakfotoupload; $i++) {
      if ( is_file($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old)) ) {
        clearstatcache();
        unlink($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old));
      }
    }
  }
  
  //jika berhasil pindahin ke files berikutnya
  if ( move_uploaded_file($src, $dest) ) {

    //Array ( [0] => 365 [1] => 480 [2] => 2 [3] => width="365" height="480" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
    $img_size = getimagesize($dest);
    if ($img_size[0] > $img_size[1]) {
      $img_type = 0; //landscape
    }else{
      $img_type = 1; //portrait
    }

      //buat nentuin format imagenya (gif,jpeg,png,wbmp)      
      switch ($img_size[2]) {
        case 1 :
          $im_src = imagecreatefromgif($dest);
          break;
        case 2 :
          $im_src = imagecreatefromjpeg($dest);
          break;
        case 3 :
          $im_src = imagecreatefrompng($dest);
          break;
        case 4 :
          $im_src = imagecreatefromwbmp($dest);
          break;
        case 8 :
          $im_src = imagecreatefromwbmp($dest);
          break;
        default :
          return false;
      }      
            
      $src_width = imagesx($im_src);
      $src_height = imagesy($im_src);

			$dst_width = $src_width;
			$dst_height = $src_height;
       //Copy and resize part images 
      $im = imagecreatetruecolor($dst_width,$dst_height);
			$background = imagecolorallocate($im, 0, 0, 0);
			imagecolortransparent($im, $background);
			imagealphablending($im, false);
			imagesavealpha($im, true);
			
      imagecopy($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height);
      
        switch ($img_size[2]) {
          case 1 :
            imagegif($im, $vdir.$idframe.$ext);
            break;
          case 2 :
						imagejpeg($im, $vdir.$idframe.$ext);
            break;
          case 3 :
            imagepng($im, $vdir.$idframe.$ext);
            break;
          case 4 :
            imagewbmp($im, $vdir.$idframe.$ext);
            break;
          default :
            return false;
        }

    imagedestroy($im_src);
    imagedestroy($im);

    return $ffoto;

  }else{
    //failed to move_uploaded_file
    return false;
  }

} //end upload




function UploadImage3($idframe,$img_old,$nofimage) {
   $foldpic = "frames";
   $cekdir="../";
  //make the folder if doesn't exist ,buat cek foleder yg namanya imeges, kalo blm ada di buat folder namanya images
  if (!is_dir($cekdir."assets/")) mkdir($cekdir."assets/");
  $foldfoto = "assets/";
  
  if (!is_dir($cekdir.$foldfoto."img/")) mkdir($cekdir.$foldfoto."img/");
    $foldfoto = $foldfoto."img/";  
		
	if (!is_dir($cekdir.$foldfoto.$foldpic."/")) mkdir($cekdir.$foldfoto.$foldpic."/");
    $foldfoto = $foldfoto.$foldpic."/";
		
	if (!is_dir($cekdir.$foldfoto.$idframe."/")) mkdir($cekdir.$foldfoto.$idframe."/");
    $foldfoto = $foldfoto.$idframe."/";
	
  $imgname = strtolower($_FILES["fimage"]["name"]);

    $getext = explode(".",$imgname);
    $ext = ".".$getext[(count($getext)-1)];

  //$ext = strtolower(strstr($imgname, "."));
  $src = $_FILES["fimage"]["tmp_name"];
  $vdir = $cekdir.$foldfoto;
  $ffoto = $foldfoto.$idframe.$ext;
  $dest = $cekdir.$foldfoto.$idframe.$ext;

    $arr_dst_width = array(1024, 800, 234, 90);
    $arr_dst_height = array(768, 600, 175, 67);
    $arr_img_size = array("l","r");

    $banyakfotoupload = 1;   

  //buat hapus file sblmnya
  if (is_file($dest)) {
    clearstatcache();
    unlink($dest);
  }

  //(*buat edit)cek ada gambar lama nya apa ga, kalo ada gambar lamanya di del
  if (strlen($img_old)!=0) {
    for ($i=0; $i <= $banyakfotoupload; $i++) {
      if ( is_file($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old)) ) {
        clearstatcache();
        unlink($cekdir.str_replace(".",$arr_img_size[$i].".",$img_old));
      }
    }
  }
  
  //jika berhasil pindahin ke files berikutnya
  if ( move_uploaded_file($src, $dest) ) {
	//Array ( [0] => 365 [1] => 480 [2] => 2 [3] => width="365" height="480" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
			$img_size = getimagesize($dest);
			if ($img_size[0] > $img_size[1]) {
				$img_type = 0; //landscape
			}else{
				$img_type = 1; //portrait
			}
			
				
			for ($i=0; $i <= $banyakfotoupload; $i++) {
			$idjenis=1;
			
				//buat nentuin format imagenya (gif,jpeg,png,wbmp)			
				switch ($img_size[2]) {
					case 1 :
						$im_src = imagecreatefromgif($dest);
						break;
					case 2 :
						$im_src = imagecreatefromjpeg($dest);
						break;
					case 3 :
						$im_src = imagecreatefrompng($dest);
						break;
					case 4 :
						$im_src = imagecreatefromwbmp($dest);
						break;
					case 8 :
						$im_src = imagecreatefromwbmp($dest);
						break;
					default :
						return false;
				}			
				
				$width = imagesx($im_src);
				$height = imagesy($im_src);
				if($i == 1){
					$src_x = $width-1;
					$src_y = 0;   
					$src_width = -$width;
					$src_height = $height;
				}else{
					$src_x = 0;
					$src_y = 0;   
					$src_width = $width;
					$src_height = $height;
				}
				
				
				/*if ($i == 0) {
					($src_width >= $arr_dst_width[$i]) ? $dst_width = $arr_dst_width[$i] : $dst_width = $src_width;
				} else {
					$dst_width = $arr_dst_width[$i];
				}*/
				
				
				//( $img_type || ($i <= 1) ) ? $dst_height = floor(($dst_width/$src_width)*$src_height) : $dst_height = $arr_dst_height[$i];
				//( $img_type ) ? $dst_height = floor(($dst_width/$src_width)*$src_height) : $dst_height = $arr_dst_height[$i];
				

				
				
				//$dst_width = $src_width;
				//$dst_height = $src_height;
				
				//buat sesuai rasio width n heightnya
				/*if($i==1){
					$dst_height = $arr_dst_height[$i];
					$dst_width = floor(($dst_height/$src_height)*$src_width);
				}*/
				
				
				
				//Copy and resize part images 
				$im = imagecreatetruecolor($width,$height);
				$background = imagecolorallocate($im, 0, 0, 0);
				imagecolortransparent($im, $background);
				imagealphablending($im, false);
				imagesavealpha($im, true);
				if($i == 1){
					imagecopyresampled($im, $im_src, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height);
				}else{
					imagecopyresampled($im, $im_src, 0, 0, 0, 0, $width, $height, $src_width, $src_height);
				}	
				
				
				if (is_file($vdir.$idframe.$arr_img_size[$i].$ext)) {
					clearstatcache();
					unlink($vdir.$idframe.$arr_img_size[$i].$ext);
				}
								
				//copy images 
				if ($idjenis==24 || $idjenis==21 || $idjenis==22 || $idjenis==23){
					//buat kontribusi selalu jadiin jpg
					imagejpeg($im, $vdir.$idframe.$arr_img_size[$i].$ext);
				}else{
					switch ($img_size[2]) {
						case 1 :
							imagegif($im, $vdir.$idframe.$arr_img_size[$i].$ext);
							break;
						case 2 :
							//buat yang jenis smokin hot dan besarnya L ga usah dibuat
							if($idjenis==19 && $arr_img_size[$i]=="l"){	
								imagejpeg($im, $vdir.$idframe.$ext);
							}else{
								imagejpeg($im, $vdir.$idframe.$arr_img_size[$i].$ext);
							}
							break;
						case 3 :
							imagepng($im, $vdir.$idframe.$arr_img_size[$i].$ext);
							break;
						case 4 :
							imagewbmp($im, $vdir.$idframe.$arr_img_size[$i].$ext);
							break;
						default :
							return false;
					}
				}

				
				if($idjenis!=2 && $idjenis!=23){
					//if the pic is portrait, enter this part
					if ( $img_type && ($i > 1) ) {
						$destp = $cekdir.$foldfoto.$$idframe.$arr_img_size[$i].$ext;
						
						$dst_height = $arr_dst_height[$i];
						$im = imagecreatetruecolor($dst_width,$dst_height);
						
						switch ($img_size[2]) {
							case 1 :
								$im_src = imagecreatefromgif($destp);
								break;
							case 2 :
								$im_src = imagecreatefromjpeg($destp);
								break;
							case 3 :
								$im_src = imagecreatefrompng($destp);
								break;
							case 4 :
								$im_src = imagecreatefromwbmp($destp);
								break;
							case 8 :
								$im_src = imagecreatefromwbmp($destp);
								break;
							default :
								return false;
						}
						
						//Copy part images
						imagecopy($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height);
						if (is_file($vdir.$$idframe.$arr_img_size[$i].$ext)) {
							clearstatcache();
							unlink($vdir.$$idframe.$arr_img_size[$i].$ext);
						}
						
				
						if ($idjenis==24 || $idjenis==21 || $idjenis==22 || $idjenis==23){
							imagejpeg($im, $vdir.$$idframe.$arr_img_size[$i].$ext);
						}else{
							switch ($img_size[2]) {
								case 1 :
									imagegif($im, $vdir.$$idframe.$arr_img_size[$i].$ext);
									break;
								case 2 :
									imagejpeg($im, $vdir.$$idframe.$arr_img_size[$i].$ext);
									break;
								case 3 :
									imagepng($im, $vdir.$$idframe.$arr_img_size[$i].$ext);
									break;
								case 4 :
									imagewbmp($im, $vdir.$$idframe.$arr_img_size[$i].$ext);
									break;
								default :
									return false;
							}
						}
						
					}
				}
			} //end for 0-3
			
			
			
			imagedestroy($im_src);
			imagedestroy($im);
			if($idjenis!=19){
				//klo bukan jenis smokin hot delete gambar
				unlink($dest);
			}
		
			return $ffoto;

  }else{
    //failed to move_uploaded_file
    return false;
  }

} //end upload


?>