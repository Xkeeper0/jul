<?php

	$windowtitle	= "Active users";

	require 'lib/function.php';
	require 'lib/layout.php';

# for hacker-testing
//	$tid	= "9 GROUP BY users.id un/**/ion select 1,2,3,pass/**/word,5,6,7,86 from users--";

	$posttime	= intval($_GET['posttime']);

	if($posttime < 1) $posttime=86400;

	$query='SELECT users.id, regdate, name, minipic, sex, powerlevel, COUNT(*) AS cnt FROM users';
	$endp=' GROUP BY users.id ORDER BY cnt DESC';

	if ($type == 'thread')	{
		$posters	= $sql-> query("$query, threads WHERE threads.user=users.id$endp");
		$desc		= "Most active thread posters";
		$column		= "Threads";
		$column2	= "threads";

	} elseif ($type == 'pm') {
		$posters	= $sql-> query("$query, pmsgs WHERE pmsgs.userto=$loguserid AND pmsgs.userfrom=users.id$endp");
		$desc		= "PMs recieved from";
		$column		= "PMs";
		$column2	= "PMs";

	} elseif ($type == 'pms') {
		$posters	= $sql-> query("$query, pmsgs WHERE pmsgs.userfrom=$loguserid AND pmsgs.userto=users.id$endp");
		$desc		= "PMs sent to";
		$column		= "PMs";
		$column2	= "PMs";

	} else {
		$posters	= $sql-> query("$query, posts WHERE posts.user=users.id". ($tid ? " AND thread='$tid'" : '') ." AND posts.date> '". (ctime() - $posttime) ."'". $endp);
		$desc		= "Most active users during the last ". timeunits2($posttime);
		$column		= "Posts";
		$column2	= "posts";
	}

	$link='<a href=?posttime';
	print "
		$header
		$tblstart
		<td align=left width=50%>$smallfont
			Show most active posters in the:
			<br>$link=3600>last hour</a> - $link=86400>last day</a> - $link=604800>last week</a> - $link=2592000>last 30 days</a>
		</td><td width=50% align=right>$smallfont
		Most active users by:<br>
		<a href=\"?type=thread\">new threads</a> - <a href=\"?type=pms\">PMs sent by you</a> - <a href=\"?type=pm\">PMs sent to you</a></td></tr>
		$tblend
	"; 

	
	if ($loguser["powerlevel"] >= 1 && false) {
		// Xk will hate me for using subqueries.
			// No, I'll just hate you for adding this period
			// It's like a sore.
			// Also, uh, interesting I guess. The more you know.
		$pcounts        = $sql -> query("
			SELECT
				(SELECT sum(u.posts) FROM users AS u WHERE u.powerlevel >= 1) AS posts_staff,
				(SELECT sum(u.posts) FROM users AS u WHERE u.powerlevel = 0) AS posts_users,
				(SELECT sum(u.posts) FROM users AS u WHERE u.powerlevel = -1) AS posts_banned");

		$pcounts = $sql->fetch($pcounts);
		print "
		$tblstart
		<tr>$tccellh colspan=2>Staff vs. Normal User Posts</tr>
		<tr>$tccell1>$pcounts[posts_staff]</td>$tccell1>$pcounts[posts_users]</td></tr>
		<tr>$tccell2 colspan=2>The ratio for staff posts to normal user posts is ".round($pcounts["posts_staff"]/$pcounts["posts_users"],3).".</td></tr>
		<tr>$tccell2 colspan=2>Not included were the ".abs($pcounts[posts_banned])." posts shat out by a collective of morons. Depressing.</td></tr>
		$tblend
		<br>
		";
	}
	print "
		$tblstart
			<tr>$tccellc colspan=6><b>$desc</b></td></tr>
			<tr>
			$tccellh width=30>#</td>
			$tccellh colspan=2>Username</td>
			$tccellh width=200>Registered on</td>
			$tccellh width=130 colspan=2>$column</td>
	";
	
	$totla	= 0;
	for($i=1; $user = $sql -> fetch($posters); $i++) {

		if($i == 1) $max = $user['cnt'];
		if ($user['cnt'] != $oldcnt) $rank = $i;
		$oldcnt	= $user['cnt'];
		$namecolor=getnamecolor($user['sex'],$user['powerlevel']);
		print "
			<tr>
			$tccell1>$rank</td>
			$tccell1 width=16>". ($user['minipic'] ? "<img src=\"". htmlspecialchars($user['minipic']) ."\" width=16 height=16>" : "&nbsp;") ."</td>
			$tccell2l><a href=\"profile.php?id=". $user['id'] ."\"><font $namecolor>". $user['name'] ."</font></a></td>
			$tccell1>".date($dateformat, $user['regdate'] + $tzoff) ."</td>
			$tccell2 width=30><b>". $user['cnt'] ."</b></td>
			$tccell2 width=100>". number_format($user['cnt'] / $max * 100, 1) ."%<br><img src=images/minibar.png width=\"". number_format($user['cnt'] / $max * 100) ."%\" align=left height=3> </td>
			</tr>";

		$total	+= $user['cnt'];
	}

	print "
			<tr>
			$tccellc colspan=6>". ($i - 1) ." users, $total $column2</td>
		</tr>
	";
	print $tblend.$footer;
	printtimedif($startingtime);
?>
