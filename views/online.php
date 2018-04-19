<?php
	require_once '../lib/function.php';

	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Online users";
	require_once '../lib/layout.php';

/*
	if (empty($_COOKIE) && $_SERVER['HTTP_REFERER'] == "http://jul.rustedlogic.net/") {
		// Some lame botnet that keeps refreshing this page every second or so.
		xk_ircsend("102|". date("Y-m-d h:i:s") ." - ".xk(7)."IP address ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." is being weird. ". xk(5) ."(UA: ". $_SERVER['HTTP_USER_AGENT'] .")");
		header("Location: http://". $_SERVER['REMOTE_ADDR'] ."/");
		die("Fuck off, forever.");
	}
	if (empty($_COOKIE)) {
		// Some lame botnet that keeps refreshing this page every second or so.
		xk_ircsend("102|". date("Y-m-d h:i:s") ." - ".xk(7)."IP address ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." is being slightly less weird, but still weird. ". xk(5) ."(UA: ". $_SERVER['HTTP_USER_AGENT'] .")");
		header("Location: http://". $_SERVER['REMOTE_ADDR'] ."/");
		die("Don't be weird.");
	}
*/

	$time = filter_int($_GET['time']) ? $_GET['time'] : 300;

	// FOR THE LOVE OF GOD XKEEPER JUST GIVE ME ~NUKE ACCESS
	$banorama	= ($_SERVER['REMOTE_ADDR'] == $x_hacks['adminip'] || $loguser['id'] == 1 || $loguser['id'] == 5 || $loguser['id'] == 2100);

	if ($banorama && filter_string($_GET['banip']) && filter_string($_GET['valid']) == md5($_GET['banip'] . "aglkdgslhkadgshlkgds")) {
		$sql->query("INSERT INTO `ipbans` SET `ip` = '". $_GET['banip'] ."', `reason`='online.php ban', `date` = '". ctime() ."', `banner` = '$loguserid'") or print mysql_error();
//		if ($_GET['uid']) mysql_query("UPDATE `users` SET `powerlevel` = -1, `title` = 'Banned; account hijacked. Contact admin via PM to change it.' WHERE `id` = '". $_GET['uid'] ."'") or print mysql_error();
		xk_ircsend("1|". xk(8) . $loguser['name'] . xk(7) ." added IP ban for ". xk(8) . $_GET['banip'] . xk(7) .".");
		return header("Location: {$GLOBALS['jul_views_path']}/online.php?m=1");
	}

	$sort	= filter_bool($_GET['sort']);

	$lnk	= "<a href={$GLOBALS['jul_views_path']}/online.php". ($sort ? "?sort=1&" : '?') .'time';
	print "
		$header$smallfont
		Show online users during the last:
		$lnk=60>minute</a> |
		$lnk=300>5 minutes</a> |
		$lnk=900>15 minutes</a> |
		$lnk=3600>hour</a> |
		$lnk=86400>day</a>
	";
	if($isadmin)
		print "<br>Admin cruft: <a href={$GLOBALS['jul_views_path']}/online.php". ($sort ? '?sort=1&' : '?') ."time=$time>Sort by ".($sort == 'IP' ? 'date' : 'IP') ."</a>";

	// Logged in users
	$posters = $sql->query("SELECT id,posts,name,sex,powerlevel,aka,lastactivity,lastip,lastposttime,lasturl,birthday FROM users WHERE lastactivity>".(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'lastip':'lastactivity DESC'));


	print "<br>
	$fonttag Online users during the last ".timeunits2($time).":
	$tblstart
		$tccellh width=20>&nbsp</td>
		$tccellh width=200>Username</td>
		$tccellh width=120> Last activity</td>
		$tccellh width=180> Last post</td>
		$tccellh width=*>URL</td>
	";
	if($isadmin) print "$tccellh width=120>IP address</td>";
	print "$tccellh width=60> Posts</tr>";

	for ($i=1; $user=$sql->fetch($posters); $i++) {
		$userlink = getuserlink($user);
		if(!$user['posts']) $user['lastposttime'] = getblankdate();
		else                $user['lastposttime'] = date($dateformat,$user['lastposttime']+$tzoff);

		$user['lasturl']=str_replace('<','&lt;',$user['lasturl']);
		$user['lasturl']=str_replace('>','&gt;',$user['lasturl']);
		$user['lasturl']=str_replace('%20',' ',$user['lasturl']);
		$user['lasturl']=str_replace('shoph','shop',$user['lasturl']);
		$user['lasturl']=preg_replace('/[\?\&]debugsql(|=[0-9]+)/i','',$user['lasturl']); // let's not give idiots any ideas
		$lasturltd	= "$tccell2l><a rel=\"nofollow\" href=\"". urlformat($user['lasturl']) ."\">$user[lasturl]";

		if (substr($user['lasturl'], -11) =='(IP banned)')
			$lasturltd	= "$tccell2l><a rel=\"nofollow\" href=\"". substr($user['lasturl'], 0, -12) ."\">". substr($user[lasturl], 0, -12) ."</a> (IP banned)";
		elseif (substr($user['lasturl'], -11) =='(Tor proxy)')
			$lasturltd	= "$tccell2l><a rel=\"nofollow\" href=\"". substr($user['lasturl'], 0, -12) ."\">". substr($user[lasturl], 0, -12) ."</a> (Tor proxy)";

		print "<tr style=\"height:24px;\">
			$tccell1>$i</td>
			$tccell2l>{$userlink}</td>
			$tccell1>".date('h:i:s A',$user['lastactivity']+$tzoff)."</td>
			$tccell1>$user[lastposttime]</td>
			$lasturltd</td>";

		if ($banorama)
			$ipban	= "$smallfont<br>[<a href=?banip=$user[lastip]&uid=$user[id]&valid=". md5($user['lastip'] . "aglkdgslhkadgshlkgds") .">Ban</a> - <a href=http://google.com/search?q=$user[lastip]>G</a>]</font>";

		if($isadmin)
			print "$tccell1><a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip=$user[lastip]'>$user[lastip]</a> $ipban</td>";
//		$tccell1r>". $user['ipmatches'] ." <img src='". ($user['ipmatches'] > 0 ? "images/dot2.gif" : "images/dot5.gif") ."' align='absmiddle'></td>";

		print "$tccell2>$user[posts]</tr>";
	}

	//WHERE date>'.(ctime()-$time).'
	$guests = $sql->query('SELECT *, (SELECT COUNT(`ip`) FROM `ipbans` WHERE `ip` = `guests`.`ip`) AS banned FROM guests ORDER BY '.($sort=='IP'&&$isadmin?'ip':'date').' DESC');

	print "
		$tblend
		$fonttag<br>Guests online in the past 5 min.:
		$tblstart<tr>
		$tccellh width=20>&nbsp</td>
		$tccellh width=300>&nbsp</td>
		$tccellh width=120>Last activity</td>
		$tccellh width=*>URL</td>
	";
	if($isadmin) print "$tccellh width=120> IP address</td>";
	print '</tr>';

	for($i=1;$guest=$sql->fetch($guests);$i++){
		$guest['lasturl']=str_replace('<','&lt;',$guest['lasturl']);
		$guest['lasturl']=str_replace('>','&gt;',$guest['lasturl']);
		$guest['lasturl']=str_replace('shoph','shop',$guest['lasturl']);
		$guest['lasturl']=preg_replace('/[\?\&]debugsql=[0-9]+/i','',$guest['lasturl']); // let's not give idiots any ideas

/*		if ($guest['useragent'] == "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.19) Gecko/2010031218 Firefox/3.0.19" && $banorama) {
//		if (stripos($guest['useragent'], "robot") !== false && $banorama)
			$marker	= " style='color: #f88;'";
		else
			$marker	= "";
*/

		$marker = '';

		$lasturltd	= "$tccell2l$marker><a rel=\"nofollow\" href=\"". urlformat($guest['lasturl']) ."\">$guest[lasturl]";
		if (substr($guest['lasturl'], -11) =='(IP banned)')
			$lasturltd	= "$tccell2l$marker><a rel=\"nofollow\" href=\"". substr($guest['lasturl'], 0, -12) ."\">". substr($guest['lasturl'], 0, -12) ."</a> (IP banned)";
		elseif (substr($guest['lasturl'], -11) =='(Tor proxy)')
			$lasturltd	= "$tccell2l$marker><a rel=\"nofollow\" href=\"". substr($guest['lasturl'], 0, -12) ."\">". substr($guest['lasturl'], 0, -12) ."</a> (Tor proxy)";


		print "<tr style=\"height:40px;\">
			$tccell1$marker>$i</td>
			$tccell2s$marker>". htmlspecialchars($guest['useragent']) ."</td>
			$tccell1$marker>".date('h:i:s A',$guest['date']+$tzoff)."</td>
			$lasturltd</td>";


		if ($banorama && !$guest['banned'])
			$ipban	= "<a href=?banip=$guest[ip]&valid=". md5($guest['ip'] . "aglkdgslhkadgshlkgds") .">Ban</a> - ";
		elseif ($guest['banned'])
		 	$ipban	= "<span style='color: #f88; font-weight: bold;'>Banned</span> - ";
		else
			$ipban	= "";

		if($isadmin)
			print "</td>$tccell1$marker>
			<a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip=$guest[ip]'>$guest[ip]</a>$smallfont
			<br>[$ipban<a href=http://google.com/search?q=$guest[ip]>G</a>-<a href=http://en.wikipedia.org/wiki/User:$guest[ip]>W</a>-<a href=http://$guest[ip]/>H</a>]</a></font>";

		print "</tr>";
	}

	print $tblend.$footer;
	printtimedif($startingtime);

	function urlformat($url) {
		return preg_replace("/^\/thread\.php\?pid=([0-9]+)$/", "/thread.php?pid=\\1#\\1", $url);
	}
