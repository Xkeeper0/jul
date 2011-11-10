<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print $header;
  if(!$page) $page=0;
  if(!$ppp) $ppp=50;
  $min=$ppp*$page;
  $posts=mysql_query("SELECT p.id,thread,ip,date,num,t.title,minpower FROM posts p,threads t,forums f WHERE p.user=$id AND thread=t.id AND t.forum=f.id ORDER BY p.id DESC");
  $posttotal=mysql_num_rows($posts);
  $pagelinks=$smallfont.'Pages:';
  for($i=0;$i<($posttotal/$ppp);$i++){
    if($i==$page) $pagelinks.=' '.($i+1);
    else $pagelinks.=" <a href=postsbyuser.php?id=$id&ppp=$ppp&page=$i>".($i+1).'</a>';
  }
  $postlist="
	$tccellhs width=50>#</td>
	$tccellhs width=50>Post</td>
	$tccellhs width=130>Date</td>
	$tccellhs>Thread</td>
  ";
  if($isadmin) $postlist.="$tccellhs width=110>IP address</td>";
  for($i=0;$post=mysql_fetch_array($posts);$i++){
    if($i>=$min and $i<($min+$ppp)){
	if($post[minpower]<=$power or !$post[minpower]) $threadlink="<a href=thread.php?pid=$post[0]#$post[0]>".str_replace('<','&lt',$post[title]).'</a>';
	else $threadlink='(restricted)';
	if(!$post[num]) $post[num]='?';
	$postlist.="
	  <tr>
	  $tccell1s>$post[0]</td>
	  $tccell1s>$post[num]</td>
	  $tccell1s>".date($dateformat,$post[3]+$tzoff)."</td>
	  $tccell1ls>#<a href=thread.php?id=$post[thread]>$post[1]</a> - $threadlink";
	if($isadmin) $postlist.="</td>$tccell1s>$post[2]";
    }
  }
  $user=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$id"));
  print $fonttag."Posts by $user[name] on the board: ($posttotal posts found)
	$tblstart$postlist$tblend$pagelinks$footer";
  printtimedif($startingtime);
?>