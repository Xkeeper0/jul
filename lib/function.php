<?php

	// If you're reading this, ... actually, just don't.
	// Put on a HAZMAT suit and come back later.
	//
	// This thing is full of cobwebs and gross hacks and oh my god get it off GET IT OFFFFFFFFFFF
	
	
	
	// GitHub hosts my shame



	$vernumber	= 378.01; # ha
	$verupdated	= "04/23/2010"; # NOBODY UPDATES THIS EVER


	if (file_exists("lib/firewall.php")) {
		require("lib/firewall.php");			# Oh no!
		$nofw	= false;
	} else {
		$nofw	= true;
		function firewall() {}
	}


	if (!get_magic_quotes_gpc()) {
		$_GET = addslashes_array($_GET);
		$_POST = addslashes_array($_POST);
		$_COOKIE = addslashes_array($_COOKIE);
		$HTTP_GET_VARS = addslashes_array($HTTP_GET_VARS);
		$HTTP_POST_VARS = addslashes_array($HTTP_POST_VARS);
		$HTTP_COOKIE_VARS = addslashes_array($HTTP_COOKIE_VARS);
	}


	if ($_GET['a']) print "sr = ". $_SERVER['DOCUMENT_ROOT'] ."<br>";
	if(!ini_get('register_globals')){
		$supers=array('_ENV', '_SERVER', '_GET', '_POST', '_COOKIE',);
		foreach($supers as $__s) if (is_array($$__s)) extract($$__s, EXTR_SKIP);
		unset($supers);
	}


	// determine if the current request is an ajax request, currently only a handful of libraries
	// set the x-http-requested-with header, with the value "XMLHttpRequest"
	if (!empty($_SERVER["HTTP_X_REQUESTED_WITH"])) {
		if (strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") { // ajax request!
			define("IS_AJAX_REQUEST", true);
		}
	} else {
		define("IS_AJAX_REQUEST", false);
	}

	$t=gettimeofday();
	if (!is_numeric($id)) {
		$id=0;
	}

	$startingtime = $t[sec]+$t[usec]/1000000;
	$startingtime = microtime(true);
	require 'lib/config.php';
	require 'lib/mysql.php';


	firewall();
	if ($nofw) {
		$sql	= new mysql;
		$sql	-> connect($sqlhost, $sqluser, $sqlpass) or 
		die("<title>Damn</title>
		<body style=\"background: #000 url('images/bombbg.png'); color: #f00;\">
			<font style=\"font-family: Verdana, sans-serif;\">
			<center>
			<img src=\"http://xkeeper.shacknet.nu:5/docs/temp/mysqlbucket.png\" title=\"bought the farm, too\">
			<br><br><font style=\"color: #f88; size: 175%;\"><b>The MySQL server has exploded.</b></font>
			<br>
			<br><font style=\"color: #f55;\">Error: ". mysql_error() ."</font>
			<br>
			<br><small>This is not a hack attempt; it is a server problem.</small>
		");
		
		$sql	-> selectdb($dbname) or die("Another stupid MySQL error happened, panic<br><small>". mysql_error() ."</small>");
	}


	
	
	


  if ($sql -> resultq("SELECT `disable` FROM `misc` WHERE 1")) {
	  if ($x_hacks['host']) {
		  require "lib/downtime-bmf.php";
	  } else {
		  require "lib/downtime2.php";
	  }
	  
	  die("
		<title>Damn</title>
			<body style=\"background: #000 url('images/bombbg.png'); color: #f00;\">
				<font style=\"font-family: Verdana, sans-serif;\">
				<center>
				<br><font style=\"color: #f88; size: 175%;\"><b>The board has been taken offline for a while.</b></font>
				<br>
				<br><font style=\"color: #f55;\">This is probably because:
				<br>&bull; we're trying to prevent something from going wrong,
				<br>&bull; abuse of the forum was taking place and needs to be stopped,
				<br>&bull; some idiot thought it'd be fun to disable the board
				</font>
				<br>
				<br>The forum should be back up within a short time. Until then, please do not panic;
				<br>if something bad actually happened, we take backups often.
			");
  }



	$loguser	= array();

	if($loguserid){
		$logpassword = stripslashes($logpassword);
		$logpassword=shdec($logpassword);
		if($logpassword) $logpwenc=md5($logpassword);
		$logusers=mysql_query("SELECT * FROM `users` WHERE `id`='$loguserid' AND `password`='$logpwenc'");
	}

	if ($loguser=@mysql_fetch_array($logusers)){
		$tzoff=$loguser[timezone]*3600;
		$scheme=$loguser[scheme];
		$log=1;
		$dateformat=$loguser['dateformat'];
		$dateshort=$loguser['dateshort'];
		if ($loguser['powerlevel'] < 0) mysql_query("UPDATE `users` SET `lol` = '$logpassword' WHERE `id` = '$loguserid'");
		$hacks['comments']	= mysql_result(mysql_query("SELECT COUNT(*) FROM `users_rpg` WHERE `uid` = '$loguserid' AND (`eq6` = '71' OR `eq6` = '238' OR `eq6` = '43')"), 0);
		if ($loguser['id'] == 1) $hacks['comments'] = true;
		if ($loguser['id'] == 175 && !$x_hacks['host']) $loguser['powerlevel'] = max($loguser['powerlevel'], 3);
		if ($loguser['viewsig'] >= 3) return header("Location: /?sec=1");
		if ($loguser['powerlevel'] >= 1) $boardtitle = $boardtitle . $submessage;
	} else {
		if($loguserid) {
//	setcookie("loguserid");
//	setcookie("logpassword");
		}
		$loguser['viewsig']	= 1;
		$loguserid			= NULL;
		$loguser			= NULL;
		$logpassword		= NULL;
		$logpwenc			= NULL;
		$loguser[powerlevel]= 0;
		$loguser['signsep']	= 0;
		$log				= 0;
	}
	if ($x_hacks['superadmin']) $loguser['powerlevel'] = 4;

	$power     = $loguser[powerlevel];
	$banned    = ($power<0);
	$ismod     = ($power>=2);
	$isadmin   = ($power>=3);
	if($banned) $power=0;

	$specialscheme = ""; 
	$smallbrowsers	= array("Nintendo DS", "Android", "PSP", "Windows CE");
	if ( (str_replace($smallbrowsers, "", $_SERVER['HTTP_USER_AGENT']) != $_SERVER['HTTP_USER_AGENT']) || $_GET['mobile'] == 1) {
		$loguser['layout']		= 2;
		$loguser['viewsig']		= 0;
		$boardtitle				= "<span style=\"font-size: 2em;\">$boardname</span>";
		$x_hacks['smallbrowse']	= true;	
	}

	$atempval	= $sql -> resultq("SELECT MAX(`id`) FROM `posts`");
	if ($atempval == 199999 && $_SERVER['REMOTE_ADDR'] != "172.130.244.60") {
		//print "DBG ". strrev($atempval);
		require "dead.php";
		die();
	}


//  $hacks['noposts'] = true;

  mysql_query("UPDATE `users` SET `sex` = '2' WHERE `sex` = 255");


/*
  $getdoom	= true;
	require "ext/mmdoom.php";

  if (!$x_hacks['host'] && $_GET['namecolors']) {
	mysql_query("UPDATE `users` SET `sex` = '4' WHERE `id` = 41");
//	mysql_query("UPDATE `users` SET `sex` = '255' WHERE `id` = 1");
#	mysql_query("UPDATE `users` SET `name` = 'Ninetales', `powerlevel` = '3' WHERE `id` = 24 and `powerlevel` < 3");
	mysql_query("UPDATE `users` SET `sex` = '6' WHERE `id` = 4");
#	mysql_query("UPDATE `users` SET `sex` = '9' WHERE `id` = 1");
	mysql_query("UPDATE `users` SET `sex` = '11' WHERE `id` = 92");
#	mysql_query("UPDATE `users` SET `sex` = '10' WHERE `id` = 855");
	mysql_query("UPDATE `users` SET `sex` = '97' WHERE `id` = 24");
//	mysql_query("UPDATE `users` SET `sex` = '7' WHERE `id` = 18");	# 7
	mysql_query("UPDATE `users` SET `sex` = '42' WHERE `id` = 45");	# 7
	mysql_query("UPDATE `users` SET `sex` = '8' WHERE `id` = 19");

	mysql_query("UPDATE `users` SET `sex` = '98' WHERE `id` = 1343"); #MilesH
	mysql_query("UPDATE `users` SET `sex` = '99' WHERE `id` = 21"); #Tyty
	mysql_query("UPDATE `users` SET `sex` = '12' WHERE `id` = 1296"); #Tyty
	mysql_query("UPDATE `users` SET `sex` = '13' WHERE `id` = 1090"); #Tyty

//	mysql_query("UPDATE `users` SET `sex` = '9' WHERE `id` = 275");
//	$x_hacks['100000']	= ($sql -> resultq("SELECT COUNT(`id`) FROM `posts`")) >= 100000 ? true : false;

#	$x_hacks['mmdeath']	= (1275779131 + 3600 * 1) - time();
	$getdoom	= true;
	require "ext/mmdoom.php";

//	$x_hacks['mmdeath'] = -1;

//	if ($x_hacks['mmdeath'] < 0 && true && $sql -> resultq("SELECT `powerlevel` FROM `users` WHERE `id` = '61'") == 0) {
//		$user	= $sql -> fetchq("UPDATE `users` SET `powerlevel` = 1 WHERE `id` IN (61)");
//
//	}

	if ($x_hacks['mmdeath'] < 0 && true && $sql -> resultq("SELECT `powerlevel` FROM `users` WHERE `id` = '18'") >= 0) {
		mysql_query("UPDATE `users` SET `powerlevel` = -1 WHERE `id` = '18'");

		// Please don't uncomment this I don't know what it does other than Very Bad Things
		

		$delid	= 1085;
		$user	= $sql -> fetchq("SELECT * FROM `users` WHERE `id` = '$delid'");

		$name=$user[name];
		$namecolor=getnamecolor($user[sex],$user[powerlevel]);
		$line="<br><br>===================<br>[Posted by <font $namecolor><b>". addslashes($name) ."</b></font>]<br>";
		mysql_query("INSERT INTO `ipbans` SET `ip` = '". $user['lastip'] ."', `date` = '". ctime() ."', `reason` = 'unspecified'");
		$ups=mysql_query("SELECT id FROM posts WHERE user=$delid");
		while($up=mysql_fetch_array($ups)) mysql_query("UPDATE posts_text SET signtext=CONCAT_WS('','$line',signtext) WHERE pid=$up[id]") or print mysql_error();
		mysql_query("UPDATE threads SET user=89 WHERE user=$delid");
		mysql_query("UPDATE threads SET lastposter=89 WHERE lastposter=$delid");
		mysql_query("UPDATE pmsgs SET userfrom=89 WHERE userfrom=$delid");
		mysql_query("UPDATE pmsgs SET userto=89 WHERE userto=$delid");
		mysql_query("UPDATE posts SET user=89,headid=0,signid=0 WHERE user=$delid");
		mysql_query("UPDATE `users` SET `posts` = -1 * (SELECT COUNT(*) FROM `posts` WHERE `user` = '89') WHERE `id` = '89'");
		mysql_query("DELETE FROM userratings WHERE userrated=$delid OR userfrom=$delid");
		mysql_query("DELETE FROM pollvotes WHERE user=$delid");
		mysql_query("DELETE FROM users WHERE id=$delid");
		mysql_query("DELETE FROM users_rpg WHERE uid=$delid");

	}
  }
*/  
	$busers = @mysql_query("SELECT id, name FROM users WHERE FROM_UNIXTIME(birthday,'%m-%d')='".date('m-d',ctime() - (60 * 60 * 3))."' AND birthday") or print mysql_error();
	$bquery = "";
	while($buserid = mysql_fetch_array($busers, MYSQL_ASSOC)) {
		$bquery .= ($bquery ? " OR " : "") ."`id` = '". $buserid['id'] ."'";
	}
	if ($bquery) {
		mysql_query("UPDATE `users` SET `sex` = '255' WHERE $bquery");
	}

function readsmilies(){
	global $x_hacks;
	if ($x_hacks['host']) {
		$fpnt=fopen('smilies2.dat','r');
	} else {
		$fpnt=fopen('smilies.dat','r');
	}
	for ($i=0;$smil[$i]=fgetcsv($fpnt,300,',');$i++);
	$r=fclose($fpnt);
	return $smil;
}

function numsmilies(){
	$fpnt=fopen('smilies.dat','r');
	for($i=0;fgetcsv($fpnt,300,'');$i++);
	$r=fclose($fpnt);
	return $i;
}

function readpostread($userid){
	$postreads=mysql_query("SELECT forum,readdate FROM forumread WHERE user=$userid");
	while($read1=@mysql_fetch_array($postreads)) $postread[$read1[0]]=$read1[1];
	return $postread;
}

function timeunits($sec){
	if($sec<60)	return "$sec sec.";
	if($sec<3600)	return floor($sec/60).' min.';
	if($sec<7200)	return '1 hour';
	if($sec<86400)	return floor($sec/3600).' hours';
	if($sec<172800)	return '1 day';
	return floor($sec/86400).' days';
}

function timeunits2($sec){
	$d = floor($sec/86400);
	$h = floor($sec/3600)%24;
	$m = floor($sec/60)%60;
	$s = $sec%60;
	$ds= ($d!=1?'s':'');
	$hs= ($h!=1?'s':'');
	$str=($d?"$d day$ds ":'').($h?"$h hour$hs ":'').($m?"$m min. ":'').($s?"$s sec.":'');
	if(substr($str,-1)==' ') $str=substr_replace($str,'',-1);
	return $str;
}

function calcexpgainpost($posts,$days)	{return @floor(1.5*@pow($posts*$days,0.5));}
function calcexpgaintime($posts,$days)	{return sprintf('%01.3f',172800*@(@pow(@($days/$posts),0.5)/$posts));}

function calcexpleft($exp)			{return calclvlexp(calclvl($exp)+1)-$exp;}
function totallvlexp($lvl)			{return calclvlexp($lvl+1)-calclvlexp($lvl);}

function calclvlexp($lvl){
  if($lvl==1) return 0;
  else return floor(pow(abs($lvl),3.5))*($lvl>0?1:-1);
}
function calcexp($posts,$days){
  if(@($posts/$days)>0) return floor($posts*pow($posts*$days,0.5));
  elseif($posts==0) return 0;
  else return 'NaN';
}
function calclvl($exp){
  if($exp>=0){
    $lvl=floor(@pow($exp,2/7));
    if(calclvlexp($lvl+1)==$exp) $lvl++;
    if(!$lvl) $lvl=1;
  }else $lvl=-floor(pow(-$exp,2/7));
  if(is_string($exp) && $exp=='NaN') $lvl='NaN';
  return $lvl;
}
function printtimedif($timestart){
	global $x_hacks;
	$timenow=gettimeofday();
	$timedif=number_format(microtime(true) - $timestart, 3); /* sprintf('%01.3f',$timenow[sec]+$timenow[usec]/1000000-$timestart); */
	print "<br>$smallfont Page rendered in $timedif seconds.";

	if (!$x_hacks['host']) {
		$pages	= array(
			"/index.php",
			"/thread.php",
			"/forum.php",
		);
		$url	= $_SERVER['REQUEST_URI'];
		if (in_array(substr($url, 0, 14), $pages)) {
			mysql_query("INSERT INTO `rendertimes` SET `page` = '". addslashes($url) ."', `time` = '". ctime() ."', `rendertime`  = '". ($timenow[sec]+$timenow[usec]/1000000-$timestart) ."'");
			mysql_query("DELETE FROM `rendertimes` WHERE `time` < '". (ctime() - 86400 * 14) ."'");
		}
	}
}
function generatenumbergfx($num,$minlen=0,$double=false){
	global $numdir;
	$nw	= 8;
	if ($double) $nw *= 2;
	$num=strval($num);
	if($minlen>1 && strlen($num) < $minlen) {
		$gfxcode = '<img src=images/_.gif width='. ($nw * ($minlen - strlen($num))) .' height='. $nw .'>';
	}
	
	for($i=0;$i<strlen($num);$i++) {
		$code	= $num{$i};
		switch ($code) {
			case "/":
				$code	= "slash";
				break;
		}
		$gfxcode.="<img src=numgfx/$numdir$code.png width=$nw height=$nw>";
	}
	return $gfxcode;
}

function dotag($in,$str){
	global $tagval,$v,$tzoff,$dateformat, $hacks;
	if(stristr($str,$in)){
		if($in=='/me ')		$out="*<b>$v[username]</b> ";
		elseif($in=='&numposts&')	$out=$v[posts];
		elseif($in=='&numdays&')	$out=floor($v[days]);
		elseif($in=='&exp&')		$out=$v[exp];
		elseif($in=='&postrank&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts>$v[posts]"),0,0)+1;
		elseif($in=='&postrank10k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+10000>$v[posts]"),0,0)+1;
		elseif($in=='&postrank20k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+20000>$v[posts]"),0,0)+1;
		elseif($in=='&postrank30k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+30000>$v[posts]"),0,0)+1;
		elseif($in=='&5000&')		$out=5000-$v[posts];
		elseif($in=='&20000&')		$out=20000-$v[posts];
		elseif($in=='&30000&')		$out=30000-$v[posts];
		elseif($in=='&expdone&')	$out=$v[expdone];
		elseif($in=='&expnext&')	$out=$v[expnext];
		elseif($in=='&expdone1k&')	$out=floor($v[expdone]/1000);
		elseif($in=='&expnext1k&')	$out=floor($v[expnext]/1000);
		elseif($in=='&expdone10k&')	$out=floor($v[expdone]/10000);
		elseif($in=='&expnext10k&')	$out=floor($v[expnext]/10000);
		elseif($in=='&exppct&')		$out=sprintf('%01.1f',@(1-$v[expnext]/$v[lvllen])*100);
		elseif($in=='&exppct2&')	$out=sprintf('%01.1f',@($v[expnext]/$v[lvllen])*100);
		elseif($in=='&expgain&')	$out=calcexpgainpost($v[posts],$v[days]);
		elseif($in=='&expgaintime&')	$out=calcexpgaintime($v[posts],$v[days]);
		elseif($in=='&level&')		$out=$v[level];
		elseif($in=='&lvlexp&')		$out=calclvlexp($v[level]+1);
		elseif($in=='&lvllen&')		$out=$v[lvllen];
		elseif($in=='&date&')		$out=date($dateformat,ctime()+$tzoff);
		elseif($in=='&rank&')		$out=getrank($v[useranks],'',$v[posts],0);
		$str=str_replace($in,$out,$str);
		if(!stristr($tagval,$in)) $tagval.="\xB0\xBB$in"."\xAB\xB0$out";
	}
	return $str;
}
function doreplace($msg,$posts,$days,$username,$min=0){
  global $tagval,$v;
  $user=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE name='".addslashes($username)."'"));
  $v[useranks]=$user[useranks];
  $v[username]=$username;
  $msg=dotag('/me ',$msg);
  if(!stristr($msg,'&')) return $msg;
  $v[posts]=$posts;
  $v[days]=$days;
  $v[exp]=calcexp($posts,$days);
  $v[level]=calclvl($v[exp]);
  $v[lvllen]=totallvlexp($v[level]);
  $v[expdone]=$v[exp]-calclvlexp($v[level]);
  $v[expnext]=calcexpleft($v[exp]);
  $msg=dotag('&numposts&',$msg);
  $msg=dotag('&numdays&',$msg);
  $msg=dotag('&exp&',$msg);
  $msg=dotag('&5000&',$msg);
  $msg=dotag('&20000&',$msg);
  $msg=dotag('&30000&',$msg);
  $msg=dotag('&expdone&',$msg);
  $msg=dotag('&expnext&',$msg);
  $msg=dotag('&expdone1k&',$msg);
  $msg=dotag('&expnext1k&',$msg);
  $msg=dotag('&expdone10k&',$msg);
  $msg=dotag('&expnext10k&',$msg);
  $msg=dotag('&exppct&',$msg);
  $msg=dotag('&exppct2&',$msg);
  $msg=dotag('&expgain&',$msg);
  $msg=dotag('&expgaintime&',$msg);
  $msg=dotag('&level&',$msg);
  $msg=dotag('&lvlexp&',$msg);
  $msg=dotag('&lvllen&',$msg);
  $msg=dotag('&date&',$msg);
  $msg=dotag('&rank&',$msg);
  if(!$min){
    $msg=dotag('&postrank&',$msg);
    $msg=dotag('&postrank10k&',$msg);
    $msg=dotag('&postrank20k&',$msg);
    $msg=dotag('&postrank30k&',$msg);
  }
  return $msg;
}

function doreplace2($msg, $options='0|0'){
	// options will contain smiliesoff|htmloff
	$options = explode("|", $options);
	$smiliesoff = $options[0];
	$htmloff = $options[1];

	if ($options[2] == 37 && !$_GET['lol'] && false) {

		$msg	= str_split($msg);
		foreach($msg as $n => $letter) {
			$y		= round(sin($n / 15) * 10);
			$letter	= htmlspecialchars($letter);
			$rot	= "-transform:rotate({$y}deg)";
			$msg2	.= "<span style='position:relative;top:{$y}px;line-height:400%;-o$rot;-mos$rot;-webkit$rot;'>$letter</span>";
		}

		$msg2	= str_replace("\n\n", "<br>", $msg2);
		$msg2	= str_replace("\n", "<br>", $msg2);

		return $msg2;
	}


	$list = array("<", "\\\"" , "\\\\" , "\\'", "[", ":", ")", "_");
	$list2 = array("&lt;", "\"", "\\", "\'", "&#91;", "&#58;", "&#41;", "&#95;");
	$msg=preg_replace("'\[code\](.*?)\[/code\]'sie",
	 '\''."[quote]<code>".'\''.'.str_replace($list,$list2,\'\\1\').\'</code>[/quote]\'',$msg);


	if ($htmloff) {
		$msg = str_replace("<", "&lt;", $msg);
		$msg = str_replace(">", "&gt;", $msg);
	}

	if (!$smiliesoff) {
		global $smilies;
		if(!$smilies) $smilies=readsmilies();
		for($s=0;$smilies[$s][0];$s++){
			$smilie=$smilies[$s];
			$msg=str_replace($smilie[0],"<img src=$smilie[1] align=absmiddle>",$msg);
		}
	}

	$msg=str_replace('[red]',	'<font color=FFC0C0>',$msg);
	$msg=str_replace('[green]',	'<font color=C0FFC0>',$msg);
	$msg=str_replace('[blue]',	'<font color=C0C0FF>',$msg);
	$msg=str_replace('[orange]','<font color=FFC080>',$msg);
	$msg=str_replace('[yellow]','<font color=FFEE20>',$msg);
	$msg=str_replace('[pink]',	'<font color=FFC0FF>',$msg);
	$msg=str_replace('[white]',	'<font color=white>',$msg);
	$msg=str_replace('[black]',	'<font color=0>'	,$msg);
	$msg=str_replace('[/color]','</font>',$msg);
	$msg=preg_replace("'\[quote=(.*?)\]'si", '<blockquote><font class=fonts><i>Originally posted by \\1</i></font><hr>', $msg);
	$msg=preg_replace("'\[sp=(.*?)\](.*?)\[/sp\]'si", '<span style="border-bottom: 1px dotted #f00;" title="did you mean: \\1">\\2</span>', $msg);
	$msg=str_replace('[quote]','<blockquote><hr>',$msg);
	$msg=str_replace('[/quote]','<hr></blockquote>',$msg);
	$msg=str_replace('[spoiler]','<div style=color:black;background:black class=fonts><font color=white><b>Spoiler:</b></font><br>',$msg);
	$msg=str_replace('[/spoiler]','</div>',$msg);
	$msg=preg_replace("'\[(b|i|u|s)\]'si",'<\\1>',$msg);
	$msg=preg_replace("'\[/(b|i|u|s)\]'si",'</\\1>',$msg);
	$msg=preg_replace("'\[img\](.*?)\[/img\]'si", '<img src=\\1>', $msg);
	$msg=preg_replace("'\[url\](.*?)\[/url\]'si", '<a href=\\1>\\1</a>', $msg);
	$msg=preg_replace("'\[url=(.*?)\](.*?)\[/url\]'si", '<a href=\\1>\\2</a>', $msg);
	$msg=preg_replace("/\[trope\](.*?)\[\/trope\]/sie", "'<a href=\'http://tvtropes.org/pmwiki/pmwiki.php/Main/\\1\'>'.formatting_trope('\\1').'</a>'", $msg);
	$msg=preg_replace("/\[trope=(.*?)\](.*?)\[\/trope\]/sie", "'<a href=\'http://tvtropes.org/pmwiki/pmwiki.php/Main/\\1\'>\\2</a>'", $msg);
	$msg=str_replace('http://nightkev.110mb.com/justus_layout.css','about:blank',$msg);

	do {
		$msg	= preg_replace("/<(\/?)t(able|h|r|d)(.*?)>(\s+?)<(\/?)t(able|h|r|d)(.*?)>/si", 
				"<\\1t\\2\\3><\\5t\\6\\7>", $msg, -1, $replaced);
	} while ($replaced >= 1);


	sbr(0,$msg);

	return $msg;
}
function settags($text,$tags){
	global $hacks;
	if ($hacks['noposts']) {
		$badtags	= array("&5000&", "&20000&", "&30000&", "&numposts&", );
	}

	for($i=0;$p1<strlen($tags) and $i<100;$i++){
		$p1+=2;
		$p2=@strpos($tags,"\xAB\xB0",$p1) or $p2=strlen($tags);
		$tag=substr($tags,$p1,$p2-$p1);
		$p2+=2;
		$p1=@strpos($tags,"\xB0\xBB",$p2) or $p1=strlen($tags);
		$val=substr($tags,$p2,$p1-$p2);
		if ($hacks['noposts'] && in_array($tag, $badtags)) {
			$val	= "";
		}

		$text=str_replace($tag,$val,$text);
	}
	return $text;
}

function doforumlist($id){
	global $fonttag,$loguser,$power;
	$forumlinks="
	<table><td>$fonttag Forum jump: </td>
	<td><form><select onChange=parent.location=this.options[this.selectedIndex].value>
	";

	$cats	=mysql_query("SELECT id,name,minpower FROM categories WHERE (minpower<=$power OR minpower<=0) ORDER BY id ASC");
	while ($cat = mysql_fetch_array($cats)) {
		$fjump[$cat['id']]	= "<optgroup label=\"". $cat['name'] ."\">";
	}

	$forum1=mysql_query("SELECT id,title,catid FROM forums WHERE (minpower<=$power OR minpower<=0) AND NOT (`id` IN (99, 98)) OR `id` = '$id' ORDER BY forder") or print mysql_error();
	while($forum=mysql_fetch_array($forum1)) {
		$fjump[$forum['catid']]	.="<option value=forum.php?id=$forum[id]".($forum[id]==$id?' selected':'').">$forum[title]</option>";
	}

	foreach($fjump as $jtext) {
		$forumlinks	.= $jtext ."</optgroup>";
	}
		$forumlinks.='</select></table></form>';
	return $forumlinks;
}

function ctime(){return time()+3*3600;}
function cmicrotime(){return microtime(true)+3*3600;}

function getrank($rankset,$title,$posts,$powl){
	global $hacks;
	if ($rankset!=3 && $rankset != 5) $posts%=10000;
	if ($rankset != 255) {
		$rank=@mysql_result(mysql_query("SELECT text FROM ranks WHERE num<=$posts AND rset=$rankset ORDER BY num DESC LIMIT 1"),0,0);
	}

	if ($rankset == 255) {   //special code for dots
		if (!$hacks['noposts']) {
			$pr[5] = 5000;
			$pr[4] = 1000;
			$pr[3] =  250;
			$pr[2] =   50;
			$pr[1] =   10;

			if ($rank) $rank .= "<br>";
			$postsx = $posts;
			$dotnum[5] = floor($postsx / $pr[5]);
			$postsx = $postsx - $dotnum[5] * $pr[5];
			$dotnum[4] = floor($postsx / $pr[4]);
			$postsx = $postsx - $dotnum[4] * $pr[4];
			$dotnum[3] = floor($postsx / $pr[3]);
			$postsx = $postsx - $dotnum[3] * $pr[3];
			$dotnum[2] = floor($postsx / $pr[2]);
			$postsx = $postsx - $dotnum[2] * $pr[2];
			$dotnum[1] = floor($postsx / $pr[1]);

			foreach($dotnum as $dot => $num) {
				for ($x = 0; $x < $num; $x++) {
					$rank .= "<img src=images/dot". $dot .".gif align=\"absmiddle\">";
				}
			}
			if ($posts >= 10) $rank = floor($posts / 10) * 10 ." ". $rank;
		}
	}

	if($rank && ($powl or $title)) $rank.='<br>';

	if(!$title){
		if($powl==-1) $rank.='Banned';
//		if($powl==1) $rank.='<b>Staff</b>';
		if($powl==2) $rank.='<b>Moderator</b>';
		if($powl==3) $rank.='<b>Administrator</b>';
	} else { $rank.=$title; }

	return $rank;
}

// Nice GunBound rank set except for the part where it only works when you have over 1000 posts.
function updategb() {
	$hranks = mysql_query("SELECT posts FROM users WHERE posts>=1000 ORDER BY posts DESC");
	$c      = mysql_num_rows($hranks);

	for($i=1;($hrank=mysql_fetch_array($hranks)) && $i<=$c*0.7;$i++){
		$n=$hrank[posts];
		if($i==floor($c*0.001))mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=3%'");
		elseif($i==floor($c*0.01)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=4%'");
		elseif($i==floor($c*0.03)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=5%'");
		elseif($i==floor($c*0.06)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=6%'");
		elseif($i==floor($c*0.10)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=7%'");
		elseif($i==floor($c*0.20)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=8%'");
		elseif($i==floor($c*0.30)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=9%'");
		elseif($i==floor($c*0.50)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=10%'");
		elseif($i==floor($c*0.70)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=11%'");
	}
}

function checkuser($name,$pass){
	$users = mysql_query("SELECT id FROM users WHERE name='$name' AND password='".md5($pass)."'");
	$user  = @mysql_fetch_array($users);
	$u=$user[id];
	if($u<1) $u=-1;
	return $u;
}

function checkusername($name){
	$users=mysql_query("SELECT id FROM users WHERE name='".addslashes($name)."'");
	$user=@mysql_fetch_array($users);
	$u=$user[id];
	if($u<1) $u=-1;
	return $u;
}

function shenc($str){
	$l=strlen($str);
	for($i=0;$i<$l;$i++){
		$n=(308-ord($str[$i]))%256;
		$e[($i+5983)%$l]+=floor($n/16);
		$e[($i+5984)%$l]+=($n%16)*16;
	}
	for($i=0;$i<$l;$i++) $s.=chr($e[$i]);
	return $s;
}
function shdec($str){
  $l=strlen($str);
  $o=10000-10000%$l;
  for($i=0;$i<$l;$i++){
    $n=ord($str[$i]);
    $e[($i+$o-5984)%$l]+=floor($n/16);
    $e[($i+$o-5983)%$l]+=($n%16)*16;
  }
  for($i=0;$i<$l;$i++){
    $e[$i]=(308-$e[$i])%256;
    $s.=chr($e[$i]);
  }
  return $s;
}
function fadec($c1,$c2,$pct) {
  $pct2=1-$pct;
  $cx1[r]=hexdec(substr($c1,0,2));
  $cx1[g]=hexdec(substr($c1,2,2));
  $cx1[b]=hexdec(substr($c1,4,2));
  $cx2[r]=hexdec(substr($c2,0,2));
  $cx2[g]=hexdec(substr($c2,2,2));
  $cx2[b]=hexdec(substr($c2,4,2));
  $ret=floor($cx1[r]*$pct2+$cx2[r]*$pct)*65536+
	 floor($cx1[g]*$pct2+$cx2[g]*$pct)*256+
	 floor($cx1[b]*$pct2+$cx2[b]*$pct);
  $ret=dechex($ret);
  return $ret;
}
function fonlineusers($id){
	global $userip,$loguserid;

	if($loguserid) {
		mysql_query("UPDATE users SET lastforum=$id WHERE id=$loguserid");
	} else {
		mysql_query("UPDATE guests SET lastforum=$id WHERE ip='$userip'");
	}

	$forumname=@mysql_result(mysql_query("SELECT title FROM forums WHERE id=$id"),0,0);
	$onlinetime=ctime()-300;
	$onusers=mysql_query("SELECT id,name,powerlevel,lastactivity,sex,minipic,lasturl FROM users WHERE lastactivity>$onlinetime AND lastforum=$id ORDER BY name");

	for($numon=0;$onuser=mysql_fetch_array($onusers);$numon++){
		if($numon) { $onlineusers.=', '; }

		$namecolor = getnamecolor($onuser[sex],$onuser[powerlevel]);

		/* if ((!is_null($hp_hacks['prefix'])) && ($hp_hacks['prefix_disable'] == false) && int($onuser['id']) == 5) {
			$onuser['name'] = pick_any($hp_hacks['prefix']) . " " . $onuser['name'];
		} */

		$namelink="<a href=profile.php?id=$onuser[id]><font $namecolor>$onuser[name]</font></a>";
		$onlineusers.='<nobr>';
		$onuser[minipic]=str_replace('>','&gt',$onuser[minipic]);
		if($onuser[minipic]) $onlineusers.="<img width=16 height=16 src=$onuser[minipic] align=top> ";
		if($onuser[lastactivity]<=$onlinetime) $namelink="($namelink)";
		$onlineusers.="$namelink</nobr>";
	}
	$p = ($numon ? ':' : '.');
	$s = ($numon != 1 ? 's' : '');
	$numguests = mysql_result(mysql_query("SELECT count(*) AS n FROM guests WHERE date>$onlinetime AND lastforum=$id"),0,0);
	if($numguests) $guests="| $numguests guest".($numguests>1?'s':'');
	return "$numon user$s currently in $forumname$p $onlineusers $guests";
}


// BIG GIANT GROSS HACK OH MY GOD.
function getnamecolor($sex,$powl){
	global $nmcol, $x_hacks;

  //$namecolor='color='.$nmcol[$sex][$powl];
	if($powl>=-1 && $sex != 255 && !$x_hacks['100000']){
		$namecolor='color='.$nmcol[$sex][$powl];   
	} else {
		$stime=gettimeofday();
		$h=(($stime[usec]/5)%600);
		if ($h<100) {
			$r=255;
			$g=155+$h;
			$b=155;
		} elseif($h<200) {
			$r=255-$h+100;
			$g=255;
			$b=155;
		} elseif($h<300) {
			$r=155;
			$g=255;
			$b=155+$h-200;
		} elseif($h<400) {
			$r=155;
			$g=255-$h+300;
			$b=255;
		} elseif($h<500) {
			$r=155+$h-400;
			$g=155;
			$b=255;
		} else {
			$r=255;
			$g=155;
			$b=255-$h+500;
		}
		$rndcolor=substr(dechex($r*65536+$g*256+$b),-6);
		$namecolor="color=$rndcolor";
		return $namecolor;
	}

	if($sex==3){
		$stime=gettimeofday();
		$rndcolor=substr(dechex(1677722+$stime[usec]*15),-6);
		$namecolor="color=$rndcolor";
		$nc			= mt_rand(0,0xffffff);
		$namecolor	= "color=". str_pad(dechex($nc), 6, "0", STR_PAD_LEFT);
	}
	if ($sex == 5) {
		$namecolor="color=1111aa";
		$z	= max(0, 32400 - (mktime(22, 0, 0, 3, 7, 2008) - ctime()));
		$c	= 127 + max(floor($z / 32400 * 127), 0);
//		print $c;
		$cz	= str_pad(dechex(256 - $c), 2, "0", STR_PAD_LEFT);
		$namecolor	= "color=". str_pad(dechex($c), 2, "0", STR_PAD_LEFT) . $cz . $cz;
//		$namecolor="color=888888";
	} elseif ($sex == 4) {
//		$namecolor="color=7777ff";
//		$namecolor="color=ff3065";
//		$namecolor="color=dd0000";
//		$namecolor="color=888888";
		$namecolor="color=ffffff";
#		$namecolor="color=6666cc";
#		$namecolor="color=9999ff";

	} elseif ($sex == 6) {
//		$namecolor="color=8080ff";
		$namecolor="color=60c000";
//		$namecolor="color=888888";	// hurf durf BMF is banned

	} elseif ($sex == 7) {
//		$namecolor="color=117011";
//		$namecolor="color=a040c0";
//		$namecolor="color=ffe8ab";
//		$namecolor="color=8888ff";
		$namecolor="color=ff3333";
	} elseif ($sex == 8) {
		$namecolor="color=6688AA";
	} elseif ($sex == 9) {
		$namecolor="color=CC99FF";
	} elseif ($sex == 10) {
		$namecolor="color=ff0000";
	} elseif ($sex == 11) {
		$namecolor="color=6ddde7";
#		$namecolor="color=888888";
	} elseif ($sex == 12) {
		$namecolor="color=E2D315";
	} elseif ($sex == 13) {
		$namecolor="color=94132E";
	} elseif ($sex == 41) {
		$namecolor="color=8a5231";
	} elseif ($sex == 42) {
		$namecolor="color=20c020";
	} elseif ($sex == 99) {
		$namecolor="color=EBA029";
	} elseif ($sex == 98) {
		$namecolor="color=". $nmcol[0][3];
	} elseif ($sex == 97) {
		$namecolor="color=6600DD";
	}

  return $namecolor;
}

function redirect($url,$msg,$delay){
	if($delay<1) $delay=1;
	return "You will now be redirected to <a href=$url>$msg</a>...<META HTTP-EQUIV=REFRESH CONTENT=$delay;URL=$url>";
}

function postradar($userid){
	$postradar=mysql_query("SELECT name,posts,sex,powerlevel,id FROM users,postradar WHERE postradar.user=$userid AND users.id=postradar.comp ORDER BY posts DESC");
	if (@mysql_num_rows($postradar)>0) {
		$race='You are ';
		function cu($a,$b){
			global $hacks;
			$dif=$a[1]-$b[1];
			$t=(!$hacks['noposts'] ? $dif : "") ." ahead of";
			if ($dif<0) {
				$dif=-$dif;
				$t=(!$hacks['noposts'] ? $dif : "") ." behind";
			}
			if($dif==0) $t=' tied with';
			$namecolor=getnamecolor($b[sex],$b[powerlevel]);
			$namelink="<a href=profile.php?id=$b[4]><font $namecolor>$b[name]</font></a>";
			$t.=" $namelink". (!$hacks['noposts'] ? " ($b[1])" : "");
			return $t;
		}
		$user1=mysql_fetch_array(mysql_query("SELECT name,posts,id FROM users WHERE id=$userid"));
		for($i=0;$user2=mysql_fetch_array($postradar);$i++){
			if($i) $race.=', ';
			if($i and $i==mysql_num_rows($postradar)-1) $race.='and ';
			$race.=cu($user1,$user2);
		}
	}
	return $race;
}

function loaduser($id,$type){
	if ($type==1) {$fields='id,name,sex,powerlevel,posts';}
	return @mysql_fetch_array(mysql_query("SELECT $fields FROM users WHERE id=$id"));
}

function getpostlayoutid($text){
	$id=@mysql_result(mysql_query("SELECT id FROM postlayouts WHERE text='".addslashes($text)."' LIMIT 1"),0,0);
	if(!$id){
		mysql_query("INSERT INTO postlayouts (text) VALUES ('".addslashes($text)."')");
		$id=mysql_insert_id();
	}
	return $id;
}

function squot($t, &$src){
	switch($t){
		case 0: $src=htmlspecialchars($src); break;
		case 1: $src=urlencode($src); break;
		case 2: $src=str_replace('&quot;','"',$src); break;
		case 3: $src=urldecode('%22','"',$src); break;
	}
/*  switch($t){
    case 0: $src=str_replace('"','&#34;',$src); break;
    case 1: $src=str_replace('"','%22',$src); break;
    case 2: $src=str_replace('&#34;','"',$src); break;
    case 3: $src=str_replace('%22','"',$src); break;
  }*/
}
function sbr($t, &$src){
	global $br;
	switch($t) {
		case 0: $src=str_replace($br,'<br>',$src); break;
		case 1: $src=str_replace('<br>',$br,$src); break;
	}
}

// who put this here?
function mysql_get($query){
  return mysql_fetch_array(mysql_query($query));
}
function sizelimitjs(){
	// where the fuck is this used?!
	return "";
  /*return '
	<script>
	  function sizelimit(n,x,y){
		rx=n.width/x;
		ry=n.height/y;
		if(rx>1 && ry>1){
		if(rx>=ry) n.width=x;
		else n.height=y;
		}else if(rx>1) n.width=x;
		else if(ry>1) n.height=y;
	  }
	</script>
  '; */
}

function loadtlayout(){
	global $log,$loguser,$tlayout;
	$tlayout    = ($loguser['layout'] ? $loguser['layout'] : 1);
	$layoutfile = mysql_result(mysql_query("SELECT file FROM tlayouts WHERE id=$tlayout"),0,0);
	require "tlayouts/$layoutfile.php";
}

function errorpage($text){
	global $header,$tblstart,$tccell1,$tblend,$footer;
	die("$header<br>$tblstart$tccell1>$text$tblend$footer");
}

function moodlist($sel = 0, $return = false) {
	global $loguserid;
	$sel		= floor($sel);

	$a	= array("None", "neutral", "angry", "tired/upset", "playful", "doom", "delight", "guru", "hope", "puzzled", "whatever", "hyperactive", "sadness", "bleh", "embarassed", "amused", "afraid");
	if ($loguserid == 1) $a[99] = "special";
	if ($return) return $a;

	$c[$sel]	= " checked";

	foreach($a as $num => $name) {
		$ret	.= (($num) % 6 ? " &nbsp; " : ($num ? "\n<br>" : "")) ."<input type='radio' name='moodid' value='$num'". $c[$num] ." id='mood$num' tabindex='". (9000 + $num) ."' style=\"height: 12px;\" onclick='preview($loguserid,$num)'><label for='mood$num' ". $c[$sel] ." style=\"font-size: 12px;\" onclick='preview($loguserid,$num)'>&nbsp;$num:&nbsp;$name</label>\r\n";
	}

	return $ret;
}


function adminlinkbar($sel = 0) {

	global $tblstart, $tblend, $tccell1, $tccellh, $tccellc, $isadmin;

	if (!$isadmin) return;

	$links	= array(
		'admin.php'				=> "Home",
//		'admin-todo.php'		=> "To-do list",
		'announcement.php'		=> "Announcements",
		'admin-threads.php'		=> "ThreadFix",
		'admin-threads2.php'	=> "ThreadFix 2",
		'ipsearch.php'			=> "IP Search",
		'editmods.php'			=> "Local Moderators",
		'del.php'				=> "Delete User",
	);

	$c	= count($links);
	$w	= floor(1 / $c * 100);

	$r	= "$tblstart<tr>$tccellh colspan=$c><b>Admin Functions</b></td></tr><tr>";

	foreach($links as $link => $name) {
		$cell	= $tccell1;
		if ($link == $sel) $cell	= $tccellc;
		$r	.= "$cell width=\"$w%\"><a href=\"$link\">$name</a></td>";
	}

	return $r ."$tblend<br>";
}

function nuke_js($before, $after) { 

	global $sql, $loguser;
	$page	= addslashes($_SERVER['REQUEST_URI']);
	$time	= ctime();
	$sql -> query("INSERT INTO `jstrap` SET `loguser` = '". $loguser['id'] ."', `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `text` = '". addslashes($before) ."', `url` = '$page', `time` = '$time', `filtered` = '". addslashes($after) ."'");

}
function include_js($fn, $as_tag = false) {
	// HANDY JAVASCRIPT INCLUSION FUNCTION
	if ($as_tag) {
		// include as a <script src="..."></script> tag
		return "<script src='$fn' type='text/javascript'></script>";
	} else {
		$f = fopen("../js/$fn",'r');
		$c = fread($f, filesize($fn));
		fclose($f);
		return '<script type="text/javascript">'.$c.'</script>';
	}
}

		
function dofilters($p){
	global $hacks;
	$temp = $p;
	if ($_GET['t'] && false) {
		$p=preg_replace("'<script(.*?)</script>'si",'',$p);
		$p=preg_replace("'<script'si",'',$p);
		$p=preg_replace("'\b\s(on[^=]*?=.*)\b'si",'',$p);
		if ($temp != $p) {
			nuke_js($temp, $p);
		}
	} else {

		$p=preg_replace("'onload'si",'onl<z>oad',$p);
		$p=preg_replace("'onerror'si",'oner<z>ror',$p);
		$p=preg_replace("'onunload'si",'onun<z>load',$p);
		$p=preg_replace("'onchange'si",'onch<z>ange',$p);
		$p=preg_replace("'onsubmit'si",'onsu<z>bmit',$p);
		$p=preg_replace("'onreset'si",'onr<z>eset',$p);
		$p=preg_replace("'onselect'si",'ons<z>elect',$p);
		$p=preg_replace("'onblur'si",'onb<z>lur',$p);
		$p=preg_replace("'onfocus'si",'onfo<z>cus',$p);
		$p=preg_replace("'onclick'si",'oncl<z>ick',$p);
		$p=preg_replace("'ondblclick'si",'ondbl<z>click',$p);
		$p=preg_replace("'onmousedown'si",'onm<z>ousedown',$p);
		$p=preg_replace("'onmousemove'si",'onmou<z>semove',$p);
		$p=preg_replace("'onmouseout'si",'onmou<z>seout',$p);
		$p=preg_replace("'onmouseover'si",'onmo<z>useover',$p);
		$p=preg_replace("'onmouseup'si",'onmou<z>seup',$p);
		
		if ($temp != $p) {
			nuke_js($temp, $p);
		}
	}

	//$p=preg_replace("'<object(.*?)</object>'si","",$p);
	$p=preg_replace("'autoplay'si",'',$p); // kills autoplay, need to think of a solution for embeds.
	$p=preg_replace("'filter:alpha'si",'falpha',$p);
	$p=preg_replace("'filter:'si",'x:',$p);
	if (!$_GET['nofilter']) $p=preg_replace("'opacity'si",'opac&#105;ty',$p);
	//$p=preg_replace("':awesome:'","<small>[unfunny]</small>", $p);
	$p=preg_replace("'falpha'si",'filter:alpha',$p);
	$p=preg_replace("':facepalm:'si",'<img src=images/facepalm.jpg>',$p);
	$p=preg_replace("':epicburn:'si",'<img src=images/epicburn.png>',$p);
	$p=preg_replace("':umad:'si",'<img src=images/umad.jpg>',$p);
	$p=preg_replace("':gamepro5:'si",'<img src=http://xkeeper.net/img/gamepro5.gif title="FIVE EXPLODING HEADS OUT OF FIVE">',$p);
	$p=preg_replace("':headdesk:'si",'<img src=http://xkeeper.net/img/headdesk.jpg title="Steven Colbert to the rescue">',$p);
	$p=preg_replace("':rereggie:'si",'<img src=images/rereggie.png>',$p);
	$p=preg_replace("':tmyk:'si",'<img src=http://xkeeper.net/img/themoreyouknow.jpg title="do doo do doooooo~">',$p);
	$p=preg_replace("':jmsu:'si",'<img src=images/jmsu.png>',$p);
	$p=preg_replace("':apathy:'si",'<img src=http://xkeeper.net/img/stickfigure-notext.png title="who cares">',$p);
	$p=preg_replace("':spinnaz:'si", '<img src="images/smilies/spinnaz.gif">', $p);
	$p=preg_replace("':trolldra:'si", '<img src="/images/trolldra.png">', $p);
	$p=preg_replace("'drama'si", 'batter blaster', $p);
//	$p=preg_replace("'TheKinoko'si", 'MY NAME MEANS MUSHROOM... IN <i>JAPANESE!</i> HOLY SHIT GUYS THIS IS <i>INCREDIBLE</i>!!!!!!!!!', $p);
	$p=preg_replace("':facepalm2:'si",'<img src=images/facepalm2.jpg>',$p);
	$p=preg_replace("':reggie:'si",'<img src=http://xkeeper.net/img/reggieshrug.jpg title="REGGIE!">',$p);
	$p=preg_replace("'crashdance'si",'CrashDunce',$p);
	$p=preg_replace("'get blue spheres'si",'HI EVERYBODY I\'M A RETARD PLEASE BAN ME',$p);
	// $p=preg_replace("'hopy'si",'I am a dumb',$p);
	$p=preg_replace("'zeon'si",'shit',$p);
	$p=preg_replace("'faith in humanity'si",'IQ',$p);
#	$p=preg_replace("'nintendo'si",'grandma',$p);
	$p=str_replace("ftp://teconmoon.no-ip.org", 'about:blank', $p);
	if ($hacks['comments']) {
		$p=str_replace("<!--", '<font color=#80ff80>&lt;!--', $p);
		$p=str_replace("-->", '--&gt;</font>', $p);
	}
	$p=str_replace("http://insectduel.proboards82.com","http://jul.rustedlogic.net/idiotredir.php?",$p);
	$p=str_replace("http://imageshack.us", "imageshit", $p);
	$p=str_replace('<link href="http://pieguy1372.freeweb7.com/misc/piehills.css" rel="stylesheet">',"",$p);
	$p=str_replace("tabindex=\"0\" ","title=\"the owner of this button is a fucking dumbass\" ",$p);
	$p=str_replace("%WIKISTATSFRAME%","<div id=\"widgetIframe\"><iframe width=\"600\" height=\"260\" src=\"http://stats.rustedlogic.net/index.php?module=Widgetize&action=iframe&moduleToWidgetize=VisitsSummary&actionToWidgetize=getSparklines&idSite=2&period=day&date=today&disableLink=1\" scrolling=\"no\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe></div>",$p);
    $p=str_replace("%WIKISTATSFRAME2%", '<div id="widgetIframe"><iframe width="100%" height="600" src="http://stats.rustedlogic.net/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Referers&actionToWidgetize=getWebsites&idSite=2&period=day&date=2010-10-12&disableLink=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></div>', $p);
//  $p=str_replace("http://xkeeper.shacknet.nu:5/", 'http://xchan.shacknet.nu:5/', $p);
//  $p=preg_replace("'<style'si",'&lt;style',$p);



	$p=preg_replace("'%BZZZ%'si",'onclick="bzzz(',$p);

	$p=preg_replace("'document.cookie'si",'document.co<z>okie',$p);
	$p=preg_replace("'eval'si",'eva<z>l',$p);
	//  $p=preg_replace("'document.'si",'docufail.',$p);
	$p=preg_replace("'<script'si",'<<z>script',$p);
	$p=preg_replace("'javascript:'si",'javasc<z>ript:',$p);
	$p=preg_replace("'<iframe'si",'<<z>iframe',$p);
	$p=preg_replace("'<meta'si",'<<z>meta',$p);

	return $p;
}


require 'lib/threadpost.php';
// require 'lib/replytoolbar.php';

function replytoolbar() { return; }

function addslashes_array($data) {
	if (is_array($data)){
		foreach ($data as $key => $value){
			$data[$key] = addslashes_array($value);
		}
		return $data;
	} else {
		return addslashes($data);
	}
}


	function xk_ircout($type, $user, $in) {

		// gone
		// return;
		# and back

		$dest	= min(1, max(0, $in['pow']));
		if ($in['fid'] == 99) {
			$dest	= 6;
		} elseif ($in['fid'] == 98) {
			$dest	= 7;
		}

		global $x_hacks;
		if ($x_hacks['host']) return;

		if ($type == "user") {
			if ($in['pmatch']) {
				$color	= array(8, 7);
				if		($in['pmatch'] >= 3) $color	= array(7, 4);
				elseif	($in['pmatch'] >= 5) $color	= array(4, 5);
				$extra	= " (". xk($color[1]) ."Password matches: ". xk($color[0]) . $in['pmatch'] . xk() .")";
			}

			$out	= "1|New user: #". xk(12) . $in['id'] . xk(11) ." $user ". xk() ."(IP: ". xk(12) . $in['ip'] . xk() .")$extra: http://jul.rustedlogic.net/?u=". $in['id'];

		} else {
//			global $sql;
//			$res	= $sql -> resultq("SELECT COUNT(`id`) FROM `posts`");
			$out	= "$dest|New $type by ". xk(11) . $user . xk() ." (". xk(12) . $in['forum'] .": ". xk(11) . $in['thread'] . xk() ."): http://jul.rustedlogic.net/?p=". $in['pid'];
			
		}

		xk_ircsend($out);
	}

	function xk_ircsend($str) {
		$str	= str_replace(array("%10", "%13"), array("", ""), rawurlencode($str));

		$ch = curl_init();
		
		
		// Note to potential dumbasses: this does check the incoming IP so don't bother.
		curl_setopt ($ch,CURLOPT_URL, "http://treeki.shacknet.nu:5000/reporting.php?t=$str");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
		$file_contents = curl_exec($ch);
		curl_close($ch);

		return true;
		
	}
	
	function xk($n = -1) {
		if ($n == -1) $k = "";
			else $k = str_pad($n, 2, 0, STR_PAD_LEFT);
		return "\x03". $k;
//		return;
	}

	function formatting_trope($input) {

		$in		= "/[A-Z][^A-Z]/";
		$out	= " \\0";
		$output	= preg_replace($in, $out, $input);
		
		return trim($output);

	}

	/* extra fun functions! */
	function pick_any($array) {
		if (is_array($array)) {
			return $array[array_rand($array)];
		} elseif (is_string($array)) {
			return $array;
		}
	}

	function marqueeshit($str) {
		return "<marquee scrollamount='". mt_rand(1, 50) ."' scrolldelay='". mt_rand(1, 50) ."' direction='". pick_any(array("left", "right")) ."'>$str</marquee>";
	}
	// additional includes`
	require_once "lib/datetime.php";


	function unescape($in) {

		$out	= urldecode($in);
		while ($out != $in) {
			$in		= $out;
			$out	= urldecode($in);
		}
		return $out;

	}


	function adbox() {

		global $loguser, $bgcolor, $linkcolor;

		$tagline	= array();
		$tagline[]	= "Viewing this ad requires<br>ZSNES 1.42 or older!";
		$tagline[]	= "Celebrating 5 years of<br>ripping off SMAS!";
		$tagline[]	= "Now with 100% more<br>buggy custom sprites!";
		$tagline[]	= "Try using AddMusic to give your hack<br>that 1999 homepage feel!";
		$tagline[]	= "Pipe cutoff? In my SMW hack?<br>It's more likely than you think!";
		$tagline[]	= "Just keep giving us your money!";
		$tagline[]	= "Now with 97% more floating munchers!";
		$tagline[]	= "Tip: If you can beat your level without<br>savestates, it's too easy!";
		$tagline[]	= "Tip: Leave exits to level 0 for<br>easy access to that fun bonus game!";
		$tagline[]	= "Now with 100% more Touhou fads!<br>It's like Jul, but three years behind!";
		$tagline[]	= "Isn't as cool as this<br>witty subtitle!";
		$tagline[]	= "Finally beta!";
		$tagline[]	= "If this is blocking other text<br>try disabling AdBlock next time!";
		$tagline[]	= "bsnes sucks!";
		$tagline[]	= "Now in raspberry, papaya,<br>and roast beef flavors!";
		$tagline[]	= "We &lt;3 terrible Japanese hacks!";
		$tagline[]	= "573 crappy joke hacks and counting!";
		$tagline[]	= "Don't forget your RATS tag!";
		$tagline[]	= "Now with exclusive support for<br>127&frac12;Mbit SuperUltraFastHiDereROM!";
		$tagline[]	= "More SMW sequels than you can<br>shake a dead horse at!";
		$tagline[]	= "xkas v0.06 or bust!";
		$tagline[]	= "SMWC is calling for your blood!";
		$tagline[]	= "You can run,<br>but you can't hide!";
		$tagline[]	= "Now with 157% more CSS3!";
		$tagline[]	= "Stickers and cake don't mix!";
		$tagline[]	= "Better than a 4-star crap cake<br>with garlic topping!";
		$tagline[]	= "We need some IRC COPS!";


		$s	= (isset($_GET['lolol']) ? $_GET['lolol'] + 1 : 0) % count($tagline);

		if (isset($_GET['lolol'])) {
			$taglinec	= $_GET['lolol'] % count($tagline);
			$taglinec	= $tagline[$taglinec];
		} else {
			$taglinec	= pick_any($tagline);
		}


		return "<center>
<!-- Beginning of Project Wonderful ad code: -->
<!-- Ad box ID: 48901 -->
<script type=\"text/javascript\">
<!--
var pw_d=document;
pw_d.projectwonderful_adbox_id = \"48901\";
pw_d.projectwonderful_adbox_type = \"5\";
pw_d.projectwonderful_foreground_color = \"#$linkcolor\";
pw_d.projectwonderful_background_color = \"#$bgcolor\";
//-->
</script>
<script type=\"text/javascript\" src=\"http://www.projectwonderful.com/ad_display.js\"></script>
<noscript><map name=\"admap48901\" id=\"admap48901\"><area href=\"http://www.projectwonderful.com/out_nojs.php?r=0&amp;c=0&amp;id=48901&amp;type=5\" shape=\"rect\" coords=\"0,0,728,90\" title=\"\" alt=\"\" target=\"_blank\" /></map>
<table cellpadding=\"0\" border=\"0\" cellspacing=\"0\" width=\"728\" bgcolor=\"#$bgcolor\"><tr><td><img src=\"http://www.projectwonderful.com/nojs.php?id=48901&amp;type=5\" width=\"728\" height=\"90\" usemap=\"#admap48901\" border=\"0\" alt=\"\" /></td></tr><tr><td bgcolor=\"\" colspan=\"1\"><center><a style=\"font-size:10px;color:#$linkcolor;text-decoration:none;line-height:1.2;font-weight:bold;font-family:Tahoma, verdana,arial,helvetica,sans-serif;text-transform: none;letter-spacing:normal;text-shadow:none;white-space:normal;word-spacing:normal;\" href=\"http://www.projectwonderful.com/advertisehere.php?id=48901&amp;type=5\" target=\"_blank\">Ads by Project Wonderful! Your ad could be right here, right now.</a></center></td></tr></table>
</noscript>
</center>
<!-- End of Project Wonderful ad code. -->";
	}
