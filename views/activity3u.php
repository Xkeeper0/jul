<?php
 require_once '../lib/function.php';


 $user['regdate']	= $sql -> resultq("SELECT MIN(`regdate`) FROM users");

// $val				= $sql -> resultq("SELECT COUNT(DISTINCT `user`) FROM `posts` GROUP BY FLOOR(`date` / 86400)");
 $val				= 99;
 $max				= ceil(($val + 1) / 50) * 50;
 $alen				= ($_GET['len'] ? $_GET['len'] : 30);

 $alen				= min(max(7, $alen), 90);
// $max				= 5500;

 $vd=date('m-d-y', $user['regdate']);
 $dd=mktime(0,0,0,substr($vd,0,2),substr($vd,3,2),substr($vd,6,2));
 $dd2=mktime(0,0,0,substr($vd,0,2),substr($vd,3,2)+1,substr($vd,6,2));


 $days=ceil((ctime()-$dd)/86400);
 $scalex	= 2;
 $scaley	= .2;
 $m			= $max / $scaley;
 $xs		= $days * $scalex;
 $xsize		= $days * $scalex;

 $img=ImageCreateTrueColor($xs,$m);

 $c[bg]= ImageColorAllocate($img,  0,  0,  0);
 $c[bg1]=ImageColorAllocate($img,  0,  0, 60);
 $c[bg2]=ImageColorAllocate($img,  0,  0, 80);
 $c[bg3]=ImageColorAllocate($img, 40, 40,100);
 $c[bg4]=ImageColorAllocate($img,100, 40, 40);
 $c[mk1]=ImageColorAllocate($img, 60, 60,130);
 $c[mk2]=ImageColorAllocate($img, 80, 80,150);
 $c[bar]=ImageColorAllocate($img,250,190, 40);
 $c[pt] =ImageColorAllocate($img,250,250,250);
 for($i=0;$i<$days;$i++){
   $num=date('m',$dd+$i*86400)%2+1;
   if(date('m-d',$dd+$i*86400)=='01-01') $num=3;
   ImageFilledRectangle($img,$i * $scalex,$m,($i + 1) * $scalex - 2,0,$c["bg$num"]);

 }
 for($i=0;$i<=($m / 50);$i++){
   ImageLine($img,0,$m-$i*100+50,($days + 1) * $scalex - 1,$m-$i*100+50,$c[mk1]);
   ImageLine($img,0,$m-$i*100,($days + 1) * $scalex - 1,$m-$i*100,$c[mk2]);
   imagestring($img, 3, 3, $m-$i*100+1, ($i * 100) * $scaley, $c[bg]);
   imagestring($img, 3, 3, $m-$i*100+51, ($i * 100 - 50) * $scaley, $c[bg]);
   imagestring($img, 3, 2, $m-$i*100, ($i * 100) * $scaley, $c[mk2]);
   imagestring($img, 3, 2, $m-$i*100+50, ($i * 100 - 50) * $scaley, $c[mk1]);

   imagestring($img, 3, $xs - 71, $m-$i*100+1, sprintf("%10d", ($i * 100) * $scaley), $c[bg]);
   imagestring($img, 3, $xs - 71, $m-$i*100+51, sprintf("%10d", ($i * 100 - 50) * $scaley), $c[bg]);
   imagestring($img, 3, $xs - 72, $m-$i*100, sprintf("%10d", ($i * 100) * $scaley), $c[mk2]);
   imagestring($img, 3, $xs - 72, $m-$i*100+50, sprintf("%10d", ($i * 100 - 50) * $scaley), $c[mk1]);
 }


	$users	= array(
		  1 => array('name' => "Users active per day", 'color' =>  imagecolorallocate($img, 255, 255, 255)),
		 -1 => array('name' => "$alen-day average", 'color' =>  0xFF8888),
/*		 50 => array('name' => "Hyperhacker    ", 'color' =>  imagecolorallocate($img,  50, 255,  50)),
		 61 => array('name' => "E. Prime       ", 'color' =>  imagecolorallocate($img, 200, 200,   0)),
		 18 => array('name' => "Hiryuu         ", 'color' =>  imagecolorallocate($img, 255,  50,  50)),
		 17 => array('name' => "NightKev       ", 'color' =>  imagecolorallocate($img, 200,   0, 200)),
/		  5 => array('name' => "Hydrapheetz    ", 'color' =>  imagecolorallocate($img,  50,  50, 255)),
		  3 => array('name' => "cpubasic13     ", 'color' =>  imagecolorallocate($img,   0, 200, 255)),
		 52 => array('name' => "Shadic         ", 'color' =>  imagecolorallocate($img, 100,  50, 200)),
		 57 => array('name' => "Kles           ", 'color' =>  imagecolorallocate($img,  50, 200, 100)),
		 12 => array('name' => "Dorito         ", 'color' =>  imagecolorallocate($img, 200, 100,  50)),

		 36 => array('name' => "Erika          ", 'color' =>  imagecolorallocate($img, 220, 100, 170)),
		100 => array('name' => "Kas            ", 'color' =>  imagecolorallocate($img, 220, 170, 100)),
		117 => array('name' => "Rydain         ", 'color' =>  imagecolorallocate($img, 220, 220,  79)),
		118 => array('name' => "Aiya           ", 'color' =>  imagecolorallocate($img, 170, 150, 255)),
		175 => array('name' => "Tina           ", 'color' =>  imagecolorallocate($img, 255, 100, 255)),
		387 => array('name' => "Acmlm          ", 'color' =>  imagecolorallocate($img, 233, 190, 153)),
		 49 => array('name' => "Dr. Sophie     ", 'color' =>  imagecolorallocate($img, 193, 210, 233)),
*/
//		  2 => array('name' => "Drag           ", 'color' =>  imagecolorallocate($img, 255,   0,   0)),

);
/*
	$users	= array();
	$userq	= $sql -> query("SELECT id, name FROM `users` ORDER BY `posts` DESC LIMIT 0, 10");
	while ($u = $sql -> fetch($userq)) {
		$users[$u['id']]	= array('name' => $u['name'], 'color' => imagecolorallocate($img, rand(100,255), rand(100,255), rand(100,255)));
	}
*/

	$z	= count($users);
	$namespace	= 12;

	imagerectangle(      $img, 61, 11, 174 + 6 * 5, 15 + $z * $namespace, $c[bg]);
	imagefilledrectangle($img, 60, 10, 173 + 6 * 5, 14 + $z * $namespace, $c[bg2]);
	imagerectangle(      $img, 60, 10, 173 + 6 * 5, 14 + $z * $namespace, $c[mk2]);

	$z	= 0;

	foreach($users as $uid => $userx) {
		if ($uid > 0) {
			$data	= getdata($uid);
			drawdata($data, $userx['color']);
		}
		imageline($img, 66, $z * $namespace + 19, 76, $z * $namespace + 19, $c[bg]);
		imageline($img, 65, $z * $namespace + 18, 75, $z * $namespace + 18, $userx['color']);
		imagestring($img, 2, 80 + 1, $z * $namespace + 12, $userx['name'], $c[bg]);
		imagestring($img, 2, 80, $z * $namespace + 11, $userx['name'], $userx['color']);
		$z++;
	}

	foreach($xdata as $k => $v) {
		$xdata2[$k - 13697]	= ($v * 5);
	}

	if (0) {
		print "<pre>days = $days \n\n\n";
		print_r($data);
		print "\n\n------------------------\n\n";
		print_r($xdata2);
		die();
	}
	drawdata($xdata2, $users[-1]['color'], true, $users[-1]['color'] + 0x40000000);
 
	Header('Content-type:image/png');
	ImagePNG($img);
	ImageDestroy($img);


 function drawdata($p, $color, $derp = false, $color2 = false) {
	 global $days, $scalex, $m, $img, $xs;
	 $oldy	= $m;
	 if ($derp) {
		$points[]	= 0;
		$points[]	= $m - 1;
	 }
	 for($i=0;$i<$days;$i++){
		$y		= $m-$p[$i];
		$x		= $i * $scalex;
		if (!$p[$i]) {
			$y	 = $oldy;
		}
		
		if (!$derp) {
			imageline($img, $x, $oldy, $x + $scalex - 1, $y, $color);
			$oldy	= $y;
		} elseif ($y != $m) {
			$points[]	= $x;
			$points[]	= $y;
		}

	 }
	 if ($derp) {
//		$points[]	= $xs;
//		$points[]	= 499;
//		$points[]	= 2769;
//		$points[]	= 499;
		$points[]	= $xs;
		$points[]	= $y;
		$points[]	= $xs;
		$points[]	= $m - 1;
		 imagefilledpolygon($img, $points, count($points) / 2, $color2);
		 imagepolygon      ($img, $points, count($points) / 2, $color);
	 }
 }

//SELECT COUNT(DISTINCT `user`) FROM `posts` GROUP BY FLOOR(`date` / 86400)

  function getdata($u) {
	 global $sql, $dd, $dd2, $scaley, $days, $xdata, $alen;
	 $nn	= $sql -> query("SELECT FROM_UNIXTIME(date,'%Y%m%d') ymd, floor(date/86400) d,  count( DISTINCT `user`) c FROM posts GROUP BY ymd ORDER BY ymd");

	 while ($n = $sql -> fetch($nn)) {
	   $p[$n['d']]=$n['c'];
	   
	   for ($temp = $n['d']; $temp > $n['d'] - $alen; $temp--) {
		$xdata[$n['d']]	+= $p[$temp];
	   }
	   $xdata[$n['d']]	/= $alen;
	 
	 }

/*
	$dat	= $sql -> query(
		"SELECT count( * ) AS cnt, floor( to_days( now( ) ) ) - floor( to_days( from_unixtime( date ) ) ) AS d "
		."FROM posts "
//		."WHERE user =$u "
		."GROUP BY d ORDER BY d DESC");
*/
	$dat	= $sql -> query(
		"SELECT count( DISTINCT `user` ) AS cnt, floor( to_days( now( ) ) ) - floor( to_days( from_unixtime( date ) ) ) AS d "
		."FROM posts "
		."GROUP BY d ORDER BY d DESC");

	while ($z = $sql -> fetch($dat)) {
	   $da = $days - $z['d'];
	   $y	+= $z['cnt'];
	   $y	= $z['cnt'];
	   $p[$da] = $y / $scaley;

	 }
	return $p;
  }


?>