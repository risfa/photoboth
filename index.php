<?php
	ob_start();
	error_reporting(0);
	session_start();
	require_once("admin/assets/db/db.php");
	(isset($_SESSION["rfid"])) ? $rfid = $_SESSION["rfid"] : $rfid = "";
	if($rfid == ""){
		$_SESSION["peringatan"] = "<ul id=\"peringatan\">Harap Login terlebih dahulu</ul>";
		header("location:loginphoto.php");
		exit;
	}
	
	$rowframe = $db->get_row("select * from ms_frame where aktif = 1 order by id_frame desc ");
	if($rowframe){
			$frame = $rowframe->image;
	}
	header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
	// $framel = str_replace(".","l.",$frame);
	// $framer = "'admin".str_replace(".","r.",$frame)."'";

	// $framel = __DIR__.'/admin/assets/img/frames/1/1r.png';
	// $framer = 'admin/assets/img/frames/1/1ra.png';
	// $framel = '1.png';
	// $framer = '1.png';
	// (isset($_SESSION["random"])) ? $random = $_SESSION["random"] : $random = "";
	function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

	$random = uniqid();
	$gambar = $random;
	$_SESSION['random'] = $gambar;

?>
<html>
  <head>
    <meta name="keywords" content="Digital Agency, creativity, experience. specialize in developing digital matters and social media management."/>
    <meta name="description" content="Digital Agency, creativity, experience. specialize in developing digital matters and social media management."/> 		  
    <link rel="shortcut icon" href="http://static.wixstatic.com/ficons/bcc5ac_3f6862dec7e742beaa998ce75ab2ace3_fi.ico" type="image/png"/>	
    <meta name="google-site-verification" content="5Mv2EA9g4AGQHxViCcT0--vXsLgDBhnPxy4ONT-_J7g" />
    <title>Photobooth RFID</title>
		<style>
			a.print{
				background:url(admin/assets/images/printer.png) no-repeat left top;
				clear:both;
				right:45px;
				top:650px;
				position:absolute;
				height:200px;
				width:200px;
			}

			*/@font-face {
				font-family: 'Sonsie One';
				font-style: normal;
				font-weight: 400;
				src: local('Sonsie One'), local('SonsieOne-Regular'), url(admin/assets/fonts/Sonsie-One.woff) format('woff');
			}*/
		</style>
    <style type='text/css'  media='all'>
      @import 'css/style.css';
    </style>
    <style type='text/css'  media='all'>
      @import 'libraries/colorbox/example1/colorbox.css';
    </style>
    <style type='text/css'  media='print'>
      @import 'css/print.css';
    </style>
  </head>
  <body>



	 <div style="clear:both;margin:0 auto;width:1200px;position:relative;" >
		<div style="clear:both;float:left;display:inline;width:100%;height:900px;">	
						<div id="preload">
							<audio src="audio/beep.wav" hidden="true"></audio>
							<audio src="audio/camera1.wav" hidden="true"></audio>
						</div>	
						<div id="filmroll-wrapper">
							<div id="slot-wrapper">
								<div id="slot"></div>
							</div>
							<div id="filmroll">
								<h4>-PhotoBooth RFID-</h4>
							</div>
						</div>
						<div id="page">
							<div id="wrapper">
								<h1 style="margin-left: -350px;">PhotoBooth RFID</h1>
								<a  href="logout.php">Logout</a>
								
								<div id="video">
									<!-- <video id="live"  width="800px" height="560px" autoplay></video> -->
									<video id="live"  width="800px" height="560px" autoplay></video>
									<canvas id="snapshot" style="display:none"></canvas>
								</div>
								<img src="admin/assets/img/frames/1/Frame IG-02.png" class="bgframe" />	
								<!-- <img src="<?php // echo $framel ?>" class="bgframe" />				 -->
									<div id="buttonContainer">
										<a href="#" class="redButton" id="start"><br>Take Picture</a>
									</div><!-- coba lagi clear cache dlo ya okok sampe selesai ya? ok -->	
								<a href="#" id="snap" onClick="snap()"></a>
								<p class="countdown"></p>       
								<a href="index.php" class="countdown3" style="font-size: 40px;"></a>
								
								<a  style="font-size: 40px;" href="#" class="printer"></a>   

								<a  style="font-size: 40px;" href="http://127.0.0.1/hutpertamina60/instagram/instagram-amplified/index.php?image=<?php echo $gambar; ?>"  
								    onclick="window.open('http://127.0.0.1/hutpertamina60/instagram/instagram-amplified/index.php?image=<?php echo $gambar; ?>', 
								                         'newwindow', 
								                         'width=300,height=250'); 
								              return false;" class="countdown2"  style="font-size: 40px;">
								              </a>   


<!-- 									<a href="#" class="printer1231" onclick="VoucherPrint('saved_images/0005542074-1512961455.jpg'); return false;"> Print</a>
 -->								              	

							<!-- 	<div id="status"></div>
								<button onclick="uploadPhoto()">Upload Photo</button>
								<button onclick="getInfo()">Get Info</button>
								<button onclick="login()" id="login">Login</button> -->
							</div>
								
							</div>

						</div>


						<?php 

						// // footo  yang di bawah
						// 	$file2 = "https://5dapps.com/amplified/rfid/images/Continuous Improvement Program/".date('d M Y')."/".$_SESSION['rfid']."/".$_SESSION['rfid']."1foto.png";
						// 	$file = str_replace(' ', '%20', $file2);
						// 	echo $file;
						// 	echo "<img src='".$file."' >" ;

						?>
						 <script>
					video = document.getElementById("live")
					var onFailSoHard = function(e) {
						console.log('Reeeejected!', e);
					};   
					
					navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
					
					navigator.getUserMedia({video: true}, function(stream) {
						video.src = window.URL.createObjectURL(stream);
					}, onFailSoHard);  
					
					var xPosMoustache, yPosMoustache;
					
					function snap() {
						live = document.getElementById("live")
						snapshot = document.getElementById("snapshot")
						filmroll = document.getElementById("filmroll")

						// Make the canvas the same size as the live video
						snapshot.width = live.clientWidth
						snapshot.height = live.clientHeight
				
						// Draw a frame of the live video onto the canvas
						c = snapshot.getContext("2d");
						c.drawImage(live, 0, 0, snapshot.width, snapshot.height-20);
						
						
						// Overlay moustache
						moustache = new Image();
						moustache.src = 'admin/assets/img/frames/1/Frame IG-02.png';
						v = $("#live");				
						videoPosition = v.position();
						
						xPosMoustache = 40;
						yPosMoustache = 115;
						
						c.drawImage(moustache, xPosMoustache - videoPosition.left, yPosMoustache - videoPosition.top);
				
						// Create an image element with the canvas image data
						img = document.createElement("img")
						img.src = snapshot.toDataURL("image/png")
						// img.style.padding-top = 50
						img.style.padding = 5
						img.width = 260
						img.height = 180
				
						// Add the new image to the film roll
						filmroll.appendChild(img)
					}
			</script>
			<script src="js/jquery.min.js"></script>
			<script>
				$(window).ready(function(){
					var moustache = new Image();
					moustache.onload = function (){}
					moustache.src = 'admin/assets/img/frames/1/Frame IG-02.png';
				});


				  function VoucherSourcetoPrint(source) {
            return "<html><head><script>function step1(){\n" +
                    "setTimeout('step2()', 10);}\n" +
                    "function step2(){window.print();window.close()}\n" +
                    "</scri" + "pt></head><body onload='step1()'>\n" +
                    "<img style='height:384px; width:576px' src='" + source + "' /></body></html>";
        }
		        function VoucherPrint(source) {
		            Pagelink = "about:blank";
		            var pwa = window.open(Pagelink, "_new");
		            pwa.document.open();
		            pwa.document.write(VoucherSourcetoPrint(source));
		            pwa.document.close();
		        }



			</script>
			<script src="js/snapstr.js"></script>
			<script src="libraries/colorbox/colorbox/jquery.colorbox-min.js"></script>
			<?php
				$row = $db->get_row("select mm.*, nama_event from ms_member mm JOIN ms_event me on mm.id_event = me.id_event where id_rfid = ".$_SESSION["rfid"]);
				$date = date("d M Y");
				$date2 = date("Y-m-d");
				if($row){
					$getidfoto = $db->get_row("select photobooth from ms_event_arsip where cretime between '".$date2." 00:00:00' and '".$date2." 23:59:59' and id_member = ".$row->id_member." ");
					if($getidfoto){
						$idfoto = $getidfoto->photobooth;
					}else{
						$idfoto = 0;
					}
					$dir = "images/".$row->nama_event."/".$date."/".$row->id_rfid."/".$row->id_rfid."1foto".$idfoto.".png";
				}
				if($idfoto > 2){
			?>
			<a href="javascript:window.print()" class="print"><img src="<?php echo $dir; ?>" style="display:none"/></a>
			<?php
				}
			?>
			</div>	
			<script>
				
				var src_image = '<?php echo $gambar ;?>';
					$(".print").click(function(){
						setTimeout(function(){
							window.location.href = "thanks2.php";
						},1000);
					});
				


			   $("a.printer").click(function(){

			   		VoucherPrint('saved_images/' + src_image + '.png');

			   })

			</script>

			  	<!-- <script>
		// initialize and setup facebook js sdk
		window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '1745823622387024',
		      xfbml      : true,
		      version    : 'v2.5'
		    });
		    FB.getLoginStatus(function(response) {
		    	if (response.status === 'connected') {
		    		document.getElementById('status').innerHTML = 'We are connected.';
		    		document.getElementById('login').style.visibility = 'hidden';
		    	} else if (response.status === 'not_authorized') {
		    		document.getElementById('status').innerHTML = 'We are not logged in.'
		    	} else {
		    		document.getElementById('status').innerHTML = 'You are not logged into Facebook.';
		    	}
		    });
		};
		(function(d, s, id){
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) {return;}
		    js = d.createElement(s); js.id = id;
		    js.src = "//connect.facebook.net/en_US/sdk.js";
		    fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		// login with facebook with extened publish_actions permission
		function login() {
			FB.login(function(response) {
				if (response.status === 'connected') {
		    		document.getElementById('status').innerHTML = 'We are connected.';
		    		document.getElementById('login').style.visibility = 'hidden';
		    	} else if (response.status === 'not_authorized') {
		    		document.getElementById('status').innerHTML = 'We are not logged in.'
		    	} else {
		    		document.getElementById('status').innerHTML = 'You are not logged into Facebook.';
		    	}
			}, {scope: 'publish_actions'});
		}
		// getting basic user info
		//publish_actions
		function getInfo() {
			FB.api('/me', 'GET', {fields: 'first_name,last_name,name,id'}, function(response) {
				document.getElementById('status').innerHTML = response.id;
			});
		}
		// uploading photo on user timeline
		function uploadPhoto() {
			var photo = '<?php echo $file; ?>';
			FB.api('/me/photos', 'post', {url: 'https://5dapps.com/pict1.png'}, function(response) {
				if (!response || response.error) {
					document.getElementById('status').innerHTML = 'error';
				} else {
					document.getElementById('status').innerHTML = response.id;
				}
			});
		}

// http://5dapps.com/amplified/rfid/images/Continuous Improvement Program/24 Oct 2017/2017/20171foto.png

// 'https://5dapps.com/amplified/rfid/images/Continuous%20Improvement%20Program/24%20Oct%202017/2017/20171foto.png'

		// FB.api('/me/photos', 'post', {url: 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Facebook_New_Logo_%282015%29.svg/1200px-Facebook_New_Logo_%282015%29.svg.png'},
	</script> -->


		</div>		
   
  </body>
	<?php
		(isset($_SESSION["savefoto"])) ? $save = $_SESSION["savefoto"] : $save = "";
		if($save == "savefoto"){
			if($idfoto < 3){
	?>
	<meta http-equiv="refresh" content="60; url=thanks.php" />
	<?php
			}
		}
	?>
</html>