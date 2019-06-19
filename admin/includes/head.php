	<div id="wphead"> 
		<img id="header-logo" src="/rfid/admin/images/wp-logo.gif" alt="" width="32px" height="32px" />
		<h1 ><a href="/rfid/index.php" target="_blank" title="Visit site">Admin RFID <span>&larr; Visit site</span></a></h1>
	
		<div id="wphead-info">
			<div id="user_info">
				<p>Hello, <a href="/rfid/admin/setting/gantipass.php" title="Ganti Pass"><?php echo $_SESSION["ssname"];?></a> | <a href="/rfid/admin/logout.php" title="Log Out">Log Out</a></p>
			</div>		
			<?php
      //variabel varmenuwakses dari layout_header.asp
			/*$getshortcut = $db->get_results("SELECT vmenu, vurl FROM ms_admin_menu WHERE (ttampil=1) AND (".$varmenuwakses.") ORDER BY tposisi ASC");
			if($getshortcut){
				echo "
					<div id=\"favorite-actions\">
						<div id=\"favorite-first\"><a href=\"#\" onclick=\"return false;\">SHORTCUT</a></div>
						<div id=\"favorite-toggle\"><br /></div>
						<div id=\"favorite-inside\">
					";
				
				foreach($getshortcut as $rssc){
					echo "<div class=\"favorite-action\"><a href=\"".$rssc->vurl."\">".$rssc->vmenu."</a></div>";
				}
					
				echo"	
						</div>
					</div>
				";
			}*/
			?>
	</div>
</div>

