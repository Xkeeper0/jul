<?php
 // function userfields(){return 'posts,sex,powerlevel,birthday,aka';}
  function userfields(){
      return 'posts,sex,powerlevel,picture,aka';
  }

  function postcode($post,$set){
    global $smallfont,$ip,$quote,$edit, $tblstart;

    $postnum=($post[num]?"$post[num]/":'').$post[posts];
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "$tblstart
	$set[tdbg]><div class='mobile-avatar'>$set[userpic]</div>
	  $set[userlink]<br>
	  $smallfont Posts: $postnum</td>
	$set[tdbg] width=50% align=right>
	    $smallfont Posted on $set[date]$threadlink
	   <br>$quote$edit
       <br>$ip
	  <tr>
	$set[tdbg] height=60 colspan=2 id=\"post". $post['id'] ."\">$post[headtext]$post[text]$post[signtext]$set[edited]</td></table><br>
    ";
  }
?>
