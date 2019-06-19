<?php
session_start();ini_set('display_errors', 1);error_reporting(error_reporting() & ~E_STRICT);
$strcss = "global,reset,bootstrap.min";
$strjs = "jquery,html5";
$bodyid = "bodyhome";
$pathdir = "./";
$judul_head = "RFID";
require_once("includes/top2.php");

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT ); 
?>
		<div class="container">
				
					<div class="col-lg-12 bgLogin" align="center" style="height:600px;width:100%;margin-top:30px;">
						<form action="loginphoto_.php" method="post">
							
							<input class="inputLogin" type="text" name="rfid" required="" id="rfid" />
						</form>
					</div> 
					<!--<div class="col-md-8" style="background-color:white;border-radius:10px;padding:50px;margin-bottom:30px;height:573px;">
							
								<form action="loginphoto_.php" method="post">
									<h1>Photobooth</h1>
										
									<div>
										<input class="form-control" type="text" placeholder="RFID" name="rfid" required="" id="rfid" />						
									</div>
								</form><!-- form -->
							
					<!--</div>	
					<div class="col-md-4">
                        <div class="col-md-12"  style="background-color:white;border-radius:10px;padding:50px;height:573px;vertical-align:middle;">
                        	<center><h1>Side Banner</h1></center>
                        </div>
				
						
					</div>
                     <div class="col-md-12" style="padding-bottom:30px">
                        <img src="admin/assets/images/logo_amplified_footer.png" width="500px" />
                    </div>-->
							
		</div>
		<div class="container">
			<div class="col-md-2"></div>
			 <div class="col-md-8" align="center" style="padding-bottom:30px">
				<?php 
					if (isset($_SESSION["peringatan"])){
						echo "<h4 style=\"color:red\">".$_SESSION["peringatan"]."</h4><br>";
						unset($_SESSION["peringatan"]);
					}
				?>
				<img src="admin/assets/images/logo_amplified_footer.png" width="404px"/>
			</div>
			<div class="col-md-2"></div>
		</div>
		<!-- container -->
		<!--<form id="rfid" name="rfid" method="post" action="index_.php">
			<div style="width:35%;height:auto;float:left;">
				<label style="text-align:center;font-weight:bold;font-size:18px;margin-left:10px">RFID </label>
				<input style="margin-left:10px;" name="rfid" id="txt1" class="textbox" type="text">
			</div>
		</form>-->
<script type="text/javascript">
try{document.getElementById('rfid').focus();}catch(e){}
</script>
	</body>
</html>
