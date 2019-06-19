<?php
	$rsmenu = $db->get_results("SELECT vmenu, idmenu, vurl, numakses, tposisi FROM ms_admin_menu WHERE (ilevel=1) AND (ttampil=1) AND (".$varmenuwakses.") ORDER BY tposisi ASC");
if($rsmenu){
		
	echo "<ul id=\"adminmenu\">";
	$varmenutopfirst = 0;	
		
	for($imenu=0;$imenu<=(count($rsmenu)-1);$imenu++){
		$varclasses = "menu-top ";
		
		//buat icon menu
		switch($rsmenu[$imenu]->tposisi){
			//menu list admin
			case 96 : $varclasses .= "menu-users ";break;
			//menu list divisi
			case 97 : $varclasses .= "menu-users ";break;
			//menu Admin menu
			case 98 : $varclasses .= "menu-appearance ";break;
			//menu ubah pass
			case 99 : $varclasses .= "menu-tools ";break;
			//menu logout
			case 100 : $varclasses .= "menu-settings ";break;
			//menu lain
			default : $varclasses .= "menu-pages ";
		}
		
		//cek ada separator / tidak
		/*if($rsmenu[$imenu]->tsep==1){
			echo "
				<li class=\"wp-menu-separator\"><br /></li>
			";
			$varclasses .= "menu-top-first ";
		}else{
			if($varmenutopfirst == 0){
				$varclasses .= "menu-top-first ";
				$varmenutopfirst = 1;
			}
		}
		
		//cek last menu / bukan
		if(!isset($rsmenu[$imenu+1]->tsep)){
			$varclasses .= "menu-top-last ";
		}else{
			if($rsmenu[$imenu+1]->tsep==1){
				$varclasses .= "menu-top-last ";
			}
		}*/
		
		//cek current page / bukan
		if(isset($numakses) && is_numeric($numakses)){
			if($numakses == $rsmenu[$imenu]->numakses){
				$varclasses .= "wp-has-current-submenu wp-menu-open ";
			}else{
				if(strtolower($_SERVER['SCRIPT_NAME']) == strtolower($rsmenu[$imenu]->vurl)){
					$varclasses .= "wp-has-current-submenu wp-menu-open ";
				}
			}
		}
		
		//cek punya submenu / tidak
		$getsubmenu = $db->get_results("SELECT vmenu, vurl, numakses FROM ms_admin_menu WHERE ilevel=2 AND idparent=".$db->escape($rsmenu[$imenu]->idmenu)." ORDER BY tposisi ASC");
		if($getsubmenu){
			$varclasses .= "wp-has-submenu ";
		}
		
		//start print menu
		echo "
			<li class=\"".$varclasses."\">
				<div class=\"wp-menu-image\"><br /></div>
				<div class=\"wp-menu-toggle\"><br /></div>
				<a href=\"".$rsmenu[$imenu]->vurl."\" class=\"".$varclasses."\">".$rsmenu[$imenu]->vmenu."</a>
		";
		
		if($getsubmenu){
			echo "
				<div class=\"wp-submenu\">
					<ul>
			";
			
			foreach($getsubmenu as $rssubmenu){
				//cek current page / bukan
				$varcurrentsub = "";
				if(isset($numakses) && is_numeric($numakses)){
					if(strtolower($_SERVER['SCRIPT_NAME']) == strtolower($rssubmenu->vurl)){
						$varcurrentsub = " current";
					}else{
						if(strtolower($_SERVER['REQUEST_URI']) == strtolower($rssubmenu->vurl)){
							$varcurrentsub = " current";
						}
					}
				}
				echo "<li class=\"wp-first-item".$varcurrentsub."\"><a href=\"".$rssubmenu->vurl."\" class=\"wp-first-item".$varcurrentsub."\">".$rssubmenu->vmenu."</a></li>";				
			}
			
			echo "
					</ul>
				</div>
			";
		}
		
		echo "
			</li>
		";
		//end print menu
	}
	echo "</ul>";
}
?>

