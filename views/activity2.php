<?php
	require_once '../lib/function.php';
 	$user['regdate']	= $sql -> resultq("SELECT MIN(`regdate`) FROM users WHERE regdate > 0") or die();
	$max				= ceil(($sql -> resultq("SELECT MAX(`posts`) FROM users") + 1) / 50) * 50;

	$vd = date('m-d-y', $user['regdate']);
	$dd = mktime(0,0,0,substr($vd,0,2),substr($vd,3,2),substr($vd,6,2));

	$days = floor((ctime()-$dd)/86400);
	$scalex	= 1;
	$scaley	= 20;
	$m = $max / $scaley;

	$img = ImageCreateTrueColor($days * $scalex,$m);
	$c['bg']  = ImageColorAllocate($img,  0,  0,  0);
	$c['bg1'] = ImageColorAllocate($img,  0,  0, 60);
	$c['bg2'] = ImageColorAllocate($img,  0,  0, 80);
	$c['bg3'] = ImageColorAllocate($img, 40, 40,100);
	$c['mk1'] = ImageColorAllocate($img, 60, 60,130);
	$c['mk2'] = ImageColorAllocate($img, 80, 80,150);
	$c['bar'] = ImageColorAllocate($img,250,190, 40);
	$c['pt']  = ImageColorAllocate($img,250,250,250);

	for($i=0;$i<$days;$i++){
		$num=date('m',$dd+$i*86400)%2+1;
		if(date('m-d',$dd+$i*86400)=='01-01') $num=3;
		ImageFilledRectangle($img,$i * $scalex,$m,($i + 1) * $scalex - 2,0,$c["bg$num"]);
	}

	for($i=0;$i<=($m / 50);$i++){
		ImageLine($img,0,$m-$i*100+50,($days + 1) * $scalex - 1, $m-$i*100+50, $c['mk1']);
		ImageLine($img,0,$m-$i*100,   ($days + 1) * $scalex - 1, $m-$i*100,    $c['mk2']);
		imagestring($img, 3, 3, $m-$i*100+1,  ($i * 100)      * $scaley, $c['bg']);
		imagestring($img, 3, 3, $m-$i*100+51, ($i * 100 - 50) * $scaley, $c['bg']);
		imagestring($img, 3, 2, $m-$i*100,    ($i * 100)      * $scaley, $c['mk2']);
		imagestring($img, 3, 2, $m-$i*100+50, ($i * 100 - 50) * $scaley, $c['mk1']);
	}

	$users	= array();
	$userq	= $sql -> query("SELECT id, name FROM `users` ORDER BY `posts` DESC LIMIT 0, 10");
	while ($u = $sql -> fetch($userq))
		$users[$u['id']]	= array('name' => $u['name'], 'color' => imagecolorallocate($img, rand(100,255), rand(100,255), rand(100,255)));

	$z = count($users);
	$namespace = 12;

	imagerectangle(      $img, 61, 11, 174, 15 + $z * $namespace, $c['bg']);
	imagefilledrectangle($img, 60, 10, 173, 14 + $z * $namespace, $c['bg2']);
	imagerectangle(      $img, 60, 10, 173, 14 + $z * $namespace, $c['mk2']);

	$z	= 0;

	$data = getdata(array_keys($users));
	foreach($users as $uid => $userx) {
		drawdata($data[$uid], $userx['color']);
		imageline($img, 66, $z * $namespace + 19, 76, $z * $namespace + 19, $c['bg']);
		imageline($img, 65, $z * $namespace + 18, 75, $z * $namespace + 18, $userx['color']);
		imagestring($img, 2, 80 + 1, $z * $namespace + 12, $userx['name'], $c['bg']);
		imagestring($img, 2, 80,     $z * $namespace + 11, $userx['name'], $userx['color']);
		$z++;
	}

/*	if($_GET['debugsql']) {
		require_once '../lib/layout.php';
		print $header.$footer;
		printtimedif(time());
		die(1);
	} */

	Header('Content-type:image/png');
	ImagePNG($img);
	ImageDestroy($img);

function drawdata($p, $color) {
	global $days, $scalex, $m, $img;
	$oldy = $m;
	for ($i=0;$i<$days;$i++){
		$y		= $m-$p[$i];
		$x		= $i * $scalex;
		if (!$p[$i]) {
			$y	 = $oldy;
		}
		imageline($img, $x, $oldy, $x + $scalex - 1, $y, $color);
		$oldy	= $y;
	}
}

function getdata($users) {
	global $sql, $dd, $scaley, $days;

	$q = $sql->query(
		"SELECT user, FROM_UNIXTIME(date, '%Y-%m-%d') day, count(*) c ".
		"FROM posts WHERE user IN (".implode(',',$users).") GROUP BY user, day ORDER BY user, day",
		'day');

	$tmp = array();
	$y = array();

	while ($r = $sql->fetch($q, MYSQL_ASSOC))
		$tmp[$r['user']][$r['day']] = $r;

	for($i=0; $i < $days; ++$i) {
		$dk = date('Y-m-d',$dd+$i*86400);
		foreach ($tmp as $uid => $qdata) {
			if (!array_key_exists($dk, $qdata)) continue;

			$y[$uid] += $qdata[$dk]['c'];
			$resp[$uid][$i] = $y[$uid] / $scaley;
		}
	}
	return $resp;
}
