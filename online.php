<?php

  
  require 'lib/function.php';
  require 'lib/layout.php';
  if(intval($_GET['time'])) {
	  
	  $time=intval($_GET['time']);

  } else {
	  $time=300;

  }

	// FOR THE LOVE OF GOD XKEEPER JUST GIVE ME ~NUKE ACCESS
	$banorama	= ($_SERVER['REMOTE_ADDR'] == $x_hacks['adminip'] || $loguser['id'] == 1 || $loguser['id'] == 5);

  if ($_GET['banip'] && $_GET['valid'] == md5($_GET['banip'] . "aglkdgslhkadgshlkgds") && $banorama) {
	mysql_query("INSERT INTO `ipbans` SET `ip` = '". $_GET['banip'] ."', `reason`='online.php ban', `date` = '". ctime() ."', `banner` = '$loguserid'") or print mysql_error();
//	if ($_GET['uid']) mysql_query("UPDATE `users` SET `powerlevel` = -1, `title` = 'Banned; account hijacked. Contact admin via PM to change it.' WHERE `id` = '". $_GET['uid'] ."'") or print mysql_error();
	xk_ircsend("1|". xk(8) . $loguser['name'] . xk(7) ." added IP ban for ". xk(8) . $_GET['banip'] . xk(7) .".");
	return header("Location: online.php?m=1");
    
  }


  $posters=mysql_query("SELECT id,posts,name,sex,powerlevel,lastactivity,lastip,lastposttime,lasturl FROM users WHERE lastactivity>".(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'lastip':'lastactivity DESC'));

/*
  if (!in_array($loguser['id'], array(1, 4, 5))) {
	die("Sorry, this'll be back soon");
  }

  $posters=mysql_query("SELECT id,posts,name,sex,powerlevel,lastactivity,lastip,lastposttime,lasturl,(SELECT COUNT(*) FROM `posts` WHERE `user` = `users`.`id` AND `ip` = `users`.`lastip`) AS ipmatches FROM users WHERE powerlevel >= 1 AND lastactivity>".(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'lastip':'lastactivity DESC'));
*/

  $server=getenv('SERVER_NAME');
  $port=getenv('SERVER_PORT');
  $host=$server;
  $lnk='<a href=online.php?'.($sort?"sort=$sort&":'').'time';
  print "
	$header$smallfont
	$lnk=60>During last minute</a> |
	$lnk=300>During last 5 minutes</a> |
	$lnk=900>During last 15 minutes</a> |
	$lnk=3600>During last hour</a> | 
	$lnk=86400>During last day</a>
  ";
  if($isadmin)
    print ' | <a href=online.php?'.($sort=='IP'?'':'sort=IP&')."time=$time>Sort by ".($sort=='IP'?'date':'IP')."</a>";
  print "<br>
	$fonttag Online users during the last $time seconds:
	$tblstart
	 $tccellh width=20>&nbsp</td>
	 $tccellh>Username</td>
	 $tccellh width=80> Last activity</td>
	 $tccellh width=130> Last post</td>
	 $tccellh>URL</td>
  ";
  if($isadmin) print "$tccellh width=100>IP address</td>";
  print "$tccellh width=60> Posts<tr>";
  for($i=1;$user=mysql_fetch_array($posters);$i++){
    if($i>1) print '<tr>';
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    $user[lastposttime]=date($dateformat,$user[lastposttime]+$tzoff);
    if(!$user[posts]) $user[lastposttime]='-------- --:-- --';
    $user[lasturl]=str_replace('<','&lt;',$user['lasturl']);
    $user[lasturl]=str_replace('>','&gt;',$user['lasturl']);
    $user[lasturl]=str_replace('%20',' ',$user['lasturl']);
    $user[lasturl]=str_replace('shoph','shop',$user['lasturl']);
	$lasturltd	= "$tccell2l><a href=\"". urlformat($user['lasturl']) ."\">$user[lasturl]";

	if (substr($user[lasturl], -11) =='(IP banned)') {
		$lasturltd	= "$tccell2l><a href=\"". substr($user[lasturl], 0, -12) ."\">". substr($user[lasturl], 0, -12) ."</a> (IP banned)</td>";
	} elseif (substr($user[lasturl], -11) =='(Tor proxy)') {
		$lasturltd	= "$tccell2l><a href=\"". substr($user[lasturl], 0, -12) ."\">". substr($user[lasturl], 0, -12) ."</a> (Tor proxy)</td>";
	}

    print "
	$tccell1>$i</td>
	$tccell2l><a href=profile.php?id=$user[id]><font $namecolor>$user[name]</td>
	$tccell1>".date('h:i:s A',$user[lastactivity]+$tzoff)."</td>
	$tccell1>$user[lastposttime]</td>
    $lasturltd";
	if ($banorama) {
		$ipban	= "[<a href=?banip=$user[lastip]&uid=$user[id]&valid=". md5($user['lastip'] . "aglkdgslhkadgshlkgds") .">Ban</a> - <a href=http://google.com/search?q=$user[lastip]>G</a>]";
	}
    if($isadmin) print "$tccell1><a href=ipsearch.php?ip=$user[lastip]>$user[lastip]</a> $ipban</td>";
//		$tccell1r>". $user['ipmatches'] ." <img src='". ($user['ipmatches'] > 0 ? "images/dot2.gif" : "images/dot5.gif") ."' align='absmiddle'></td>";

	print "$tccell2>$user[posts]";
  }
  print "
	$tblend
	$fonttag<br>Guests:
	$tblstart
	 $tccellh width=20>&nbsp</td>
	 $tccellh width=150>&nbsp</td>
	 $tccellh width=80>Last activity</td>
	 $tccellh>URL
  ";
  if($isadmin) print "</td>$tccellh width=100> IP address";
  print '<tr>';

  $guests=mysql_query('SELECT *, (SELECT COUNT(`ip`) FROM `ipbans` WHERE `ip` = `guests`.`ip`) AS banned FROM guests WHERE date>'.(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'ip':'date').' DESC');

  for($i=1;$guest=mysql_fetch_array($guests);$i++){
    if($i>1) print "<tr>";
    $guest[lasturl]=str_replace('<','&lt;',$guest['lasturl']);
    $guest[lasturl]=str_replace('>','&gt;',$guest['lasturl']);
    $guest[lasturl]=str_replace('shoph','shop',$guest['lasturl']);

	if ($guest['useragent'] == "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.19) Gecko/2010031218 Firefox/3.0.19" && $banorama) {
//	if (stripos($guest['useragent'], "robot") !== false && $banorama) {
		$marker	= " style='color: #f88;'";
	} else {
		$marker	= "";
	}

	$lasturltd	= "$tccell2l$marker><a href=\"". urlformat($guest['lasturl']) ."\">$guest[lasturl]";
	if (substr($guest[lasturl], -11) =='(IP banned)') {
		$lasturltd	= "$tccell2l$marker><a href=\"". substr($guest[lasturl], 0, -12) ."\">". substr($guest[lasturl], 0, -12) ."</a> (IP banned)</td>";
	} elseif (substr($guest[lasturl], -11) =='(Tor proxy)') {
		$lasturltd	= "$tccell2l$marker><a href=\"". substr($guest[lasturl], 0, -12) ."\">". substr($guest[lasturl], 0, -12) ."</a> (Tor proxy)</td>";
	}


    print "
	$tccell1$marker>$i</td>
	$tccell2s$marker>". htmlspecialchars($guest['useragent']) ."</td>
	$tccell1$marker>".date('h:i:s A',$guest[date]+$tzoff)."</td>
    $lasturltd";
	if ($banorama && !$guest['banned']) {
		$ipban	= "<a href=?banip=$guest[ip]&valid=". md5($guest['ip'] . "aglkdgslhkadgshlkgds") .">Ban</a> - ";

	} elseif ($guest['banned']) {
	 	$ipban	= "<span style='color: #f88; font-weight: bold;'>Banned</span> - ";
	} else {
		$ipban	= "";
	}

    if($isadmin) print "</td>$tccell1$marker><a href=ipsearch.php?ip=$guest[ip]>$guest[ip]</a>$smallfont<br>[$ipban<a href=http://google.com/search?q=$guest[ip]>G</a>-<a href=http://en.wikipedia.org/wiki/User:$guest[ip]>W</a>-<a href=http://$guest[ip]/>H</a>]</a>";
  }


  print $tblend.$footer;

  printtimedif($startingtime);


	function urlformat($url) {
		return preg_replace("/^\/thread\.php\?pid=([0-9]+)$/", "/thread.php?pid=\\1#\\1", $url);
	}

?>
