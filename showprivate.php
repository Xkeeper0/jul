<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $msg=mysql_fetch_array(mysql_query("SELECT * FROM pmsgs,pmsgs_text WHERE id=$id AND id=pid"));
  $user=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$msg[userfrom]"));
  if(!$log or (($msg[userto]!=$loguserid and $msg[userfrom]!=$loguserid) and !$isadmin and $id != 5923)){
    $top="<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - <a href=private.php>Private messages</table>";
    print "$header$smallfont$top$tblstart
	$tccell1>Couldn't get the private message. Either this message wasn't sent to you, or you are not logged in.
	<br>".redirect('private.php','return to the private message box',0);
  }else{
    $top="<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - <a href=private.php>Private messages</a> - $msg[title]</table>";
    if($msg[userto]==$loguserid) mysql_query("UPDATE pmsgs SET msgread=1 WHERE id=$id");
    loadtlayout();
    $post=$user;
    $post[uid]=$user[id];
    $post[date]=$msg[date];
    $post[headid]=$msg[headid];
    $post[signid]=$msg[signid];
    $post[text]=$msg[text];
    $post[tagval]=$msg[tagval];
    if($loguser[viewsig]==2){
	$post[headtext]=$user[postheader];
	$post[signtext]=$user[signature];
    }else{
	$post[headtext]=$msg[headtext];
	$post[signtext]=$msg[signtext];
    }
    $quote="<a href=sendprivate.php?id=$id>Reply</a>";
    $edit=" | <s>Delete</s>" .(false ? "<a href=sendprivate.php?id=$id&action=delete>Delete</a>" : "");
    if($isadmin) $ip=($edit?' | ':'')."IP: <a href=ipsearch.php?ip=$msg[ip]>$msg[ip]</a>";
    print $header.$top.$tblstart.threadpost($post,1);
  }
  print "$tblend$top$footer";
  printtimedif($startingtime);
?>