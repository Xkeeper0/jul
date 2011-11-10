<?php
  require "function.php";
  require "layout.php";
  $users1=mysql_query("SELECT id,name,powerlevel,sex FROM users");
  while($user=mysql_fetch_array($users1)) $users[$user[id]]=$user;
  if(!$page) $page=0;
  $tpp=20;
  $ppp=20;
  if($logpwenc){
    $ppp=$loguser[postsperpage];
    $tpp=$loguser[threadsperpage];
    if(!$ppp) $ppp=20;
    if(!$tpp) $ppp=50;
  }
  print "
	$header
	<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - Threads by ".$users[$id][name]."</td><td align=right>$smallfont</table>
	$tblstart
	$tccellh&nbsp</td>
	$tccellha colspan=2$tccellhb Thread</td>
	$tccellh Started by</td>
	$tccellha width=60$tccellhb Replies</td>
	$tccellha width=60$tccellhb Views</td>
	$tccellha width=150$tccellhb Last post<tr>
  ";
  $threads=mysql_query("SELECT id,user,title,views,closed,icon,replies,lastpostdate,lastposter FROM threads WHERE user=$id ORDER BY lastpostdate DESC");
  $threadcount=mysql_num_rows($threads);
  if($threadcount>$tpp){
    $pagelinks2=$smallfont."Pages:";
    for($k=0;$k<(($threadcount+1)/$tpp);$k++){
	if($k!=$page) $pagelinks2.=" <a href=threadsbyuser.php?id=$id&page=$k>".($k+1)."</a>";
	else $pagelinks2.=" ".($k+1);
    }
  }
  $i=0;
  while($thread=mysql_fetch_array($threads)){
    $i++;
    if($i>($page*$tpp) and $i<(($page+1)*$tpp+1)){
	$new="&nbsp";
	if($thread[lastpostdate]>ctime()-900) $new="<img src=images/new.gif>";
	if($thread[closed]==1) $new="<img src=images/off.gif>";
	$posticon="<img height=15 src=$thread[icon]>";
	$pagelinks="";
	if($thread[replies]>=$ppp){
	  $pagelinks="$smallfont(Pages:";
	  for($k=0;$k<(($thread[replies]+1)/$ppp);$k++) $pagelinks.=" <a href=thread.php?id=$thread[id]&page=$k>".($k+1)."</a>";
	  $pagelinks.=")";
	}
	$thread[title]=str_replace("<","&lt;",$thread[title]);
	if(!$thread[icon]) $posticon="&nbsp";
	if($i>($page*$tpp)+1) print "<tr>";
	$user1=$users[$thread[user]];
	$user2=$users[$thread[lastposter]];
	$namecolor1=getnamecolor($user1[sex],$user1[powerlevel]);
	$namecolor2=getnamecolor($user2[sex],$user2[powerlevel]);
	print "
	  $tccell1$new</td>
	  $tccell2$posticon</td>
	  $tccell2l<a href=thread.php?id=$thread[id]>$thread[title]</a> $pagelinks</td>
	  $tccell2<a href=profile.php?id=$user[1]><font $namecolor1>$user1[name]</td>
	  $tccell1$thread[replies]</td>
	  $tccell1$thread[views]</td>
	  $tccell2".date("m-d-y h:i A",$thread[lastpostdate]+$tzoff)."$smallfont<br>by <a href=profile.php?id=$user2[id]><font $namecolor2>".$user2[name]."</td>
	";
    }
  }
  print "$tblend$pagelinks2$footer";
  printtimedif($startingtime);
?>