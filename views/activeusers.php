<?php
	require_once '../lib/function.php';

	$windowtitle	= "{$GLOBALS['jul_settings']['board_name']} -- Active users";
	require_once '../lib/layout.php';

	if (($type == 'pm' || $type == 'pms') && !$log)
		unset($type);

	if (!isset($_GET['time']))
		$time = 86400;
	else
		$time = intval($_GET['time']);

	$query='SELECT users.id, regdate, name, minipic, sex, powerlevel, aka, birthday, COUNT(*) AS cnt FROM users';
	$endp=' GROUP BY users.id ORDER BY cnt DESC';

	$linklist[0] = "<a href=\"?time=$time\">posts made</a>";
	$linklist[1] = "<a href=\"?type=thread&time=$time\">new threads</a>";
	if ($log) {
		$linklist[2] = "<a href=\"?type=pms&time=$time\">PMs sent by you</a>";
		$linklist[3] = "<a href=\"?type=pm&time=$time\">PMs sent to you</a>";
	}

	if ($type == 'thread')	{
		$posters	= $sql-> query("$query, threads WHERE threads.user=users.id"
			.($time ? " AND threads.firstpostdate> '". (ctime() - $time) ."'" : '')
			.$endp);
		$desc		= "Most active thread posters";
		$column		= "Threads";
		$column2	= "threads";
		$stat = "most thread creators";
		$linklist[1] = "new threads";

	} elseif ($type == 'pm') {
		$posters	= $sql-> query("$query, pmsgs WHERE pmsgs.userto=$loguserid"
			.($time ? " AND pmsgs.date> '". (ctime() - $time) ."'" : '')
			." AND pmsgs.userfrom=users.id$endp");
		$desc		= "PMs recieved from";
		$column		= "PMs";
		$column2	= "PMs";
		$stat = "most message senders";
		$linklist[3] = "PMs sent to you";

	} elseif ($type == 'pms') {
		$posters	= $sql-> query("$query, pmsgs WHERE pmsgs.userfrom=$loguserid"
			.($time ? " AND pmsgs.date> '". (ctime() - $time) ."'" : '')
			." AND pmsgs.userto=users.id$endp");
		$desc		= "PMs sent to";
		$column		= "PMs";
		$column2	= "PMs";
		$stat = "who you've sent the most messages to";
		$linklist[2] = "PMs sent by you";

	} else {
		$posters	= $sql-> query("$query, posts WHERE posts.user=users.id"
			.($tid ? " AND thread='$tid'" : '')
			.($time ? " AND posts.date> '". (ctime() - $time) ."'" : '')
			.$endp);
		$desc		= "Most active posters";
		$column		= "Posts";
		$column2	= "posts";
		$stat = "most active posters";
		$linklist[0] = "posts made";
		$type = '';
	}

	$link='<a href='.(($type) ? "?type={$type}&" : '?').'time';
	print "
		$header
		$tblstart
		<td align=left width=50%>$smallfont
			Show $stat in the:
			<br>$link=3600>last hour</a> - $link=86400>last day</a> - $link=604800>last week</a> - $link=2592000>last 30 days</a> - $link=0>from the beginning</a>
		</td><td width=50% align=right>$smallfont
		Most active users by:<br>
		".implode(" - ", $linklist)."
		$tblend
	";

	if ($time)
		$timespan = " during the last ". timeunits2($time);
	else
		$timespan = "";

/*
	if ($loguser["powerlevel"] >= 1) {
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
*/

	print "
		$tblstart
			<tr>$tccellc colspan=6><b>$desc$timespan</b></td></tr>
			<tr>
			$tccellh width=30>#</td>
			$tccellh colspan=2>Username</td>
			$tccellh width=200>Registered on</td>
			$tccellh width=130 colspan=2>$column</td>
	";

	$total = 0;
	for($i = 1; $user = $sql->fetch($posters); $i++) {
		if($i == 1) $max = $user['cnt'];
		if ($user['cnt'] != $oldcnt) $rank = $i;
		$oldcnt	= $user['cnt'];
		$ulink = getuserlink($user);
		print "
			<tr>
			$tccell1>$rank</td>
			$tccell1 width=16>". ($user['minipic'] ? "<img src=\"". htmlspecialchars($user['minipic']) ."\" width=16 height=16>" : "&nbsp;") ."</td>
			$tccell2l>{$ulink}</td>
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
