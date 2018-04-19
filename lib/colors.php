<?php
/*
	if (!$x_hacks['host']) {
		if ($loguserid == 1) $GLOBALS['jul_settings']['board_title']	= "";

		$autobancount = $sql->fetchq("SELECT COUNT(*) AS cnt, MAX(`date`) as time FROM `ipbans` WHERE `reason` LIKE 'Autoban'", MYSQL_ASSOC);
		$totalbancount = $sql->fetchq("SELECT COUNT(*) AS cnt, MAX(`date`) as time FROM `ipbans`", MYSQL_ASSOC);

		$GLOBALS['jul_settings']['board_title']	.= "<br><font class=font color=#ff0000><b>If you got banned, PM an admin for a password change</b></font><br><font class=fonts>". $autobancount['cnt'] ." automatic IP bans have been issued, last ". timeunits2(ctime() - $autobancount['time']) ." ago"
			."<br>". $totalbancount['cnt'] ." IP bans have been issued in total, last ". timeunits2(ctime() - $totalbancount['time']) ." ago";

		$GLOBALS['jul_settings']['board_title']= "<span style='font-size: 40pt; font-variant: small-caps; color: #f33;'>The Hivemind Collective</span><br><span style='font-size: 6pt; font-variant: small-caps; color: #c00'>(because a group of friends sharing a similar opinion is totally hivemind, dood!)</span>";
	}
*/

	$pwlnames=array('-2'=>'Permabanned','-1'=>'Banned','Normal','Normal +','Moderator','Administrator','Sysadmin');
	$nmcol[0]=array('-2'=>'6a6a6a','-1'=>'888888','97ACEF','D8E8FE','AFFABE','FFEA95');
	$nmcol[1]=array('-2'=>'767676','-1'=>'888888','F185C9','FFB3F3','C762F2','C53A9E');
	$nmcol[2]=array('-2'=>'767676','-1'=>'888888','7C60B0','EEB9BA','47B53C','F0C413');

	$linkcolor='FFD040';
	$linkcolor2='F0A020';
	$linkcolor3='FFEA00';
	$linkcolor4='FFFFFF';
	$textcolor='E0E0E0';

	$font	='Verdana, sans-serif';
	$font2	='Verdana, sans-serif';
	$font3	='Tahoma, sans-serif';

	$newpollpic		= '<img src="'.$GLOBALS['jul_base_dir'].'/images/newpoll.png" alt="New poll" align="absmiddle">';
	$newreplypic	= '<img src="'.$GLOBALS['jul_base_dir'].'/images/newreply.png" alt="New reply" align="absmiddle">';
	$newthreadpic	= '<img src="'.$GLOBALS['jul_base_dir'].'/images/newthread.png" alt="New thread" align="absmiddle">';
	$closedpic		= '<img src="'.$GLOBALS['jul_base_dir'].'/images/threadclosed.png" alt="Thread closed" align="absmiddle">';
	$numdir			='jul/';

	$statusicons['new']			= '<img src='.$GLOBALS['jul_base_dir'].'/images/new.gif>';
	$statusicons['newhot']		= '<img src='.$GLOBALS['jul_base_dir'].'/images/hotnew.gif>';
	$statusicons['newoff']		= '<img src='.$GLOBALS['jul_base_dir'].'/images/off.gif>';
	$statusicons['newhotoff']	= '<img src='.$GLOBALS['jul_base_dir'].'/images/hotoff.gif>';
	$statusicons['hot']			= '<img src='.$GLOBALS['jul_base_dir'].'/images/hot.gif>';
	$statusicons['hotoff']		= '<img src='.$GLOBALS['jul_base_dir'].'/images/hotoff.gif>';
	$statusicons['off']			= '<img src='.$GLOBALS['jul_base_dir'].'/images/off.gif>';

	$statusicons['getnew']		= '<img src='.$GLOBALS['jul_base_dir'].'/images/getnew.png title="Go to new posts" align="absmiddle">';
	$statusicons['getlast']		= '<img src='.$GLOBALS['jul_base_dir'].'/images/getlast.png title="Go to last post" style="position:relative;top:1px;">';

	$statusicons['sticky']		= 'Sticky:';
	$statusicons['poll']		= 'Poll:';
	$statusicons['stickypoll']	= 'Sticky poll:';

	$schemetime	= -1; // mktime(9, 0, 0) - time();

	// $numfil='numnes';
	$schemepre	= false;

	$scheme		= filter_int($scheme);
	if (isset($_GET['scheme']) && is_numeric($_GET['scheme'])) {
		$scheme		= intval($_GET['scheme']);
		$schemepre	= true;
	} elseif (isset($_GET['scheme'])) {
		$scheme		= 0;
	}

	// Force Xmas scheme (cue whining, as always)
	if (false && !($log && $loguserid == 2100)) { // ... just ... not now please.
		if (!$x_hacks['host']) $scheme = 3;
		$x_hacks['rainbownames']	= true;
	}

	$schemerow	= $sql -> fetchq("SELECT `name`, `file` FROM schemes WHERE id='$scheme'");

	$filename	= "";
	if ($schemerow) {
		$filename	= $schemerow['file'];
	} else {
		$filename	= "night.php";
		$schemepre	= false;
	}

#	if (!$x_hacks['host'] && true) {
#		$filename	= "ymar.php";
#	}

	require_once "schemes/$filename";

	if ($schemepre) {
		$GLOBALS['jul_settings']['board_title']	.= "</a><br><span class='font'>Previewing scheme \"<b>". $schemerow['name'] ."</b>\"</span>";
	}

#	if (!$x_hacks['host'] && true) {
#		$GLOBALS['jul_settings']['board_title']	.= "</a><br><a href='/thread.php?id=10372'><span style='font-size: 14px;'>Now with more celebrations!</span></a>";
#	}

	# hack for compat
	if (!$inputborder) $inputborder	= $tableborder;

	$newpic					= $statusicons['new'];	# hack for compat

	if ($loguser['powerlevel'] < 3) {
		$nmcol[0][1]	= $nmcol[0][0];
		$nmcol[1][1]	= $nmcol[1][0];
		$nmcol[2][1]	= $nmcol[2][0];
	}
	//$nmcol[0][4]		= "#ffffff";

/*
	if (!$x_hacks['host'])
		$GLOBALS['jul_settings']['board_title']	.= "</a><br><a href='/thread.php?id=9218'><span style='color: #f00; font-weight: bold;'>Security notice for certain users, please read and see if you are affected</span></a>";

	if ($loguser['id'] >= 1 && false) {
		$numdir2	= $numdir;
		$numdir		= "num3/";

		$votetu		= max(0, 1000000 - floor((mktime(15, 0, 0, 7, 22, 2009) - microtime(true)) * (1000000 / 86400)));

		$votetally	= max(0, $votetu / (1000000));

		$votepct2	= floor($votetu * 1);			// no decimal point, so x100 for added precision
		$votepctm	= 5;									// width of the bar
		$votepct	= floor($votetally * 100 * $votepctm);
//		$GLOBALS['jul_settings']['board_title']	.= "</a><br><a href='/thread.php?id=5710'><span style='color: #f22; font-size: 14px;'>". generatenumbergfx($votetu ."/1000000", 2) ." <img src='numgfx/num3/barleft.png'><img src='numgfx/num3/bar-on.png' height='8' width='". ($votepct) ."'><img src='numgfx/num3/bar-off.png' height='8' width='". (100 * $votepctm - $votepct) ."'><img src='numgfx/num3/barright.png'></span></a>";
		$numdir		= $numdir2;
		$cycler		= str_replace("color=", "#", getnamecolor(0, 0));
		$GLOBALS['jul_settings']['board_title']	.= "</a><br><a href='/thread.php?id=5866'><span style='color: $cycler; font-size: 14px;'>Mosts Results posted. Go view.</span></a>";
	} */
