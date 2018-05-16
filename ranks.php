<?php
	require 'lib/function.php';
	require 'lib/layout.php';

	$set = (($_GET['set']) ? (int)$_GET['set'] : -1);
	$showall = (($_GET['showall']) ? 1 : 0);

	$rsets = $sql->query('SELECT * FROM ranksets WHERE id>0 ORDER BY id');
	while($rset = $sql->fetch($rsets)) {
		// First rankset
		if($set < 0) $set = $rset['id'];
		$selected = (($rset['id']==$set) ? 'selected' : '' );

		$ranksetlist.="<option value={$rset['id']} {$selected}>{$rset['name']}";
	}
	$ch[$showall]='checked';

	print "
		$header
		<FORM ACTION=ranks.php NAME=REPLIER>
		$tblstart
		$tccellh colspan=2>&nbsp;<tr>
		$tccell1><b>Rank set</b></td>
		$tccell2l><select name=set>$ranksetlist</select><tr>
		$tccell1><b>Users to show</b></td>
		$tccell2l>
			$radio=showall value=0 $ch[0]> Selected rank set only
			&nbsp; &nbsp;
			$radio=showall value=1 $ch[1]> All users
		<tr>
		$tccellh colspan=2>&nbsp;<tr>
		$tccell1>&nbsp;</td>
		$tccell2l><input type=submit class=submit value=View></td>
		</FORM>
		$tblend
		<br>
		$tblstart
		$tccellh width=150>Rank</td>
		$tccellh width=60>Posts</td>
		$tccellh width=60>Ranking</td>
		$tccellh colspan=2>Users on that rank
	";

	
	$btime=ctime()-86400*30;

	$where = array('rset' => $set);
	$ranks = $sql->queryp("SELECT * FROM ranks WHERE rset=:rset ORDER BY num", $where);
	$totalranks = $sql->num_rows($ranks);

	if ($totalranks > 0) {
		$rank  = $sql->fetch($ranks);
		
		// Rank divider
		$qwhere = "posts >= ?";
		$where  = array($rank['num']);
		// Rank select
		if (!$showall) {
			$qwhere .= " AND useranks=?";
			$where[] = $set;
		}
		// 300 queries [11sec] ---> 20 queries [1sec]	
		$users = $sql->queryp("SELECT id,name,sex,powerlevel,aka,birthday,posts,lastactivity,lastposttime FROM users WHERE {$qwhere} ORDER BY posts ASC", $where);
		$user  = $sql->fetch($users);
		$total = $sql->num_rows($users);
	}
	
	for($i=0; $i<$totalranks; ++$i) {
		$rankn=$sql->fetch($ranks);
		if(!$rankn['num']) $rankn['num']=8388607;

		$userarray = array();
		$inactive = 0;

		for($u=0;$user && $user['posts'] < $rankn['num'];$u++){
			if (max($user['lastactivity'], $user['lastposttime']) > $btime)
				$userarray[$user['name']] = getuserlink($user);
			else ++$inactive;
			$user = $sql->fetch($users);
		}

		@ksort($userarray);
		$userlisting = implode(", ", $userarray);

		if($inactive) $userlisting.=($userlisting?', ':'')."$inactive inactive";
		if(!$userlisting) $userlisting='&nbsp;';

		if($userlisting != '&nbsp;' || $rank['num'] <= $loguser['posts'] || $ismod) {
			print "<tr>
				$tccell2ls width=200>$rank[text]</td>
				$tccell1 width=60>$rank[num]</td>
				$tccell1 width=60>$total</td>
				$tccell1 width=30>$u</td>
				$tccell2s width=*>$userlisting";
		}
		else {
			print "<tr>
				$tccell1>? ? ?</td>
				$tccell1>???</td>
				$tccell1>?</td>
				$tccell1>?</td>
				$tccell1s>?";
		}
		$rank = $rankn;
		$total -= $u;
	}

	print $tblend.$footer;
	printtimedif($startingtime);
?>