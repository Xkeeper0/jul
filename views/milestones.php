<?php
	require_once '../lib/function.php';
	$windowtitle="{$GLOBALS['jul_settings']['board_name']} -- Milestones";
	require_once '../lib/layout.php';

  $posts   = max(10000, intval($_GET['p']));
  $threads = max(1000,  intval($_GET['t']));

  $tmp1 = $tmp2 = 0;
	$milestones = $sql->query("SELECT p.*, t.title as threadname, u.name as uname, u.sex as usex, u.powerlevel as upowerlevel, f.id as fid, f.title as ftitle, f.minpower as mpl "
			."FROM posts p "
			."LEFT JOIN users u ON p.user = u.id "
			."LEFT JOIN threads t ON p.thread = t.id "
			."LEFT JOIN forums f ON t.forum = f.id "
			."WHERE (p.id % $posts = 0 OR p.id = 1) "
			."ORDER BY p.id ASC");
	$poststable = "<tr>$tccellh colspan=6 style=\"font-weight:bold;\">Post Milestones</td></tr><tr>
			$tccellh width=30>&nbsp;</td>
			$tccellh width=280>Forum</td>
			$tccellh width=*>In Thread</td>
			$tccellh width=200>User</td>
			$tccellh width=250>Time</td>
		</tr>";
	$last = 0;
	while ($ms = $sql->fetch($milestones)) {
    $tmp2 = $ms['id'];
    while (($tmp2 -= $posts) > $tmp1) {
			$poststable .= "<tr>
				$tccell1>$tmp2</td>
				$tccell2><i>(unknown)</i></td>
				$tccell2><i>(post deleted)</i></td>
				$tccell1>????</td>
				$tccell1>????<br>$smallfont(????)</td>
			</td>";
    }
    $tmp1 = $ms['id'];

    if ($ms['mpl'] && $ms['mpl'] > $loguser['powerlevel']) {
			$forumlink = "<i>(restricted forum)</i>";
			$threadlink = "<i>(restricted)</i>";
			$userlink = "????";
		}
		else {
			$forumlink = "<a href='{$GLOBALS['jul_views_path']}/forum.php?id=$ms[fid]'>$ms[ftitle]</a>";
			$threadlink = "<a href='{$GLOBALS['jul_views_path']}/thread.php?pid=$ms[id]#$ms[id]'>$ms[threadname]</a>";
			$userlink = "<a href='{$GLOBALS['jul_views_path']}/profile.php?id=$ms[user]'><font ". getnamecolor($ms['usex'], $ms['upowerlevel']) .">$ms[uname]</font></a>";
		}

    if ($last)
      $timetaken = "<br>$smallfont(".timeunits($ms['date']-$last).")";
    else
      $timetaken = "<br>$smallfont(first post)";
		$last = $ms['date'];
		$timestamp = date($dateformat,$ms['date']+$tzoff).$timetaken;

		$poststable .= "<tr>
			$tccell1>$ms[id]</td>
			$tccell2>$forumlink</td>
			$tccell2>$threadlink</td>
			$tccell1>$userlink</td>
			$tccell1>$timestamp</td>
		</td>";
	}

  $tmp1 = $tmp2 = 0;

	$milestones = $sql->query("SELECT t.*,u1.name AS name1,u1.sex AS sex1,u1.powerlevel AS power1,u2.name AS name2,u2.sex AS sex2,u2.powerlevel AS power2, f.minpower as mpl, f.title as forumtitle "
			."FROM threads t,forums f,users u1,users u2 "
			."WHERE (t.id % $threads = 0 OR t.id = 1) "
			."AND f.id=t.forum "
			."AND u1.id=t.user "
			."AND u2.id=t.lastposter "
			."ORDER BY t.id ASC");
	$threadstable = "<tr>$tccellh colspan=7 style=\"font-weight:bold;\">Thread Milestones</td></tr><tr>
			$tccellh width=30></td>
			$tccellh colspan=2> Thread</td>
			$tccellh width=20%>Started by</td>
			$tccellh width=60> Replies</td>
			$tccellh width=60> Views</td>
			$tccellh width=180> Last post</td>
    </tr>";
	while ($ms = $sql->fetch($milestones)) {
    $tmp2 = $ms['id'];
    while (($tmp2 -= $threads) > $tmp1) {
			$threadstable .= "<tr>
				$tccell1>$tmp2</td>
				$tccell1 width=40px>&nbsp;</td>
				$tccell2l><i>(thread deleted)</i></td>
				$tccell2>????</td>
				$tccell1>????</td>
				$tccell1>????</td>
				$tccell1>????$smallfont<br>by ????</td>
			</td>";
    }
    $tmp1 = $ms['id'];

    if ($ms['mpl'] && $ms['mpl'] > $loguser['powerlevel']) {
			$threadlink = "<i>(restricted)</i>";
			$userlink = "????";
			$tpic = "&nbsp;";
			$replies = "????";
			$views = "????";
			$lastpost = "????$smallfont<br>by ????";
		}
		else {
			$threadlink = "<a href='{$GLOBALS['jul_views_path']}/thread.php?id=$ms[id]'>$ms[title]</a>";
			$threadlink .= '<br><span class="fonts" style="position: relative; top: -1px;">&nbsp;&nbsp;&nbsp;'
       ."In <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$ms[forum]'>".$ms['forumtitle']."</a>"
       .'</span>';
			$userlink = "<a href='{$GLOBALS['jul_views_path']}/profile.php?id=$ms[user]'><font ". getnamecolor($ms['sex1'], $ms['power1']) .">$ms[name1]</font></a>";
			$lastpost = date($dateformat,$ms['lastpostdate']+$tzoff)."
        $smallfont<br>by <a href='{$GLOBALS['jul_views_path']}/profile.php?id=$ms[user]'><font ". getnamecolor($ms['sex2'], $ms['power2']) .">$ms[name2]</font></a>
				<a href='{$GLOBALS['jul_views_path']}/thread.php?id=$ms[id]&end=1'>$statusicons[getlast]</a>
      ";

      $replies = $ms['replies'];
      $views = $ms['views'];
      $tpic = ($ms['icon']) ? "<img src='$ms[icon]'>" : "&nbsp;";
		}
		$threadstable .= "<tr>
			$tccell1>$ms[id]</td>
			$tccell1 width=40px style=\"max-width:40px;max-height:30px;overflow:hidden;\">$tpic</td>
			$tccell2l>$threadlink</td>
			$tccell2>$userlink</td>
			$tccell1>$replies</td>
			$tccell1>$views</td>
			$tccell1>$lastpost</td>
		</td>";
	}



	print "
		$header
			<br>
			<table class='table' cellspacing='0'>
			$poststable
			$tblend
			<br>
			$tblstart
			$threadstable
			$tblend
    $footer";
		printtimedif($startingtime);

?>
