<?php
  require_once('../lib/function.php');
  $windowtitle = "IP Address Search";
  require_once('../lib/layout.php');
  print "$header<br>";
  admincheck();
  print adminlinkbar("{$GLOBALS['jul_views_path']}/ipsearch.php");
  print "$tblstart";

	if(!$su) $su='n';
	if(!$sp) $sp='u';
	if(!$sm) $sm='n';
	if(!$d)  $d='y';
	$ch1[$su]=' checked';
	$ch2[$sp]=' checked';
	$ch3[$sm]=' checked';
	$ch4[$d]=' checked';

	print "
	  <form action='{$GLOBALS['jul_views_path']}/ipsearch.php' method=post>
	  $tccellh colspan=2>IP search<tr>
	  $tccell1 width=20%><b>IP to search:</b></td>
	  $tccell2l>$inpt=ip size=15 maxlength=15 value=$ip><tr>
	  $tccell1><b>Sort users by:</b></td>
	  $tccell2l>
	    $radio=su value=n$ch1[n]> Name &nbsp; &nbsp;
	    $radio=su value=p$ch1[p]> Posts &nbsp; &nbsp;
	    $radio=su value=r$ch1[r]> Registration &nbsp; &nbsp;
	    $radio=su value=s$ch1[s]> Last post &nbsp; &nbsp;
	    $radio=su value=a$ch1[a]> Last activity &nbsp; &nbsp;
	    $radio=su value=i$ch1[i]> Last IP
	  <tr>
	  $tccell1><b>Sort posts by:</b></td>
	  $tccell2l>
	    $radio=sp value=u$ch2[u]> User &nbsp; &nbsp;
	    $radio=sp value=d$ch2[d]> Date &nbsp; &nbsp;
	    $radio=sp value=i$ch2[i]> IP
	  <tr>
	  $tccell1><b>Sort private messages by:</b></td>
	  $tccell2l>
	    $radio=sm value=n$ch3[n]> Sent by &nbsp; &nbsp;
	    $radio=sm value=d$ch3[d]> Date &nbsp; &nbsp;
	    $radio=sm value=i$ch3[i]> IP
	  <tr>
	  $tccell1><b>Distinct users and IP's:</b></td>
	  $tccell2l>
	    $radio=d value=y$ch4[y]> Yes &nbsp; &nbsp;
	    $radio=d value=n$ch4[n]> No
	  <tr>
	  $tccell1>&nbsp</td>
	  $tccell1l>$inps=s value=Submit></td>
	  </form>
	";

	if($ip) {
		$ip=str_replace('*','%',$ip);
		switch($su) {
		  case 'n': $usort='ORDER BY name'; break;
		  case 'p': $usort='ORDER BY posts DESC'; break;
		  case 'r': $usort='ORDER BY regdate'; break;
		  case 's': $usort='ORDER BY lastposttime'; break;
		  case 'a': $usort='ORDER BY lastactivity'; break;
		  case 'i': $usort='ORDER BY lastip'; break;
		}
		switch($sp) {
		  case 'u': $psort='ORDER BY name'; break;
		  case 'd': $psort='ORDER BY date'; break;
		  case 'i': $psort='ORDER BY ip'; break;
		}
		switch($sm) {
		  case 'n': $msort='ORDER BY name1'; break;
		  case 'd': $msort='ORDER BY date'; break;
		  case 'i': $msort='ORDER BY ip'; break;
		}
		if($d === 'y') {
		  $pgroup='GROUP BY p.ip,u.id';
		  $mgroup='GROUP BY p.ip,u1.id';
		}
		$users=$sql->query("SELECT * FROM users WHERE lastip LIKE '$ip' $usort");
		$posts=$sql->query("SELECT p.*,u.name,u.sex,u.powerlevel,t.title FROM posts p,users u,threads t WHERE ip LIKE '$ip' AND p.user=u.id AND p.thread=t.id $pgroup $psort");
		$pmsgs=$sql->query("SELECT p.*,t.title,u1.name AS name1,u2.name AS name2,u1.sex AS sex1,u2.sex AS sex2,u1.powerlevel pow1,u2.powerlevel pow2 FROM pmsgs p,pmsgs_text t,users u1,users u2 WHERE ip LIKE '$ip' AND p.userfrom=u1.id AND p.userto=u2.id AND p.id=pid $mgroup $msort");

		print "
		  $tblend<br>$tblstart
		  $tccellh colspan=7><b>Users: ".mysql_num_rows($users)."</b><tr>
		  $tccellc>id</td>
		  $tccellc>Name</td>
		  $tccellc>Registered on</td>
		  $tccellc>Last post</td>
		  $tccellc>Last activity</td>
		  $tccellc>Posts</td>
		  $tccellc>Last IP</td>
		";
		for($c=0;$c<500 && $user=$sql->fetch($users);$c++)
		  print "
		    <tr>
		    $tccell2>$user[id]</td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/profile.php?id=$user[id]><font ".getnamecolor($user['sex'],$user['powerlevel']).">$user[name]</font></a></td>
		    $tccell1>".@date($dateformat,$user['regdate'])."</td>
		    $tccell1>".date($dateformat,$user['lastposttime'])."</td>
		    $tccell1>".date($dateformat,$user['lastactivity'])."</td>
		    $tccell1>$user[posts]</td>
		    $tccell2>$user[lastip]</td>
		  ";
		if($post=$sql->fetch($users))
		  print "<tr>$tccell2 colspan=7>Too many results!";

		print "
		  $tblend<br>$tblstart
		  $tccellh colspan=5><b>Posts: ".mysql_num_rows($posts)."</b><tr>
		  $tccellc>id</td>
		  $tccellc>Posted by</td>
		  $tccellc>Thread</td>
		  $tccellc>Date</td>
		  $tccellc>IP</td>
		";
		for($c=0;$c<500 && $post=$sql->fetch($posts);$c++)
			print "
		    <tr>
		    $tccell2>$post[id]</td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/profile.php?id=$post[user]><font ".getnamecolor($post['sex'],$post['powerlevel']).">$post[name]</font></a></td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/thread.php?id=$post[thread]>$post[title]</a></td>
		    $tccell1><nobr>".date($dateformat,$post['date'])."</nobr></td>
		    $tccell2>$post[ip]</td>
		  ";
		if($post=$sql->fetch($posts))
		  print "<tr>$tccell2 colspan=5>Too many results!";

		print "
		  $tblend<br>$tblstart
		  $tccellh colspan=6><b>Private messages: ".mysql_num_rows($pmsgs)."</b><tr>
		  $tccellc>id</td>
		  $tccellc>Sent by</td>
		  $tccellc>Sent to</td>
		  $tccellc>Title</td>
		  $tccellc>Date</td>
		  $tccellc>IP</td>
		";
		for($c=0;$c<500 && $pmsg=$sql->fetch($pmsgs);$c++)
			print "
		    <tr>
		    $tccell2>$pmsg[id]</td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/profile.php?id=$pmsg[userfrom]><font ".getnamecolor($pmsg['sex1'],$pmsg['pow1']).">$pmsg[name1]</font></a></td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/profile.php?id=$pmsg[userto]><font ".getnamecolor($pmsg['sex2'],$pmsg['pow2']).">$pmsg[name2]</font></a></td>
		    $tccell1><a href={$GLOBALS['jul_views_path']}/showprivate.php?id=$pmsg[id]>$pmsg[title]</a></td>
		    $tccell1><nobr>".date($dateformat,$pmsg['date'])."</nobr></td>
		    $tccell2>$pmsg[ip]</td>
		  ";
		if($pmsg=$sql->fetch($pmsgs))
			print "<tr>$tccell2 colspan=6>Too many results!";

  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>
