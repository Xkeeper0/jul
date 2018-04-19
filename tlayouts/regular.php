<?php

	function userfields(){
		return 'posts,sex,powerlevel,birthday,aka,picture,moodurl,title,useranks,location,lastposttime,lastactivity,imood,pronouns';
	}


	function postcode($post,$set){
		global $tzoff, $smallfont, $ip, $quote, $edit, $dateshort, $dateformat, $tlayout, $textcolor, $numdir, $numfil, $tblstart, $hacks, $x_hacks, $loguser;

		$tblend		= "</table>";
		$exp		= calcexp($post['posts'],(ctime()-$post['regdate']) / 86400);
		$lvl		= calclvl($exp);
		$expleft	= calcexpleft($exp);

		if ($tlayout == 1) {
			$level		= "Level: $lvl";
			$poststext	= "Posts: ";
			$postnum	= "$post[num]/";
			$posttotal	= $post['posts'];
			$experience	= "EXP: $exp<br>For next: $expleft";
			$totalwidth	= 96;
			$barwidth	= $totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);

			if ($barwidth < 1) $barwidth=0;

			if ($barwidth > 0) $baron="<img src={$GLOBALS['jul_base_dir']}/images/$numdir"."bar-on.gif width=$barwidth height=8>";

			if ($barwidth < $totalwidth) $baroff="<img src={$GLOBALS['jul_base_dir']}/images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
			$bar="<br><img src={$GLOBALS['jul_base_dir']}/images/$numdir"."barleft.gif height=8>$baron$baroff<img src={$GLOBALS['jul_base_dir']}/images/$numdir".'barright.gif height=8>';

		} else {
			$level		= "<img src={$GLOBALS['jul_base_dir']}/images/$numdir"."level.gif width=36 height=8><img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$lvl&l=3&f=$numfil height=8>";
			$experience	= "<img src={$GLOBALS['jul_base_dir']}/images/$numdir"."exp.gif width=20 height=8><img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$exp&l=5&f=$numfil height=8><br><img src={$GLOBALS['jul_base_dir']}/images/$numdir"."fornext.gif width=44 height=8><img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$expleft&l=2&f=$numfil height=8>";
			$poststext	= "<img src={$GLOBALS['jul_base_dir']}/images/_.gif height=2><br><img src={$GLOBALS['jul_base_dir']}/images/$numdir"."posts.gif width=28 height=8>";
			$postnum	= "<img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$post[num]/&l=5&f=$numfil height=8>";
			$posttotal	= "<img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$post[posts]&f=$numfil".($post['num']?'':'&l=4')." height=8>";
			$totalwidth	= 56;
			$barwidth	= $totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);

			if($barwidth<1) $barwidth=0;

			if($barwidth>0) $baron="<img src={$GLOBALS['jul_base_dir']}/images/$numdir"."bar-on.gif width=$barwidth height=8>";

			if($barwidth<$totalwidth) $baroff="<img src={$GLOBALS['jul_base_dir']}/images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
			$bar="<br><img src={$GLOBALS['jul_base_dir']}/images/$numdir"."barleft.gif width=2 height=8>$baron$baroff<img src={$GLOBALS['jul_base_dir']}/images/$numdir".'barright.gif width=2 height=8>';
		}


		if(!$post['num']){
			$postnum	= '';

			if($postlayout==1) $posttotal="<img src={$GLOBALS['jul_views_path']}/numgfx.php?n=$post[posts]&f=$numfil&l=4 height=8>";
		}


		$reinf=syndrome(filter_int($post['act']));

		if ($post['lastposttime']) {
			$sincelastpost	= 'Since last post: '.timeunits(ctime()-$post['lastposttime']);
		}
		$lastactivity	= 'Last activity: '.timeunits(ctime()-$post['lastactivity']);
		$since			= 'Since: '.@date($dateshort,$post['regdate']+$tzoff);
		$postdate		= date($dateformat,$post['date']+$tzoff);

		$threadlink		= "";
		if (filter_string($set['threadlink'])) {
			$threadlink	= ", in $set[threadlink]";
		}

		$post['edited']	= filter_string($post['edited']);
		if ($post['edited']) {
			//		.="<hr>$smallfont$post[edited]";
		}

		$sidebars	= array(1, 3, 19, 89, 387, 45, 92, 47);

		$sidebars	= array(19, 89, 387, 45, 92, 47, 1420, 1090, 2100, 2069);

		// Large block of user-specific hacks follows //


	if (false && $post['uid'] == 1 && !$x_hacks['host'] && true) {

		global $numdir;
		$numdir_	= $numdir;
		$numdir		= "num3/";

		if ($post['num']) {
			$numtext	= generatenumbergfx($post['num'], 1, true) ."<br>". generatenumbergfx($post['posts']);
		} else {
			$numtext	= generatenumbergfx($post['posts'], 1, true);
		}
		$numdir		= $numdir_;

	return "
	$tblstart
	$set[tdbg] rowspan=2 style='padding: 5px 1px 5px 1px;'>
	  <center>$set[userlink]$smallfont<br>
	  $set[userrank]
		$reinf
		<br>
		<br>$set[userpic]
		<br><br>$numtext</center>
	  <br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
	</td>
	$set[tdbg] height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
	$tblend";
	}

	// Inu's sidebar
	// (moved up here for to display for everyone during doomclock mode!)
	if ($post['uid'] == "2100" && !$x_hacks['host']) {
		$posttable = "<table style=\"border:none;border-spacing:0px;\">";
		// doomclock
		if (($doomclock_time = mktime(12,20,0,4,20,2014) - cmicrotime()) >= 0) {
			$doomclock_secs = (int)($doomclock_time % 60);
			$doomclock_mins = (int)(($doomclock_time % 3600) / 60);
			$doomclock_hrs  = (int)($doomclock_time / 3600);
			$doomclock_str  = sprintf(" %d=%02d=%02d", $doomclock_hrs, $doomclock_mins, $doomclock_secs);
			$doomclock_desc = "{$doomclock_hrs} hours, {$doomclock_mins} minutes, {$doomclock_secs} seconds";
			$posttable .= "<tr><td><img src=\"images/inu/cifont/d.gif\" title=\"Doomsday\"></td><td align='right'>";
			$posttable .= inu_hexclock($doomclock_desc, $doomclock_time);
			$posttable .= "</td><td align='right'><img src=\"/images/inu/7sd.php?s=>FFF{$doomclock_str}\"></td></tr>";
		}
		if ($post['num']) {
			$posttable .= "<tr><td><img src=\"images/inu/cifont/p.gif\" title=\"Post Number\"></td><td>";
			$posttable .= inu_binaryposts($post['num'], "images/dot3.gif", "images/dot1.gif", $post['posts']);
			$posttable .= "</td><td align='right'><img src=\"/images/inu/7sd.php?s=".sprintf("%4d",$post['num'])."\"></td></tr>";
		}
		$posttable .= "<tr><td><img src=\"images/inu/cifont/t.gif\" title=\"Total Posts\"></td><td>";
		$posttable .= inu_binaryposts($post['posts'], "images/dot5.gif", "images/dot1.gif");
		$posttable .= "</td><td align='right'><img src=\"/images/inu/7sd.php?s=>F90".sprintf("%4d",$post['posts'])."\"></td></tr></table>";

    /*
		$lp = ((!$post['lastposttime']) ? "" : 'Last posted >fff'.timeunits(ctime()-$post['lastposttime']).' >0f0ago');
		$la ='Last active >fff'.timeunits(ctime()-$post['lastactivity']).' >0f0ago';
		$jd ='Joined >f11'.@date("m.d.Y",$post['regdate']+$tzoff);

		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set['tdbg']) ." rowspan=2 align=center style=\"font-size: 12px;\">
				".inu_hexclock()."
				 <a name=$post[id]></a><a href=\"{$GLOBALS['jul_views_path']}/profile.php?id=2100\"><img src=\"/images/inu/7sd.php?s=- >EC1Inuyasha>0f0 -\"></a>
				$smallfont
				<br><marquee scrolldelay=250 scrollamount=30 width=30 height=8 behavior=alternate><img src=\"/images/inu/7sd.php?s=>f0012=00\"><img src=\"/images/inu/7sd.php?s=>f00  =%20%20\"></marquee>
				<br>$reinf
				$set[userpic]
				<br>
				<br>". ($hacks['noposts'] ? "" : "$posttable") ."
				<br>
				<br><img src=\"/images/inu/7sd.php?s=$jd\">
				<br>
				<br><img src=\"/images/inu/7sd.php?s=$lp\">
				<br><img src=\"/images/inu/7sd.php?s=$la\"></font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
		$set[tdbg] height=1 width=100%>
			<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
		$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
		$tblend"; */

		// non-image old version
		$lp = ((!$post['lastposttime']) ? "" : 'Last posted '.timeunits(ctime()-$post['lastposttime']).' ago');
		$la ='Last active '.timeunits(ctime()-$post['lastactivity']).' ago';
		$jd ='Joined '.@date("m.d.Y",$post['regdate']+$tzoff);

		$dstyle = '';

		// [D]Inuyasha
		if ($post['moodid'] == 5) {
			$post['headtext'] = str_replace(
				array('class="inu-bg"','class="inu-tx"'),
				array('class="inu-dbg"','class="inu-dtx"'), $post['headtext']);
			$set['userlink'] =" <a name={$post['id']}></a><a class=\"url2100\" href=\"{$GLOBALS['jul_views_path']}/profile.php?id=2100\"><font color=\"FF0202\">[D]Inuyasha</font></a>";
			$set['userrank'] = 'Now you\'ve done it...!';
			$set['userpic'] = '<img src="http://inuyasha.rustedlogic.net/personal/moodav/5.png">';
			$dstyle = ' style="color:#b671e8;background:black;"';
		}

		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set['tdbg']) ."{$dstyle} rowspan=2 align=center style=\"font-size: 12px;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] ."<br>" : "") ."
				$reinf
				<br>
				$set[userpic]
				<br>
				<br>". ($hacks['noposts'] ? "" : "$posttable") ."
				<br>
				<br>$jd
				<br>
				<br>$lp
				<br>$la</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
		$set[tdbg]{$dstyle} height=1 width=100%>
			<table cellspacing=0 cellpadding=2 width=100% class=fonts{$dstyle}>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
		$set[tdbg]{$dstyle} height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
		$tblend";
	}
	// End Inu's sidebar

	if (($post['uid'] == 18) && !$x_hacks['host'] && $x_hacks['mmdeath'] >= 0 && !$_GET['test2']) {
	return "
	<table style=\"background: #f00 url('{$GLOBALS['jul_views_path']}/numgfx/red.gif');\" cellpadding=3 cellspacing=1>
	$set[tdbg] style='background: #000;' rowspan=2>
		<br><center class='stupiddoomtimerhack'><img src='{$GLOBALS['jul_views_path']}/numgfx.php?f=numdeath&n=". $x_hacks['mmdeath'] ."' height=32 style=\"background: #f00 url('{$GLOBALS['jul_views_path']}/numgfx/red.gif');\" title=\"Doom.\"></center>
		<br>
	  <center>$set[userlink]$smallfont<br>
		<br>
		<br>$set[userpic]
		</center>

		<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=194 height=1>
	</td>
	$set[tdbg] style='background: #000;'height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] style='background: #000;' height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
	$tblend";
	}

  // Default layout
	if (!(in_array($post['uid'], $sidebars) && !$x_hacks['host']) || $loguser['viewsig'] == 0) {
	return "
	<div style='position:relative'>
	$tblstart
	$set[tdbg] rowspan=2>
	  $set[userlink]$smallfont<br>
	  $set[userrank]$reinf<br>
        $level$bar<br>
	  $set[userpic]<br>
	  ". (filter_bool($hacks['noposts']) ? "" : "$poststext$postnum$posttotal<br>") ."
	  $experience<br><br>
	  $since<br>
	  ". (isset($set['pronouns']) ? "<br>".$set['pronouns'] : "")."
	  ". (isset($set['location']) ? "<br>".$set['location'] : "")."
	  <br>
	  <br>
	  $sincelastpost<br>$lastactivity<br>
	  </font>
	  <br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
	</td>
	$set[tdbg] height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
	$tblend
	</div>";
	}

  elseif ($post['uid'] == "1" && !$x_hacks['host']) {
		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post['lastactivity']) ."<font color=#bbbbbb> ago";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}

		//". str_replace('valign=top', 'valign=top', $set[tdbg]) ."
		return "<table width=100% cellpadding=0 cellspacing=0 style=\"background: #004c5a; background-position: top right; background-repeat: repeat-x; border: 1px solid #000;\">
			<tr>
			<td rowspan=2 valign=top align=center style=\"font-size: 12px; color: #fff; font-family: Verdana, sans-serif; border-right: 3px double #000; background: #004c5a;\">
				&mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] ."<br>" : "") ."
				<br>$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=#cccccc>" : "<br>Post$postss $postnum<font color=#cccccc>$posttotal") ."
				<br>$lastactivity</font>
				". (false ? "<br><a href={$GLOBALS['jul_views_path']}/sendprivate.php?uid=1>PM</a> - <a href=rateuser.php?id=1>Rate</a>" : "") ."
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			<td height=1 width=100% style=\"font-size: 12px; color: #ddd; font-family: Verdana, sans-serif; background: #004c5a; border-bottom: 1px solid #000;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			<td valign='top' id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";
	} elseif ($post['uid'] == "3" && !$x_hacks['host']) {
		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) .'<font color=#bb0000> ago';
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: #000; font-size: 12px; color: #f00; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=#bb0000>" : "<br>Post$postss $postnum<font color=#bb0000>$posttotal") ."
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: #000000; font-size: 12px; color: #ff0000; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";

// ************************************************************
// SYAORAN COLIN
// ************************************************************
	} elseif ($post['uid'] == "45" && !$x_hacks['host']) {

		$fcol1			= "#204080";
		$fcol2			= "#3070a0";
		$fcol3			= "#f0f8ff";


		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$reinf
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=$fcol2>" : "<br>Post$postss $postnum<font color=$fcol2>$posttotal") ."
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style='color: $fcol1;'>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";


	} elseif ($post['uid'] == "47" && !$x_hacks['host']) {
		$fcol1			= "#204080";
		$fcol2			= "#3070a0";
		$fcol3			= "#ffffff";
		$fcol1			= "#9966bb";
		$fcol2			= "#ccaadd";
		$fcol3			= "#000000";

		if ($post['posts'] >= 20000) {
			$fcol1			= "#bbaadd";
			$fcol2			= "#eebbff";
			$fcol3			= "#000000";
		}

		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3". ($post['posts'] >= 20000 ? " url('http://www.ffalexandria.com/orlandu/anya/side_bg.jpg'); background-position:bottom left" : "") ."; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				<br>$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$reinf
				<br><br>$set[userpic]
				<br><br>Post$postss $postnum$posttotal
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style='color: $fcol1;'>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";

		return "
		$tblstart
		$set[tdbg] rowspan=2 style='padding: 5px;'>
		  <img src='images/smilies/bigeyes.gif' title='o_O' align='absmiddle'> $set[userlink]$smallfont<br>
		  ". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$reinf
				<br>
				$set[userpic]
				<br>
				<br>Post$postss $postnum$posttotal
		  </center><br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=190 height=1>
		</td>
		$set[tdbg] height=1 width=100%>
		  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
			<td>Posted on $postdate$threadlink$post[edited]</td>
			<td width=255><nobr>$quote$edit$ip
		  </table><tr>
		$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
		$tblend";

// ************************************************************
// SAKURA HIRYUU
// ************************************************************
	} elseif ($post['uid'] == "4xxxxxxxxxxx7" && !$x_hacks['host']) {

		$fcol1			= "#802040";
		$fcol2			= "#a07030";
		$fcol3			= "#fff0f8";


		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$reinf
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=$fcol2>" : "<br>Post$postss $postnum<font color=$fcol2>$posttotal") ."
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style=\"color: $fcol1;\">Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";


// ************************************************************
// REAL HIRYUU
// ************************************************************
	} elseif ($post['uid'] == "92" && !$x_hacks['host']) {
		$fcol1			= "#e2bbff";
		$fcol2			= "#bb70dd";
		$fcol3			= "#220033";

		$fcol1			= "#ffeeaa";
		$fcol2			= "#998844";
		$fcol3			= "#221100";

		$fcol1			= "#ffaaaa";
		$fcol2			= "#ff7777";
		$fcol3			= "#661111";

		$fcol1			= "#ffffff";
		$fcol2			= "#eeeeee";
		$fcol3			= "#000000";


		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] : "") ."
				$reinf
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=$fcol2>" : "<br>Post$postss $postnum<font color=$fcol2>$posttotal") ."
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";

	} elseif ($post['uid'] == "19" && !$x_hacks['host']) {
		$fcol1			= "#bbbbeb";
		$fcol2			= "#8888a8";
		$fcol3			= "#080818 url('http://bloodstar.rustedlogic.net/layout/background.png')";
		$lastactivity	= 'Active </font>'. timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$joindate		= 'Joined </font>'. date($dateshort,$post[regdate]+$tzoff) ."<font color=$fcol2>";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] ."<br>" : "") ."
				$reinf
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=$fcol2>" : "<br>Post$postss $postnum<font color=$fcol2>$posttotal") ."
				<br>
				<br>$joindate
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";

	} elseif ($post['uid'] == "4" && !$x_hacks['host']) {
		$fcol1			= "#9999cc";
		$fcol2			= "#7777aa";
		$fcol3			= "#000011";
		$lastactivity	= 'Active </font>'. timeunits(ctime()-$post[lastactivity]) ."<font color=$fcol2> ago";
		$joindate		= 'Joined </font>'. date($dateshort,$post[regdate]+$tzoff) ."<font color=$fcol2>";
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				 &mdash; $set[userlink] &mdash;
				$smallfont
				". ($set['userrank'] ? "<br>". $set['userrank'] ."<br>" : "") ."
				$set[userpic]
				<br>". ($hacks['noposts'] ? "<font color=$fcol2>" : "<br>Post$postss $postnum<font color=$fcol2>$posttotal") ."
				<br>
				<br>$joindate
				<br>$lastactivity</font>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";

	} elseif ($post['uid'] == "387" && !$x_hacks['host']) {
		if (!$x_hacks['rpgstats'][$post['uid']]) {
			$css	= "<style> .a1{ height:100%; min-height: 286px; background:#000 url(http://acmlm.rustedlogic.net/etc/nismilly/bg.jpg) 50% 0% no-repeat; } div.a2{ height:100%; min-height: 286px; background:url(http://acmlm.rustedlogic.net/etc/nismilly/map.png) 50% 226px no-repeat; font:9px tahoma; color:#FD4; text-align:center; line-height:19px; } div.a2 img{ margin-top:-5px; border:0px; } div.a2 span{ color:#DEF; } </style>";
			$x_hacks['rpgstats'][$post['uid']] == "lol";
		}
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s:";
		}
		return "$tblstart
			". str_replace('\' valign=top', ' a1\' valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				$css
				<div class=a2>
					Post$postss <span><b>$postnum</b></span><span><b>$posttotal</b></span> (<span>". timeunits(ctime()-$post[lastposttime]) ."</span>),
					online <span>". timeunits(ctime()-$post[lastactivity]) ."</span> ago
					<a href=//jul.rustedlogic.net/profile.php?id=387>
			". (strpos($_SERVER['USER_AGENT'], "MSIE 6.0") ? "<img src=_.png style=filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=http://acmlm.rustedlogic.net/etc/nismilly/stat.php)>" : "<img src=http://acmlm.rustedlogic.net/etc/nismilly/stat.php>") ."
					</a>
					<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
				</div>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";


	} elseif ($post['uid'] == "89" && !$x_hacks['host']) {
		$fcol1			= "#bbbbbb";
		$fcol2			= "#555555";
		$fcol3			= "#181818";

		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 14px; color: $fcol1; font-family: Verdana, sans-serif; padding-top: .5em;\">
				$set[userlink]
				<br><span style=\"letter-spacing: 0px; color: $fcol2; font-size: 10px;\">Collection of nobodies</span>
				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=200>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"background: $fcol3; padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";


	} elseif (($post['uid'] == "16" || $post['uid'] == "5") && !$x_hacks['host']) {

		// top bg #614735
		// mid-bg #e1cfb6
		// darker #d0bca4

		if (!function_exists("basestat")) {
			 require_once 'lib/rpg.php';
		}

		if (!$x_hacks['rpgstats'][$post['uid']]) {

			$eq	= array(
				'1' => "<center style=\"text-align: center; color: #b09080;\">(Weapon)</center>",
				'2' => "<center style=\"text-align: center; color: #b09080;\">(Armor)</center>",
				'3' => "<center style=\"text-align: center; color: #b09080;\">(Shield)</center>",
				'4' => "<center style=\"text-align: center; color: #b09080;\">(Helm)</center>",
				'5' => "<center style=\"text-align: center; color: #b09080;\">(Shoes)</center>",
				'6' => "<center style=\"text-align: center; color: #b09080;\">(Acc.)</center>",
			);
			$user=mysql_fetch_array(mysql_query("SELECT name,posts,regdate,users_rpg.* FROM users,users_rpg WHERE id='". $post['uid'] ."' AND uid=id"));
			$d=(ctime()-$user[regdate])/86400;
			$eqitems=mysql_query("SELECT * FROM items WHERE id=$user[eq1] OR id=$user[eq2] OR id=$user[eq3] OR id=$user[eq4] OR id=$user[eq5] OR id=$user[eq6]") or print mysql_error();
			while($item=mysql_fetch_array($eqitems)) {
				$items[$item[id]]=$item;
				$eq[$item['cat']] = $item['name'];
			}
			if($ct){
			$GPdif=floor($items[$user['eq'.$ct]][coins]*0.6)-$items[$it][coins];
			$user['eq'.$ct]=$it;
			}
			$st=getstats($user,$items);
			$st[GP]+=$GPdif;
			if($st[lvl]>0) $pct=1-calcexpleft($st[exp])/totallvlexp($st[lvl]);
			$st['expn']	= calcexpleft($st[exp]);
			$st['eq']	= $eq;

			$x_hacks['rpgstats'][$post['uid']]	= $st;
		} else {
			$st	= $x_hacks['rpgstats'][$post['uid']];
		}

		$lastactivity	= 'Active '. timeunits(ctime()-$post[lastactivity]) ." ago";
		$joindate		= 'Joined '. date($dateshort,$post[regdate]+$tzoff);
		$postnum		= ($post['num']) ."/";
		$posttotal		= $post['posts'];
		if(!$post['num']) {
			$postnum	= '';
			$postss		= "s";
		}

		if (!$set['picture']) $set['picture']	= "images/_.gif";

		if ($_GET['z']) {
			print_r($st['eq']);
		}


		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 width=200>
				 <table style=\"font-family: Tahoma; font-size: 12px; color: #000; background: #e1cfb6;\" width=100% cellspacing=0>
					<tr>
						<td style=\"background: #614735; text-align: left; padding: 2px 0px 2px 15px; font-size: 14px; letter-spacing: 1px; border: 2px outset #614735;\" colspan=4>$set[userlink]</td>
					</tr>
					<tr>
						<td style=\"border: 2px outset #614735;\" colspan=4>
							 <table width=100% cellspacing=0 cellpadding=0 style=\"color: #000; font-size: 12px;\">
								<tr><td rowspan=5 width=0 style=\"padding: 0 3px 0 0;\"><img src=\"". $set['picture'] ."\" width=80 height=80></td>
									<td width=100% colspan=4 style=\"font-size: 14px; letter-spacing: 1px; padding: 0 0 7px 0;\">". $set['userrank'] ."</td></tr>
								<tr style=\"font-weight: bold;\"><td colspan=2 width=50%>Lv</td><td style=\"text-align: right;\" colspan=2 width=50%>". $st['lvl'] ."</td></tr>
								<tr style=\"font-weight: bold;\"><td colspan=2 width=50% style=\"font-variant: small-caps;\">Post$postss</td><td style=\"text-align: right;\" colspan=2 width=50%>$postnum$posttotal</tr>
								<tr style=\"font-weight: bold;\"><td colspan=2 width=50% style=\"font-variant: small-caps;\">Counter</td><td style=\"text-align: right;\" colspan=2 width=50%>2</td></tr>
								<tr style=\"font-weight: bold;\"><td>Mv</td><td style=\"text-align: right;\">7</td><td style=\"padding: 0 0 0 5px;\">Jm</td><td style=\"text-align: right;\">26</td></tr>
							</table>

							<table width=100% cellspacing=0 cellpadding=0 style=\"color: #000; font-size: 12px; font-weight: bold;\">
								<tr style=\"background: #d0bca4;\"><td style=\"font-variant: small-caps;\">Hp</td><td style=\"text-align: right;\" colspan=3>". $st['HP'] ."/". $st['HP'] ."</td></tr>
								<tr                               ><td style=\"font-variant: small-caps;\">Sp</td><td style=\"text-align: right;\" colspan=3>". $st['MP'] ."/". $st['MP'] ."</td></tr>
								<tr style=\"background: #d0bca4;\">
									<td style=\"font-variant: small-caps;\">Atk</td><td style=\"text-align: right; padding: 0 4px 0 0;\">". $st['Atk'] ."</td>
									<td style=\"font-variant: small-caps; padding: 0 0 0 4px;\">Int</td><td style=\"text-align: right;\">". $st['Int'] ."</td></tr>
								<tr                               >
									<td style=\"font-variant: small-caps;\">Def</td><td style=\"text-align: right; padding: 0 4px 0 0;\">". $st['Def'] ."</td>
									<td style=\"font-variant: small-caps; padding: 0 0 0 4px;\">Spd</td><td style=\"text-align: right;\">". $st['Spd'] ."</td></tr>
								<tr style=\"background: #d0bca4;\">
									<td style=\"font-variant: small-caps;\">Hit</td><td style=\"text-align: right; padding: 0 4px 0 0;\">". $st['Dex'] ."</td>
									<td style=\"font-variant: small-caps; padding: 0 0 0 4px;\">Res</td><td style=\"text-align: right;\">". $st['MDf'] ."</td></tr>
								<tr                               ><td style=\"font-variant: small-caps;\">Exp</td><td style=\"text-align: right;\" colspan=3>". $st['exp'] ."</td></tr>
								<tr style=\"background: #d0bca4;\"><td style=\"font-variant: small-caps;\">Next</td><td style=\"text-align: right;\" colspan=3>". $st['expn'] ."</td></tr>
								<tr                               ><td colspan=4><img src=\"images/_.gif\" height=4 width=1></td></tr>
								<tr                               ><td colspan=4>". $st['eq'][1] ."</td></tr>
								<tr                               ><td colspan=4>". $st['eq'][2] ."</td></tr>
								<tr                               ><td colspan=4>". $st['eq'][3] ."</td></tr>
								<tr                               ><td colspan=4>". $st['eq'][4] ."</td></tr>
								<tr                               ><td colspan=4>". $st['eq'][5] ."</td></tr>
								<tr                               ><td colspan=4>". $st['eq'][6] ."</td></tr>
							</table>
						</td>
					</tr>
				</table>

				<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>$tblend
		";
/*
<!--
				$smallfont
				<br>$joindate
				<br>$lastactivity</font>
*/

	}

	// BlackRose/Lain's sidebar
	elseif ($post['uid'] == 1090 && !$x_hacks['host']) {
		$brltype = "catgirlredux2011";

		$brsidebar = "
			<link rel=\"stylesheet\" type=\"text/css\" href=\"http://lain.rustedlogic.net/layouts/css/_br_sidebar.css\" />
			$tblstart
			$set[tdbg] rowspan=2>
				<div class=\"brsidebar lain-sidebar-$brltype\">
				<div class=\"bruserlink\">&mdash; $set[userlink] &mdash;</div>
				<div class=\"bruserrank\">$set[userrank]</div>" .
				(!empty($reinf) ? "<div class=\"brsyndrome\">$reinf</div>" : "") . "
				<div class=\"bruserpic\">$set[userpic]</div>
				</div>
			</td>
			$set[tdbg] height=1 width=100%>
			<table cellspacing=0 cellpadding=2 width=100% class=fonts>
			<td>Posted on $postdate$threadlink$post[edited]</td>
			<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
			$tblend";

		return $brsidebar;
	}

  // Non-defined / Blank
  // (Adelheid uses this)
	else {
	return "
	$tblstart
	$set[tdbg] rowspan=2>
	  $set[userlink]$smallfont<br>
	  $set[userrank]$reinf<br>
	  <br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=200 height=1>
	</td>
	$set[tdbg] height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]</td>
	$tblend";
	}

  }


  function kittynekomeowmeow($p) {
		global $loguser;
		$kitty	= array("meow", "mrew", "mew", "mrow", "mrrrr", "mrowl", "rrrr", "mrrrrow", "mreeeew",);
		$punc	= array(",", ".", "!", "?");
		$p		= preg_replace('/\s\s+/', ' ', $p);

		$c		= substr_count($p, " ");
		for ($i = 0; $i < $c; $i++) {
			$mi	= array_rand($kitty);
			$m	.= ($m ? " " : "") . $kitty[$mi];
			$l	= false;
			if (mt_rand(0,7) == 7) {
				$pi	= array_rand($punc);
				$m	.= $punc[$pi];
				$l	= true;
			}
		}

		if ($l != true) {
			$pi	= array_rand($punc);
			$m	.= $punc[$pi];
		}

		// if ($loguser['id'] == 1)
		return $m ." :3";
  }


	//For Inu's layout
	function inu_binaryposts($n, $timg, $fimg, $min = 0) {
		$tx = "<span title=\"$n\">";
		if ($n > $min) $min = $n;
		for ($i = 1; $i <= $min; $i<<=1)
			$bits[] = "<img src=\"" . (($n & $i) ? $timg : $fimg) . "\">";
		$tx .= implode("", array_reverse($bits));
		$tx .= "</span>";
		return $tx;
	}

	function inu_hexclock($n, $time) {
		$tx = "<span title=\"$n\">";
		$time = (($time*65536) / 86400);
		$hex = str_split(dechex($time));
		foreach ($hex as $letter)
			$tx .= "<img src=\"images/inu/cifont/{$letter}.gif\">";
		$tx .= "</span>";
		return $tx;
	}
	//End random shit for Inu's layout

?>
