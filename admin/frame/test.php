<?php
function ImageFlip ( $imgsrc, $mode )
{
		$imgsrc=imagecreatefrompng($imgsrc);

    $width                        =    imagesx ( $imgsrc );
    $height                       =    imagesy ( $imgsrc );

    $src_x                        =    0;
    $src_y                        =    0;
    $src_width                    =    $width;
    $src_height                   =    $height;

    switch ( $mode )
    {

        case '1': //vertical
            $src_y                =    $height -1;
            $src_height           =    -$height;
        break;

        case '2': //horizontal
            $src_x                =    $width -1;
            $src_width            =    -$width;
        break;

        case '3': //both
            $src_x                =    $width -1;
            $src_y                =    $height -1;
            $src_width            =    -$width;
            $src_height           =    -$height;
        break;

        default:
            return $imgsrc;

    }

    $imgdest                    =    imagecreatetruecolor ( $width, $height );
		$background = imagecolorallocate($imgdest, 0, 0, 0);
		imagecolortransparent($imgdest, $background);
		imagealphablending($imgdest, false);
		imagesavealpha($imgdest, true);
    if ( imagecopyresampled ( $imgdest, $imgsrc, 0, 0, $src_x, $src_y , $width, $height, $src_width, $src_height ) )
    {
        return $imgdest;
    }

    return $imgsrc;

}


$path   = '../assets/img/frames/2';
$file   = '2.png';
$degrees = 90;

header('Content-type: image/png');

$filename = $path . "/" .$file;



//$source = imagecreatefrompng($filename) or notfound();
//$rotate = imagerotate($source,$degrees,0);

$rotate=ImageFlip($filename,2);

imagepng($rotate,"testing");
imagedestroy($source);
imagedestroy($rotate);

?>