<?php
	if(!$_GET['posttime']) $posttime=86400;
	else $posttime = $_GET['posttime'];
  require 'lib/function.php';
  require 'lib/layout.php';
  $posters=mysql_query("SELECT t.id,t.replies,t.title,t.forum,f.minpower,COUNT(p.id) cnt FROM threads t,posts p,forums f WHERE p.user=$id AND p.thread=t.id AND p.date>".(ctime()-$posttime).' AND t.forum=f.id GROUP BY t.id ORDER BY cnt DESC');
  $u=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$id"));
  $username=$u[name];
  $lnk="<a href=postsbythread.php?id=$id&posttime";
  if($posttime<999999999) $during=' during the last '.timeunits2($posttime);
  print "$header$smallfont
	$lnk=3600>During last hour</a> |
	$lnk=86400>During last day</a> |
	$lnk=604800>During last week</a> |
	$lnk=2592000>During last 30 days</a> | 
	$lnk=999999999>Total</a><br>
	$fonttag Posts by $username in threads$during:
	<table class='table tdbg2 font center' cellspacing=0>
	$tccellh width=20>&nbsp</td>
	$tccellh>Thread</td>
	$tccellh width=60>Posts</td>
	$tccellh width=80>Thread total
  ";
  for($i=1;$t=mysql_fetch_array($posters);$i++){
    print "
	<tr>
	<td class=tbl>$i</td>
	<td class=tbl align=left>
    ";
    if($t[minpower]>$power && $t[minpower]>0)
	print '-- Private thread --';
    else print "<a href=thread.php?id=$t[id]>$t[title]</a>";
    print "
	</td>
	<td class=tbl><b>$t[cnt]</td>
	<td class=tbl>".($t[replies]+1)
    ;
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>