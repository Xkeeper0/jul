<?php
	require 'lib/function.php';
	require 'lib/layout.php';
	$misc=mysql_fetch_array(mysql_query('SELECT * FROM misc'));
	$tstats=mysql_query('SHOW TABLE STATUS');
	while($t=mysql_fetch_array($tstats)) $tbl[$t[Name]]=$t;
	function sp($sz){
//    $b="$sz B";
//    if($sz>1023) $b=sprintf('%01.2f',$sz/1024).' kB';
//    if($sz>10239) $b=sprintf('%01.1f',$sz/1024).' kB';
//    if($sz>102399) $b=sprintf('%01.0f',$sz/1024).' kB';
//    if($sz>1048575) $b=sprintf('%01.2f',$sz/1048576).' MB';
//    if($sz>10485759) $b=sprintf('%01.1f',$sz/1048576).' MB';
//    if($sz>104857599) $b=sprintf('%01.0f',$sz/1048576).' MB';
		$b=number_format($sz,0,'.',',');
		return $b;
	}
	$sch_info = "";
	$schemes = mysql_query('
		SELECT COUNT(u.id) as schemecount, u.scheme, schemes.name
		FROM users AS u
		LEFT JOIN schemes ON (u.scheme = schemes.id)
		WHERE (schemes.ord >= 0)
		GROUP BY u.scheme
		ORDER BY schemecount DESC
	');

	while ($row = mysql_fetch_array($schemes)) {
		$sch_info .= "<tr>$tccell1>$row[name]</td>$tccell1>$row[schemecount]</tr>";
	}

	function tblinfo($n){
		global $tbl,$tccell2,$tccell2l;
		$t=$tbl[$n];
		return "
		<tr align=right>
		$tccell2>$t[Name]</td>
		$tccell2l>".sp($t[Rows]) ."</td>
		$tccell2l>".sp($t[Avg_row_length])."</td>
		$tccell2l>".sp($t[Data_length])."</td>
		$tccell2l>".sp($t[Index_length])."</td>
		$tccell2l>".sp($t[Data_free])."</td>
		$tccell2l>".sp($t[Data_length]+$t[Index_length]);
	}
	print "
	$header<br>$tblstart
	$tccellh>Records$tccellh>&nbsp<tr>
	$tccell1s><b>Most posts within 24 hours:</td>
	$tccell2ls>$misc[maxpostsday], on ".date($dateformat,$misc[maxpostsdaydate])."<tr>
	$tccell1s><b>Most posts within 1 hour:</td>
	$tccell2ls>$misc[maxpostshour], on ".date($dateformat,$misc[maxpostshourdate])."<tr>
	$tccell1s><b>Most users online:</td>
	$tccell2ls>$misc[maxusers], on ".date($dateformat,$misc[maxusersdate])."$misc[maxuserstext]
	$tblend<br>
<!--
	This is kind of in Edit Profile already.
	$tblstart<tr>$tccellh colspan='2'>Scheme Usage Breakdown</td></tr>
	<tr>$tccellh>Scheme Name</td>$tccellh>Users</td></tr>
	$sch_info
	$tblend<br>
-->
	$tblstart
	$tccellh>Table name</td>
	$tccellh>Rows</td>
	$tccellh>Avg. data/row</td>
	$tccellh>Data size</td>
	$tccellh>Index size</td>
	$tccellh>Overhead</td>
	$tccellh>Total size"
	.tblinfo('posts_text')
	.tblinfo('posts')
	.tblinfo('pmsgs_text')
	.tblinfo('pmsgs')
	.tblinfo('postlayouts')
	.tblinfo('threads')
	.tblinfo('users')
	.tblinfo('forumread')
	.tblinfo('threadsread')
	.tblinfo('postradar')
	.tblinfo('ipbans')
	.tblinfo('defines')
	.tblinfo('dailystats')
	.tblinfo('rendertimes')



	."$tblend<br>$tblstart
	$tccellhs colspan=9>Daily stats<tr>
	$tccellcs>Date</td>
	$tccellcs>Total users</td>
	$tccellcs>Total posts</td>
	$tccellcs>Total threads</td>
	$tccellcs>Total views</td>
	$tccellcs>New users</td>
	$tccellcs>New posts</td>
	$tccellcs>New threads</td>
	$tccellcs>New views
  ";
	$users=0;
	$posts=0;
	$threads=0;
	$views=0;
	$stats=mysql_query("SELECT * FROM dailystats");
	while($day=mysql_fetch_array($stats)){
		print "<tr>
		$tccell1s>$day[date]</td>
		$tccell2s>$day[users]</td>
		$tccell2s>$day[posts]</td>
		$tccell2s>$day[threads]</td>
		$tccell2s>$day[views]</td>
		$tccell2s>".($day[users]-$users)."</td>
		$tccell2s>".($day[posts]-$posts)."</td>
		$tccell2s>".($day[threads]-$threads)."</td>
		$tccell2s>".($day[views]-$views)."</td>
		";
		$users=$day[users];
		$posts=$day[posts];
		$threads=$day[threads];
		$views=$day[views];
	}
	print $tblend.$footer;
	printtimedif($startingtime);
?>
