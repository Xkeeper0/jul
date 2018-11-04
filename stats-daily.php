<?php
	require 'lib/function.php';
	require 'lib/layout.php';

	print "
	$header
	<br>$tblstart
	<tr>$tccellh>Information</td></tr>
	<tr>$tccellc>
		<br>
		Daily stats for the board. This page might take a while to fully display.
		<br><br>
	</td>
	</tr>
	$tblend
	<br>
  $tblstart<tr>
	$tccellh colspan=9>Daily stats<tr>
	$tccellc>Date</td>
	$tccellc>Total users</td>
	$tccellc>Total posts</td>
	$tccellc>Total threads</td>
	$tccellc>Total views</td>
	$tccellc>New users</td>
	$tccellc>New posts</td>
	$tccellc>New threads</td>
	$tccellc>New views</td></tr>
  ";
	$users=0;
	$posts=0;
	$threads=0;
	$views=0;
	$stats=$sql->query("SELECT * FROM dailystats");
	$oldyear	= "";
	while($day=$sql->fetch($stats)){
		$year	= substr($day['date'], 6);
		if ($year !== $oldyear) {
			print "<tr>
		  	$tccellh colspan=9>20$year<tr>";
			$oldyear	= $year;
		}
		print "<tr>
		$tccell1>$day[date]</td>
		$tccell2>". number_format($day['users']) ."</td>
		$tccell2>". number_format($day['posts']) ."</td>
		$tccell2>". number_format($day['threads']) ."</td>
		$tccell2>". number_format($day['views']) ."</td>
		$tccell2>". number_format($day['users']-$users)."</td>
		$tccell2>". number_format($day['posts']-$posts)."</td>
		$tccell2>". number_format($day['threads']-$threads)."</td>
		$tccell2>". number_format($day['views']-$views)."</td></tr>
		";
		$users=$day['users'];
		$posts=$day['posts'];
		$threads=$day['threads'];
		$views=$day['views'];
	}
	print $tblend.$footer;
	printtimedif($startingtime);
