<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location,homepageurl';}
    
  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit;

    $userrank=getrank($post[useranks],$post[title],$post[num],$post[powerlevel]);
    if($quote) $quote='['.$quote.']';
    $edit=str_replace(' | ','',$edit);
    $edit=str_replace('a>','a>] ',$edit);
    $edit=str_replace('<a','[<a',$edit);
    $ip=str_replace('| ','&nbsp; &nbsp;',$ip);
    if($post[homepageurl]) $homepage=" [<a href=$post[homepageurl]>www</a>]";
    if($post[location]) $location="<br>Location: $post[location]";
    $since='Registered: '.date('M Y',$post[regdate]+$tzoff);
    $postdate=date('m-d-Y h:i A',$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=" - posted in $set[threadlink]";
    $u=$post[uid];
    return "
	$set[tdbg]>
	  $set[userlink]$smallfont<br>
	  $set[userrank]<br>
	  $set[userpic]<br><br>
	  $since$location<br>
	  Posts: $post[posts]</td>
	$set[tdbg] width=80%>$post[headtext]$post[text]$post[signtext]$set[edited]<tr>
	$set[tdbg]><table class=fonts><td>$postdate</table></td>
	$set[tdbg]>
	  <table width=100% class=fonts>
	    <td>[<a href=profile.php?id=$u>Profile</a>] [<a href=sendprivate?userid=$u>Send PM</a>]$homepage [<a href=thread.php?user=$u>Search</a>]$threadlink</td>
	    <td width=270>$quote $edit $ip
	  </table>
    ";
  }
?>