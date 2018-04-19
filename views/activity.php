<?php
	require_once '../lib/function.php';

	$u = intval($_GET['u']);
	if (!$u) die();
	$user['regdate'] = $sql->resultq("SELECT regdate FROM users WHERE id='$u'") or die();

	$vd=date('m-d-y', $user['regdate']);
	$dd=mktime(0,0,0,substr($vd,0,2),substr($vd,3,2),substr($vd,6,2));

	$days = floor((ctime()-$dd)/86400);
	$pq = $sql->getresultsbykey(
		"SELECT FROM_UNIXTIME(date, '%Y-%m-%d') day, count(*) c ".
		"FROM posts WHERE user={$u} GROUP BY day ORDER BY day",
		'day', 'c');
	for($i=0; $i < $days; ++$i) {
        $dk = date('Y-m-d',$dd+$i*86400);
		if (!array_key_exists($dk, $pq)) continue;
		$p[$i] = $pq[$dk];
	}

/*
	if($_GET['debugsql']) {
		require_once '../lib/layout.php';
		print $header.$footer;
		printtimedif(time());
		die(1);
	}
*/

	$m=max($p);
	$img=ImageCreate($days,$m);

	$c['bg']  = ImageColorAllocate($img,  0,  0,  0);
	$c['bg1'] = ImageColorAllocate($img,  0,  0, 80); // Month colors
	$c['bg2'] = ImageColorAllocate($img,  0,  0,130); //
	$c['bg3'] = ImageColorAllocate($img, 80, 80,250); // (New year)
	$c['mk1'] = ImageColorAllocate($img,110,110,160); // Horizontal Rulers
	$c['mk2'] = ImageColorAllocate($img, 70, 70,130); //
	$c['bar'] = ImageColorAllocate($img,240,190, 40); // Post count bar
	$c['pt1']  = ImageColorAllocate($img,250,250,250); // Average
	$c['pt2']  = ImageColorAllocate($img,240,230,220); // Average (over top of post bar)

	for($i=0;$i<$days; ++$i){
		$num=date('m',$dd+$i*86400)%2+1;
		if(date('m-d',$dd+$i*86400)=='01-01') $num=3;
		ImageLine($img,$i,$m,$i,0,$c["bg$num"]);
	}
	for($i=50, $ct=1; $i<=$m; $i+=50, ++$ct)
		ImageLine($img,0,$m-$i,$days,$m-$i,(($ct&1) ? $c['mk2'] : $c['mk1']));

	$pt=0;
	for($i=0;$i<$days;$i++) {
		if (isset($p[$i])) {
			ImageLine($img,$i,$m,$i,$m-$p[$i],$c['bar']);
			$pt += $p[$i];
		}
		$avg = $pt/($i+1);
		ImageSetPixel($img,$i,$m-$avg,(($p[$i] >= $avg) ? $c['pt2'] : $c['pt1']));
	}

	Header('Content-type:image/png');
	ImagePNG($img);
	ImageDestroy($img);