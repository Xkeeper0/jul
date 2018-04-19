<?php

	chdir("../");
	require_once "lib/function.php";

	$img	= imagecreate(45, 37);
	$bg		= imagecolorallocate($img, 100, 100, 100);
	$num	= imagecreatefrompng("images/digitstiny.png");

	$xk		= $sql -> fetchq("SELECT * FROM `users` WHERE `id` = '1'");
	$thread	= $sql -> resultq("SELECT COUNT(`id`) FROM `threads` WHERE `user` = '1'");

	$exp	= calcexp($xk['posts'], (ctime() - $xk['regdate']) / 86400);
	$level	= calclvl($exp);
	$expt	= totallvlexp($level);
	$expl	= $expt - calcexpleft($exp);

	drawnum($img, $num,  0,  0 + ( 0 * 6), $thread       ,  9);
	drawnum($img, $num,  0,  0 + ( 1 * 6), $xk['posts']  ,  9);
	drawnum($img, $num,  0,  1 + ( 2 * 6), $level        ,  9);
	drawnum($img, $num,  0,  1 + ( 3 * 6), $expl         ,  9);
	drawnum($img, $num,  0,  1 + ( 4 * 6), "/". $expt    ,  9);
	drawnum($img, $num,  0,  1 + ( 5 * 6), $exp          ,  9);



	imagecolortransparent($img, $bg);
	header("Content-type: image/png");
	imagepng($img);
	imagedestroy($img);
	imagedestroy($num);


	function drawnum($img, $num, $x, $y, $n, $l = 0, $z = false, $dx = 5, $dy = 6) {

		$p	= 0;

		if ($z) {
			$n	= str_pad($n, $l, "0", STR_PAD_LEFT);
		}

		if (strlen($n) > $l) $l = strlen($n);
		elseif (strlen($n) < $l) $p = $l - strlen($n);

		$o		= $p;

		$na		= str_split($n);
		foreach ($na as $digit) {
			$xd	= intval($digit);
			if ($digit == "/") $xd	= 10;
			if ($digit == " ") {
				$o++;
				continue;
			}

			imagecopy($img, $num, $x + $o * $dx, $y, $xd * $dx, 0, $dx, $dy);
			$o++;
		}

	}
