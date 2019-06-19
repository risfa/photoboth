<?php


function issel($a,$b,$c,$d){
	if($a==$b){
		return $c;
	}else{
		return $d;
	}
}

function set_session() {
  $mgName = $_SESSION["ssname"];
  $mgID = $_SESSION["ssid"];
  $mgnis = $_SESSION["ssnis"];

  session_unset();
  $_SESSION["ssname"] = $mgName;
  $_SESSION["ssnis"] = $mgnis;
  $_SESSION["ssid"] = $mgID;
}

function set_page() {
  $page = 1;
  if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = $_GET["page"];
  }
  return $page;
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


function set_submit($nama) {
	return "<p class=\"submit\"><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".$nama."\" /></p>";
}


?>