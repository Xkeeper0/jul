<?php
	require 'lib/function.php';
	require 'lib/rpg.php';
	require 'lib/layout.php';

	function sortbyexp($a,$b) {
		$cmpa = (($a['exp'] === 'NaN') ? -1 : intval($a['exp']));
		$cmpb = (($b['exp'] === 'NaN') ? -1 : intval($b['exp']));
		if ($cmpa==$cmpb) return $a['id']-$b['id'];
		return $cmpb-$cmpa;
	}

	$sex	= $_GET['sex'] ?? null;
	$qsex	= ($sex !== null ? "&sex=$sex" : "");

	$pow	= null;
	$qpow	= "";
	if (isset($_GET['pow'])) {
		$pow	= intval($_GET['pow']);
		$qpow	= "&pow=$pow";
	}
	$qrpg	= "";
	$rpg	= intval($_GET['rpg'] ?? 0);
	$qrpg	= "&rpg=$rpg";

	$sort	= $_GET['sort'] ?? "posts";
	if (!in_array($sort, array('name','reg','exp','age','posts', 'act')))
		$sort = 'posts';


	$page	= intval($_GET['page'] ?? 0);
	$ppp	= intval($_GET['ppp'] ?? 50);
	$qppp	= ($ppp !== 50) ? "&ppp=$ppp" : "";



	$qwhere = [];
	if($sex=='m') $qwhere[] = '(sex=0)';
	if($sex=='f') $qwhere[] = '(sex=1)';
	if($sex=='n') $qwhere[] = '(sex=2)';

	if ($pow !== null) {
		if (($pow == 1 || $pow == 0) && $loguser['powerlevel'] <= 0)
			$pow = "IN (0, 1)";
		elseif ($pow == 3 || $pow == 4) // merge admin + sysadmin (they appear the same)
			$pow = "IN (3, 4)";
		elseif ($pow == -1 || $pow == -2) // merge banned + permabanned
			$pow = "IN (-1, -2)";
		else
			$pow = "= '$pow'";

		$qwhere[] = "powerlevel $pow";
	}

	$where = 'WHERE '.((empty($qwhere)) ? '1' : implode(' AND ', $qwhere));


	$query='SELECT id,posts,regdate,lastactivity,name,minipic,sex,powerlevel,aka,r.* FROM users LEFT JOIN users_rpg r ON id=uid ';
	if($sort=='name')  $users1=$sql->query("$query$where ORDER BY name", MYSQL_ASSOC);
	if($sort=='reg')   $users1=$sql->query("$query$where ORDER BY regdate DESC", MYSQL_ASSOC);
	if($sort=='act')   $users1=$sql->query("$query$where ORDER BY lastactivity DESC", MYSQL_ASSOC);
	if($sort=='exp')   $users1=$sql->query("$query$where", MYSQL_ASSOC);
	if($sort=='age')   $users1=$sql->query("$query$where AND birthday ORDER BY birthday", MYSQL_ASSOC);
	if($sort=='posts') $users1=$sql->query("$query$where ORDER BY posts DESC", MYSQL_ASSOC);

	$numusers=mysql_num_rows($users1);

	for ($i = 0; $user = $sql->fetch($users1); $i++) {
		$user['days'] = (ctime() - $user['regdate']) / 86400;
		$user['exp']  = calcexp($user['posts'], $user['days']);
		$user['lvl']  = calclvl($user['exp']);
		$users[] = $user;
	}

	if($sort=='exp')
		usort($users,'sortbyexp');

	$pagelinks=$smallfont.'Pages:';
	for($i=0;$i<($numusers/$ppp);$i++)
		$pagelinks.=($i==$page?' '.($i+1):" <a href=memberlist.php?sort=$sort$qsex$qpow$qrpg$qppp&page=$i>".($i+1).'</a>');

	$lnk='<a href=memberlist.php?sort';

	print "
		$header<br>$tblstart
		<tr>
		$tccellh colspan=2>$numusers user". ($numusers != 1 ? "s" : "") ." found.
		</tr><tr>
		$tccell1s>	Sort by:
		$tccell2s>
			$lnk=posts$qpow$qsex>Total posts</a> |
			$lnk=exp$qpow$qsex>EXP</a> |
			$lnk=name$qpow$qsex>User name</a> |
			$lnk=reg$qpow$qsex>Registration date</a> |
			$lnk=act$qpow$qsex>Last activity</a> |
			$lnk=age$qpow$qsex>Age</a>
		</tr><tr>
		$tccell1s>	Sex:
		$tccell2s>
			$lnk=$sort$qpow&sex=m>Male</a> |
			$lnk=$sort$qpow&sex=f>Female</a> |
			$lnk=$sort$qpow&sex=n>N/A</a> |
			$lnk=$sort$qpow>All</a><tr>
		</tr><tr>
		$tccell1s>	Powerlevel:
		$tccell2s>
			$lnk=$sort$qsex&pow=-1>Banned</a> |
			$lnk=$sort$qsex&pow=0>Normal</a> |
			". ($loguser['powerlevel'] >= 1 ? "$lnk=$sort$qsex&pow=1>Normal +</a> | " : "") ."
			$lnk=$sort$qsex&pow=2>Moderator</a> |
			$lnk=$sort$qsex&pow=3>Administrator</a> |
			$lnk=$sort$qsex>All</a>
		</tr>$tblend<br>$tblstart
		<tr>
		$tccellh width=30>#</td>
		$tccellh width=16><img src=images/_.gif width=16 height=8></td>
		$tccellh>Username</td>
	";

	if (!$rpg) {
		print "
			$tccellh width=200>Registered on</td>
			$tccellh width=200>Last active</td>
			$tccellh width=60>Posts</td>
			$tccellh width=35>Level</td>
			$tccellh width=100>EXP</td></tr>
		";
	} else {
		$items   = $sql->getarraybykey("SELECT * FROM items", 'id');
		$classes = $sql->getarraybykey("SELECT * FROM rpg_classes", 'id');

		print "$tccellh width=35>Level</td>";
		print "$tccellh width=90>Class</td>";
		for($i=0;$i<9;$i++) print "$tccellh width=65>".$stat[$i].'</td>';
		print "$tccellh width=80><img src=images/coin.gif></td>";
		print "$tccellh width=60><img src=images/coin2.gif></td>";
		print "</tr>";
	}

	$s = $ppp*$page;
	$ulist	= "";
	for($u=0;$u < $ppp;$u++) {
		$i = $s + $u;
		$user = $users[$i] ?? null;
		if (!$user) break;
		$ulist.="<tr style=\"height:24px;\">";

		$userpicture='&nbsp';
		if ($user['minipic'])
			$userpicture="<img width=16 height=16 src='".str_replace('>','&gt;',$user['minipic'])."'>";

		$userlink = getuserlink($user);
		$ulist.="
			$tccell2>".($i+1)."</td>
			$tccell1l>{$userpicture}</td>
			$tccell2l>{$userlink}</td>
		";

		if(!$rpg){
			$ulist.="
				$tccell2><span title='". timeunits2(ctime() - $user['regdate']) ." ago'>".date($dateformat,$user['regdate']+$tzoff)."</span></td>
				$tccell2><span title='". timeunits2(ctime() - $user['lastactivity']) ." ago'>".date($dateformat,$user['lastactivity']+$tzoff)."</span></td>
				$tccell1r>{$user['posts']}</td>
				$tccell1r>{$user['lvl']}</td>
				$tccell1r>{$user['exp']}</td>
			";
		}
		else {
			if (!($class = ($classes[$user['class']] ?? null)))
				$class = array('name'=>'None');
			$stats=getstats($user,$items,$class);

			$ulist.="$tccell1>$user[lvl]</td>";
			$ulist.="$tccell1>$class[name]</td>";
			for($k=0;$k<9;$k++) $ulist.="$tccell1s>".$stats[$stat[$k]].'</td>';
			$ulist.="$tccell1s>$stats[GP]</td>";
			$ulist.="$tccell1s>$user[gcoins]</td>";
		}
		$ulist.="</tr>";
	}

	print "$ulist$tblend$pagelinks$footer";
	printtimedif($startingtime);
