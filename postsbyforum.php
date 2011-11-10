<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if($id){
    $useridquery="posts.user=$id AND";
    $by='by ';
    $u=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$id"));
    $username=$u[name];
  }
	
	if(!$_GET['posttime']) $posttime=86400;
	else $posttime = $_GET['posttime'];
  
  $posters=mysql_query("SELECT forums.*,COUNT(posts.id) AS cnt FROM forums,threads,posts WHERE $useridquery posts.thread=threads.id AND threads.forum=forums.id AND posts.date>".(ctime()-$posttime).' GROUP BY forums.id ORDER BY cnt DESC');
  $userposts=mysql_num_rows(mysql_query("SELECT id FROM posts WHERE $useridquery date>".(ctime()-$posttime).''));
  $lnk="<a href=postsbyforum.php?id=$id&posttime";
  if($posttime<999999999) $during=' during the last '.timeunits2($posttime);
  print "$header
	$smallfont
	$lnk=3600>During last hour</a> |
	$lnk=86400>During last day</a> |
	$lnk=604800>During last week</a> |
	$lnk=2592000>During last 30 days</a> | 
	$lnk=999999999>Total</a><br>
	$fonttag Posts $by$username in forums$during:
	$tblstart
	 $tccellh width=20>&nbsp</td>
	 $tccellh>Forum</td>
	 $tccellh width=60>Posts</td>
	 $tccellh width=80>Forum total<tr>
  ";
  for($i=1;$f=mysql_fetch_array($posters);$i++){
      if($i>1) print '<tr>';
	if($f[minpower]>$power) $link="(restricted)";
	else $link="<a href=forum.php?id=$f[id]>$f[title]</a>";
      print "
	  $tccell2>$i</td>
	  $tccell2l>$link</td>
	  $tccell2><b>$f[cnt]</td>
	  $tccell2>$f[numposts]
      ";
  }
  print "$tblend Total: $userposts posts$footer";
  printtimedif($startingtime);
?>