<?php

	$n	= intval($_GET['n']) ."";
	$l	= intval($_GET['l']);
	$p	= 0;

	if ($_GET['z']) {
		$n	= str_pad($n, $l, "0", STR_PAD_LEFT);
	}

	if (strlen($n) > $l) $l = strlen($n);
	elseif (strlen($n) < $l) $p = $l - strlen($n);

	$img	= imagecreate(26 * $l, 28);
	$bg		= imagecolorallocate($img, 5, 5, 5);
	$num	= imagecreatefrompng("numgfx/bigdigits.png");
	$o		= $p;

	$na		= str_split($n);
	foreach ($na as $x) {
		$x	= intval($x);
		$y	= floor($x / 5) * 28;
		$x	= ($x % 5) * 26;

		imagecopy($img, $num, $o * 26, 0, $x, $y, 26, 28);
		$o++;
	}

	imagecolortransparent($img, $bg);
	header("Content-type: image/png");
	imagepng($img);
	imagedestroy($img);
	imagedestroy($num);
