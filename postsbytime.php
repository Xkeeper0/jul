<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if(!$posttime) $posttime=86400;
  $time=ctime()-$posttime;
  if($id){
    $user=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$id"));
    $from=" from $user[name]";
  }else $from=' on the board';
  $posts=mysql_query("SELECT count(*) AS cnt, FROM_UNIXTIME(date,'%k') AS hour FROM posts WHERE ".($id?"user=$id AND ":'')."date>$time GROUP BY hour");
  if($posttime<999999999) $during=' during the last '.timeunits2($posttime);
  $link="<a href=postsbytime.php?id=$id&posttime";
  print "$header$smallfont
	$link=3600>During last hour</a> |
	$link=86400>During last day</a> |
	$link=604800>During last week</a> |
	$link=2592000>During last 30 days</a> |
	$link=999999999>Total</a><br>
	$fonttag Posts$from by time of day$during:
	$tblstart
	 $tccellh width=40>Hour</td>
	 $tccellh width=50>Posts</td>
	 $tccellh>&nbsp<tr>";
  for($i=0;$i<24;$i++) $postshour[$i]=0;
  while($h=mysql_fetch_array($posts)) $postshour[$h[hour]]=$h[cnt];
  for($i=0;$i<24;$i++) if($postshour[$i]>$max) $max=$postshour[$i];
  for($i=0;$i<24;$i++){
    if($i) print '<tr>';
    $bar="<img src=images/$numdir".'bar-on.gif width='.(@floor($postshour[$i]/$max*10000)/100).'% height=8>';
    print "
	$tccell2s>$i</td>
	$tccell2s>$postshour[$i]</td>
	$tccell2ls width=100%>$bar
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>