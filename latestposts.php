<?php
	require 'lib/function.php';

	$windowtitle	= "$boardname - A revolution in posting technology&trade;";
//	$start		= $sql -> resultq("SELECT MAX(`id`) FROM `posts`");

	if ($_GET['t']) { $maxtime = (int)$_GET['t'] <= 86400 ? (int)$_GET['t'] : 86400; } else { $maxtime = 86400; }
	if ($_GET['p']) { $maxposts = (int)$_GET['p'] <= 100 ? (int)$_GET['p'] : 50; } else { $maxposts = 50; }
	
	$data	= mysql_query("SELECT p.id, p.user,p.date as date, f.title as ftitle, t.forum as fid, t.title as title, ".
							"u.name as uname, u.sex as usex, u.powerlevel as upowerlevel ".
							"FROM `posts` p ".
							"LEFT JOIN `threads` t ON p.thread = t.id ".
							"LEFT JOIN `forums` f ON t.forum = f.id ".
							"LEFT JOIN `users` u ON p.user = u.id ".
							"\n WHERE f.minpower <= '". $loguser['powerlevel'] ."' ".
							"AND p.date >= ". (ctime()-$maxtime) .($_GET['lastid'] ? " AND p.id > ".$_GET['lastid']:"")." ".
							"\nORDER BY `id` DESC ".
							"LIMIT 0, $maxposts" # posts limit here
							);
	$_count = mysql_num_rows($data);

	$output	= "";
		
	if ($_GET['raw']) {
		$outarray	= array('id', 'ftitle', 'fid', 'title', 'uname', 'upowerlevel', 'usex', 'ucolor', 'date', 'user');
		$outchunks = Array();
	}
	require 'lib/layout.php';

	$_counter = 1;
	while ($in = mysql_fetch_array($data, MYSQL_ASSOC)) {
		if (!$_GET['raw']) {
			$output	.= "<tr>
					$tccell2>". $in['id'] ."</td>
					$tccell2><a href='forum.php?id=". $in['fid'] ."'>". $in['ftitle'] ."</a></td>
					$tccell1l><a href='thread.php?pid=". $in['id'] ."&r=1#". $in['id'] ."'>". $in['title'] ."</a></td>
					$tccell1><a href='profile.php?id=". $in['user'] ."'><font ". getnamecolor($in['usex'], $in['upowerlevel']) .">". $in['uname'] ."</font></a></td>
					$tccell2>". timeunits(ctime() - $in['date']) ."</td>
				</tr>\n";
		} else {
			$in['ucolor']	= str_replace('color=', '', getnamecolor($in['usex'], $in['upowerlevel']));
			$in['title'] = str_replace("\\", "\\\\", $in['title']);
			$temp	= Array();
			foreach($outarray as $outkey) {
				$temp[] = ('"'.$outkey . "\":\"" . htmlspecialchars($in[$outkey]) . '"');
			}
			$temp = "{".implode(",",$temp)."}".($_counter == $_count ? "" : ",");
			$output	.= $temp;
		}
		$_counter++;
	}

	if (!$_GET['raw']) {
		if ($_GET['fungies']) {
			$jscripts	= '<script type="text/javascript" src="/js/jquery.min.js"></script><script type="text/javascript" src="/js/latestposts.js"></script>';
		}
		print "
			$header
			Show:$smallfont
			<br>Last <a href='?t=1800'>30 minutes</a> - <a href='?t=3600'>1 hour</a> - <a href='?t=18000'>5 hours</a> - <a href='?t=86400'>1 day</a>
			<br>Most recent <a href='?p=20'>20 posts</a> - <a href='?p=50'>50 posts</a> - <a href='?p=100'>100 posts</a>
			<table class='table' cellspacing='0' name='latest'>
				<tr>$tccellc colspan=6><b>Latest Posts</b></td></tr>
				<tr>
				$tccellh width=30>&nbsp;</td>
				$tccellh width=200>Forum</td>
				$tccellh>Thread</td>
				$tccellh width=200>User</td>
				$tccellh width=130>Time</td>
				</tr>
				$output
		". $tblend . $jscripts . $footer;
		printtimedif($startingtime);
	} else {
		header("Content-type: text/plain");
		header("Ajax-request: ".IS_AJAX_REQUEST);
		print '{"tzoff": "'.$tzoff.'", "localtime": "'.ctime().'", "posts": ['.$output."]}";
	}
