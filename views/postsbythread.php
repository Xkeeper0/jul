<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	$qstrings = array();

	if ($id) {
		$qstrings[] = "p.user={$id}";
		$by = 'by '.$sql->resultq("SELECT name FROM users WHERE id={$id}");
	}

	if (!isset($_GET['posttime'])) $posttime = 86400;
	else $posttime = intval($_GET['posttime']);
	if (($posttime === 0 || $posttime > 2592000) && !$id)
		$posttime = 2592000;

	if ($posttime !== 0) {
		$qstrings[] = "p.date > ".(ctime()-$posttime);
		$during = ' during the last '.timeunits2($posttime);
	}

	if (empty($qstrings)) $qwhere = '1';
	else $qwhere = implode(' AND ', $qstrings);

	$posters = $sql->query(
		"SELECT t.id,t.replies,t.title,t.forum,f.minpower,f.title ftitle,COUNT(p.id) cnt " .
		"FROM threads t,posts p,forums f " .
		"WHERE p.thread=t.id AND t.forum=f.id AND $qwhere " .
		"GROUP BY t.id ORDER BY cnt DESC,t.firstpostdate DESC LIMIT 1000");

	$lnk="<a href={$GLOBALS['jul_views_path']}/postsbythread.php?id=$id&posttime";

	print "$header$smallfont
		$lnk=3600>During last hour</a> |
		$lnk=86400>During last day</a> |
		$lnk=604800>During last week</a> |
		$lnk=2592000>During last 30 days</a>".
		((!$id) ? "" : " | $lnk=0>Total</a>").
		"<br>
		$fonttag Posts $by in threads$during:
		$tblstart
		$tccellh width=30>&nbsp</td>
		$tccellh width=300>Forum</td>
		$tccellh>Thread</td>
		$tccellh width=70>Posts</td>
		$tccellh width=90>Thread total
	";

	for($i=1;$t=$sql->fetch($posters);$i++) {
		$t['title'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $t['title']);

		if($t['minpower']>$power && $t['minpower']>0) {
			$forum  = '(restricted forum)';
			$thread = '(private thread)';
		}
		else {
			$forum  = "<a href='{$GLOBALS['jul_views_path']}/forum.php?id={$t['forum']}'>{$t['ftitle']}</a>";
			$thread = "<a href='{$GLOBALS['jul_views_path']}/thread.php?id={$t['id']}'>{$t['title']}</a>";
		}

		print "<tr>
			$tccell2>$i</td>
			$tccell2>{$forum}</td>
			$tccell1l>{$thread}</td>
			$tccell1 style='font-weight:bold;'>$t[cnt]</td>
			$tccell1>".($t['replies']+1)."</td></tr>";
	}

	print $tblend.$footer;
	printtimedif($startingtime);
?>
