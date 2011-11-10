<?php

	$windowtitle	= "Biggest posters";

	require 'lib/function.php';
	require 'lib/layout.php';

	$sort	= "waste";
	if ($_GET['order'] == "posts") $sort	= "u.posts";
	elseif ($_GET['order'] == "avg") $sort	= "average";


	$posters	= $sql -> query("SELECT u.*, SUM(LENGTH(pt.`text`)) as waste, (SUM(LENGTH(pt.`text`)) / u.posts) AS average FROM `posts` p LEFT JOIN `users` u ON p.user = u.id LEFT JOIN `posts_text` pt on p.id = pt.pid WHERE (u.posts >= 5 OR u.posts < 0) GROUP BY p.user ORDER BY $sort DESC");

	print "
		$header

		<br>

		$tblstart
			<tr>$tccellc colspan=7><b>Biggest posters, by post size</b></td></tr>
			<tr>
			$tccellh width=30>#</td>
			$tccellh colspan=2>Username</td>
			$tccellh width=200>Registered on</td>
			$tccellh width=130><a href=\"?order=posts\">Posts</a></td>
			$tccellh width=130><a href=\"?\">Size</a></td>
			$tccellh width=130><a href=\"?order=avg\">Average</a></td>
	";
	
	for($i=1; $user = $sql -> fetch($posters); $i++) {

		if($i == 1) $max = $user['waste'];
		if ($user['waste'] != $oldcnt) $rank = $i;
		$oldcnt	= $user['waste'];
		$namecolor=getnamecolor($user['sex'],$user['powerlevel']);

		$col	= "";
		if ($user['average'] >=  500) $col	= "#88ff88";
		if ($user['average'] >=  750) $col	= "#8888ff";
		if ($user['average'] <=  200) $col	= "#ffff80";
		if ($user['average'] <=  100) $col	= "#ff8080";
		if ($user['average'] <=    0) $col	= "#888888";

		$avgc	= number_format(abs($user['average']), 1);
		if ($col) $avgc	= "<font color=$col>$avgc</font>";



		print "
			<tr>
			$tccell1>$rank</td>
			$tccell1 width=16>". ($user['minipic'] ? "<img src=\"". htmlspecialchars($user['minipic']) ."\" width=16 height=16>" : "&nbsp;") ."</td>
			$tccell2l><a href=\"profile.php?id=". $user['id'] ."\"><font $namecolor>". $user['name'] ."</font></a></td>
			$tccell1>".date($dateformat, $user['regdate'] + $tzoff) ."</td>
			$tccell1r>". $user['posts'] ."</td>
			$tccell1r><b>". number_format($user['waste']) ."</b></td>
			$tccell2r><b>". $avgc ."</b></td>
			</tr>";
	}

	print "
		$tblend

		$smallfont(Note: this doesn't take into account quotes, joke posts, or other things. It isn't a very good judge of actual post content, just post <i>size</i>.)</font>
		
		$footer";
	printtimedif($startingtime);
?>