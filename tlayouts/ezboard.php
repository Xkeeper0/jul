<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit;

    if($post[num]) $userrank=getrank($post[useranks],$post[title],$post[num],$post[powerlevel]);
    $quote=str_replace('Quote','Reply',$quote);
    $edit=str_replace('>Delete','>Del',$edit);
    $ip=str_replace('| IP: ','',$ip);
    $postdate=date('n/j/y g:i:s a',$post[date]+$tzoff);
    if($set[threadlink]) $threadlink="<br>in $set[threadlink]";
    if(!$post[num]) $post[num]=$post[posts];
    return "
	$set[tdbg]>
	  $set[userlink]$smallfont<br>
	  <b>$userrank</b><br>
	  Posts: $post[num]<br>
	  ($postdate)
	  $threadlink<br>
	  $ip<br>$quote$edit<br>
	  $set[userpic]</td>
	$set[tdbg] width=80%>$post[headtext]$post[text]$post[signtext]$set[edited]</td>
    ";
  }
?>