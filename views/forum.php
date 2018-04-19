<?php
	require_once '../lib/function.php';
	$id = intval($_GET['id']);
	$user = intval($_GET['user']);

	if ($log)
		$postread = $sql->getresultsbykey("SELECT forum,readdate FROM forumread WHERE user=$loguserid", 'forum', 'readdate');

	$forumlist="";
	$fonline="";

	// Add/remove favorites
	if ($act == 'add' || $act == 'rem') {
		$meta['noindex'] = true; // prevent search engines from indexing
		$t = $sql->fetchq("SELECT title,forum FROM threads WHERE id=$thread");

		if (!$log) {
			require_once '../lib/layout.php';
			errorpage("You need to be logged in to edit your favorites!",'return to the forum',"{$GLOBALS['jul_views_path']}/forum.php?id=$t[forum]");
		}

		$sql->query("DELETE FROM favorites WHERE user=$loguserid AND thread=$thread");
		if ($act == 'add') {
			$tx = "\"$t[title]\" has been added to your favorites.";

			$minpower = $sql->resultq("SELECT minpower FROM forums WHERE id=$t[forum]");
			if($minpower < 1 || $minpower <= $power)
				$sql->query("INSERT INTO favorites (user,thread) VALUES ($loguserid,$thread)");
			else
				$tx = "You can't favorite a thread you don't have access to!";
		}
		else
			$tx = "\"$t[title]\" has been removed from your favorites.";

		require_once '../lib/layout.php';
		errorpage($tx,'return to the forum',"{$GLOBALS['jul_views_path']}/forum.php?id=$t[forum]");
	}

	// Forum Setup
	if ($fav) {
		if (!$log) {
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			errorpage("You need to be logged in to view your favorites.",'log in (then try again)',"{$GLOBALS['jul_views_path']}/login.php");
		}

		$forum['title'] = 'Favorites';
		if ($user && $user != $loguserid && $isadmin)
			$forum['title'] .= ' of '.$sql->resultq("SELECT name FROM users WHERE id={$user}");
		else
			$user = $loguserid;

		$threadcount = $sql->resultq("SELECT COUNT(*) FROM favorites where user={$user}");
	}
	elseif ($user) {
		$user1=$sql->fetchq("SELECT name,sex,powerlevel,aka,birthday FROM users WHERE id={$user}");
		if (!$user1) {
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			errorpage("No user with that ID exists.",'the index page','index.php');
		}

		$forum['title']="Threads by $user1[name]";
		$threadcount = $sql->resultq("SELECT COUNT(*) FROM threads where user=$user");
	}
	elseif ($id) { # Default case, show forum with id
		error_log("asdf.$id.");
		$id	= intval($id);
		$forum = $sql->fetchq("SELECT title,minpower,numthreads,specialscheme,pollstyle FROM forums WHERE id=$id");

		if (!$forum) {
			trigger_error("Attempted to access invalid forum $id", E_USER_NOTICE);
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			notAuthorizedError();
		}
		elseif ($forum['minpower'] > max(0,$power)) {
			if ($log)
				trigger_error("Attempted to access level-$forum[minpower] restricted forum $id (user's powerlevel: ".intval($loguser['powerlevel']).")", E_USER_NOTICE);
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			notAuthorizedError();
		}
		else
			$specialscheme=$forum['specialscheme'];

		global $fourmid;
		$forumid=$id;

		$threadcount = $forum['numthreads'];
	}
	else {
		$meta['noindex'] = true; // prevent search engines from indexing what they can't access
		require_once '../lib/layout.php';
		errorpage("No forum specified.",'the index page',"index.php");
	}


	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- $forum[title]";
	require_once '../lib/layout.php';

	$hotcount = $sql->resultq('SELECT hotcount FROM misc',0,0);
	if ($hotcount <= 0) $hotcount = 0xFFFF;

	$ppp = (($_GET['ppp']) ? intval($_GET['ppp']) : (($log) ? $loguser['postsperpage'] : 20));
	$ppp = max(min($ppp, 500), 1);

	$tpp = (($_GET['tpp']) ? intval($_GET['tpp']) : (($log) ? $loguser['threadsperpage'] : 50));
	$tpp = max(min($tpp, 500), 1);

	$page = intval($_GET['page']);
    $min = $page*$tpp;

	$newthreadbar = $forumlist = '';
	if ($id) {
		$forumlist = doforumlist($id);
		$fonline = fonlineusers($id);

		if($log) {
			$headlinks.=" - <a href={$GLOBALS['jul_base_dir']}/index.php?action=markforumread&forumid=$id>Mark forum read</a>";
			$header = makeheader($header1,$headlinks,$header2 .(($fonline) ? "$tblstart$tccell1s>$fonline$tblend" : ""));
		}

		$newthreadbar =
			"<td align=right class=fonts>".
			(($forum['pollstyle'] != -2) ? "<a href={$GLOBALS['jul_views_path']}/newthread.php?poll=1&id=$id>$newpollpic</a>" : "<img src=\"images/nopolls.png\" align=\"absmiddle\">")
			." - <a href={$GLOBALS['jul_views_path']}/newthread.php?id=$id>$newthreadpic</a></td>";
	}
	$infotable =
		"<table width=100%><tr>
			<td align=left class='font'><a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - $forum[title]</td>
			$newthreadbar
		</tr></table>";

	$forumpagelinks = '';
	if($threadcount > $tpp){
		$query = ($id ? "id=$id" : ($user ? "user=$user" : "fav=1"));
		if ($_GET['tpp']) $query .= "&tpp=$tpp";

		$forumpagelinks = "<table width=100%><tr>
			<td align=left class='fonts'>Pages:";
		for($k=0;$k<($threadcount/$tpp);$k++)
			$forumpagelinks .= ($k!=$page ? " <a href='?$query&page=$k'>".($k+1).'</a>':' '.($k+1));
		$forumpagelinks .= "</td></tr></table>";
    }

	$threadlist = "{$tblstart}";

	// Announcements
	if ($id) {
		if($annc = $sql->fetchq('SELECT announcements.id aid,user id,date,announcements.title,name,sex,powerlevel FROM announcements,users WHERE forum=0 AND user=users.id ORDER BY date DESC LIMIT 1')) {
			$userlink = getuserlink($annc);
			$threadlist .= "<tr>
				<td colspan=7 class='tbl tdbgh center fonts'>Announcements</td>
			</tr><tr>
				$tccell2>". ($loguser['lastannouncement'] < $annc['aid'] && $loguser['id'] ? $newpic : "&nbsp;") ."</td>
				$tccell1l colspan=6><a href='{$GLOBALS['jul_views_path']}/announcement.php'>$annc[title]</a> -- Posted by {$userlink} on ".date($dateformat,$annc['date']+$tzoff)."</td>
			</tr>";
		}
		if($annc = $sql->fetchq("SELECT user id,date,announcements.title,name,sex,powerlevel FROM announcements,users WHERE forum=$id AND user=users.id ORDER BY date DESC LIMIT 1")) {
			$userlink = getuserlink($annc);
			$threadlist .= "<tr>
				$tccellhs colspan=7>Forum announcements</td>
			</tr><tr>
				$tccell2>&nbsp;</td>
				$tccell1l colspan=6><a href='{$GLOBALS['jul_views_path']}/announcement.php?f=$id'>$annc[title]</a> -- Posted by {$userlink} on ".date($dateformat,$annc['date']+$tzoff)."</td>
			</tr>";
		}
    }
    // Forum names
    else
		$forumnames = $sql->getresultsbykey("SELECT id,title FROM forums WHERE minpower <= ".intval($loguser['powerlevel']), 'id', 'title');

	// Get threads
	if($fav)
		$threads = $sql->query("SELECT t.*, minpower,f.id as forumid, "
			."u1.name AS name1, u1.sex AS sex1, u1.powerlevel AS pwr1, u1.aka as aka1, u1.birthday as bd1,"
			."u2.name AS name2, u2.sex AS sex2, u2.powerlevel AS pwr2, u2.aka as aka2, u2.birthday as bd2 "
			.($log ? ", r.read AS tread, r.time as treadtime " : " ")
			."FROM threads t "
			.($log ? "LEFT JOIN threadsread r ON (t.id=r.tid AND r.uid=$loguserid) " : "")
			.",users u1,users u2,forums f,favorites fav "
			."WHERE u1.id=t.user "
			."AND u2.id=t.lastposter "
			."AND fav.thread=t.id "
			."AND fav.user={$user} "
			."AND f.id=t.forum "
			."ORDER BY sticky DESC,lastpostdate DESC "
			."LIMIT $min,$tpp");
	elseif($user)
		$threads = $sql->query("SELECT t.*, minpower, f.id as forumid, "
			."'".addslashes($user1['name'])."' AS name1, {$user1['sex']} AS sex1, {$user1['powerlevel']} AS pwr1, '"
			.addslashes($user1['aka'])."' as aka1, {$user1['birthday']} as bd1, "
			."name AS name2, sex AS sex2, powerlevel AS pwr2, aka as aka2, birthday as bd2"
			.($log ? ", r.read AS tread, r.time as treadtime " : " ")
			."FROM threads t "
			.($log ? "LEFT JOIN threadsread r ON (t.id=r.tid AND r.uid=$loguserid) " : "")
			.",users u,forums f "
			."WHERE t.user=$user "
			."AND u.id=t.lastposter "
			."AND f.id=t.forum "
			."ORDER BY sticky DESC,lastpostdate DESC "
			."LIMIT $min,$tpp");
	else
		$threads = $sql->query("SELECT t.*,"
			."u1.name AS name1, u1.sex AS sex1, u1.powerlevel AS pwr1, u1.aka as aka1, u1.birthday as bd1,"
			."u2.name AS name2, u2.sex AS sex2, u2.powerlevel AS pwr2, u2.aka as aka2, u2.birthday as bd2 "
			.($log ? ", r.read AS tread, r.time as treadtime " : " ")
			."FROM threads t "
			.($log ? "LEFT JOIN threadsread r ON (t.id=r.tid AND r.uid=$loguserid) " : "")
			.",users u1,users u2 "
			."WHERE forum=$id "
			."AND u1.id=t.user "
			."AND u2.id=t.lastposter "
			."ORDER BY sticky DESC,lastpostdate DESC "
			."LIMIT $min,$tpp");

    $threadlist .= "<tr>
		$tccellh width=30></td>
		$tccellh colspan=2 width=*> Thread</td>
		$tccellh width=14%>Started by</td>
		$tccellh width=60> Replies</td>
		$tccellh width=60> Views</td>
		$tccellh width=150> Last post</td>
	</tr>";

	$sticklast = 0;

	if (mysql_num_rows($threads) <= 0) {
		$threadlist .= "<tr>
			$tccell1 style='font-style:italic;' colspan=7>There are no threads to display.</td>
		</td></tr>";
	}
	else for($i=1; $thread=@$sql->fetch($threads, MYSQL_ASSOC); ++$i) {
		if($sticklast && !$thread['sticky'])
			$threadlist .= "<tr>$tccellh colspan=7><img src='images/_.gif' height=6 width=6>";
		$sticklast = $thread['sticky'];

		if(!$id && $thread['minpower'] > max(0,$power)) {
			$threadlist .= "<tr>$tccell2s colspan=7>(restricted)</td></tr>";
			continue;
		}

		// Disabled polls
		if ($forum['pollstyle'] == -2)
			$thread['poll'] = 0;

		$new          = "&nbsp;";
		$newpost      = false;
		$threadstatus	= "";

		// Forum, logged in
		if ($log && $id && $thread['lastpostdate']>$postread[$id] && !$thread['tread']) {
			$threadstatus	.= "new";
			$newpost		= true;
			$newpostt		= ($thread['treadtime'] ? $thread['treadtime'] : $postread[$id]);
		}
		// User's thread list / Favorites, logged in
		elseif ($log && !$id && $thread['lastpostdate']>$postread[$thread['forumid']] && !$thread['tread']) {
			$threadstatus	.= "new";
			$newpost		= true;
			$newpostt		= ($thread['treadtime'] ? $thread['treadtime'] : $postread[$thread['forumid']]);
		}
		// Not logged in
		elseif (!$log && $thread['lastpostdate']>ctime()-3600) {
			$threadstatus	.= "new";
			$newpost		= true;
			$newpostt		= ctime() - 3600;
		}

		if ($thread['replies'] >= $hotcount) $threadstatus .= "hot";
		if ($thread['closed'])	$threadstatus .= "off";
		if ($threadstatus) $new	= $statusicons[$threadstatus];

		$posticon="<img src='$thread[icon]'>";

		if (trim($thread['title']) == "")
			$thread['title']	= "<i>hurr durr i'm an idiot who made a blank thread</i>";
		else
			$thread['title'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), trim($thread['title']));

		$threadtitle	= "<a href='{$GLOBALS['jul_views_path']}/thread.php?id=$thread[id]'>$thread[title]</a>";
		$belowtitle   = array(); // An extra line below the title in certain circumstances

		$sicon			= "";
		if ($thread['sticky'])	{
			$threadtitle	= "<i>". $threadtitle ."</i>";
			$sicon	.= "sticky";
		}
		if ($thread['poll'])	$sicon	.= "poll";
		if ($sicon)
			$threadtitle	= $statusicons[$sicon] ." ". $threadtitle;

		// Show forum name if not in a forum
		if (!$id)
			$belowtitle[] = "In <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$thread[forumid]'>".$forumnames[$thread['forumid']]."</a>";

		// Extra pages
		if($thread['replies']>=$ppp) {
			$pagelinks='';

			$maxfromstart = (($loguser['pagestyle']) ?  9 :  4);
			$maxfromend   = (($loguser['pagestyle']) ? 20 : 10);

			$totalpages	= ceil(($thread['replies']+1)/$ppp);
			for($k=0; $k < $totalpages; $k++) {
				if ($totalpages >= ($maxfromstart+$maxfromend+1) && $k > $maxfromstart && $k < ($totalpages - $maxfromend)) {
				  $k = ($totalpages - $maxfromend);
					$pagelinks .= " ...";
				}
				$pagelinks.=" <a href='{$GLOBALS['jul_views_path']}/thread.php?id=$thread[id]&page=$k'>".($k+1).'</a>';
			}

			if ($loguser['pagestyle'])
				$belowtitle[] = "Page:{$pagelinks}";
			else
				$threadtitle .= " <span class='pagelinks fonts'>(Pages:{$pagelinks})</span>";
		}

		if (!empty($belowtitle))
			$secondline = '<br><span class="fonts" style="position: relative; top: -1px;">&nbsp;&nbsp;&nbsp;' . implode(' - ', $belowtitle) . '</span>';
		else
			$secondline = '';

		if(!$thread['icon']) $posticon='&nbsp;';
		$userlink1 = getuserlink($thread,
			array('sex'=>'sex1', 'powerlevel'=>'pwr1', 'id'=>'user',       'aka'=>'aka1', 'name'=>'name1', 'birthday'=>'bd1'));
		$userlink2 = getuserlink($thread,
			array('sex'=>'sex2', 'powerlevel'=>'pwr2', 'id'=>'lastposter', 'aka'=>'aka2', 'name'=>'name2', 'birthday'=>'bd2'));

		$threadlist .= "<tr>
			$tccell1>$new</td>
			$tccell2 width=40px><div style=\"max-width:60px;max-height:30px;overflow:hidden;\">$posticon</div></td>
			$tccell2l>". ($newpost ? "<a href='{$GLOBALS['jul_views_path']}/thread.php?id=$thread[id]&lpt=". $newpostt ."'>". $statusicons['getnew'] ."</a> " : "") ."$threadtitle$secondline</td>
			$tccell2>{$userlink1}</td>
			$tccell1>$thread[replies]</td>
			$tccell1>$thread[views]</td>
			$tccell2><div class='lastpost'>".date($dateformat,$thread['lastpostdate']+$tzoff)."<br>
				by {$userlink2}
				<a href='{$GLOBALS['jul_views_path']}/thread.php?id=$thread[id]&end=1'>$statusicons[getlast]</a>
			</div></td></tr>";
	}
	$threadlist .= "{$tblend}";

	print "
		{$header}
		{$infotable}
		{$forumpagelinks}
		{$threadlist}
		{$forumpagelinks}
		{$infotable}
		{$forumlist}
		{$footer}
	";
	printtimedif($startingtime);

function notAuthorizedError() {
	global $log;
	$rreason = (($log) ? 'don\'t have access to it' : 'are not logged in');
	$redir = (($log) ? 'index.php' : "{$GLOBALS['jul_views_path']}/login.php");
	$rtext = (($log) ? 'the index page' : 'log in (then try again)');
	errorpage("Couldn't enter this restricted forum, as you {$rreason}.", $rtext, $redir);
}
