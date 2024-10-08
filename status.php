<?php
	require 'lib/function.php';
	require 'lib/rpg.php';

	if(!intval($u)) die();

	$user = $sql->fetchq("SELECT name,posts,regdate,users_rpg.* FROM users,users_rpg WHERE id='$u' AND uid=id");
	$p = $user['posts'];
	$d = (ctime()-$user['regdate'])/86400;

	$it	= intval($_GET['it'] ?? 0);
	$ne = intval($_GET['ne'] ?? 0);
	$nc = intval($_GET['nc'] ?? 0);
	$ct = intval($_GET['ct'] ?? 0);
	if(!$ne)
		$items = $sql->getarraybykey("SELECT * FROM items WHERE id=$user[eq1] OR id=$user[eq2] OR id=$user[eq3] OR id=$user[eq4] OR id=$user[eq5] OR id=$user[eq6] OR id=$it", 'id');
	if(!$nc)
		$class = $sql->fetchq("SELECT * FROM `rpg_classes` WHERE `id` = '". $user['class'] ."'");

	$GPdif = 0;
	if($ct) {
		$GPdif=floor($items[$user['eq'.$ct]]['coins']*0.6)-$items[$it]['coins'];
		$user['eq'.$ct]=$it;
	}

	$st = getstats($user,$items,$class);
	$st['GP']+=$GPdif;
	if($st['lvl'] > 0) $pct = 1 - calcexpleft($st['exp'])/totallvlexp($st['lvl']);

	if (!$class) {
		$class['name']	= "None";
	}

	$img=imagecreate(256,224 - (8 * 0));
	imagesavealpha($img, true);
	imagealphablending($img, false);
	$c['bg']=  imagecolorallocatealpha($img, 40, 40, 90, 127);
	$c['bxb0']=imagecolorallocate($img,  0,  0,  0);

//	$c['bxb1']=imagecolorallocate($img,225,200,180);
//	$c['bxb2']=imagecolorallocate($img,190,160,130);
//	$c['bxb3']=imagecolorallocate($img,130,110, 90);

	$c['bxb1']=imagecolorallocate($img, 200, 180, 225);
	$c['bxb2']=imagecolorallocate($img, 160, 130, 190);
	$c['bxb3']=imagecolorallocate($img,  90, 110, 130);

	for($i=0;$i<100;$i++)
		 $c[$i]=imagecolorallocate($img,  15+$i/1.5,  8, 20+$i);

	$c['barE1']=imagecolorallocate($img,120,150,180);
	$c['barE2']=imagecolorallocate($img, 30, 60, 90);

	$c['bar1'][ 1]	= imagecolorallocate($img, 215,  91, 129);
	$c['bar1'][ 2]	= imagecolorallocate($img, 255, 136, 154);
	$c['bar1'][ 3]	= imagecolorallocate($img, 255, 139,  89);
	$c['bar1'][ 4]	= imagecolorallocate($img, 255, 251,  89);
	$c['bar1'][ 5]	= imagecolorallocate($img,  89, 255, 139);
	$c['bar1'][ 6]	= imagecolorallocate($img,  89, 213, 255);
	$c['bar1'][ 7]	= imagecolorallocate($img, 196,  33,  33);
	$c['bar1'][ 8]	= imagecolorallocate($img, 196,  66, 196);
	$c['bar1'][ 9]	= imagecolorallocate($img, 100,   0, 155);
	$c['bar1'][10]	= imagecolorallocate($img,  88,   0, 121);
	$c['bar1'][11]	= imagecolorallocate($img,   0, 174, 215);
	$c['bar1'][12]	= imagecolorallocate($img,   0,  99, 151);
	$c['bar1'][13]	= imagecolorallocate($img, 175, 175, 175);
	$c['bar1'][14]	= imagecolorallocate($img, 222, 222, 222);
	$c['bar1'][15]	= imagecolorallocate($img, 255, 255, 255);

	$st['CHP'] = max($st['HP'] - $user['damage'], 0);
	if ($st['CHP'] <= 0)
		$classtext = 'K.O.\'d';
	else
		$classtext = $class['name'];

	box( 0, 0,2+strlen($user['name']),3);
	box( 0, 3,2+strlen($classtext),3);
	box( 0, 7,32, 4);
	box( 0,12,32, 9);
	box( 0,22,18, 6);
	box(19,22,13, 6);

	$fontY=fontc(255,250,240, 255,240, 80,  0, 0, 0);
	$fontR=fontc(255,230,220, 240,160,150,  0, 0, 0);
	$fontG=fontc(190,255,190,  60,220, 60,  0, 0, 0);
	$fontB=fontc(160,240,255, 120,190,240,  0, 0, 0);
	$fontW=fontc(255,255,255, 210,210,210,  0, 0, 0);

	twrite($fontW, 1, 1,0,"$user[name]");
	twrite((($classtext == $class['name']) ? $fontB : $fontR), 1, 4,0, $classtext);

	twrite($fontB, 1, 8,0,'HP       /');
	twrite($fontR, 3, 8,7,nlimiter($st['CHP']));
	twrite($fontY, 9, 8,7,nlimiter($st['HP']));

	if ($class['name'] == 'Technomancer') {
		twrite($fontB, 1, 9,0,'TP       %');
		twrite($fontR, 3, 9,7,'100');
	}
	else {
		twrite($fontB, 1, 9,0,'MP       /');
		twrite($fontR, 3, 9,7,nlimiter($st['MP']));
		twrite($fontY, 9, 9,7,nlimiter($st['MP']));
	}

	for($i=2;$i<9;$i++){
		 twrite($fontB, 1,11+$i,0,"$stat[$i]");
		 twrite($fontY, 3,11+$i,7,nlimiter($st[$stat[$i]]));
	}

	twrite($fontB, 1,23, 0,'Level');
	twrite($fontY, 1,23,16,$st['lvl']);
	twrite($fontB, 1,25, 0,'EXP:');
	twrite($fontY, 1,25,16,$st['exp']);
	twrite($fontB, 1,26, 0,'Next:');
	twrite($fontY, 1,26,16,calcexpleft($st['exp']));

	twrite($fontB,20,23, 0,'Coins:');
	twrite($fontY,20,25, 0,chr(0));
	twrite($fontG,20,26, 0,chr(0));
	twrite($fontY,21,25,10,max(0, $st['GP']));
	twrite($fontG,21,26,10,max(0, $user['gcoins']));

	$sc[ 1]=     1;
	$sc[ 2]=     5;
	$sc[ 3]=    25;
	$sc[ 4]=   100;
	$sc[ 5]=   250;
	$sc[ 6]=   500;
	$sc[ 7]=  1000;
	$sc[ 8]=  2500;
	$sc[ 9]=  5000;
	$sc[10]= 10000;
	$sc[11]= 100000;
	$sc[12]= 1000000;
	$sc[13]= 10000000;
	$sc[14]= 100000000;
	$sc[15]= 1000000000;

	bars();

	header('Content-type:image/png');
	imagepng($img);
	imagedestroy($img);



function twrite($font,$x,$y,$l,$text){
	global $img;

	$x*=8;
	$y*=8;
	$text.='';
	if (strlen($text)<$l)
		$x+=($l-strlen($text))*8;

	for($i=0;$i<strlen($text);$i++)
		imagecopy($img,$font,$i*8+$x,$y,(ord($text[$i])%16)*8,floor(ord($text[$i])/16)*8,8,8);
}

function fontc($r1,$g1,$b1,$r2,$g2,$b2,$r3,$g3,$b3){
	$font=imagecreatefrompng('images/rpg/font.png');
	imagecolortransparent($font,1);
	imagecolorset($font,6,$r1,$g1,$b1);
	imagecolorset($font,5,($r1*2+$r2)/3,($g1*2+$g2)/3,($b1*2+$b2)/3);
	imagecolorset($font,4,($r1+$r2*2)/3,($g1+$g2*2)/3,($b1+$b2*2)/3);
	imagecolorset($font,3,$r2,$g2,$b2);
	imagecolorset($font,0,$r3,$g3,$b3);
	return $font;
}

function box($x,$y,$w,$h){
	global $img,$c;

	$x*=8;
	$y*=8;
	$w*=8;
	$h*=8;

	imagerectangle($img,$x+0,$y+0,$x+$w-1,$y+$h-1,$c['bxb0']);
	imagerectangle($img,$x+1,$y+1,$x+$w-2,$y+$h-2,$c['bxb3']);
	imagerectangle($img,$x+2,$y+2,$x+$w-3,$y+$h-3,$c['bxb1']);
	imagerectangle($img,$x+3,$y+3,$x+$w-4,$y+$h-4,$c['bxb2']);
	imagerectangle($img,$x+4,$y+4,$x+$w-5,$y+$h-5,$c['bxb0']);

	for($i=5;$i<$h-5;$i++) {
	  $n=(1-$i/$h)*100;
	  imageline($img,$x+5,$y+$i,$x+$w-6,$y+$i,$c[$n]);
	}
}

function bars(){
	global $st,$img,$c,$sc,$pct,$stat,$user;

	for($s=1;@(max($st['HP'],$st['MP'])/$sc[$s])>113;$s++) {}
	if(!$sc[$s]) $sc[$s]=1;

	if ($st['HP'] > 0) {
		imagefilledrectangle($img,137,41+24,136+$st['HP']/$sc[$s],47+24,$c['bxb0']);
		imagefilledrectangle($img,136,40+24,135+$st['HP']/$sc[$s],46+24,$c['bar1'][$s]);
		if ($user['damage'] > 0) {
			$dmg	= max($st[HP] - $user['damage'], 0) / $sc[$s];
			$ctemp	= imagecolorsforindex($img, $c['bar1'][$s]);
			$df		= 0.6;
			imagefilledrectangle($img,135 + $st['HP']/$sc[$s],40+24,135+$dmg,46+24,imagecolorallocate($img, $ctemp['red'] * $df, $ctemp['green'] * $df, $ctemp['blue'] * $df));
		}
	}

	if ($st['MP'] > 0) {
		imagefilledrectangle($img,137,49+24,136+$st['MP']/$sc[$s],55+24,$c['bxb0']);
		imagefilledrectangle($img,136,48+24,135+$st['MP']/$sc[$s],54+24,$c['bar1'][$s]);
	}

	for($i=2;$i<9;$i++) $st2[$i]=$st[$stat[$i]];
	for($s=1;@(max($st2)/$sc[$s])>161;$s++){}
	if(!$sc[$s]) $sc[$s]=1;
	for($i=2;$i<9;$i++){
		if (floor($st[$stat[$i]]/$sc[$s]) > 0) {
			imagefilledrectangle($img,89,65+$i*8+24,89+$st[$stat[$i]]/$sc[$s], 71+$i*8+24,$c['bxb0']);
			imagefilledrectangle($img,88,64+$i*8+24,88+$st[$stat[$i]]/$sc[$s], 70+$i*8+24,$c['bar1'][$s]);
		}
	}

	$e2	= 16 * 8;	// width of bar
	$e1	= $e2 * $pct;
	$y	= 168+1+24;
	imagefilledrectangle($img,9,$y + 1, 8 + $e2, $y + 4, $c['bxb0']);
	imagefilledrectangle($img,8,$y    , 7 + $e2, $y + 3, $c['barE2']);
	imagefilledrectangle($img,8,$y    , 7 + $e1, $y + 3, $c['barE1']);
}

function nlimiter($n) {
	if ($n <            0) return "???"; //$n = abs($n);
	if ($n <=       99999) return $n;
	if ($n <=     9999999) return number_format(floor($n / 1000), 0, ".", "") ."K";
	if ($n <=    99999999) return number_format(floor($n / 100000) / 10, 1, ".", "") ."M";
	if ($n <=  9999999999) return number_format(floor($n / 1000000), 0, ".", "") ."M";
	if ($n <= 99999999999) return number_format(floor($n / 100000000) / 10, 1, ".", "") ."B";
	return number_format(floor($n / 1000000000), 0, ".", "") ."B";
}

