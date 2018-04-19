<?php
	require_once '../lib/function.php';

	$user = $sql->resultq("SELECT name FROM users WHERE id=$id");
	$windowtitle = "Listing posts by $user";
	require_once '../lib/layout.php';

	if (!$id)
		errorpage('No user specified.', 'return to the board', 'index.php');

	if ($_GET['forum']) {
		$fid = intval($_GET['forum']);
		$forum = $sql->fetchq("SELECT title, minpower FROM forums WHERE id={$fid}");
		if ($forum['minpower'] > 0 && $power < $forum['minpower'])
			errorpage('You don\'t have access to view posts in this forum.', 'return to the board', 'index.php');
		$where = "in $forum[title]";
		$forumquery = " AND t.forum = {$fid}";
	}
	else {
		$forumquery = '';
		$where = "on the board";
	}

	if ($_GET['time']) {
		$time = intval($_GET['time']);
		$when = " over the past ".timeunits2($time);
		$timequery = ' AND p.date > ' . (ctime()-$time);
	}
	else
		$timequery = $when = '';

	if (!$page) $page=0;
 	if (!$ppp) $ppp=50;
	$min = $ppp*$page;

	$posts=$sql->query("SELECT p.id,thread,ip,date,num,t.title,minpower "
		."FROM posts p "
		."LEFT JOIN threads t ON (thread=t.id) "
		."LEFT JOIN forums f ON (t.forum=f.id) "
		."WHERE p.user={$id}{$forumquery}{$timequery} ORDER BY p.id DESC");

	$posttotal=mysql_num_rows($posts);

	// Seek to page
	if (!@mysql_data_seek($posts, $min)) $page = 0;

	$pagelinks=$smallfont.'Pages:';
	for($i=0;$i<($posttotal/$ppp);$i++) {
		if($i==$page) $pagelinks.=' '.($i+1);
		else {
			if($ppp != 50) $postperpage = "&ppp=$ppp";
			if($forumquery) $forumlink = '&forum='.intval($_GET['forum']);
			$pagelinks.=" <a href={$GLOBALS['jul_views_path']}/postsbyuser.php?id=$id$postperpage$forumlink&page=$i>".($i+1).'</a>';
		}
	}

	$postlist="
	$tccellhs width=50>#</td>
	$tccellhs width=50>Post</td>
	$tccellhs width=130>Date</td>
	$tccellhs>Thread</td>
	" . (($isadmin) ? "$tccellhs width=110>IP address</td>" : "");

	while(($post = $sql->fetch($posts)) && $ppp--) {
		if($post['minpower']<=$power or !$post['minpower'])
			$threadlink="<a href={$GLOBALS['jul_views_path']}/thread.php?pid=$post[0]#$post[0]>".str_replace('<','&lt',$post['title']).'</a>';
		else $threadlink='(restricted)';

		if(!$post['num']) $post['num']='?';

		$postlist.="<tr>
			$tccell1s>$post[0]</td>
			$tccell1s>$post[num]</td>
			$tccell1s>".date($dateformat,$post[3]+$tzoff)."</td>
			$tccell1ls>#<a href={$GLOBALS['jul_views_path']}/thread.php?id=$post[thread]>$post[1]</a> - $threadlink
			" . (($isadmin) ? "</td>$tccell1s>$post[2]" : "") ."
		</tr>";
	 }

	print "{$header}{$fonttag}Posts by {$user} {$where}{$when}: ({$posttotal} posts found)
		$tblstart$postlist$tblend$pagelinks$footer";
	printtimedif($startingtime);
?>
