<?php
	require 'lib/function.php';

	$user = $sql->resultq("SELECT name FROM users WHERE id=$id");
	$windowtitle = "Listing posts by $user";
	require 'lib/layout.php';

	if (!$id)
		errorpage('No user specified.', 'return to the board', 'index.php');
	
	$qstrings = array('p.user = ?');
	$qvalues  = array($_GET['id']);
	
	if ($_GET['forum']) {
		$forum = $sql->fetchp("SELECT title, minpower FROM forums WHERE id=?", array($_GET['forum']));
		if ($forum['minpower'] > 0 && $power < $forum['minpower'])
			errorpage('You don\'t have access to view posts in this forum.', 'return to the board', 'index.php');
		$where = "in $forum[title]";
		
		$qstrings[] = "t.forum = ?";
		$qvalues[]  = $_GET['forum'];
	}
	else $where = "on the board";
	
	if ($_GET['time']) {
		$time = intval($_GET['time']);
		$when = " over the past ".timeunits2($_GET['time']);
		$qstrings[] = "p.date > ?";
		$qvalues[]  = ctime()-$_GET['time'];
	}
	else $when = '';
	
	
	$qwhere = implode(' AND ', $qstrings);
	$posttotal = $sql->resultp("SELECT COUNT(*) "
		."FROM posts p "
		."LEFT JOIN threads t ON p.thread = t.id "
		."LEFT JOIN forums f ON t.forum = f.id "
		."WHERE {$qwhere}", $qvalues);
		

	if (!$page) $page=0;
 	if (!$ppp) $ppp=50;
	$min = $ppp*$page;
	$qvalues[] = $min;
	$qvalues[] = $ppp;
	
	$posts=$sql->queryp("SELECT p.id,thread,ip,date,num,t.title,minpower "
		."FROM posts p "
		."LEFT JOIN threads t ON (thread=t.id) "
		."LEFT JOIN forums f ON (t.forum=f.id) "
		."WHERE {$qwhere} "
		."ORDER BY p.id DESC "
		."LIMIT ?,?", $qvalues);
	

	//$posttotal=$sql->num_rows($posts);

	// Seek to page
	//if (!@mysql_data_seek($posts, $min)) $page = 0;
	
	$pagelinks=$smallfont.'Pages:';
	for($i=0;$i<($posttotal/$ppp);$i++) {
		if($i==$page) $pagelinks.=' '.($i+1);
		else {
			if($ppp != 50) $postperpage = "&ppp=$ppp";
			if($forumquery) $forumlink = '&forum='.intval($_GET['forum']);
			$pagelinks.=" <a href=postsbyuser.php?id=$id$postperpage$forumlink&page=$i>".($i+1).'</a>';
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
			$threadlink="<a href=thread.php?pid=$post[0]#$post[0]>".str_replace('<','&lt',$post['title']).'</a>';
		else $threadlink='(restricted)';

		if(!$post['num']) $post['num']='?';

		$postlist.="<tr>
			$tccell1s>$post[0]</td>
			$tccell1s>$post[num]</td>
			$tccell1s>".date($dateformat,$post[3]+$tzoff)."</td>
			$tccell1ls>#<a href=thread.php?id=$post[thread]>$post[1]</a> - $threadlink
			" . (($isadmin) ? "</td>$tccell1s>$post[2]" : "") ."
		</tr>";
	 }

	print "{$header}{$fonttag}Posts by {$user} {$where}{$when}: ({$posttotal} posts found)
		$tblstart$postlist$tblend$pagelinks$footer";
	printtimedif($startingtime);
?>