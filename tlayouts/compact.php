<?php
  function userfields(){return 'posts,sex,powerlevel,birthday,aka';}

  function postcode($post,$set){
    global $smallfont,$ip,$quote,$edit, $tblstart;

    $postnum=($post[num]?"$post[num]/":'').$post[posts];
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "$tblstart
	$set[tdbg]>
	  $set[userlink]<br>
	  $smallfont Posts: $postnum</td>
	$set[tdbg] width=50% align=right>
	    $smallfont Posted on $set[date]$threadlink
	   <br>$quote$edit$ip
	  <tr>
	$set[tdbg] height=60 colspan=2 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td></table>
    ";
  }
?>