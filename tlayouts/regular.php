<?php
  function userfields(){return 'posts,sex,powerlevel,picture,moodurl,title,useranks,location,lastposttime,lastactivity,imood';}

  function postcode($post,$set){
	if ($_GET['uhoh']) die($post['id']);
    global $tzoff, $smallfont, $ip, $quote, $edit, $dateshort, $dateformat, $tlayout, $textcolor, $numdir, $numfil, $tblstart, $hacks, $x_hacks, $loguser;
	$tblend	= "</table>";
	$exp=calcexp($post[posts],(ctime()-$post[regdate])/86400);
    $lvl=calclvl($exp);
    $expleft=calcexpleft($exp);
    if($tlayout==1){
	$level="Level: $lvl";
	$poststext="Posts: ";
	$postnum="$post[num]/";
	$posttotal=$post[posts];
	$experience="EXP: $exp<br>For next: $expleft";
	$totalwidth=96;
	$barwidth=$totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);
	if($barwidth<1) $barwidth=0;
	if($barwidth>0) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
	$bar="<br><img src=images/$numdir"."barleft.gif height=8>$baron$baroff<img src=images/$numdir".'barright.gif height=8>';
    }else{
	$level="<img src=images/$numdir"."level.gif width=36 height=8><img src=numgfx.php?n=$lvl&l=3&f=$numfil height=8>";
	$experience="<img src=images/$numdir"."exp.gif width=20 height=8><img src=numgfx.php?n=$exp&l=5&f=$numfil height=8><br><img src=images/$numdir"."fornext.gif width=44 height=8><img src=numgfx.php?n=$expleft&l=2&f=$numfil height=8>";
	$poststext="<img src=images/_.gif height=2><br><img src=images/$numdir"."posts.gif width=28 height=8>";
	$postnum="<img src=numgfx.php?n=$post[num]/&l=5&f=$numfil height=8>";
	$posttotal="<img src=numgfx.php?n=$post[posts]&f=$numfil".($post[num]?'':'&l=4')." height=8>";
	$totalwidth=56;
	$barwidth=$totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);
	if($barwidth<1) $barwidth=0;
	if($barwidth>0) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
		$bar="<br><img src=images/$numdir"."barleft.gif width=2 height=8>$baron$baroff<img src=images/$numdir".'barright.gif width=2 height=8>';
	}
	if(!$post[num]){
		$postnum='';
		if($postlayout==1) $posttotal="<img src=numgfx.php?n=$post[posts]&f=$numfil&l=4 height=8>";
	}
    if($post[icq]) $icqicon="<a href=http://wwp.icq.com/$post[icq]#pager><img src=http://wwp.icq.com/scripts/online.dll?icq=$post[icq]&img=5 border=0 width=13 height=13 align=absbottom></a>";
    if($post[imood]) $imood="<img src=http://www.imood.com/query.cgi?email=$post[imood]&type=1&fg=$textcolor&trans=1 height=15 align=absbottom>";
    $reinf=syndrome($post[act]);
    if ($post[lastposttime]) $sincelastpost='Since last post: '.timeunits(ctime()-$post[lastposttime]);
    $lastactivity='Last activity: '.timeunits(ctime()-$post[lastactivity]);
    $since='Since: '.@date($dateshort,$post[regdate]+$tzoff);
    $postdate=date($dateformat,$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=", in $set[threadlink]";

	if($post[edited]){
//		$set[edited].="<hr>$smallfont$post[edited]";
	}
	
	$sidebars	= array(1, 3, 19, 89, 387, 45, 92, 47);

	$sidebars	= array(1, 19, 89, 387, 45, 92, 47, 1420);

#	$edit		.= " | <a href=\"#". $post['id'] ."\" %BZZZ%". $post['id'] .")\"><img src='http://xkeeper.net/img/soccerball.png' align='absmiddle' title='guess what this does'></a>";

//	global $loguser;
//	if ($loguser['id'] == 1 || $_GET['stupid']) {
//		$sidebars[]	= 16;
//	}

/*
	if ($post['noob']) {
		$noobpos				= floor(strlen($post['name']) * 2.5);
//		$set['userlink']	= "<div style=\"display: inline; position: relative; top: 0; left: 0;\"><img src=\"xkeeper/img/noobsticker2-". mt_rand(1,6) .".png\" style=\"position: absolute; top: -3px; left: ". $noobpos ."px;\" title=\"n00b\">". $set['userlink'] ."</div>";

		$set['userlink']	= $set['userlink'] ."<br><img src=\"xkeeper/img/noobsticker2-". mt_rand(1,6) .".png\" style=\"position: relative; left: ". mt_rand(4, 23) ."px; bottom: ". mt_rand(14, 22) ."px;\" title=\"n00b\">";
	}

	if ($loguser['powerlevel'] >= 1 && $post['date'] && $post['num']) {
		$edit		.= " | <a href=\"editpost.php?id=". $post['id'] ."&action=noob\">". ($post['noob'] ? "de-" : "") ."n00b</a>";
	}

	if ($post[uid] == 902 && !$x_hacks['host']) {
		$post[signtext]	.= "<div style='width: 100%; text-align: right;'><a href='sendprivate.php?userid=902&subject=Wow, you are as dumb as a brick.'><img src='http://xkeeper.net/img/sendpm.png' style='margin-bottom: -193px; position: relative; top: -100px; right: -10px;'></a></div>";
	}
*/

	if ($post[uid] == 1 && !$x_hacks['host'] && true) {
	return "
	$tblstart
	$set[tdbg] rowspan=2 style='padding: 5px;'>
	  <center>$set[userlink]$smallfont<br>
	  $set[userrank]
		$reinf
		<br>
		<br>$set[userpic]</center>
	  <br><img src=images/_.gif width=190 height=1>
	</td>
	$set[tdbg] height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>
	$tblend";
	}

	if (($post[uid] == 18) && !$x_hacks['host'] && $x_hacks['mmdeath'] >= 0 && !$_GET['test2']) {
	return "
	<table style=\"background: #f00 url('numgfx/red.gif');\" cellpadding=3 cellspacing=1>
	$set[tdbg] style='background: #000;' rowspan=2>
		<br><center class='stupiddoomtimerhack'><img src='numgfx.php?f=numdeath&n=". $x_hacks['mmdeath'] ."' height=32 style=\"background: #f00 url('numgfx/red.gif');\" title=\"Doom.\"></center>
		<br>
	  <center>$set[userlink]$smallfont<br>
		<br>
		<br>$set[userpic]
		</center>

		<br><img src=images/_.gif width=194 height=1>
	</td>
	$set[tdbg] style='background: #000;'height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] style='background: #000;' height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>
	$tblend";
	}

	if (!(in_array($post[uid], $sidebars) && !$x_hacks['host']) || $loguser['viewsig'] == 0) {
	return "
	$tblstart
	$set[tdbg] rowspan=2>
	  $set[userlink]$smallfont<br>
	  $set[userrank]$reinf<br>
        $level$bar<br>
	  $set[userpic]<br>
	  ". ($hacks['noposts'] ? "" : "$poststext$postnum$posttotal<br>") ."
	  $experience<br><br>
	  $since<br>".str_ireplace("&lt;br&gt;", "<br>", substr(htmlspecialchars($set[location]),10))."<br><br>
	  $sincelastpost<br>$lastactivity<br>
	  $icqicon$imood</font>
	  <br><img src=images/_.gif width=200 height=1>
	</td>
	$set[tdbg] height=1 width=100%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink$post[edited]</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>
	$tblend";
	} elseif ($post[uid] == "1" && !$x_hacks['host']) {
		$lastactivity	= 'Active </font>' .timeunits(ctime()-$post[lastactivity]) ."<font color=#bbbbbb> ago";
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
				". (false ? "<br><a href=sendprivate.php?uid=1>PM</a> - <a href=rateuser.php?id=1>Rate</a>" : "") ."
				<br><img src=images/_.gif width=200 height=1>
			</td>
			<td height=1 width=100% style=\"font-size: 12px; color: #ddd; font-family: Verdana, sans-serif; background: #004c5a; border-bottom: 1px solid #000;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			<td valign='top' id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";
	} elseif ($post[uid] == "3" && !$x_hacks['host']) {
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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: #000000; font-size: 12px; color: #ff0000; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";

// ************************************************************
// SYAORAN COLIN
// ************************************************************
	} elseif ($post[uid] == "45" && !$x_hacks['host']) {

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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style='color: $fcol1;'>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";


	} elseif ($post[uid] == "47" && !$x_hacks['host']) {
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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style='color: $fcol1;'>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
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
		  </center><br><img src=images/_.gif width=190 height=1>
		</td>
		$set[tdbg] height=1 width=100%>
		  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
			<td>Posted on $postdate$threadlink$post[edited]</td>
			<td width=255><nobr>$quote$edit$ip
		  </table><tr>
		$set[tdbg] height=220 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>
		$tblend";

// ************************************************************
// SAKURA HIRYUU
// ************************************************************
	} elseif ($post[uid] == "4xxxxxxxxxxx7" && !$x_hacks['host']) {

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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td style=\"color: $fcol1;\">Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";


// ************************************************************
// REAL HIRYUU
// ************************************************************
	} elseif ($post[uid] == "92" && !$x_hacks['host']) {
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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";

	} elseif ($post[uid] == "19" && !$x_hacks['host']) {
		$fcol1			= "#bbbbeb";
		$fcol2			= "#8888a8";
		$fcol3			= "#080818 url('http://bloodstar.exaltedlegion.com/layout/newblue/Sidebar-LeavesB.png')";
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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";

	} elseif ($post[uid] == "4" && !$x_hacks['host']) {
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
				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";

	} elseif ($post[uid] == "387" && !$x_hacks['host']) {
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
					<br><img src=images/_.gif width=200 height=1>
				</div>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";


	} elseif ($post[uid] == "89" && !$x_hacks['host']) {
		$fcol1			= "#bbbbbb";
		$fcol2			= "#555555";
		$fcol3			= "#181818";

		return "$tblstart
			". str_replace('valign=top', 'valign=top', $set[tdbg]) ." rowspan=2 align=center style=\"background: $fcol3; font-size: 14px; color: $fcol1; font-family: Verdana, sans-serif; padding-top: .5em;\">
				$set[userlink]
				<br><span style=\"letter-spacing: 0px; color: $fcol2; font-size: 10px;\">Collection of nobodies</span>
				<br><img src=images/_.gif width=200 height=200>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"background: $fcol3; padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";

	
	} elseif (($post[uid] == "16" || $post[uid] == "5") && !$x_hacks['host']) {

		// top bg #614735
		// mid-bg #e1cfb6
		// darker #d0bca4

		if (!function_exists("basestat")) {
			 require 'lib/rpg.php';
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

				<br><img src=images/_.gif width=200 height=1>
			</td>
			$set[tdbg] height=1 width=100% style=\"background: $fcol3; font-size: 12px; color: $fcol1; font-family: Verdana, sans-serif;\">
				<table cellspacing=0 cellpadding=2 width=100% class=fonts>
				<td>Posted on $postdate$threadlink$post[edited]</td>
				<td width=255><nobr>$quote$edit$ip
			</table><tr>
			$set[tdbg] style=\"padding: 0;\" id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td>$tblend
		";
/*
<!--
				$smallfont
				<br>$joindate
				<br>$lastactivity</font>
*/
	
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




?>
