<?php
	require 'lib/function.php';
	$windowtitle = "Posts by time of day";
	require 'lib/layout.php';
	
	$qstrings = array();
	$qvalues  = array();

	if ($id) {
		$qstrings[] = "user=?";
		$qvalues[]   = $_GET['id'];
		$from = 'by '.$sql->resultp("SELECT name FROM users WHERE id=?", array($_GET['id']));
	}
	else $from = ' on the board';
	
	$posttime = filter_int($_GET['posttime'], 86400);
	if ($posttime !== 0) {
		$qstrings[] = "date > ".(ctime()-$posttime);
		$during = ' during the last '.timeunits2($posttime);
	}

	if (empty($qstrings)) $qwhere = '1';
	else $qwhere = implode(' AND ', $qstrings);

	$posts = $sql->queryp("SELECT count(*) AS cnt, FROM_UNIXTIME(date,'%k') AS hour FROM posts WHERE {$qwhere} GROUP BY hour", $qvalues);
	$link = "<a href=postsbytime.php?" . (($id) ? "id=$id&" : "") . "posttime";
	print "$header$smallfont
		Timeframe:
		$link=86400>Last day</a> |
		$link=604800>Last week</a> |
		$link=2592000>Last 30 days</a> |
		$link=31536000>Last year</a> |
		$link=0>All-time</a><br>
		$fonttag Posts$from by time of day$during:
		$tblstart
			$tccellh width=100>Time</td>
			$tccellh width=50>Posts</td>
			$tccellh>&nbsp</tr>";

	$postshour = array_fill(0, 24, 0);
	$max = 0;
	while($h=$sql->fetch($posts))
		if (($postshour[$h['hour']] = $h['cnt']) > $max)
			$max = $h['cnt'];

	for($i=0;$i<24;$i++) {
		$time = sprintf('%1$02d:00 - %1$02d:59', $i);
		$bar  = "<img src=images/$numdir".'bar-on.gif width='.(@floor($postshour[$i]/$max*10000)/100).'% height=8>';

		print "<tr>
		$tccell2s>$time</td>
		$tccell2s>$postshour[$i]</td>
		$tccell1ls width=*>$bar</td>
		</tr>";
	}

	print $tblend.$footer;
	printtimedif($startingtime);
?>