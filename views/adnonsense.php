<?php

//	die();

	require_once "../lib/function.php";
	
	header("Cache-Control: max-age=43200");
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + 43200).' GMT');

	$img	= imagecreatetruecolor(622, 22);	
	imagealphablending($img, false);
	imagesavealpha($img, true);
	imagefilledrectangle($img, 0, 0, 888, 888, imagecolorallocatealpha($img, 255, 0, 0, 127));

	$font	= imageloadfont("images/terminal6.gdf");

	imageline($img,  21,   0, 621,   0, 0x000000);
	imageline($img,   0,  21, 600,  21, 0x000000);
	imageline($img,   0,  21,  21,   0, 0x000000);
	imageline($img, 600,  21, 621,   0, 0x000000);


	$data	= $sql -> fetchq("SELECT `donations`, `ads`, `valkyrie` FROM `misc`");
	$bonusr	= 0;
	$bonusg	= 0;
	$bonusb	= 0;

	if ($_GET['m'] == "d") {
		$money	= $data['donations'];
		$text	= "Donations";
		$textc	= imagecolorallocatealpha($img,  80, 200,  80, 40);
		if ($money >= 120) {
			$money	-= 120;
			$bonusr	= -.5;
			$bonusg	= .1;
			$bonusb	= -.5;
			$bonusm	= 120;
		}
	} elseif ($_GET['m'] == "t") {
		$money	= $data['donations'] + $data['ads'];
		$text	= "Total";
		$textc	= imagecolorallocatealpha($img, 140, 140, 255, 40);
		if ($money >= 120) {
			$money	= min(120, $data['donations']) + $data['ads'] - 120;	// Extra donations don't count towards extra funding
//			$money	= $data['donations'] + $data['adsense'] - 120;
//			$text	= "Extra!";
			$bonusr	= -.5;
			$bonusg	= -.5;
			$bonusb	= .3;
		}

	} elseif ($_GET['m'] == "v") {
		$money	= $data['valkyrie'];
		$text	= "VPS fund";
		$textc	= imagecolorallocatealpha($img, 140, 140, 255, 40);
		if ($money >= 120) {
			$money	-= 120;
			$bonusr	= -.5;
			$bonusg	= -.5;
			$bonusb	= .3;
		}
	} else {
		$money	= $data['ads'];
		$text	= "Ad rev.";
		$textc	= imagecolorallocatealpha($img, 255,  80,  80,  40);
		if ($money >= 120) {
			$money	-= 120;
			$bonusr	= .3;
			$bonusg	= -.3;
			$bonusb	= -.3;
			$bonusm	= 120;
		}
	}

	
	for ($i = 0; $i < 600; $i++) {
		$c	= floor($i / 600 * 100) + 27;
		if ($i % 50 == 0) $c = floor($c * 0.8);
		if ($i >= 597) $c = floor($c * (1.3 + ($i == 599 ? 0.4 : 0.0)));
		if ($i <= 2) $c = floor($c * (1.3 + ($i == 0 ? 0.4 : 0.0)));

		fillbar($img, $i, $c * (1 + $bonusr ), $c * (1 + $bonusg ), $c * (1 + $bonusb ));
	}

	$max	= min(600, ($money / 120) * 600);

	for ($i = 0; $i < $max; $i++) {
		if ($_GET['m'] == "d") {	// Donations use a different color scheme
			$r	=  20 + floor($i / 600 * 100);
			$g	=  80 + floor($i / 600 * 170);
			$b	=  25 + floor($i / 600 * 110);
		} elseif ($_GET['m'] == "t" || $_GET['m'] == "v") {
			$b	= 100 + floor($i / 600 * 150);
			$g	=  20 + floor($i / 600 *  60);
			$r	=  25 + floor($i / 600 *  80);
		} else {
			$r	= 100 + floor($i / 600 * 150);
			$g	=  20 + floor($i / 600 *  60);
			$b	=  25 + floor($i / 600 *  80);
		}
		if ($i % 50 == 0 && $i <= $max - 3) {
			$r = floor($r * 0.8);
			$g = floor($g * 0.8);
			$b = floor($b * 0.8);
		} elseif ($i >= $max - 3) {
			$r = floor($r * (1.3 + ($i == $max - 1 ? 0.4 : 0.0)));
			$g = floor($g * (1.3 + ($i == $max - 1 ? 0.4 : 0.0)));
			$b = floor($b * (1.3 + ($i == $max - 1 ? 0.4 : 0.0)));
		} elseif ($i <= 2) {
			$r = floor($r * (1.3 + ($i == 0 ? 0.4 : 0.0)));
			$g = floor($g * (1.3 + ($i == 0 ? 0.4 : 0.0)));
			$b = floor($b * (1.3 + ($i == 0 ? 0.4 : 0.0)));
		}


		fillbar($img, $i, $r, $g, $b);
/*		imageline($img, $i + 1,  20, $i + 20,   1, 0x010101 * $c);
		imagesetpixel($img, $i + 20,  1, 0x010101 * min(255, floor($c * 1.7)));
		imagesetpixel($img, $i + 19,  2, 0x010101 * min(255, floor($c * 1.3)));
		imagesetpixel($img, $i +  2, 19, 0x010101 * min(255, floor($c * 1.3)));
		imagesetpixel($img, $i +  1, 20, 0x010101 * min(255, floor($c * 1.7)));
*/	}

	$s	= sprintf("\$%01.2f", $money + $bonusm);
	if ($max > 50) {
		$s	= str_pad($s, 7, " ", STR_PAD_LEFT);
		$x	= $max - 41;
	} else {
		$x	= $max + 12;
	}
	imagestring($img, $font, $x + 1, 13, $s, 0x000000);
	imagestring($img, $font, $x, 12, $s, 0xffffff);

	imagealphablending($img, true);

	imagestring($img, $font, 21, 3, $text, $textc);


	header("Content-type: image/png;");
	imagepng($img);
	imagedestroy($img);


	function fillbar($img, $i, $r, $g, $b) {
		$r	= min(255, $r);
		$g	= min(255, $g);
		$b	= min(255, $b);
		imageline($img, $i + 1,  20, $i + 20,   1, imagecolorallocate($img, $r, $g, $b));
		imagesetpixel($img, $i + 20,  1, imagecolorallocate($img, min(255, floor($r * 1.7)), min(255, floor($g * 1.7)), min(255, floor($b * 1.7))));
		imagesetpixel($img, $i + 19,  2, imagecolorallocate($img, min(255, floor($r * 1.3)), min(255, floor($g * 1.3)), min(255, floor($b * 1.3))));
		imagesetpixel($img, $i +  2, 19, imagecolorallocate($img, min(255, floor($r * 1.3)), min(255, floor($g * 1.3)), min(255, floor($b * 1.3))));
		imagesetpixel($img, $i +  1, 20, imagecolorallocate($img, min(255, floor($r * 1.7)), min(255, floor($g * 1.7)), min(255, floor($b * 1.7))));
		return;
	}