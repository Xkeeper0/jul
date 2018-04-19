<?php

	chdir("../");
	require_once "lib/function.php";
	//$sql->selectdb("sonicret_s2bivb");

	if ($_GET['s']) $size = $_GET['s'];
	if ($size > 1024 || $size < 80) $size = 255;
	if ($_GET['t']) $classt = $_GET['t'];
	if ($classt > 9999 || $classt < 10) $classt = 500;

	$size			= 255;
	$classt			= 500;
	$class			= $sql->resultq("SELECT COUNT(`id`) FROM `posts` WHERE `date` > ". (ctime() - 86400));

	if ($_GET['s']) $size = numrange((int)$_GET['s'], 80, 1024);
	if ($_GET['t']) $classt = numrange((int)$_GET['t'], 10, 1000);

	$classtxt		= "    ppd   ";

	$image			= imagecreatetruecolor( $size + 1, $size + 1 );

	imageantialias($image, true);
	imagesavealpha($image, true);
	imagealphablending($image, false);

	$color['white']	= imagecolorallocate($image, 255, 255, 255);
	$color['black']	= imagecolorallocate($image,   0,   0,   0);
	$color['gray1']	= imagecolorallocate($image,  40,  40,  40);
	$color['gray2']	= imagecolorallocate($image, 120, 120, 120);
	$color['gray3']	= imagecolorallocate($image, 230, 230, 230);
	$color['red']	= imagecolorallocate($image, 255, 100, 100);
	$color['blue']	= imagecolorallocate($image, 125, 125, 255);

	imagefilledrectangle($image, 0, 0, $size+1, $size+1, imagecolorallocatealpha($image, 0, 0, 0, 127));
	imagealphablending($image, true);

	$point	= anglepos($size / 3, $size/2, $size/2, 90+45);

//	drawgauge($size/2, $size/2, $size, 180, 270);
	drawgauge($size/2, $size/2, $size, 180 - 45, 270);

//	drawgauge($point['x'], $point['y'], $size / 3, 180, 270);

	function drawgauge($xpos, $ypos, $size, $startangle, $endangle) {
		global $image, $color, $class, $classt, $classtxt;

//		$startangle	= 90;
		$endangle	= $startangle + $endangle;
		$angsize	= $endangle - $startangle;

		$radius			= $size / 2;

		imagefilledarc($image, $xpos, $ypos, $size, $size, 0, 360, $color['gray1'], IMG_ARC_PIE);
		imagearc      ($image, $xpos, $ypos, $size, $size, 0, 360, $color['black']);

		for ($i = 0; $i <= 100; $i ++) {

			if ($i % 5 == 0) {
				$c	= $color['white'];
				$l	= 10;
			} else {
				$c	= $color['black'];
				$l	= 0;
			}

			if ($i >= 0 && $i <= 99) {
				if ($i <= 24) {
					$cx	= ($i) / 25 * 255;
					$cc	= imagecolorallocate($image, 255, $cx, 0);
				} elseif ($i <= 49) {
					$cx	= ($i - 75) / 25 * 127;
					$cc	= imagecolorallocate($image, 255 - $cx, 255 - $cx, $cx);
				} elseif ($i <= 74) {
					$cx	= ($i - 50) / 25 * 31;
					$cc	= imagecolorallocate($image, 128 - $cx, 128 - $cx, 128 + $cx);
				} else {
					$cx	= ($i - 75) / 25 * 95;
					$cc	= imagecolorallocate($image, 95 - $cx, 95 - $cx, 255 - $cx);
				}

				$r	= $i * ($angsize * .01) - $startangle + ($angsize * .01);
				$q[0]	= anglepos($radius - 3, $xpos, $ypos, $r);
				$q[1]	= anglepos($radius - 3 - 5, $xpos, $ypos, $r);
				$r	= $i * ($angsize * .01) - $startangle - 1;
				$q[2]	= anglepos($radius - 3, $xpos, $ypos, $r);
				$q[3]	= anglepos($radius - 3 - 5, $xpos, $ypos, $r);

				$pts	= array(
					$q[0]['x'], $q[0]['y'],
					$q[2]['x'], $q[2]['y'],
					$q[3]['x'], $q[3]['y'],
					$q[1]['x'], $q[1]['y'],
					);

//				imageline($image, $p1['x'], $p1['y'], $p2['x'], $p2['y'], $cc);
//				imageline($image, $p1['x'], $p1['y'], $p2['x'], $p2['y'], $cc);
				imagefilledpolygon($image, $pts, 4, $cc);
//				imageline($image, $q[0]['x'], $q[0]['y'], $q[3]['x'], $q[3]['y'], $cc);
			}

			$r	= $i * ($angsize * .01) - $startangle;

			$p1	= anglepos($radius - 3, $xpos, $ypos, $r);
			$p2	= anglepos($radius - 3 - $l, $xpos, $ypos, $r);

			imageline($image, $p1['x'], $p1['y'], $p2['x'], $p2['y'], $c);

			$markers	= ($size < 255 ? 25 : 10);
			if ($i % $markers == 0) {
				$p3		= anglepos($radius - 20, $xpos, $ypos, $r);
				$p4		= anglepos($radius - 42, $xpos, $ypos, $r);

				$n		= round($classt * ($i/100)) ."";

				$nl		= strlen($n);

				$x	= 2 + (3.5 * ($nl - 1));
				imagestring($image, 3, $p3['x'] - $x, $p3['y'] - 7, $n, $color['white']);
			}

		}


		$rad	= 45;

	//	imageline($image, $radius, $radius, $point['x'], $point['y'], $color['black']);
	//	drawneedle($houra, $radius, $radius, $radius - 30,  7, - 3, $color['black']);
	//	drawneedle($mina,  $radius, $radius, $radius -  5,  5, - 5, $color['black']);
	//	drawneedle($seca,  $radius, $radius, $radius -  2,  4, -10, $color['red']);

		$ang	= @(min($classt, $class)/$classt) * $angsize - $startangle;
		drawneedle($ang,  $xpos, $ypos, $radius -  2, 5, -20, $color['blue']);

		if ($size >= 128) {
			imagestring($image, 1, $size / 2 - 26, $size * .7 + 4    , $classtxt, $color['white']);
//			if (!$_GET['p']) imagestring($image, 1, $size / 2 + 14, $size * .7 + 16, "%", 0xcccccc);
			digits($size * .50 - 12, $size * .7 + 14, $class, 3, 0);
		} else {
			digits($size * .50 - 12, $size * .7 + 9, $class, 3, 0);
		}
	}

//	imagestring($image, 1, $size / 2 - 42, $size * .93 - 10, date("m/d/y H:i:s", time()), 0xcccccc);
	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);



	function anglepos($radius, $origo_x, $origo_y, $angle) {
//		$angle	+= 90;
		$radius--;
		$pont['x'] = $origo_x + ($radius * sin(deg2rad($angle)));
		$pont['y'] = $origo_y - ($radius * cos(deg2rad($angle)));
		return $pont;
	}


	function drawneedle($angle, $cx, $cy, $radius, $width, $tail, $color) {
		global $image;
		$point	= anglepos($tail * -1, $cx, $cy, $angle + 180);

		$width	*= .75;

		$point1	= anglepos($width, $point['x'], $point['y'], $angle - 90);
		$point2	= anglepos($width, $point['x'], $point['y'], $angle + 90);
		$point3	= anglepos($radius, $cx, $cy, $angle);
//		$point3	= anglepos($radius, $cx, $cy, $angle - .5);
//		$point4	= anglepos($radius, $cx, $cy, $angle + .5);
		$point9	= anglepos($radius - 4, $cx, $cy, $angle);

		$points	= array(
					$point1['x'], $point1['y'],
//					$point['x'], $point['y'],
					$point2['x'], $point2['y'],
//					$point4['x'], $point4['y'],
					$point3['x'], $point3['y'],
			);
		imagefilledpolygon($image, $points, 3, $color);
		imagepolygon($image, $points, 3, $color);
		imageline($image, $point9['x'], $point9['y'], $cx, $cy, 0xffffff);
//		imagefilledpolygon($image, $points, 3, $color);

	}


	function digits($x, $y, $n, $l = 4, $d = 2, $overlay = false) {
		global $image;

		$numimage	= imagecreatefrompng("images/digits4.png");
		$n			= number_format($n, $d);
		$n			= str_replace(",", "", $n);
		$n2			= explode(".", $n);

		$big		= str_pad($n2[0], $l, ":", STR_PAD_LEFT);
		$len		= strlen($big);

		for($o = 0; $o < $len; $o++) {
			$chrpos	= ord($big{$o}) - 48;
			imagecopy($image, $numimage, $x + ($o * 8), $y, $chrpos * 9, 0, 8, 14);
		}

		$small		= $n2[1];
		$slen		= strlen($small) + $o - 1;
		for(; $o <= $slen; $o++) {
			$lp		= $o - $len;
			imagecopy($image, $numimage, $x + $o * 8, $y, $small{$lp} * 9, 14, 8, 14);
		}

		$maxlen		= strlen($big) + strlen($small);
		imagerectangle($image, $x - 1, $y - 1, $x + $maxlen * 8, $y + 14, 0x000000);

		if ($overlay) imagefilledrectangle($image, $x, $y, $x + $maxlen * 8 - 1, $y + 13, $overlay);
	}
