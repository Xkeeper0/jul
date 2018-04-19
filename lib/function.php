<?php

	// Set this right away to hopefully prevent fuckups
	ini_set("default_charset", "UTF-8");

	// The base path we're currently in. If the forum is located at http://example.com/jul/, this will be '/jul'.
	// Take the current directory and pop off the last two items to get the containing dir.
	// This removes /lib and /jul (or whatever the containing directory is named, e.g. 'www').
	$containing_dir = implode('/', array_splice(explode('/', __DIR__), 0, -2));
	// The base directory contains the project root.
	$base_dir = implode('/', array_splice(explode('/', __DIR__), 0, -1));

	$GLOBALS['jul_base_path'] = $base_dir;
	$GLOBALS['jul_base_dir'] = str_replace($containing_dir, '', $base_dir);
	// Path where we can find the views, e.g. thread.php, forum.php.
	$GLOBALS['jul_views_path'] = "{$GLOBALS['jul_base_dir']}/views";

	$startingtime = microtime(true);

	// Allow us to include from lib/ wherever we are.
	set_include_path($GLOBALS['jul_base_path']);

	// Awful old legacy thing. Too much code relies on register globals,
	// and doesn't distinguish between _GET and _POST, so we have to do it here. fun
	$id = filter_int($_POST['id']);
	if ($id === null)
		$id = filter_int($_GET['id']);
	if ($id === null)
		$id = 0;

	require_once 'lib/error.php';

	if (!is_file("{$GLOBALS['jul_base_path']}/lib/config.php")) {
		early_html_die('Could not find a configuration file. Make sure you have a <tt>lib/config.php</tt> file and it contains <tt>$sql_settings</tt> and <tt>$forum_settings</tt>. See <tt>lib/config.example.php</tt>.');
	}
	require_once 'lib/defaults.php';
	require_once 'lib/config.php';

	// Merge defaults and user supplied config.
	if (!isset($sql_settings) || !isset($forum_settings)) {
		early_html_die('Your forum settings are not correct. Make sure you have a <tt>lib/config.php</tt> file and it contains <tt>$sql_settings</tt> and <tt>$forum_settings</tt>. See <tt>lib/config.example.php</tt>.');
	}
	$GLOBALS['jul_sql_settings'] = array_merge($default_sql_settings, $sql_settings);
	$GLOBALS['jul_settings'] = array_merge($default_forum_settings, $forum_settings);

	require_once 'lib/routing.php';
	require_once 'lib/helpers.php';
	require_once 'lib/mysql.php';
	require_once 'lib/xss.php';

	$sql	= new mysql;

	$sql->connect($GLOBALS['jul_sql_settings']['host'], $GLOBALS['jul_sql_settings']['user'], $GLOBALS['jul_sql_settings']['pass']) or
		early_html_die('The MySQL server has exploded.', true);
	$sql->selectdb($sql_settings['name']) or early_html_die('Another stupid MySQL error happened, panic', true);


	if (file_exists("lib/firewall.php") && !filter_bool($disable_firewall)) {
		require_once 'lib/firewall.php';

	} else {

		// Bad Design Decisions 2001.
		// :(
		if (!get_magic_quotes_gpc()) {
			$_GET = addslashes_array($_GET);
			$_POST = addslashes_array($_POST);
			$_COOKIE = addslashes_array($_COOKIE);
		}
		if(!ini_get('register_globals')){
			$supers=array('_ENV', '_SERVER', '_GET', '_POST', '_COOKIE',);
			foreach($supers as $__s) if (is_array($$__s)) extract($$__s, EXTR_SKIP);
			unset($supers);
		}
	}

	if (filter_int($die) || filter_int($_GET['sec'])) {
		if ($die) {
			$sql -> query("INSERT INTO `minilog` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `time` = '". ctime() ."', `banflags` = '$banflags'");

			if ($_COOKIE['loguserid'] > 0) {
				$newid	= 0;
			} elseif (!$_COOKIE['loguserid'])
				$newid	= 0 - ctime();

			if ($newid) setcookie('loguserid',$newid,2147483647);

		}

		suspicious_die();
	}

	if ($sql -> resultq("SELECT `disable` FROM `misc` WHERE 1")) {
		unexpected_downtime_die();
	}

	$dateformat = $GLOBALS['jul_settings']['date_format_long'];
	$dateshort  = $GLOBALS['jul_settings']['date_format_short'];

	$loguser = array();

	// This function was not included in the original code.
	// I have no idea what the idea behind it was so I'll just use this for now.
	function getpwhash($name, $id) {
		return password_hash("{$name}{$id}", PASSWORD_DEFAULT);
	}

	// Just making sure.  Don't use this anymore.
	// (This is backup code to auto update passwords from cookies.)
	if (filter_int($_COOKIE['loguserid']) && filter_string($_COOKIE['logpassword'])) {
		$loguserid = intval($_COOKIE['loguserid']);

		$passinfo = $sql->fetchq("SELECT name,password FROM `users` WHERE `id`='$loguserid'");
		$logpassword = shdec($_COOKIE['logpassword']);

		// Passwords match
		if ($passinfo['password'] === md5($logpassword)) {
			$logpwenc = getpwhash($logpassword, $loguserid);
			$sql->query("UPDATE users SET `password` = '{$logpwenc}' WHERE `id` = '{$loguserid}'");
			xk_ircsend("102|".xk(3)."Password hash for ".xk(9).$passinfo['name'].xk(3)." (uid ".xk(9).$loguserid.xk(3).") has been automatically updated (from cookie).");

			$verify = create_verification_hash(0, $logpwenc);
			setcookie('logverify',$verify,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);
			$_COOKIE['logverify'] = $verify; // above only takes effect after next page load

			unset($verify);
		}
		setcookie('logpassword','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);
		unset($passinfo);
	}
	$logpassword = null;
	$logpwenc = null;

	if(filter_int($_COOKIE['loguserid']) && filter_string($_COOKIE['logverify'])) {
		$loguserid = intval($_COOKIE['loguserid']);
		$loguser = $sql->fetchq("SELECT * FROM `users` WHERE `id`='$loguserid'");

		$logverify = $_COOKIE['logverify'];
		$verifyid = intval(substr($logverify, 0, 1));

		$verifyhash = create_verification_hash($verifyid, $loguser['password']);

		// Compare what we just created with what the cookie says, assume something is wrong if it doesn't match
		if ($verifyhash !== $logverify)
			$loguser = NULL;

	}

	$tzoff		= 0;

	if ($loguser) {
		$loguserid = $loguser['id'];
		$tzoff = $loguser['timezone']*3600;
		$scheme = $loguser['scheme'];
		if ($loguser['dateformat'])
			$dateformat	= $loguser['dateformat'];
		if ($loguser['dateshort'])
			$dateshort	= $loguser['dateshort'];

		$log = 1;

		if ($loguser['id'] == 1)
			$hacks['comments'] = true;
		else
			$hacks['comments'] = $sql->resultq("SELECT COUNT(*) FROM `users_rpg` WHERE `uid` = '$loguserid' AND `eq6` IN ('43', '71', '238')");

		if ($loguser['viewsig'] >= 3)
			return header("Location: /?sec=1");
		if ($loguser['powerlevel'] >= 1)
			$GLOBALS['jul_settings']['board_title'] .= $submessage;

		if ($loguser['id'] == 175 && !$x_hacks['host'])
			$loguser['powerlevel'] = max($loguser['powerlevel'], 3);
	}
	else {
		$loguserid				= null;
		$loguser				= array();
		$loguser['viewsig']		= 0;
		$loguser['powerlevel']	= 0;
		$loguser['signsep']		= 0;
		$loguser['id']			= null;
		$log					= 0;
	}

	if ($x_hacks['superadmin']) $loguser['powerlevel'] = 4;

	$power     = $loguser['powerlevel'];
	$banned    = ($power<0);
	$ismod     = ($power>=2);
	$isadmin   = ($power>=3);
	if($banned) $power=0;

	$specialscheme = "";
	$smallbrowsers	= array("Nintendo DS", "Android", "PSP", "Windows CE");
	if ( (str_replace($smallbrowsers, "", $_SERVER['HTTP_USER_AGENT']) != $_SERVER['HTTP_USER_AGENT']) || filter_int($_GET['mobile']) == 1) {
		$loguser['layout']		= 2;
		$loguser['viewsig']		= 0;
		$GLOBALS['jul_settings']['board_title'] = "<span style=\"font-size: 2em;\">{$GLOBALS['jul_settings']['board_name']}</span>";
		$x_hacks['smallbrowse']	= true;
	}

//	$atempval	= $sql -> resultq("SELECT MAX(`id`) FROM `posts`");
//	if ($atempval == 199999 && $_SERVER['REMOTE_ADDR'] != "172.130.244.60") {
//		//print "DBG ". strrev($atempval);
//		require_once "dead.php";
//		die();
//	}

//  $hacks['noposts'] = true;

	$getdoom	= true;
	require_once "ext/mmdoom.php";

	//$x_hacks['rainbownames'] = ($sql->resultq("SELECT MAX(`id`) % 100000 FROM `posts`")) <= 100;
	$x_hacks['rainbownames'] = ($sql->resultq("SELECT `date` FROM `posts` WHERE (`id` % 100000) = 0 ORDER BY `id` DESC LIMIT 1") > ctime()-86400);

	if (!$x_hacks['host'] && filter_int($_GET['namecolors'])) {
		//$sql->query("UPDATE `users` SET `sex` = '255' WHERE `id` = 1");
		//$sql->query("UPDATE `users` SET `name` = 'Ninetales', `powerlevel` = '3' WHERE `id` = 24 and `powerlevel` < 3");
		//$sql->query("UPDATE `users` SET `sex` = '9' WHERE `id` = 1");
		//$sql->query("UPDATE `users` SET `sex` = '10' WHERE `id` = 855");
		//$sql->query("UPDATE `users` SET `sex` = '7' WHERE `id` = 18");	# 7
		//$sql->query("UPDATE `users` SET `sex` = '99' WHERE `id` = 21"); #Tyty (well, not anymore)
		//$sql->query("UPDATE `users` SET `sex` = '9' WHERE `id` = 275");

		$sql->query("UPDATE `users` SET `sex` = '4' WHERE `id` = 41");
		$sql->query("UPDATE `users` SET `sex` = '6' WHERE `id` = 4");
		$sql->query("UPDATE `users` SET `sex` = '11' WHERE `id` = 92");
		$sql->query("UPDATE `users` SET `sex` = '97' WHERE `id` = 24");
		$sql->query("UPDATE `users` SET `sex` = '42' WHERE `id` = 45");	# 7
		$sql->query("UPDATE `users` SET `sex` = '8' WHERE `id` = 19");
		$sql->query("UPDATE `users` SET `sex` = '98' WHERE `id` = 1343"); #MilesH
		$sql->query("UPDATE `users` SET `sex` = '12' WHERE `id` = 1296");
		$sql->query("UPDATE `users` SET `sex` = '13' WHERE `id` = 1090");
		$sql->query("UPDATE `users` SET `sex` = '14' WHERE `id` = 6"); #mm88
		$sql->query("UPDATE `users` SET `sex` = '21' WHERE `id` = 1840"); #Sofi
		$sql->query("UPDATE `users` SET `sex` = '22' WHERE `id` = 20"); #nicole
		$sql->query("UPDATE `users` SET `sex` = '23' WHERE `id` = 50"); #Rena
		$sql->query("UPDATE `users` SET `sex` = '24' WHERE `id` = 2069"); #Adelheid/Stark/etc.

		$sql->query("UPDATE `users` SET `name` = 'Xkeeper' WHERE `id` = 1"); #Xkeeper. (Change this and I WILL Z-Line you from Badnik for a week.)

	}

// New birthday shit
/*
	$today = date('m-d',ctime() - (60 * 60 * 3));
	@$sql->query("UPDATE `users` SET `sex` = `oldsex` WHERE `sex` = 255 AND FROM_UNIXTIME(birthday,'%m-%d')!='$today'");
	@$sql->query("UPDATE `users` SET `oldsex` = `sex`, `sex` = '255' WHERE sex != 255 AND birthday AND FROM_UNIXTIME(birthday,'%m-%d')='$today'");
*/

// Old birthday shit
/*
	mysql_query("UPDATE `users` SET `sex` = '2' WHERE `sex` = 255");
	$busers = @mysql_query("SELECT id, name FROM users WHERE FROM_UNIXTIME(birthday,'%m-%d')='".date('m-d',ctime() - (60 * 60 * 3))."' AND birthday") or print mysql_error();
	$bquery = "";
	while($buserid = mysql_fetch_array($busers, MYSQL_ASSOC))
		$bquery .= ($bquery ? " OR " : "") ."`id` = '". $buserid['id'] ."'";
	if ($bquery)
		mysql_query("UPDATE `users` SET `sex` = '255' WHERE $bquery");
*/


function filter_int(&$v) {
	if (!isset($v)) {
		return null;
	} else {
		$v	= intval($v);
		return $v;
	}
}

function filter_bool(&$v) {
	if (!isset($v)) {
		return null;
	} else {
		$v	= (bool)$v;
		return $v;
	}
}


function filter_string(&$v) {
	if (!isset($v)) {
		return null;
	} else {
		$v	= (string)$v;
		return $v;
	}
}


function readsmilies(){
	global $x_hacks;
	$smile = base_path().'/resources/smilies.dat';
	$fpnt=fopen($smile,'r');
	for ($i=0;$smil[$i]=fgetcsv($fpnt,300,',');$i++);
	$r=fclose($fpnt);
	return $smil;
}

function numsmilies(){
	$smile = base_path().'/resources/smilies.dat';
	$fpnt=fopen($smile,'r');
	for($i=0;fgetcsv($fpnt,300,'');$i++);
	$r=fclose($fpnt);
	return $i;
}

function readpostread($userid){
	global $sql;
	if (!$userid) return array();
	return $sql->getresultsbykey("SELECT forum,readdate FROM forumread WHERE user=$userid", 'forum', 'readdate');
}

function timeunits($sec){
	if($sec<60)			return "$sec sec.";
	if($sec<3600)		return floor($sec/60).' min.';
	if($sec<7200)		return '1 hour';
	if($sec<86400)		return floor($sec/3600).' hours';
	if($sec<172800)		return '1 day';
	if($sec<31556926)	return floor($sec/86400).' days';
	return sprintf("%.1f years", floor($sec/31556926));
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

function generatenumbergfx($num,$minlen=0,$double=false){
	global $numdir;

	$nw			= 8 * ($double ? 2 : 1);
	$num		= strval($num);
	$gfxcode	= "";
	$img_base = base_dir().'/';

	if($minlen>1 && strlen($num) < $minlen) {
		$gfxcode = "<img src=\"{$img_base}images/_.gif\" width=". ($nw * ($minlen - strlen($num))) ." height=". $nw .">";
	}

	for($i=0;$i<strlen($num);$i++) {
		$code	= $num{$i};
		switch ($code) {
			case "/":
				$code	= "slash";
				break;
		}
		if ($code == " ") {
			$gfxcode.="<img src={$img_base}images/_.gif width=$nw height=$nw>";

		} else {
			$gfxcode.="<img src={$img_base}numgfx/$numdir$code.png width=$nw height=$nw>";

		}
	}
	return $gfxcode;
}



function dotags($msg, $user, &$tags = array()) {
	global $sql, $dateformat, $tzoff;
	if (is_string($tags)) {
		$tags	= json_decode($tags, true);
	}

	if (empty($tags) && empty($user)) {
		// settags sent us here and we have nothing to go off of.
		// Shrug our shoulders, and move on.
		return $msg;
	}

	if (empty($tags)) {
		$tags	= array(
			'/me '			=> "*<b>". $user['username'] ."</b> ",
			'&date&'		=> date($dateformat, ctime() + $tzoff),
			'&numdays&'		=> floor($user['days']),

			'&numposts&'	=> $user['posts'],
			'&rank&'		=> getrank($user['useranks'], '', $user['posts'], 0),
			'&postrank&'	=> $sql->resultq("SELECT count(*) FROM `users` WHERE posts>$user[posts]")+1,
			'&5000&'		=>  5000 - $user['posts'],
			'&10000&'		=> 10000 - $user['posts'],
			'&20000&'		=> 20000 - $user['posts'],
			'&30000&'		=> 30000 - $user['posts'],

			'&exp&'			=> $user['exp'],
			'&expgain&'		=> calcexpgainpost($user['posts'], $user['days']),
			'&expgaintime&'	=> calcexpgaintime($user['posts'], $user['days']),

			'&expdone&'		=> $user['expdone'],
			'&expdone1k&'	=> floor($user['expdone'] /  1000),
			'&expdone10k&'	=> floor($user['expdone'] / 10000),

			'&expnext&'		=> $user['expnext'],
			'&expnext1k&'	=> floor($user['expnext'] /  1000),
			'&expnext10k&'	=> floor($user['expnext'] / 10000),

			'&exppct&'		=> sprintf('%01.1f', ($user['lvllen'] ? (1 - $user['expnext'] / $user['lvllen']) : 0) * 100),
			'&exppct2&'		=> sprintf('%01.1f', ($user['lvllen'] ? (    $user['expnext'] / $user['lvllen']) : 0) * 100),

			'&level&'		=> $user['level'],
			'&lvlexp&'		=> calclvlexp($user['level'] + 1),
			'&lvllen&'		=> $user['lvllen'],
			);
	}

	$msg	= strtr($msg, $tags);
	return $msg;
}


function doreplace($msg, $posts, $days, $username, &$tags = null) {
	global $tagval, $sql;

	// This should probably go off of user ID but welp
	$user			= $sql->fetchq("SELECT * FROM `users` WHERE `name` = '".addslashes($username)."'", MYSQL_BOTH, true);

	$userdata		= array(
		'id'		=> $user['id'],
		'username'	=> $username,
		'posts'		=> $posts,
		'days'		=> $days,
		'useranks'	=> $user['useranks'],
		'exp'		=> calcexp($posts,$days)
		);

	$userdata['level']		= calclvl($userdata['exp']);
	$userdata['expdone']	= $userdata['exp'] - calclvlexp($userdata['level']);
	$userdata['expnext']	= calcexpleft($userdata['exp']);
	$userdata['lvllen']		= totallvlexp($userdata['level']);


	if (!$tags) {
		$tags	= array();
	}
	$msg	= dotags($msg, $userdata, $tags);

	return $msg;
}

function escape_codeblock($text) {
	$list = array("[code]", "[/code]", "<", "\\\"" , "\\\\" , "\\'", "[", ":", ")", "_");
	$list2 = array("", "", "&lt;", "\"", "\\", "\'", "&#91;", "&#58;", "&#41;", "&#95;");

	// @TODO why not just use htmlspecialchars() or htmlentities()
	return "[quote]<code>". str_replace($list, $list2, $text[0]) ."</code>[/quote]";
}

function doreplace2($msg, $options='0|0'){
	// options will contain smiliesoff|htmloff
	$options = explode("|", $options);
	$smiliesoff = $options[0];
	$htmloff = $options[1];


	$list = array("<", "\\\"" , "\\\\" , "\\'", "[", ":", ")", "_");
	$list2 = array("&lt;", "\"", "\\", "\'", "&#91;", "&#58;", "&#41;", "&#95;");
	$msg=preg_replace_callback("'\[code\](.*?)\[/code\]'si", 'escape_codeblock',$msg);


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
	$msg=str_replace('[quote]','<blockquote><hr>',$msg);
	$msg=str_replace('[/quote]','<hr></blockquote>',$msg);
	$msg=preg_replace("'\[sp=(.*?)\](.*?)\[/sp\]'si", '<span style="border-bottom: 1px dotted #f00;" title="did you mean: \\1">\\2</span>', $msg);
	$msg=preg_replace("'\[abbr=(.*?)\](.*?)\[/abbr\]'si", '<span style="border-bottom: 1px dotted;" title="\\1">\\2</span>', $msg);
	$msg=str_replace('[spoiler]','<div class="fonts pstspl2"><b>Spoiler:</b><div class="pstspl1">',$msg);
	$msg=str_replace('[/spoiler]','</div></div>',$msg);
	$msg=preg_replace("'\[(b|i|u|s)\]'si",'<\\1>',$msg);
	$msg=preg_replace("'\[/(b|i|u|s)\]'si",'</\\1>',$msg);
	$msg=preg_replace("'\[img\](.*?)\[/img\]'si", '<img src=\\1>', $msg);
	$msg=preg_replace("'\[url\](.*?)\[/url\]'si", '<a href=\\1>\\1</a>', $msg);
	$msg=preg_replace("'\[url=(.*?)\](.*?)\[/url\]'si", '<a href=\\1>\\2</a>', $msg);
	$msg=str_replace('http://nightkev.110mb.com/justus_layout.css','about:blank',$msg);


	do {
		$msg	= preg_replace("/<(\/?)t(able|h|r|d)(.*?)>(\s+?)<(\/?)t(able|h|r|d)(.*?)>/si",
				"<\\1t\\2\\3><\\5t\\6\\7>", $msg, -1, $replaced);
	} while ($replaced >= 1);


	sbr(0,$msg);

	return $msg;
}


function settags($text, $tags) {

	if (!$tags) {
		return $text;
	} else {
		$text	= dotags($text, array(), $tags);
	}

	return $text;
}


function doforumlist($id){
	global $fonttag,$loguser,$power,$sql;
	$forumlinks="
	<table><td>$fonttag Forum jump: </td>
	<td><form><select onChange=parent.location=this.options[this.selectedIndex].value style=\"position:relative;top:8px;\">
	";

	$cats	= $sql->query("SELECT id,name,minpower FROM categories WHERE (minpower<=$power OR minpower<=0) ORDER BY id ASC");
	while ($cat = $sql->fetch($cats)) {
		$fjump[$cat['id']]	= "<optgroup label=\"". $cat['name'] ."\">";
	}

	$forum1= $sql->query("SELECT id,title,catid FROM forums WHERE (minpower<=$power OR minpower<=0) AND `hidden` = '0' AND `id` != '0' OR `id` = '$id' ORDER BY forder") or print mysql_error();
	while($forum=$sql->fetch($forum1)) {
		$fjump[$forum['catid']]	.="<option value='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]".($forum['id']==$id?' selected':'')."'>$forum[title]</option>";
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
	global $hacks, $sql;
	$rank	= "";
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
					$rank .= "<img src={$img_base}images/dot". $dot .".gif align=\"absmiddle\">";
				}
			}
			if ($posts >= 10) $rank = floor($posts / 10) * 10 ." ". $rank;
		}
	}
	else if ($rankset) {
		$posts%=10000;
		$rank = @$sql->resultq("SELECT text FROM ranks WHERE num<=$posts AND rset=$rankset ORDER BY num DESC LIMIT 1", 0, 0, true);
	}

	$powerranks = array(
		-1 => 'Banned',
		//1  => '<b>Staff</b>',
		2  => '<b>Moderator</b>',
		3  => '<b>Administrator</b>'
	);

	if($rank && (in_array($powl, $powerranks) || $title)) $rank.='<br>';

	if($title)
		$rank .= $title;
	elseif (in_array($powl, $powerranks))
		$rank .= filter_string($powerranks[$powl]);

	return $rank;
}

function updategb() {
	global $sql;
	$hranks = $sql->query("SELECT posts FROM users WHERE posts>=1000 ORDER BY posts DESC");
	$c      = mysql_num_rows($hranks);

	for($i=1;($hrank=$sql->fetch($hranks)) && $i<=$c*0.7;$i++){
		$n=$hrank[posts];
		if($i==floor($c*0.001))    $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=3%'");
		elseif($i==floor($c*0.01)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=4%'");
		elseif($i==floor($c*0.03)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=5%'");
		elseif($i==floor($c*0.06)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=6%'");
		elseif($i==floor($c*0.10)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=7%'");
		elseif($i==floor($c*0.20)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=8%'");
		elseif($i==floor($c*0.30)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=9%'");
		elseif($i==floor($c*0.50)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=10%'");
		elseif($i==floor($c*0.70)) $sql->query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=11%'");
	}
}

function checkusername($name){
	global $sql;
	$u = $sql->resultq("SELECT id FROM users WHERE name='".addslashes($name)."'");
	if($u<1) $u=-1;
	return $u;
}

function checkuser($name,$pass){
	global $hacks, $sql;

	$user = $sql->fetchq("SELECT id,password FROM users WHERE name='$name'");

	if (!$user) return -1;
	if (!password_verify("{$pass}{$user['id']}", $user['password'])) {
		// Also check for the old md5 hash, allow a login and update it if successful
		// This shouldn't impact security (in fact it should improve it)
		if (!$hacks['password_compatibility'])
			return -1;
		else {
			if ($user['password'] === md5($pass)) { // Uncomment the lines below to update password hashes
				$sql->query("UPDATE users SET `password` = '".getpwhash($pass, $user['id'])."' WHERE `id` = '$user[id]'");
				xk_ircsend("102|".xk(3)."Password hash for ".xk(9).$name.xk(3)." (uid ".xk(9).$user['id'].xk(3).") has been automatically updated.");
			}
			else return -1;
		}
	}

	return $user['id'];
}

function create_verification_hash($n,$pw) {
	$ipaddr = explode('.', $_SERVER['REMOTE_ADDR']);
	$vstring = 'verification IP: ';

	$tvid = $n;
	while ($tvid--)
		$vstring .= array_shift($ipaddr) . "|";

	// don't base64 encode like I do on my fork, waste of time (honestly)
	return $n . sha1($pw . $vstring, false);
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

function getuserlink(&$u, $substitutions = null, $urlclass = '') {
	if ($substitutions === true) {
		global $herpderpwelp;
		if (!$herpderpwelp)
			trigger_error('Deprecated: $substitutions passed true (old behavior)', E_USER_NOTICE);
		$herpderpwelp = true;
	}

	// dumb hack for $substitutions
	$fn = array(
		'aka'			=> 'aka',
		'id'			=> 'id',
		'name'			=> 'name',
		'sex'			=> 'sex',
		'powerlevel'	=> 'powerlevel',
		'birthday'		=> 'birthday'
	);
	if ($substitutions)
		$fn = array_merge($fn, $substitutions);

	$akafield = htmlspecialchars($u[$fn['aka']], ENT_QUOTES);
	$alsoKnownAs = (($u[$fn['aka']] && $u[$fn['aka']] != $u[$fn['name']])
		? " title='Also known as: {$akafield}'" : '');

	$u[$fn['name']] = htmlspecialchars($u[$fn['name']], ENT_QUOTES);

	global $tzoff;
	$birthday = (date('m-d', $u[$fn['birthday']]) == date('m-d',ctime() + $tzoff));
	$rsex = (($birthday) ? 255 : $u[$fn['sex']]);

	$namecolor = getnamecolor($rsex, $u[$fn['powerlevel']], false);

	if ($urlclass)
		$class = " class='{$urlclass}'";
	else $class = '';
	return "<a style='color:#{$namecolor};'{$class} href='{$GLOBALS['jul_views_path']}/profile.php?id="
		. $u[$fn['id']] ."'{$alsoKnownAs}>". $u[$fn['name']] ."</a>";
}

// eventually: change/remove prefix. ugh. it's there so nothing old breaks.
function getnamecolor($sex, $powl, $prefix = true){
	global $nmcol, $x_hacks;

	// don't let powerlevels above admin have a blank color
	$powl = min(3, $powl);

	$namecolor = (($prefix) ? 'color=' : '');

	if ($powl < 0) // always dull drab banned gray.
		$namecolor .= $nmcol[0][$powl];

	// RAINBOW MULTIPLIER
	elseif ($x_hacks['rainbownames'] || $sex == 255) {
		$stime=gettimeofday();
		// slowed down 5x
		$h = (($stime['usec']/25) % 600);
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
		$namecolor .= substr(dechex($r*65536+$g*256+$b),-6);
	}

	else switch ($sex) {
		case 3:
			//$stime=gettimeofday();
			//$rndcolor=substr(dechex(1677722+$stime[usec]*15),-6);
			//$namecolor .= $rndcolor;
			$nc = mt_rand(0,0xffffff);
			$namecolor .= str_pad(dechex($nc), 6, "0", STR_PAD_LEFT);
			break;
		case 4:
			$namecolor .= "ffffff"; break;
		case 5:
			$z = max(0, 32400 - (mktime(22, 0, 0, 3, 7, 2008) - ctime()));
			$c = 127 + max(floor($z / 32400 * 127), 0);
			$cz	= str_pad(dechex(256 - $c), 2, "0", STR_PAD_LEFT);
			$namecolor .= str_pad(dechex($c), 2, "0", STR_PAD_LEFT) . $cz . $cz;
			break;
		case 6:
			$namecolor .= "60c000"; break;
		case 7:
			$namecolor .= "ff3333"; break;
		case 8:
			$namecolor .= "6688aa"; break;
		case 9:
			$namecolor .= "cc99ff"; break;
		case 10:
			$namecolor .= "ff0000"; break;
		case 11:
			$namecolor .= "6ddde7"; break;
		case 12:
			$namecolor .= "e2d315"; break;
		case 13:
			$namecolor .= "94132e"; break;
		case 14:
			$namecolor .= "ffffff"; break;
		case 21: // Sofi
			$namecolor .= "DC143C"; break;
		case 22: // Nicole
			$namecolor .= "FFB3F3"; break;
		case 23: // Rena
			$namecolor .= "77ECFF"; break;
		case 24: // Adelheid
			$namecolor .= "D2A6E1"; break;
		case 41:
			$namecolor .= "8a5231"; break;
		case 42:
			$namecolor .= "20c020"; break;
		case 99:
			$namecolor .= "EBA029"; break;
		case 98:
			$namecolor .= $nmcol[0][3]; break;
		case 97:
			$namecolor .= "6600DD"; break;
		default:
			$namecolor .= $nmcol[$sex][$powl];
			break;
	}

	return $namecolor;
}

function fonlineusers($id){
	global $userip,$loguserid,$sql;

	if($loguserid)
		$sql->query("UPDATE users SET lastforum=$id WHERE id=$loguserid");
	else
		$sql->query("UPDATE guests SET lastforum=$id WHERE ip='$userip'");

	$forumname		=@$sql->resultq("SELECT title FROM forums WHERE id=$id",0,0);
	$onlinetime		=ctime()-300;
	$onusers		=$sql->query("SELECT id,name,lastactivity,minipic,lasturl,aka,sex,powerlevel,birthday FROM users WHERE lastactivity>$onlinetime AND lastforum=$id ORDER BY name");

	$onlineusers	= "";

	for($numon=0;$onuser=$sql->fetch($onusers);$numon++){
		if($numon) $onlineusers.=', ';

		/* if ((!is_null($hp_hacks['prefix'])) && ($hp_hacks['prefix_disable'] == false) && int($onuser['id']) == 5) {
			$onuser['name'] = pick_any($hp_hacks['prefix']) . " " . $onuser['name'];
		} */

		$namelink							= getuserlink($onuser);
		$onlineusers						.='<nobr>';
		$onuser['minipic']					=str_replace('>','&gt;',$onuser['minipic']);
		if($onuser['minipic']) $onlineusers	.="<img width=16 height=16 src=$onuser[minipic] align=top> ";
		if($onuser['lastactivity']			<=$onlinetime) $namelink="($namelink)";
		$onlineusers						.="$namelink</nobr>";
	}
	$p = ($numon ? ':' : '.');
	$s = ($numon != 1 ? 's' : '');
	$numguests = $sql->resultq("SELECT count(*) AS n FROM guests WHERE date>$onlinetime AND lastforum=$id",0,0);
	if($numguests) $guests="| $numguests guest".($numguests>1?'s':'');
	return "$numon user$s currently in $forumname$p $onlineusers $guests";
}

/* WIP
$jspcount = 0;
function jspageexpand($start, $end) {
	global $jspcount;

	if (!$jspcount) {
		echo '
			<script type="text/javascript">
				function pageexpand(uid,st,en)
				{
					var elem = document.getElementById(uid);
					var res = "";
				}
			</script>
		';
	}

	$entityid = "expand" . ++$jspcount;

	$js = "#todo";
	return $js;
}
*/

function redirect($url,$msg,$delay){
	if($delay<1) $delay=1;
	return "You will now be redirected to <a href=$url>$msg</a>...<META HTTP-EQUIV=REFRESH CONTENT=$delay;URL=$url>";
}

function postradar($userid){
	global $sql, $loguser, $loguserid;
	if (!$userid) return "";

	//$postradar = $sql->query("SELECT posts,id,name,aka,sex,powerlevel,birthday FROM users u RIGHT JOIN postradar p ON u.id=p.comp WHERE p.user={$userid} ORDER BY posts DESC", MYSQL_ASSOC);
	$postradar = $sql->query("SELECT posts,id,name,aka,sex,powerlevel,birthday FROM users,postradar WHERE postradar.user={$userid} AND users.id=postradar.comp ORDER BY posts DESC", MYSQL_ASSOC);
	if (@mysql_num_rows($postradar)>0) {
		$race = 'You are ';

		function cu($a,$b) {
			global $hacks;

			$dif = $a-$b['posts'];
			if ($dif < 0)
				$t = (!$hacks['noposts'] ? -$dif : "") ." behind";
			elseif ($dif > 0)
				$t = (!$hacks['noposts'] ?  $dif : "") ." ahead of";
			else
				$t = ' tied with';

			$namelink = getuserlink($b);
			$t .= " {$namelink}" . (!$hacks['noposts'] ? " ($b[posts])" : "");
			return "<nobr>{$t}</nobr>";
		}

		// Save ourselves a query if we're viewing our own post radar
		// since we already fetch all user fields for $loguserid
		if ($userid == $loguserid)
			$myposts = $loguser['posts'];
		else
			$myposts = $sql->resultq("SELECT posts FROM users WHERE id=$userid");

		for($i=0;$user2=$sql->fetch($postradar);$i++) {
			if($i) $race.=', ';
			if($i && $i == mysql_num_rows($postradar)-1) $race.='and ';
			$race .= cu($myposts, $user2);
		}
	}
	return $race;
}

function loaduser($id,$type){
  global $sql;
	if ($type==1) {$fields='id,name,sex,powerlevel,posts';}
	return @$sql->fetchq("SELECT $fields FROM users WHERE id=$id");
}

function getpostlayoutid($text){
	global $sql;
	$id=@$sql->resultq("SELECT id FROM postlayouts WHERE text='".addslashes($text)."' LIMIT 1",0,0);
	if(!$id){
		$sql->query("INSERT INTO postlayouts (text) VALUES ('".addslashes($text)."')");
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
function mysql_get($query){
  global $sql;
  return $sql->fetchq($query);
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
	global $log,$loguser,$tlayout,$sql;
	$tlayout    = (filter_int($loguser['layout']) ? $loguser['layout'] : 1);
	$layoutfile = $sql->resultq("SELECT file FROM tlayouts WHERE id='$tlayout'",0,0);
	if (!$layoutfile) $layoutfile = 'regular';
	require_once "tlayouts/$layoutfile.php";
}

function moodlist($sel = 0, $return = false) {
	global $loguserid, $log, $loguser;
	$img_base = base_dir().'/';
	$sel		= floor($sel);

	$a	= array("None", "neutral", "angry", "tired/upset", "playful", "doom", "delight", "guru", "hope", "puzzled", "whatever", "hyperactive", "sadness", "bleh", "embarrassed", "amused", "afraid");
	//if ($loguserid == 1) $a[99] = "special";
	if ($return) return $a;

	$c[$sel]	= " checked";
	$ret		= "";

	if ($log && $loguser['moodurl'])
		$ret = '
			<script type="text/javascript">
				function avatarpreview(uid,pic)
				{
					if (pic > 0)
					{
						var moodav="'.htmlspecialchars($loguser['moodurl']).'";
						document.getElementById(\'prev\').src=moodav.replace("$", pic);
					}
					else
					{
						document.getElementById(\'prev\').src="'.$img_base.'images/_.gif";
					}
				}
			</script>
		';

	$ret .= "<b>Mood avatar list:</b><br><table cellpadding=0 border=0 cellspacing=0><tr><td width=150px style='white-space:nowrap;'>";

	foreach($a as $num => $name) {
		$jsclick = (($log && $loguser['moodurl']) ? "onclick='avatarpreview($loguserid,$num)'" : "");
		$ret .= "<input type='radio' name='moodid' value='$num'". filter_string($c[$num]) ." id='mood$num' tabindex='". (9000 + $num) ."' style=\"height: 12px;\" $jsclick>
             <label for='mood$num' ". filter_string($c[$sel]) ." style=\"font-size: 12px;\">&nbsp;$num:&nbsp;$name</label><br>\r\n";
	}

	if (!$sel || !$log || !$loguser['moodurl'])
		$startimg = $img_base.'images/_.gif';
	else
		$startimg = htmlspecialchars(str_replace('$', $sel, $loguser['moodurl']));

	$ret .= "</td><td><img src=\"$startimg\" id=prev></td></table>";
	return $ret;
}

function admincheck() {
	global $tblstart, $tccell1, $tblend, $footer, $isadmin;
	if (!$isadmin) {
		$home = base_dir().'/';
		print "
			$tblstart
				$tccell1>This feature is restricted to administrators.<br>You aren't one, so go away.<br>
        ".redirect("{$home}index.php",'return to the board',0)."
        </td>
			$tblend

		$footer
		";
		die();
	}
}

// Generic error page.
function errorpage($text, $redir = '', $redirurl = '') {
	global $header,$tblstart,$tccell1,$tblend,$footer,$startingtime;

	print "{$header}<br>{$tblstart}{$tccell1}>{$text}";

	if ($redir)
		print '<br>'.redirect($redirurl,$redir,0);

	print "{$tblend}{$footer}";

	printtimedif($startingtime);
	die();
}

function adminlinkbar($sel = 'admin.php') {
	global $tblstart, $tblend, $tccell1, $tccellh, $tccellc, $isadmin;

	if (!$isadmin) return;

	$links	= array(
		array(
			"{$GLOBALS['jul_views_path']}/admin.php"	=> "Admin Control Panel",
		),
		array(
//			'admin-todo.php'       => "To-do list",
			"{$GLOBALS['jul_views_path']}/announcement.php"     => "Go to Announcements",
			"{$GLOBALS['jul_views_path']}/admin-editforums.php" => "Edit Forum List",
			"{$GLOBALS['jul_views_path']}/admin-editmods.php"   => "Edit Forum Moderators",
			"{$GLOBALS['jul_views_path']}/ipsearch.php"   => "IP Search",
			"{$GLOBALS['jul_views_path']}/admin-threads.php"    => "ThreadFix",
			"{$GLOBALS['jul_views_path']}/admin-threads2.php"   => "ThreadFix 2",
			"{$GLOBALS['jul_views_path']}/del.php"    => "Delete User",
		)
	);

	$r = "<div style='padding:0px;margins:0px;'>
		$tblstart<tr>$tccellh><b>Admin Functions</b></td></tr>$tblend";

    foreach ($links as $linkrow) {
		$c	= count($linkrow);
		$w	= floor(1 / $c * 100);

		$r .= "$tblstart<tr>";

		foreach($linkrow as $link => $name) {
			$cell = $tccell1;
			if (strpos($link, $sel) !== false) $cell = $tccellc;
			$r .= "$cell width=\"$w%\"><a href=\"$link\">$name</a></td>";
		}

		$r .= "</tr>$tblend";
	}
	$r .= "</div><br>";

	return $r;
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
	$img_base = base_dir().'/';
	$temp = $p;

	$p=preg_replace("'position\s*:\s*fixed'si", "display:none", $p);


	//$p=preg_replace("':awesome:'","<small>[unfunny]</small>", $p);

	$p=preg_replace("':facepalm:'si","<img src={$img_base}images/facepalm.jpg>",$p);
	$p=preg_replace("':facepalm2:'si","<img src={$img_base}images/facepalm2.jpg>",$p);
	$p=preg_replace("':epicburn:'si","<img src={$img_base}images/epicburn.png>",$p);
	$p=preg_replace("':umad:'si","<img src={$img_base}images/umad.jpg>",$p);
	$p=preg_replace("':gamepro5:'si","<img src=http://xkeeper.net/img/gamepro5.gif title=\"FIVE EXPLODING HEADS OUT OF FIVE\">",$p);
	$p=preg_replace("':headdesk:'si","<img src=http://xkeeper.net/img/headdesk.jpg title=\"Steven Colbert to the rescue\">",$p);
	$p=preg_replace("':rereggie:'si","<img src={$img_base}images/rereggie.png>",$p);
	$p=preg_replace("':tmyk:'si","<img src=http://xkeeper.net/img/themoreyouknow.jpg title=\"do doo do doooooo~\">",$p);
	$p=preg_replace("':jmsu:'si","<img src={$img_base}images/jmsu.png>",$p);
	$p=preg_replace("':noted:'si","<img src={$img_base}images/noted.png title=\"NOTED, THANKS!!\">",$p);
	$p=preg_replace("':apathy:'si","<img src=http://xkeeper.net/img/stickfigure-notext.png title=\"who cares\">",$p);
	$p=preg_replace("':spinnaz:'si", "<img src=\"{$img_base}images/smilies/spinnaz.gif\">", $p);
	$p=preg_replace("':trolldra:'si", "<img src=\"/{$img_base}images/trolldra.png\">", $p);
	$p=preg_replace("':reggie:'si","<img src=http://xkeeper.net/img/reggieshrug.jpg title=\"REGGIE!\">",$p);

	$p=preg_replace("'zeon'si",'shit',$p);

	if (filter_bool($hacks['comments'])) {
		$p=str_replace("<!--", '<font color=#80ff80>&lt;!--', $p);
		$p=str_replace("-->", '--&gt;</font>', $p);
	}

	$p=preg_replace("'(https?://.*?photobucket.com/)'si","{$img_base}images/photobucket.png#\\1",$p);
	$p=preg_replace("'http://.{0,3}\.?tinypic\.com'si",'tinyshit',$p);
	$p=str_replace('<link href="http://pieguy1372.freeweb7.com/misc/piehills.css" rel="stylesheet">',"",$p);
	$p=str_replace("tabindex=\"0\" ","title=\"the owner of this button is a fucking dumbass\" ",$p);

//	$p=str_replace("http://xkeeper.shacknet.nu:5/", 'http://xchan.shacknet.nu:5/', $p);
//	$p=preg_replace("'<style'si",'&lt;style',$p);


	//$p=preg_replace("'%BZZZ%'si",'onclick="bzzz(',$p);

	/*
	$p=preg_replace("'document.cookie'si",'document.co<z>okie',$p);
	$p=preg_replace("'eval'si",'eva<z>l',$p);
	$p=preg_replace("'<script'si",'<<z>script',$p);
	$p=preg_replace("'</script'si",'<<z>/script',$p);
	$p=preg_replace("'javascript:'si",'javasc<z>ript:',$p);
	$p=preg_replace("'<iframe(?! src=\"https://www.youtube.com/embed/)'si",'<<z>iframe',$p);
	$p=preg_replace("'<meta'si",'<<z>meta',$p);
	*/

	$p	= xss_clean($p);

	$p	=preg_replace("'\[youtube\]([a-zA-Z0-9_-]{11})\[/youtube\]'si", '<iframe src="https://www.youtube.com/embed/\1" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>', $p);


	return $p;
}

require_once 'lib/threadpost.php';
// require_once 'lib/replytoolbar.php';

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

			$out	= "1|New user: #". xk(12) . $in['id'] . xk(11) ." $user ". xk() ."(IP: ". xk(12) . $in['ip'] . xk() .")$extra: https://jul.rustedlogic.net/?u=". $in['id'];

		} else {
//			global $sql;
//			$res	= $sql -> resultq("SELECT COUNT(`id`) FROM `posts`");
			$out	= "$dest|New $type by ". xk(11) . $user . xk() ." (". xk(12) . $in['forum'] .": ". xk(11) . $in['thread'] . xk() ."): https://jul.rustedlogic.net/?p=". $in['pid'];

		}

		xk_ircsend($out);
	}

	function xk_ircsend($str) {
		// Only continue if IRC notifications are enabled.
		if (!$GLOBALS['jul_settings']['irc_enable_notifications']) return;

		$str = str_replace(array("%10", "%13"), array("", ""), rawurlencode($str));

		$str = html_entity_decode($str);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://treeki.rustedlogic.net:5000/reporting.php?t=$str");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // <---- HERE
		curl_setopt($ch, CURLOPT_TIMEOUT, 5); // <---- HERE
		$file_contents = curl_exec($ch);
		curl_close($ch);

		return true;
	}

	function xk($n = -1) {
		if ($n == -1) $k = "";
			else $k = str_pad($n, 2, 0, STR_PAD_LEFT);
		return "\x03". $k;
	}

	function formatting_trope($input) {
		$in		= "/[A-Z][^A-Z]/";
		$out	= " \\0";
		$output	= preg_replace($in, $out, $input);

		return trim($output);
	}

	// I'm picky about this sorta thing
	function getblankdate() {
		global $dateformat;

		// We only need to do the replacing one time
		static $bl;
		if ($bl) return $bl;

		$bl = $dateformat;
		$bl = preg_replace('/[jNwzWnLgGI]/',	'-',      $bl);
		$bl = preg_replace('/[dSmtyaAhHis]/',	'--',     $bl);
		$bl = preg_replace('/[DFMBe]/',			'---',    $bl);
		$bl = preg_replace('/[oY]/',			'----',   $bl);
		$bl = preg_replace('/[lu]/',			'------', $bl);
		$bl = preg_replace('/[c]/',				'----------T--:--:--+00:00', $bl);
		$bl = preg_replace('/[r]/',				'---, -- --- ---- --:--:-- +0000', $bl);
		return $bl;
	}

	function cleanurl($url) {
		$pos1 = $pos = strrpos($url, '/');
		$pos2 = $pos = strrpos($url, '\\');
		if ($pos1 === FALSE && $pos2 === FALSE)
			return $url;

		$spos = max($pos1, $pos2);
		return substr($url, $spos+1);
	}

	/* extra fun functions! */
	function pick_any($array) {
		if (is_array($array)) {
			return $array[array_rand($array)];
		} elseif (is_string($array)) {
			return $array;
		}
	}

	function numrange($n, $lo, $hi) {
		return max(min($hi, $n), $lo);
	}

	function marqueeshit($str) {
		return "<marquee scrollamount='". mt_rand(1, 50) ."' scrolldelay='". mt_rand(1, 50) ."' direction='". pick_any(array("left", "right")) ."'>$str</marquee>";
	}


	function unescape($in) {

		$out	= urldecode($in);
		while ($out != $in) {
			$in		= $out;
			$out	= urldecode($in);
		}
		return $out;

	}


function adbox() {

	// no longer needed. RIP
	return "";

	global $loguser, $bgcolor, $linkcolor;

/*
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

	if (isset($_GET['lolol'])) {
		$taglinec	= $_GET['lolol'] % count($tagline);
		$taglinec	= $tagline[$taglinec];
	}
	else
		$taglinec	= pick_any($tagline);
*/

	return "
<center>
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
<!-- End of Project Wonderful ad code. -->
</center>";
}

// for you-know-who's bullshit
function gethttpheaders() {
	$ret = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 5) == 'HTTP_') {
			$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
			if ($name == "User-Agent" || $name == "Cookie" || $name == "Referer" || $name == "Connection")
				continue; // we track the first three already, the last will always be "close"

			$ret .= "$name: $value\r\n";
		}
	}

	return $ret;
}

function printtimedif($timestart){
	global $x_hacks, $sql, $sqldebuggers, $smallfont;

	$exectime = microtime(true) - $timestart;

	$qseconds = sprintf("%01.6f", mysql::$time);
	$sseconds = sprintf("%01.6f", $exectime - mysql::$time);
	$tseconds = sprintf("%01.6f", $exectime);

	$queries = mysql::$queries;
	$cache = mysql::$cachehits;

	// Old text
	//print "<br>{$smallfont} Page rendered in {$tseconds} seconds.</font><br>";

	print "<br>
		{$smallfont}{$queries} database queries". (($cache > 0) ? ", {$cache} query cache hits" : "") .".</font>
		<table cellpadding=0 border=0 cellspacing=0 class='fonts'>
			<tr><td align=right>Query execution time:&nbsp;</td><td>{$qseconds} seconds</td></tr>
			<tr><td align=right>Script execution time:&nbsp;</td><td>{$sseconds} seconds</td></tr>
			<tr><td align=right>Total render time:&nbsp;</td><td>{$tseconds} seconds</td></tr>
		</table>";

	/*
	if (in_array($_SERVER['REMOTE_ADDR'], $sqldebuggers)) {
		if (!mysql::$debug_on && $_SERVER['REQUEST_METHOD'] != 'POST')
			print "<br><a href=".$_SERVER['REQUEST_URI'].(($_SERVER['QUERY_STRING']) ? "&" : "?")."debugsql>Useless mySQL query debugging shit</a>";
		else
			print mysql::debugprinter();
	}
	*/

	if (!$x_hacks['host']) {
		$pages	= array(
			"/index.php",
			"/thread.php",
			"/forum.php",
		);
		$url = $_SERVER['REQUEST_URI'];
		if (in_array(substr($url, 0, 14), $pages)) {
			$sql->query("INSERT INTO `rendertimes` SET `page` = '". addslashes($url) ."', `time` = '". ctime() ."', `rendertime`  = '". $exectime ."'");
			$sql->query("DELETE FROM `rendertimes` WHERE `time` < '". (ctime() - 86400 * 14) ."'");
		}
	}
}

function ircerrors($type, $msg, $file, $line, $context) {
 	global $loguser;

	// They want us to shut up? (@ error control operator) Shut the fuck up then!
	if (!error_reporting())
		return true;

	switch($type) {
		case E_USER_ERROR:		$typetext = xk(4) . "- Error";  break;
		case E_USER_WARNING:	$typetext = xk(7) . "- Warning"; break;
		case E_USER_NOTICE:		$typetext = xk(8) . "- Notice";  break;
		default: return false;
	}

	// Get the ACTUAL location of error for mysql queries
	if ($type == E_USER_ERROR && substr($file, -9) === "mysql.php") {
		$backtrace = debug_backtrace();
		for ($i = 1; isset($backtrace[$i]); ++$i) {
			if (substr($backtrace[$i]['file'], -9) !== "mysql.php") {
				$file = $backtrace[$i]['file'];
				$line = $backtrace[$i]['line'];
				break;
			}
		}
	}
	// Get the location of error for deprecation
	elseif ($type == E_USER_NOTICE && substr($msg, 0, 10) === "Deprecated") {
		$backtrace = debug_backtrace();
		$file = $backtrace[2]['file'];
		$line = $backtrace[2]['line'];
	}

	$errorlocation = str_replace($_SERVER['DOCUMENT_ROOT'], "", $file) ." #$line";

	xk_ircsend("102|".($loguser['id'] ? xk(11) . $loguser['name'] .' ('. xk(10) . $_SERVER['REMOTE_ADDR'] . xk(11) . ')' : xk(10) . $_SERVER['REMOTE_ADDR']) .
	           " $typetext: ".xk()."($errorlocation) $msg");
	return true;
}
