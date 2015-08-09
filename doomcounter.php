<?php

	if (!$_GET['notice']) {
		
		header("Pragma: no-cache;");
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

		$image	= imagecreatetruecolor(116, 19);


		$offsets	= array( 0, 1, -1, 2, 0, 1, -1, 1, 0, 2);

		shuffle($offsets);

#		for ($i = 0; $i < 10; $i++) {
#			$offsets[$i]	= mt_rand(-1, 3);
#		}


		switch (((time() % 300) / 300 * 7) % 7) {
			case 0:
				$color['text']			= imagecolorallocatealpha($image,   0,   0, 128,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 225, 210, 255,  10);
				break;

			case 1:
				$color['text']			= imagecolorallocatealpha($image,   0,  64,  64,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 210, 225, 255,  10);
				break;

			case 2:
				$color['text']			= imagecolorallocatealpha($image,   0,  96,   0,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 225, 255, 225,  10);
				break;

			case 3:
				$color['text']			= imagecolorallocatealpha($image,  99,  44,   0,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 255, 255, 210,  10);
				break;

			case 4:
				$color['text']			= imagecolorallocatealpha($image, 128,  22,  20,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 255, 244, 222,  10);
				break;

			case 5:
				$color['text']			= imagecolorallocatealpha($image, 128,   0,   0,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 255, 212, 232,  10);
				break;

			case 6:
				$color['text']			= imagecolorallocatealpha($image,  64,   0, 128,  50);
				$color['shadow']		= imagecolorallocatealpha($image, 235, 212, 255,  10);
		}
#		$color['text']			= imagecolorallocatealpha($image,   0,   0,   0,  50);
#		$color['shadow']		= imagecolorallocatealpha($image, 255,   0,   0,  10);

		imagealphablending($image, false);
		imagefilledrectangle($image, 0, 0, 500, 500, imagecolorallocatealpha($image, 255, 0, 0, 127));
		imagealphablending($image, true);
		imagesavealpha($image, true);
	//	imagerectangle($image, 0, 0, 113, 18, 0xFF0000);

		$time	= mktime(  0,  0,  0,  4, 5, 2012) - microtime(true);
		$time	= floor($time * (1000000 / 86400));

		#$time	= microtime(true) - (time() - time() % 86400);
		#$time	= floor($time * (1000000 / 86400));

		$xpos	= 1;
		$ypos	= 16;

//		$time	= "---------";

		lazy($image, $xpos - 1, $ypos - 1, $time, $color['shadow']);
		lazy($image, $xpos - 1, $ypos + 1, $time, $color['shadow']);
		lazy($image, $xpos + 1, $ypos - 1, $time, $color['shadow']);
		lazy($image, $xpos + 1, $ypos + 1, $time, $color['shadow']);
		lazy($image, $xpos - 1, $ypos    , $time, $color['shadow']);
		lazy($image, $xpos + 1, $ypos    , $time, $color['shadow']);
		lazy($image, $xpos    , $ypos - 1, $time, $color['shadow']);
		lazy($image, $xpos    , $ypos + 1, $time, $color['shadow']);
		lazy($image, $xpos    , $ypos    , $time, $color['text']);

		if (!$_GET['x'] && false) {
			$image2	= imagecreatetruecolor(116, 19);
			imagealphablending($image2, false);
			imagealphablending($image, false);
			imagefilledrectangle($image2, 0, 0, 115, 18, imagecolorallocatealpha($image, 0, 0, 0, 127));
			imagecopy($image2, $image, 0, 0, 0, 0, 116, 19);
			imagefilledrectangle($image, 0, 0, 115, 18, imagecolorallocatealpha($image, 0, 0, 0, 127));
			for ($y = 0; $y < 19; $y++) {
				$xofs	= 0;
				if (mt_rand(0, 10) < 1) {
//					$xofs	= mt_rand(-15, 15);
					$xofs	= mt_rand(-1, 1);
//					$xofs	= 1;
				}
				imagecopy($image, $image2, $xofs, $y, 0, $y, 116, 1);
			}
		}

		header("Content-type: image/png;");
		imagepng($image);
		imagedestroy($image);

	
	
	
	} else {

		header("Pragma: no-cache;");
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

		$image	= imagecreatetruecolor(98, 67);


		$color['text']		= imagecolorallocatealpha($image, 225, 210, 255,   0);
		$color['shadow']	= imagecolorallocatealpha($image, 255,   0,   0,  50);

		imagealphablending($image, false);
		imagefilledrectangle($image, 0, 0, 500, 500, imagecolorallocatealpha($image, 255, 0, 0, 127));
		imagealphablending($image, true);
		imagesavealpha($image, true);


		$xpos	= 1;
		$ypos	= 14;

		$str	= "Notice this ››";

		lazy2($image, $xpos - 1, $ypos - 1, $str, $color['shadow']);
		lazy2($image, $xpos - 1, $ypos + 1, $str, $color['shadow']);
		lazy2($image, $xpos + 1, $ypos - 1, $str, $color['shadow']);
		lazy2($image, $xpos + 1, $ypos + 1, $str, $color['shadow']);
		lazy2($image, $xpos - 1, $ypos    , $str, $color['shadow']);
		lazy2($image, $xpos + 1, $ypos    , $str, $color['shadow']);
		lazy2($image, $xpos    , $ypos - 1, $str, $color['shadow']);
		lazy2($image, $xpos    , $ypos + 1, $str, $color['shadow']);
		lazy2($image, $xpos    , $ypos    , $str, $color['text']);

		header("Content-type: image/png;");
		imagepng($image);
		imagedestroy($image);
	}

	function lazy($image, $x, $y, $t, $c) {
		global $offsets;
		$t		= str_pad($t, 9, " ", STR_PAD_LEFT);
		$len	= strlen($t);
		for ($i = 0; $i < $len; $i++) {
			imagettftext($image, 24, 0, $x + $i * 11, $y + $offsets[$i], $c, "images/angsa.ttf", $t{$i});
		}
		
		imagettftext($image, 20, 0, $x + 1 + 11 * strlen($t), $y - 2, $c, "images/angsa.ttf", "tu");
	}

	function lazy2($image, $x, $y, $t, $c) {
		global $offsets;
		$t		= str_pad($t, 9, " ", STR_PAD_LEFT);
		imagettftext($image, 24, 330, $x, $y, $c, "images/angsa.ttf", $t);
		
	}