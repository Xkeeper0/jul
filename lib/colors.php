<?php

	if (!defined("IT_IS_CHRISTMAS")) {
		define("IT_IS_CHRISTMAS", false);
	}

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

	$newpollpic		= '<img src="images/newpoll.png" alt="New poll" align="absmiddle">';
	$newreplypic	= '<img src="images/newreply.png" alt="New reply" align="absmiddle">';
	$newthreadpic	= '<img src="images/newthread.png" alt="New thread" align="absmiddle">';
	$closedpic		= '<img src="images/threadclosed.png" alt="Thread closed" align="absmiddle">';
	$numdir			='jul/';

	$statusicons['new']			= '<img src=images/new.gif>';
	$statusicons['newhot']		= '<img src=images/hotnew.gif>';
	$statusicons['newoff']		= '<img src=images/off.gif>';
	$statusicons['newhotoff']	= '<img src=images/hotoff.gif>';
	$statusicons['hot']			= '<img src=images/hot.gif>';
	$statusicons['hotoff']		= '<img src=images/hotoff.gif>';
	$statusicons['off']			= '<img src=images/off.gif>';

	$statusicons['getnew']		= '<img src=images/getnew.png title="Go to new posts" align="absmiddle">';
	$statusicons['getlast']		= '<img src=images/getlast.png title="Go to last post" style="position:relative;top:1px;">';

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

	// Force Xmas scheme for the holidays
	if (IT_IS_CHRISTMAS && !($log && $loguserid == 2100)) { // ... just ... not now please.
		$scheme = 3;
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


	require "schemes/$filename";

	if ($schemepre) {
		$boardtitle	.= "</a><br><span class='font'>Previewing scheme \"<b>". $schemerow['name'] ."</b>\"</span>";
	}

	# hack for compat
	if (!$inputborder) $inputborder	= $tableborder;

	$newpic					= $statusicons['new'];	# hack for compat

	if ($loguser['powerlevel'] <= 0) {
		$nmcol[0][1]	= $nmcol[0][0];
		$nmcol[1][1]	= $nmcol[1][0];
		$nmcol[2][1]	= $nmcol[2][0];
	}
	//$nmcol[0][4]		= "#ffffff";


