<?php
  require "lib/function.php";
  $windowtitle = "Member Links";
  require "lib/layout.php";
  print "$header<br>
	$tblstart
	 <tr>$tccellh colspan=2>Username</td>
	 $tccellh >Homepage name</td>
	 $tccellh >Homepage URL</td>
	 $tccellh width=50>Posts</td></tr><tr>
  ";
  $users1 = mysql_query("SELECT id,posts,name,powerlevel,sex,minipic,homepageurl,homepagename FROM users WHERE homepageurl!='' ORDER BY posts DESC") or print mysql_error();
  while($user=mysql_fetch_array($users1)){
    if($i) print "<tr>";
    $i++;
    $hpage="<a href=$user[homepageurl]>$user[homepagename]";
    $url="<a href=$user[homepageurl]>$user[homepageurl]";
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    if($user[minipic]) $minipic="<img width=11 height=11 src=\"$user[minipic]\">"; else $minipic="&nbsp;";
    print "
	$tccell2 width=13>$minipic</td>
	$tccell2l><a href=profile.php?id=$user[id]><font $namecolor>$user[name]</td>
	$tccell2>$hpage</td>
	$tccell2>$url</td>
	$tccell2>$user[posts]</td></tr>";
  }
  print "$tblend$pagelinks$footer";
  printtimedif($startingtime);
?>