<?php

	require_once '../lib/function.php';

	$id			= filter_int($_GET['id']);
	$user		= filter_int($_GET['user']);
	$gotopost	= null;

	// Skip to last post/end thread
	if (filter_int($_GET['lpt'])) {
		$gotopost = $sql->resultq("SELECT MIN(`id`) FROM `posts` WHERE `thread` = '{$id}' AND `date` > '".intval($_GET['lpt'])."'");
	} elseif (filter_int($_GET['end']) || (filter_int($_GET['lpt']) && !$gotopost)) {
		$gotopost = $sql->resultq("SELECT MAX(`id`) FROM `posts` WHERE `thread` = '{$id}'");
	}
	if ($gotopost) {
		return header("Location: ?pid={$gotopost}#{$gotopost}");
	}

	// Poll votes
	if ($id && (filter_int($_GET['addvote']) || filter_int($_GET['delvote']))) {
		$option	= (($_GET['addvote']) ? 'addvote' : 'delvote');
		$choice	= filter_int($_GET[$option]);

		$pollid	= $sql->resultq("SELECT poll FROM threads WHERE id='{$id}'");
		if (!$pollid)
			return header("Location: ?id={$id}#{$id}");

		$poll = $sql->fetchq("SELECT * FROM poll WHERE id='$pollid'");
		$confirm = md5($loguser['name'] . "sillysaltstring");

		// no wrong poll bullshit
		$valid = $sql->resultq("SELECT COUNT(*) FROM `poll_choices` WHERE `poll` = '$pollid' AND `id` = '$choice'");

		if ($log && $poll && !$poll['closed'] && $_GET['dat'] == $confirm && $valid) {
			if ($option == 'addvote') {
				if (!$poll['doublevote'])
					$sql->query("DELETE FROM `pollvotes` WHERE `user` = '$loguserid' AND `poll` = '$pollid'");
				$sql->query("INSERT INTO pollvotes (poll,choice,user) VALUES ($pollid,$choice,$loguserid)");
			}
			else
				$sql->query("DELETE FROM `pollvotes` WHERE `user` = '$loguserid' AND `poll` = '$pollid' AND `choice` = '$choice'");
		}
		return header("Location: ?id={$id}#{$id}");
	}

	$ppp	= filter_int($_GET['ppp']) ? $_GET['ppp'] : ($log ? $loguser['postsperpage'] : 20);
	$ppp	= max(min($ppp, 500), 1);

	if (filter_int($_GET['pid'])) {
		$pid	= $_GET['pid'];
		$id		= $sql->resultq("SELECT `thread` FROM `posts` WHERE `id` = '{$pid}'");
		if (!$id) {
			$meta['noindex'] = true; // prevent search engines from indexing
			require_once '../lib/layout.php';
			errorpage("Couldn't find a post with ID #".intval($pid).".  Perhaps it's been deleted?",'the index page',"index.php");
		}
		$numposts = $sql->resultq("SELECT COUNT(*) FROM `posts` WHERE `thread` = '{$id}' AND `id` < '{$pid}'");
		$page = floor($numposts / $ppp);

		// Canonical page w/o ppp link (for bots)
		$meta['canonical']	= "{$GLOBALS['jul_views_path']}/thread.php?id=$id&page=$page";
	}

	define('E_BADPOSTS', -1);
	define('E_BADFORUM', -2);
	$thread_error = 0;

	$thread	= array();

	// fuck brace overkill
	if ($id) do {
		$thread = $sql->fetchq("SELECT * FROM threads WHERE id=$id");
		$tlinks = '';

		if (!$thread) {
			$meta['noindex'] = true; // prevent search engines from indexing
			if (!$ismod) {
				trigger_error("Accessed nonexistant thread number #$id", E_USER_NOTICE);
				require_once '../lib/layout.php';
				notAuthorizedError();
			}

			if ($sql->resultq("SELECT COUNT(*) FROM `posts` WHERE `thread` = '{$id}'") <= 0) {
				require_once '../lib/layout.php';
				errorpage("Thread ID #{$id} doesn't exist, and no posts are associated with the invalid thread ID.",'the index page',"index.php");
			}

			// Mod+ can see and possibly remove bad posts
			$thread_error = E_BADPOSTS;
			$thread['closed'] = true;
			$thread['title'] = "Bad posts with ID #$id";
			break;
		}

		$thread['title'] = str_replace("<", "&lt;", $thread['title']);

		$forumid = intval($thread['forum']);
		$forum = $sql->fetchq("SELECT * FROM forums WHERE id=$forumid");

		if (!$forum) {
			$meta['noindex'] = true; // prevent search engines from indexing
			if (!$ismod) {
				trigger_error("Accessed thread number #$id with bad forum ID $forumid", E_USER_WARNING);
				require_once '../lib/layout.php';
				notAuthorizedError();
			}
			$thread_error = E_BADFORUM;
			$forum['title'] = " --- BAD FORUM ID --- ";
			break;
		}

		if ($forum['minpower'] > max(0, $power)) {
			if ($log)
				trigger_error("Attempted to access thread $id in level-$forum[minpower] restricted forum $forumid (user's powerlevel: ".intval($loguser['powerlevel']).")", E_USER_NOTICE);
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			notAuthorizedError();
		}

		$specialscheme = $forum['specialscheme'];

		if ($log) {
			$readdate = $sql->resultq("SELECT `readdate` FROM `forumread` WHERE `user` = '$loguserid' AND `forum` = '$forumid'");

			if ($thread['lastpostdate'] > $readdate)
				$sql->query("REPLACE INTO threadsread SET `uid` = '$loguserid', `tid` = '$thread[id]', `time` = '".ctime()."', `read` = '1'");

			$unreadcount = $sql->resultq(
				"SELECT COUNT(*) FROM `threads` ".
				"WHERE `id` NOT IN (SELECT `tid` FROM `threadsread` WHERE `uid` = '$loguserid' AND `read` = '1') ".
				"AND `lastpostdate` > '$readdate' AND `forum` = '$forumid'");
			if ($unreadcount == 0)
				$sql->query("REPLACE INTO forumread VALUES ('$loguserid', '$forumid', '".ctime().'\')');
		}

		$tlinks = array();

		// Favorites
		if ($log) {
			if ($sql->fetchq("SELECT * FROM favorites WHERE user={$loguserid} AND thread={$id}"))
				$tlinks[] = "<a href='{$GLOBALS['jul_views_path']}/forum.php?act=rem&thread={$id}' style='white-space:nowrap;'>Remove from favorites</a>";
			else
				$tlinks[] = "<a href='{$GLOBALS['jul_views_path']}/forum.php?act=add&thread={$id}' style='white-space:nowrap;'>Add to favorites</a>";
		}

		$tnext = $sql->resultq("SELECT id FROM threads WHERE forum=$forumid AND lastpostdate>$thread[lastpostdate] ORDER BY lastpostdate ASC LIMIT 1");
		if ($tnext) $tlinks[] = "<a href='?id={$tnext}' style='white-space:nowrap;'>Next newer thread</a>";
		$tprev = $sql->resultq("SELECT id FROM threads WHERE forum=$forumid AND lastpostdate<$thread[lastpostdate] ORDER BY lastpostdate DESC LIMIT 1");
		if ($tprev) $tlinks[] = "<a href='?id={$tprev}' style='white-space:nowrap;'>Next older thread</a>";

		$tlinks = implode(' | ', $tlinks);

		// Description for bots
		$text = $sql->resultq("SELECT text FROM posts_text pt LEFT JOIN posts p ON (pt.pid = p.id) WHERE p.thread=$id ORDER BY pt.pid ASC LIMIT 1");
		$text = strip_tags(str_replace(array("[", "]", "\r\n"), array("<", ">", " "), $text));
		$text = ((strlen($text) > 160) ? substr($text, 0, 157) . "..." : $text);
		$text = str_replace("\"", "&quot;", $text);
		$meta['description'] = $text;

		$sql->query("UPDATE threads SET views=views+1 WHERE id=$id");

		$windowtitle = "{$forum['title']}: {$thread['title']}";
	} while (false);
	elseif($user) {
		$uname = $sql->resultq("SELECT name FROM users WHERE id={$user}");
		if (!$uname) {
			$meta['noindex'] = true; // prevent search engines from indexing what they can't access
			require_once '../lib/layout.php';
			errorpage("User ID #{$user} doesn't exist.",'the index page',"index.php");
		}

		$thread['replies'] = $sql->resultq("SELECT count(*) FROM posts WHERE user={$user}") - 1;
		$thread['title'] = "Posts by {$uname}";
		$windowtitle = "Posts by {$uname}";
		$tlinks = '';
	}
	else {
		$meta['noindex'] = true; // prevent search engines from indexing what they can't access
		require_once '../lib/layout.php';
		errorpage("No thread specified.",'the index page',"index.php");
	}

	//temporary
	if ($windowtitle) $windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- $windowtitle";
	require_once '../lib/layout.php';

	$fonline = "";
	if ($id && !$thread_error) {
		$fonline = fonlineusers($forumid);
		if (mysql_num_rows($sql->query("SELECT user FROM forummods WHERE forum='$forumid' and user='$loguserid'")))
			$ismod = true;
	}
	$modfeats = '';
	if ($id && $ismod) {
		$trashid = 27;

		$fulledit = "<a href='{$GLOBALS['jul_views_path']}/editthread.php?id={$id}'>Edit thread<a>";
		$linklist = array();
		$link = "<a href='{$GLOBALS['jul_views_path']}/editthread.php?id={$id}&action";

		if (!$thread['sticky'])
			$linklist[] = "$link=qstick'>Stick</a>";
		else
			$linklist[] = "$link=qunstick'>Unstick</a>";

		if (!$thread['closed'])
			$linklist[] = "$link=qclose'>Close</a>";
		else
			$linklist[] = "$link=qunclose'>Open</a>";

		if ($thread['forum'] != $trashid)
			$linklist[] = "$link=trashthread'>Trash</a>";

		//$linklist[] = "$link=delete'>Delete</a>";
		$linklist = implode(' | ', $linklist);
		$modfeats = "<tr>$tccellcls colspan=2>Moderating options: $linklist -- $fulledit</td></tr>";
	}

	$errormsgs = '';
	if ($thread_error) {
		switch($thread_error) {
        	case E_BADPOSTS: $errortext='This thread does not exist, but posts exist that are associated with this invalid thread ID.'; break;
        	case E_BADFORUM: $errortext='This thread has an invalid forum ID; it is located in a forum that does not exist.'; break;
		}
		$errormsgs = "<tr><td style='background:#cc0000;color:#eeeeee;text-align:center;font-weight:bold;'>$errortext</td></tr>";
	}

	$polltbl	= "";
	if ($forum['pollstyle'] != -2 && $thread['poll']) {
		$poll = $sql->fetchq("SELECT * FROM poll WHERE id='$thread[poll]'");

		$uservote = array();
		if ($log) {
			$lsql = $sql->query("SELECT `choice` FROM `pollvotes` WHERE `poll` = '$poll[id]' AND `user` = '$loguserid'");
			while ($userchoice = $sql->fetch($lsql, MYSQL_ASSOC))
				$uservote[$userchoice['choice']] = true;
		}

		if ($forum['pollstyle'] >= 0)
			$pollstyle = $forum['pollstyle'];
		else
			$pollstyle = $loguser['pollstyle'];

		$tvotes2 = $sql->resultq("SELECT count(*) FROM pollvotes WHERE poll=$poll[id]");
		$tvotesi = $sql->resultq("SELECT sum(u.`influence`) as influence FROM pollvotes p LEFT JOIN users u ON p.user = u.id WHERE poll=$poll[id]");

		$pollvotes = $sql->getresultsbykey("SELECT choice, count(*) cnt FROM pollvotes WHERE poll=$poll[id] GROUP BY choice WITH ROLLUP",'choice','cnt');
		$pollinflu = $sql->getresultsbykey("SELECT choice, sum(u.influence) as inf FROM pollvotes p LEFT JOIN users u ON p.user = u.id WHERE poll=$poll[id] GROUP BY choice WITH ROLLUP",'choice','inf');

		$tvotes_u = $sql->resultq("SELECT count(distinct `user`) FROM pollvotes WHERE poll=$poll[id]");
		$tvotes_c = $pollvotes[""];
		$tvotes_i = $pollinflu[""];

		$confirm = md5($loguser['name'] . "sillysaltstring");

		$pollcs = $sql->query("SELECT * FROM poll_choices WHERE poll=$poll[id]");
		while ($pollc = $sql->fetch($pollcs)) {
			$votes = intval($pollvotes[$pollc['id']]);
			$influ = intval($pollinflu[$pollc['id']]);

			if ($pollstyle) {
				if ($tvotes_i != 0)
					$pct = $pct2 = sprintf('%02.1f', $influ / $tvotes_i * 100);
				else
					$pct = $pct2 = "0.0";
				$votes = intval($influ)." point".($influ == 1 ? '' : 's')." ($votes)";
			}
			else {
				if ($tvotes_c != 0) {
					$pct = sprintf('%02.1f', $votes / $tvotes_c * 100);
					$pct2 = sprintf('%02.1f', $votes / $tvotes_u * 100);
				} else
					$pct = $pct2 = "0.0";
				$votes = "$votes vote".($votes == 1 ? '' : 's');
			}

			$barpart = "<table cellpadding=0 cellspacing=0 width=$pct% bgcolor='".($pollc['color'] ? $pollc['color'] : "cccccc")."'><td>&nbsp;</table>";
			if ($pct == "0.0")
				$barpart = '&nbsp;';

			if ($uservote[$pollc['id']]) {
				$linkact = 'del';
				$dot = "<img src=\"images/dot4.gif\" align=\"absmiddle\"> ";
			}
			else {
				$linkact = 'add';
				$dot = "<img src=\"images/_.gif\" width=8 height=8 align=\"absmiddle\"> ";
			}

			$link = '';
			if ($log && !$poll['closed'])
				$link = "<a href='?id={$id}&dat={$confirm}&{$linkact}vote=$pollc[id]'>";

			$choices	.= "<tr>
				$tccell1l width=20%>$dot$link".($pollc['choice'])."</a></td>
				$tccell2l width=60%>$barpart</td>
				$tccell1 width=20%>".($poll['doublevote'] ? "$pct% of users, $votes ($pct2%)" : "$pct%, $votes")."</td>
				</tr>";
		}

		if ($poll['closed']) $polltext = 'This poll is closed.';
		else                 $polltext = 'Multi-voting is '.(($poll['doublevote']) ? 'enabled.' : 'disabled.');
		if ($tvotes_u != 1) $s_have = 's have';
		else                $s_have = ' has';

		if ($ismod)
			$polledit = "<!-- edit would go here -->";

		$polltbl = "$tblstart
			<tr>$tccellc colspan=3><b>".htmlspecialchars($poll['question'])."</td></tr>
			<tr>$tccell2ls colspan=3>".nl2br(dofilters($poll['briefing']))."</td></tr>
			$choices
			<tr>$tccell2l colspan=3>$smallfont $polltext $tvotes_u user$s_have voted. $polledit</td></tr>
			$tblend<br>
			";
	}

	loadtlayout();
	switch($loguser['viewsig']) {
		case 1:  $sfields = ',headtext,signtext'; break;
		case 2:  $sfields = ',u.postheader headtext,u.signature signtext'; break;
		default: $sfields = ''; break;
	}
	$ufields = userfields();

	$activity = $sql->query("SELECT user, count(*) num FROM posts WHERE date>".(ctime() - 86400)." GROUP BY user");
	while ($n = $sql->fetch($activity))
		$act[$n['user']] = $n['num'];

	$postlist = "
		$polltbl
		$tblstart
		$modfeats
		$errormsgs
	";

	if ($log && $id && $forum['id']) {
		$headlinks	.= " - <a href=".$GLOBALS['jul_base_dir']."/index.php?action=markforumread&forumid=$forum[id]>Mark forum read</a>";
		$header = makeheader($header1, $headlinks, $header2 . (($fonline) ? "$tblstart$tccell1s>$fonline$tblend" : ""));
	}

	$threadforumlinks = "
		<table width=100%><td align=left>$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>".$GLOBALS['jul_settings']['board_name']."</a>"
		.
		(($forum['title']) ? " - <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forumid'>$forum[title]</a>" : "")
		.
		" - $thread[title]</td><td align=right>$smallfont
	";
	if ($forumid) {
		if ($forum['pollstyle'] != -2) $threadforumlinks .= "<a href='{$GLOBALS['jul_views_path']}/newthread.php?poll=1&id=$forumid'>$newpollpic</a> - ";
		else                           $threadforumlinks .= "<img src=\"images/nopolls.png\" align=\"absmiddle\"> - ";
		$threadforumlinks .= "<a href='{$GLOBALS['jul_views_path']}/newthread.php?id=$forumid'>$newthreadpic</a>";
		if (!$thread['closed']) $threadforumlinks .= " - <a href='{$GLOBALS['jul_views_path']}/newreply.php?id=$id'>$newreplypic</a>";
		else                    $threadforumlinks .= " - $closedpic";
	}
	$threadforumlinks .= '</table>';

	$page	= max(0, filter_int($page));
	$min	= $ppp * $page;

	if ($user) $searchon = "user={$user}";
	else       $searchon = "thread={$id}";

	$posts = $sql->query(
		"SELECT p.*,text$sfields,edited,editdate,options,tagval,u.id uid,name,$ufields,regdate ".
		"FROM posts_text, posts p LEFT JOIN users u ON p.user=u.id ".
		"WHERE {$searchon} AND p.id=pid ORDER BY p.id LIMIT $min,$ppp");

	preplayouts($posts);

	for ($i = 0; $post = $sql->fetch($posts); $i++) {
		$postlist	.= '<tr>';

		$bg = $i % 2 + 1;

		$quote = "<a href=\"?pid=$post[id]#$post[id]\">Link</a>";
		if ($id and ! $thread['closed'])
			$quote	.= " | <a href='{$GLOBALS['jul_views_path']}/newreply.php?id=$id&postid=$post[id]'>Quote</a>";

		$edit = '';
		if ($ismod || (!$banned && $post['user'] == $loguserid)) {
        	if (!$thread['closed'])
				$edit = ($quote ? ' | ' : '')        . "<a href='{$GLOBALS['jul_views_path']}/editpost.php?id=$post[id]'>Edit</a>";
			$edit .= ($quote || $edit ? ' | ' : ''). "<a href='{$GLOBALS['jul_views_path']}/editpost.php?id=$post[id]&action=delete'>Delete</a>";
		}

		if ($isadmin)
			$ip = " | IP: <a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip=$post[ip]'>$post[ip]</a>";


		$pforum		= null;
		$pthread	= null;
		if (!$id) {
			// Enable caching for these
			$pthread = $sql->fetchq("SELECT id,title,forum FROM threads WHERE id=$post[thread]", MYSQL_BOTH, true);
			$pforum  = $sql->fetchq("SELECT minpower FROM forums WHERE id=".intval($pthread[forum]), MYSQL_BOTH, true);
		}

		$post['act'] = filter_int($act[$post['user']]);

		if (!$pforum || $pforum['minpower'] <= $power)
			$postlist .= threadpost($post, $bg, $pthread);
		else
			$postlist .=
				"<table class=\"table\" cellspacing=0>
					<tr><td class='tbl font tdbg$bg' align=center><small><i>
					(post in restricted forum)
					</i></small></td></tr>
				</table>";
	}

	$query = preg_replace("'page=(\d*)'si", '', '?'.getenv("QUERY_STRING"));
	$query = preg_replace("'pid=(\d*)'si", "id={$id}", $query);
	$query = preg_replace("'&{2,}'si", "&", $query);
	if ($query && substr($query, -1) != "&")
		$query	.= "&";
	if (!$page)
		$page = 0;

	$pageend = (int)($thread['replies'] / $ppp);
	$pagelinks = "Pages:";
	if ($thread['replies'] < $ppp)
		$pagelinks = '';
	else for ($i = 0; $i <= $pageend; $i++) {
		// restrict page range to sane values
		if ($i > 9 && $i < $pageend-9) {
			if ($i < $page-4) {
				$i = min($page-4, $pageend-9);
				$pagelinks .= " ...";
			}
			if ($i > $page+4) {
				$i = $pageend-9;
				$pagelinks .= " ...";
			}
		}

		if ($i == $page)
			$pagelinks	.= " ".($i + 1);
		else
			$pagelinks	.= " <a href='$query"."page=$i'>".($i + 1)."</a>";
	}

	print $header.sizelimitjs()."
		$threadforumlinks
		<table width=100%><td align=left>$smallfont$pagelinks</td><td align=right>$smallfont$tlinks</table>
		$postlist
		$tblstart
		$modfeats
		$tblend
		<table width=100%><td align=left>$smallfont$pagelinks</td><td align=right>$smallfont$tlinks</table>
		$threadforumlinks
	$footer";
	printtimedif($startingtime);


function notAuthorizedError() {
	global $log;
	$redir = (($log) ? 'index.php' : "{$GLOBALS['jul_views_path']}/login.php");
	$rtext = (($log) ? 'the index page' : 'log in (then try again)');
	errorpage("Couldn't enter the forum. You don't have access to this restricted forum.", $rtext, $redir);
}
