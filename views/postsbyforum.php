<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	$qstrings = array();

	if ($id) {
		$qstrings[] = "posts.user={$id}";
		$by = 'by '.$sql->resultq("SELECT name FROM users WHERE id={$id}");
	}

	if (!isset($_GET['posttime'])) $posttime = 86400;
	else $posttime = intval($_GET['posttime']);
	if (($posttime === 0 || $posttime > 2592000) && !$id)
		$posttime = 2592000;

	if ($posttime !== 0) {
		$qstrings[] = "posts.date > ".(ctime()-$posttime);
		$during = ' during the last '.timeunits2($posttime);
	}

	if (empty($qstrings)) $qwhere = '1';
	else $qwhere = implode(' AND ', $qstrings);

	$posters = $sql->query(
		"SELECT forums.*,COUNT(posts.id) AS cnt ".
		"FROM forums,threads,posts ".
		"WHERE posts.thread=threads.id ".
		"AND threads.forum=forums.id ".
		"AND {$qwhere} ".
		"GROUP BY forums.id ORDER BY cnt DESC");

	$userposts = $sql->resultq("SELECT COUNT(*) FROM posts WHERE $qwhere");
	$lnk="<a href='{$GLOBALS['jul_views_path']}/postsbyforum.php?id=$id&posttime";

	print "$header
		$smallfont
		$lnk=3600'>During last hour</a> |
		$lnk=86400'>During last day</a> |
		$lnk=604800'>During last week</a> |
		$lnk=2592000'>During last 30 days</a>".
		((!$id) ? "" : " | $lnk=0'>Total</a>").
		"<br>
		$fonttag Posts $by in forums$during:
		$tblstart<tr>
		$tccellh width=20>&nbsp</td>
		$tccellh width=100>&nbsp</td>
		$tccellh>Forum</td>
		$tccellh width=60>Posts</td>
		$tccellh width=80>Forum total</td></tr>
	";

	for ($i=1;$f=$sql->fetch($posters);$i++) {
		print '<tr>';

		if($f['minpower']>$power) {
			$link="(restricted)";
			$viewall="(<s><b>view</b></s>)";
		}
		else {
			$link="<a href='{$GLOBALS['jul_views_path']}/forum.php?id=$f[id]'>$f[title]</a>";
			$timeid = (($posttime !== 0) ? "&time={$posttime}" : '');
			$viewall="(<a href='{$GLOBALS['jul_views_path']}/postsbyuser.php?id={$id}&forum={$f['id']}{$timeid}'>View</a>)";
		}

   if (!$id) $viewall = '';

		print "
			$tccell2>$i</td>
			$tccell2>$viewall</td>
			$tccell1l>$link</td>
			$tccell1><b>$f[cnt]</td>
			$tccell1>$f[numposts]</td></tr>
		";
	}

	print "$tblend Total: $userposts posts$footer";
	printtimedif($startingtime);
?>
