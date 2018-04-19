<?php

	chdir("../");
	require_once "lib/function.php";

	set_time_limit(0);
	ini_set("memory_limit", "256M");

	$mintime	= ctime() - (($_GET['d'] ? min($_GET['d'], 14) : 7) * 24 * 3600);
//	$rangemin	= floor($sql -> resultq("SELECT MIN(`time` / 3600) FROM `rendertimes`"));
	$rangemin	= floor($sql -> resultq("SELECT MIN(`time` / 3600) FROM `rendertimes` WHERE `time` > $mintime"));
	$num		= ceil(ctime() / 3600) - $rangemin;

	$image			= imagecreatetruecolor(1100, $num * 10);
	$col['bg']		= imagecolorallocate($image,   0,   0,   0);
	$col['text']	= imagecolorallocate($image, 255, 255, 255);
	$col['bar1']	= imagecolorallocatealpha($image, 128, 128, 128, 30);
	$col['bar2']	= imagecolorallocatealpha($image, 255, 255, 255, 30);
	$col['bar3']	= imagecolorallocatealpha($image, 170, 100, 100, 30);
	$col['sca1']	= imagecolorallocate($image,  55,  55,  75);
	$col['sca2']	= imagecolorallocate($image,  80,  80, 125);
//	$col['bg1']		= imagecolorallocate($image,  10,  10,  30);
//	$col['bg2']		= imagecolorallocate($image,  15,  15,  35);


	for ($i = 0; $i <= 40; $i++) {
		$mx		= $i * 25;
		$c1	= (!($i % 4) ? 2 : 1);
		$c2	= (!($i % 2) ? 2 : 1);
		$col['sca1']	= imagecolorallocate($image,  55 + $i * 2,  55,  75);
		$col['sca2']	= imagecolorallocate($image,  80 + $i * 2,  80, 125);
		$col['bg1']		= imagecolorallocate($image,  10 + $i * 2,  10,  30);
		$col['bg2']		= imagecolorallocate($image,  15 + $i * 2,  15,  35);
		imagefilledrectangle($image,  76 + $mx, 0, 100 + $mx, $num * 10, $col['bg'. $c2]);
		imageline           ($image, 100 + $mx, 0, 100 + $mx, $num * 10, $col['sca'. $c1]);
	}	


	$data		= $sql -> query("SELECT FLOOR(`time` / 3600) AS time, ".
								"COUNT( `rendertime` ) AS readings, ".
								"ROUND(AVG( `rendertime` ), 4) AS average, ".
								"ROUND(MIN( `rendertime` ), 4) AS minimum, ".
								"ROUND(MAX( `rendertime` ), 4) AS maximum ".
								"FROM `rendertimes` ".
								"WHERE `page` = '/index.php' ".
								"AND `time` > $mintime ".
								"GROUP BY FLOOR(`time` / 3600)") or die(mysql_error());

	while ($rt = $sql -> fetch($data)) {
		$y		= ($rt['time'] - $rangemin) * 10;
		$date	= date("m.d ha", $rt['time'] * 3600);
		imagestring($image, 2, 2, $y - 2, $date ."  ". str_pad($rt['readings'], 4, " ", STR_PAD_LEFT), $col['text']);
		$datenew	= substr($date, 3, 2);
		if ($dateold != $datenew) {
			imageline           ($image,  0, $y, 1100, $y, $col['sca2']);
		}
		$dateold	= $datenew;

		$bmin	= $rt['minimum'] * 100;
		$bmax	= $rt['maximum'] * 100;
		$bavg	= $rt['average'] * 100;
		$c1	= ($bmax >= 1000 ? 3 : 1);

		imagefilledrectangle($image, 100 + $bmin, $y, 100 + $bmax, $y + 8, $col['bar'. $c1]);
		imageline           ($image, 100 + $bavg, $y, 100 + $bavg, $y + 8, $col['bar2']);
		
		$tx	= min($bmax - 39, 961);
		if ($tx <= 33) $tx = $bmax;

		imagestring($image, 1, $tx + 102, $y + 1, str_pad(number_format($rt['maximum'], 3), 7, " ", STR_PAD_LEFT), $col['bg']);		
		imagestring($image, 1, $tx + 103, $y    , str_pad(number_format($rt['maximum'], 3), 7, " ", STR_PAD_LEFT), $col['bg']);		
		imagestring($image, 1, $tx + 103, $y + 1, str_pad(number_format($rt['maximum'], 3), 7, " ", STR_PAD_LEFT), $col['bg']);		
		imagestring($image, 1, $tx + 102, $y, str_pad(number_format($rt['maximum'], 3), 7, " ", STR_PAD_LEFT), $col['text']);		

	}

	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);

?>