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

require_once("".$pathdir."layout_header.php");

if(isset($_GET["id"])){
	$id = $_GET["id"];
}else{
	header("location:index.php");
	exit;
}
?>
<div class="wrap">
  <div id="icon-user-edit" class="icon32"><br />
  </div>
  <h2>Add Event</h2>
  <form name="frmadd" id="frmadd" method="post" action="add_.php" autocomplete="off" enctype="multipart/form-data">
		<input type='hidden' name='option_page' value='general' />
		<input type="hidden" name="action" value="update" />
		<table class="form-table">
			
		<?php
			$rowmember = $db->get_row("select * from ms_member where id_member = ".$id);
			
			if($rowmember){
				if($rowmember->kota_id != NULL && $rowmember->propinsi_id != NULL){
					$rowkota = $db->get_row("select * from ms_kota where kota_id = ".$rowmember->kota_id);
					$rowpropinsi = $db->get_row("select * from ms_propinsi where propinsi_id = ".$rowmember->propinsi_id);
					if($rowkota){
						$kota = $rowkota->kota_kabupaten;
					}else{
						$kota = "";
					}
					if($rowpropinsi){
						$propinsi = $rowpropinsi->propinsi;
					}else{
						$propinsi = "";
					}
				}				
			}
			
			(isset($rowmember->nama)) ? $nama = $rowmember->nama : $nama = "-";
			(isset($rowmember->alamat)) ? $alamat = $rowmember->alamat : $alamat = "-";
			(isset($rowmember->telp)) ? $telp = $rowmember->telp : $telp = "-";
			(isset($rowmember->hp)) ? $hp = $rowmember->hp : $hp = "-";
			(isset($rowmember->email)) ? $email = $rowmember->email : $email = "-";
			(isset($kota)) ? $kota = $kota : $kota = "-";
			(isset($propinsi)) ? $propinsi = $propinsi : $propinsi = "-";
			(isset($rowmember->agama)) ? $agama = $rowmember->agama : $agama = "-";
			(isset($rowmember->nomor_sim)) ? $nomor_sim = $rowmember->nomor_sim : $nomor_sim = "-";
			(isset($rowmember->tempat_lahir)) ? $tempat_lahir = $rowmember->tempat_lahir : $tempat_lahir = "-";
			(isset($rowmember->tgl_lahir)) ? $tgl_lahir = $rowmember->tgl_lahir : $tgl_lahir = "-";
			(isset($rowmember->gender)) ? $gender = $rowmember->gender : $gender = "-";
			(isset($rowmember->nama_ortu)) ? $nama_ortu = $rowmember->nama_ortu : $nama_ortu = "-";
			(isset($rowmember->nik)) ? $nik = $rowmember->nik : $nik = "-";
			(isset($rowmember->golongan_darah)) ? $golongan_darah = $rowmember->golongan_darah : $golongan_darah = "-";
			(isset($rowmember->hobi)) ? $hobi = $rowmember->hobi : $hobi = "-";
			(isset($rowmember->pekerjaan)) ? $pekerjaan = $rowmember->pekerjaan : $pekerjaan = "-";
			(isset($rowmember->pendidikan)) ? $pendidikan = $rowmember->pendidikan : $pendidikan = "-";
			(isset($rowmember->kewarganegaraan)) ? $kewarganegaraan = $rowmember->kewarganegaraan : $kewarganegaraan = "-";
			(isset($rowmember->status)) ? $status = $rowmember->status : $status = "-";
			(isset($rowmember->p21)) ? $p21 = $rowmember->p21 : $p21 = "-";
			(isset($rowmember->p22)) ? $p22 = $rowmember->p22 : $p22 = "-";
			(isset($rowmember->p23)) ? $p23 = $rowmember->p23 : $p23 = "-";
			(isset($rowmember->p24)) ? $p24 = $rowmember->p24 : $p24 = "-";
			(isset($rowmember->p25)) ? $p25 = $rowmember->p25 : $p25 = "-";
			(isset($rowmember->p26)) ? $p26 = $rowmember->p26 : $p26 = "-";
			(isset($rowmember->p27)) ? $p27 = $rowmember->p27 : $p27 = "-";
			(isset($rowmember->p28)) ? $p28 = $rowmember->p28 : $p28 = "-";
			(isset($rowmember->p29)) ? $p29 = $rowmember->p29 : $p29 = "-";
			(isset($rowmember->p30)) ? $p30 = $rowmember->p30 : $p30 = "-";

					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Nama : </label></th>".
							"<td>".
								"<span>".$nama."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Alamat : </label></th>".
							"<td>".
								"<span>".$alamat."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Telepon : </label></th>".
							"<td>".
								"<span>".$telp."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Hp : </label></th>".
							"<td>".
								"<span>".$hp."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Email : </label></th>".
							"<td>".
								"<span>".$email."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Kota : </label></th>".
							"<td>".
								"<span>".$kota."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Propinsi : </label></th>".
							"<td>".
								"<span>".$propinsi."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Agama : </label></th>".
							"<td>".
								"<span>".$agama."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> SIM : </label></th>".
							"<td>".
								"<span>".$nomor_sim."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Tempat Lahir : </label></th>".
							"<td>".
								"<span>".$tempat_lahir."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Tanggal Lahir : </label></th>".
							"<td>".
								"<span>".$tgl_lahir."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Gender : </label></th>".
							"<td>".
								"<span>".$gender."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Nama Ortu : </label></th>".
							"<td>".
								"<span>".$nama_ortu."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Nomor KTP : </label></th>".
							"<td>".
								"<span>".$nik."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Golongan Darah : </label></th>".
							"<td>".
								"<span>".$golongan_darah."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Hobi : </label></th>".
							"<td>".
								"<span>".$hobi."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Pekerjaan : </label></th>".
							"<td>".
								"<span>".$pekerjaan."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Pendidikan : </label></th>".
							"<td>".
								"<span>".$pendidikan."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Kewarganegaraan : </label></th>".
							"<td>".
								"<span>".$kewarganegaraan."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> Status : </label></th>".
							"<td>".
								"<span>".$status."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P21 : </label></th>".
							"<td>".
								"<span>".$p21."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P22 : </label></th>".
							"<td>".
								"<span>".$p22."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P23 : </label></th>".
							"<td>".
								"<span>".$p23."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P24 : </label></th>".
							"<td>".
								"<span>".$p24."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P25 : </label></th>".
							"<td>".
								"<span>".$p25."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P26 : </label></th>".
							"<td>".
								"<span>".$p26."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P27 : </label></th>".
							"<td>".
								"<span>".$p27."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P28 : </label></th>".
							"<td>".
								"<span>".$p28."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P29 : </label></th>".
							"<td>".
								"<span>".$p29."</span>".
							"</td>".
						"</tr>";
						
					echo "
						<tr valign=\"top\">".
							"<th scope=\"row\"><label for=\"txtjudul\"> P30 : </label></th>".
							"<td>".
								"<span>".$p30."</span>".
							"</td>".
						"</tr>";
						
					
		?>	
		</table>
	
		<p class="submit"><input type="submit" name="btnsubmit"  onclick="window.history.back();" class="button-primary" value="Back" />
  </form>
</div>
<div class="clear"></div>
<?php 
set_session();
require_once("../layout_footer.php");
?>