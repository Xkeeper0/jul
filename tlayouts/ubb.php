<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit;

    $since='<br>Registered: '.date('M Y',$post[regdate]+$tzoff);
    $postdate=date('m-d-Y h:i A',$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=" in $set[threadlink]";
    return "
	$set[tdbg]>
	  $set[userlink]$smallfont<br>
	  $set[userrank]<br>
	  $set[userpic]<br><br>
	  Posts: $post[posts]
	  $set[location]$since</td>
	$set[tdbg] width=80%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>posted $postdate$threadlink</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><hr>
	  $post[headtext]$post[text]$post[signtext]$set[edited]</td>
    ";
  }
?>