<?php
session_start();
error_reporting(error_reporting() & ~E_STRICT);
$pathdir = "/";
require_once("assets/db/db.php");
require_once("includes/function.php");

//get cookies, klo ada cookies = langsung login
if(isset($_COOKIE["vusn"])){
	$row = $db->get_row("SELECT id,name,username,lvl,akses,vip,tdiv, vlastip from ms_admin WHERE vhash = '".$db->escape($_COOKIE["vusn"])."' AND taktif=1");
	if($row){
		if($row->vip!="" && !is_null($row->vip)){
		
			$varip = $_SERVER['REMOTE_ADDR'];		
			
			//cek klo ipnya sama = login
			if($row->vip==$varip){			
				//set sessions
				$_SESSION["ssname"]=$row->name;
				$_SESSION["ssusername"]=$row->username;
				$_SESSION["ssid"]=$row->id;
				$_SESSION["ssLV"]=$row->lvl;
				$_SESSION["ssakses"]=$row->akses;
				
				//edit lastlogin + add ip	
				if(is_null($row->vlastip) || $row->vlastip==""){
					$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vlastip=';".$db->escape($varip).";', vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
				}else{
					$sqladdip = $row->vlastip;
					if(strpos($sqladdip,$varip)==false){
						$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vlastip='".$db->escape($sqladdip).$varip.";', vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
					}else{
						$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
					}
				}//end if(is_null($row->vlastip) || $row->vlastip==""){
				
				//redirect ke halaman yang dia ada akses
				$strsql = "SELECT vurl, numakses FROM ms_admin_menu WHERE ttampil=1 AND idparent=0 ORDER BY tposisi";
				$result = $db->get_results($strsql);
				if($result){
					foreach($result as $row){
						if(CekDiv($_SESSION["ssid"],$row->numakses)){
							header("Location: ".$row->vurl."");
							exit;
						}
					}
				}
				
			}//end if($row->vip==$varip){			
		}
	}
}


if (isset($_POST['Submit'])){

	
	if (strcasecmp($_POST['Submit'],"Log In")==0){
		
		$name=trim($_POST['name']);
		$pass=trim($_POST['pwd']);
		$valid=1;
		
		$row = $db->get_row("select name, id, username, lvl, akses,tdiv, vlastip from ms_admin where username='".$db->escape($name)."' and password='".md5($db->escape($pass))."' AND taktif=1");
		
		
		if ($row) {
			
			//set cookie buat remember me
			if(isset($_POST["rememberme"]) && $_POST["rememberme"]=="forever"){
				$kdhash = md5("usn".time());				
				setcookie("vusn", $kdhash, time()+2592000);
				$inserthash = $db->query("update ms_admin set vhash='".$kdhash."' where id=".$row->id);
				
			}else{ //else if($_POST["rememberme"]=="forever"){			
				setcookie("vusn", "", time() - 3600);			
			}//end if($_POST["rememberme"]=="forever"){
			
			//set sessions
				$_SESSION["ssname"]=$row->name;
				$_SESSION["ssusername"]=$row->username;
				$_SESSION["ssid"]=$row->id;
				$_SESSION["ssLV"]=$row->lvl;
				$_SESSION["ssakses"]=$row->akses;
			
			//edit lastlogin + add ip
			$varip = $_SERVER['REMOTE_ADDR'];
			if(is_null($row->vlastip) || $row->vlastip==""){
				$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vlastip=';".$db->escape($varip).";', vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
			}else{
				$sqladdip = $row->vlastip;
				if(strpos($sqladdip,$varip)==false){
					$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vlastip='".$db->escape($sqladdip).$varip.";', vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
				}else{
					$lastlog = $db->query("UPDATE ms_admin SET lastlogin=NOW(), vip='".$db->escape($varip)."' WHERE id=".$db->escape($row->id));
				}
			}
			
			//redirect ke halaman yang dia ada akses
			$strsql = "SELECT vurl, numakses FROM ms_admin_menu WHERE ttampil=1 AND idparent=0 ORDER BY tposisi";
			$result = $db->get_results($strsql);
			
			if($result){
				foreach($result as $row){

					if(CekDiv($_SESSION["ssid"],$row->numakses)){
	
						header("Location: ".$row->vurl."");
						exit;
					}
				}
			}
			
			
			$_SESSION["err"]="<span>Anda tidak mempunyai akses</span>";
			header("Location: index.php");
			exit();
		
		} else { //else if ($row) {
		
			$_SESSION["err"]="<span>username / password salah</span>";
			//echo $_SESSION["err"];
			//exit();
			header("Location: index.php");
			exit();
			
		}//end if ($row) {
	}//end if (strcasecmp($_POST['Submit'],"Log In")==0){
}//end if (isset($_POST['Submit'])){

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>RFID &rsaquo; Log In</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel='stylesheet' href='assets/css/homebackend.css' type='text/css' media='all' />

</head>

<body class="login">
	<div class="container">
	<section id="content">
		<form action="index.php" method="post">
			<h1>Login Admin</h1>
		<?php
			if(isset($_SESSION["err"])){
				echo "<div id=\"login_error\">".$_SESSION["err"]."</div>";
				unset($_SESSION["err"]);	
			}
			?>
			<div>
				<input type="text" placeholder="Username" name="name" required="" id="username" />
			</div>
			<div>
				<input type="password" placeholder="Password" name="pwd" required="" id="password" />
			</div>
			<div>
				<input type="submit" name="Submit" value="Log in" />
			</div>
		</form><!-- form -->
	</section><!-- content -->
</div><!-- container -->
<script type="text/javascript">
try{document.getElementById('username').focus();}catch(e){}
</script>
</body>
</html>
