<?php

		
	session_start();

	// error_reporting(1);
	// require_once("admin/assets/db/db.php");
	date_default_timezone_set("Asia/Bangkok");
	// ini_set('mysql.connect_timeout', 14400);
	// ini_set('default_socket_timeout', 14400);
	

		
	// $url="saved_images/test.png";
	// $contents=file_get_contents($url);
	// $save_path="saved_images/testBak.png";
	// if(!file_put_contents($save_path,$contents)){
	// 	print('error');
	// }


	// if(!move_uploaded_file('../test.png', '../testBak.png')){
	// 	print 'error';
	// }

	// if(!move_uploaded_file('test.png',  'testBak.png')){
	// 	print 'error2';
	// }

			// $file1 = $_SERVER['DOCUMENT_ROOT'].'/photobooth/test.png';
			// $file2 = $_SERVER['DOCUMENT_ROOT'].'/photobooth/testBak.png';
			// var_dump($file1);

			// $placee = copy($file1,  $file2);
			// $move = move_uploaded_file($file1, $file2i);

			// $move2 = move_uploaded_file(__DIR__ . 'test.png', __DIR__ .'test.png');
			// var_dump($placee2);
			// var_dump($placee);
			// var_dump($_SERVER['DOCUMENT_ROOT']);

	(isset($_SESSION["random"])) ? $random = $_SESSION["random"] : $random = "";



		$post = $_POST['img'];


		$img = str_replace('data:image/png;base64,', '', $post);
		$img = str_replace(' ', '+', $img);
		$img = base64_decode($img);

		// $random = uniqid();

		if(file_put_contents('saved_images/'.$random. '.png', $img)){
			        chmod('saved_images/'.$random. '.png',0777);
		}


		png2jpg('saved_images/'. $random.'.png','saved_images/'.$random. '.jpg',100);
			chmod('saved_images/'.$random. '.jpg',0777);
		

	function png2jpg($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}


	// 	$images = explode('data:image/png;base64,', $post);
	// 	$i = 0;
	// 	foreach($images as $img) {
	// 		if (strlen($img) > 1) {
	// 			$img = str_replace(' ', '+', $img);
	// 			$data = base64_decode($img);
	// 			// $uniq = $dir3. $i."foto";
	// 			$file = 'saved_images/test.png';
	// 			$success = file_put_contents($file, $data);

	// 			// echo $success;die;
	// 			$width=1000;
	// 			$height=700;
	// 			if($success != 0 || $success != "" || $success != NULL || !is_numeric($success)){
	// 				$src = imagecreatefrompng($file);
	// 				$src = image_flip($src, 'horiz');
	// 				$x = imagesx($src);
	// 				$y = imagesy($src);
	// 				$truecolor = imagecreatetruecolor($width,$height);
								
	// 				// Copy and merge
	// 				if(!ImageCopyResampled($truecolor, $src, 0, 0, 0, 0, $width, $height,$x,$y)) {
	// 					print "IMAGE COPY FAILED";
	// 					return FALSE;
	// 				}
	// 				// Output and free from memory
	// 				header('Content-Type: image/png');
	// 				if(!imagepng($truecolor, $file)) {
	// 					print "IMAGE CREATE FAILED";
	// 					return FALSE;
	// 				}
	// 				echo $src;
	// 				imagedestroy($src);	  
	// 			}
	// 		}
	// 		$i++;
	// 	}
	// // }
	
	// $_SESSION["savefoto"] = "savefoto";
	
function image_flip($img, $type=''){
    $width  = imagesx($img);
    $height = imagesy($img);
    $dest   = imagecreatetruecolor($width, $height);
    switch($type){
        case '':
            return $img;
        break;
        case 'vert':
            for($i=0;$i<$height;$i++){
                imagecopy($dest, $img, 0, ($height - $i - 1), 0, $i, $width, 1);
            }
        break;
        case 'horiz':
            for($i=0;$i<$width;$i++){
                imagecopy($dest, $img, ($width - $i - 1), 0, $i, 0, 1, $height);
            }
        break;
        case 'both':
            for($i=0;$i<$width;$i++){
                imagecopy($dest, $img, ($width - $i - 1), 0, $i, 0, 1, $height);

            }
            $buffer = imagecreatetruecolor($width, 1);
            for($i=0;$i<($height/2);$i++){
                imagecopy($buffer, $dest, 0, 0, 0, ($height - $i -1), $width, 1);
                imagecopy($dest, $dest, 0, ($height - $i - 1), 0, $i, $width, 1);
                imagecopy($dest, $buffer, 0, $i, 0, 0, $width, 1);
            }
            imagedestroy($buffer);
        break;
    }
    return $dest;
}
?>