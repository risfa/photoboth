<?php
session_start();
ini_set('display_errors', 1);
error_reporting(error_reporting() & ~E_STRICT);
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once($pathdir."includes/function.php");
$opt = $db->query("SET character_set_results=utf8");

if(!isset($_GET["id"])){
  header("Location:index.php");
  exit();
}else{
  $id=$_GET["id"];
}

//konvert to excel
$strsql="select mea.*, me.nama_event from ms_event_arsip mea join ms_event me on mea.id_event = me.id_event where mea.id_event=".$id." order by cretime desc";
$result = $db->get_results($strsql);
$row2 = $db->get_row($strsql);
  if($row2){
    
    header("Content-type: application/x-msdownload"); 
    header("Content-Disposition: attachment; filename=Report_Peserta_Event_".$row2->nama_event.".xls"); 
    header("Pragma: no-cache"); 
    header("Expires: 0");
		
		
?>
	<span>Event( <?php echo $row2->nama_event; ?> )</span>
<?php
	}
?>
	<table class="widefat" cellspacing="0" width="100%" border="1">			
		<tr>
			<th>No</th>
			<th>Nama Member</th>
			<th>Nama Event</th>
			<th>Post Location</th>
			<th>Photobooth</th>
			<th>Sequence</th>
			<th>Cretime</th>
		</tr>
	<?php
		if ($result) {
			$a=1;		
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
				echo "<td>".$a."</td>";
				echo "<td>".$namamember."</td>";
				echo "<td>".$namaevent."</td>";
				echo "<td>".$row->location."</td>";
				echo "<td>".$row->photobooth."</td>";
				echo "<td>".$row->sequence."</td>";
				echo "<td>".$vartglcomplete."</td>";
				
				$a++;
			}
		}else{
			echo "<td class=\"column-center\" colspan=\"7\"><strong style=\"color:red;\">There's no data</strong></td>";
		}
		echo "</tr>";
		echo "</table>";
  ?>
