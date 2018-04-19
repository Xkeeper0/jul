<?php
	require_once '../lib/function.php';

	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Biggest posters";
	require_once '../lib/layout.php';

	if ($_GET['sort'] == "posts")   { $sort = "posts";   $headertext = 'sorted by post count'; }
	elseif ($_GET['sort'] == "avg") { $sort = "average"; $headertext = 'by average post size'; }
	else                            { $sort	= "waste";   $headertext = 'by post size'; }

  // Time for an update?
  if ($sql->resultq("SELECT bigpostersupdate FROM misc") <= ctime()-(3600 * 8)) {
    $sql->query("TRUNCATE biggestposters");
  	$sql->query("INSERT INTO biggestposters "
    ."(id, posts, waste) "
    ."SELECT u.id, u.posts, SUM(LENGTH(pt.`text`)) "
    ."FROM `posts` p "
    ."LEFT JOIN `users` u ON p.user = u.id "
    ."LEFT JOIN `posts_text` pt on p.id = pt.pid "
    ."WHERE (u.posts >= 5 OR u.posts < 0) GROUP BY p.user");
    $sql->query("UPDATE biggestposters SET average = waste / posts");
    $sql->query("UPDATE misc SET bigpostersupdate = ".ctime());
  }
  $posters = $sql->query("SELECT bp.*, u.name, u.regdate, u.minipic, u.sex, u.powerlevel FROM biggestposters bp LEFT JOIN users u ON bp.id=u.id ORDER BY $sort DESC");

	print "
		$header

		<br>

		$tblstart
			<tr>$tccellc colspan=7><b>Biggest posters, $headertext</b></td></tr>
			<tr>
			$tccellh width=30>#</td>
			$tccellh colspan=2>Username</td>
			$tccellh width=200>Registered on</td>
			$tccellh width=130><a href=\"?sort=posts\">Posts</a></td>
			$tccellh width=130><a href=\"?\">Size</a></td>
			$tccellh width=130><a href=\"?sort=avg\">Average</a></td>
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
			$tccell2l><a href=\"{$GLOBALS['jul_views_path']}/profile.php?id=". $user['id'] ."\"><font $namecolor>". $user['name'] ."</font></a></td>
			$tccell1>".date($dateformat, $user['regdate'] + $tzoff) ."</td>
			$tccell1r>". $user['posts'] ."</td>
			$tccell1r><b>". number_format($user['waste']) ."</b></td>
			$tccell2r><b>". $avgc ."</b></td>
			</tr>";
	}

	print "
		$tblend

		$smallfont(Note: this doesn't take into account quotes, joke posts, or other things. It isn't a very good judge of actual post content, just post <i>size</i>.)
    <br>(This table is cached and updated every few hours.)</font>

		$footer";
	printtimedif($startingtime);
?>
