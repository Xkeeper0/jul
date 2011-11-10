<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $smilies=readsmilies();
  if($id) $msg=mysql_fetch_array(mysql_query("SELECT * FROM pmsgs,pmsgs_text WHERE id=$id AND id=pid"));
  print "$header$fonttag<a href=index.php>$boardname</a> - <a href=private.php>Private messages</a>$tblstart";
  if(!$action and $log and (!$id or ($id and $loguserid==$msg[userto]))){
    print '<body onload=window.document.REPLIER.message.focus()><FORM ACTION=sendprivate.php NAME=REPLIER METHOD=POST>';
    if($log && $id){
	$user=loaduser($msg[userfrom],1);
	$quotemsg="[quote=$user[name]]$msg[text][/quote]
";
	$subject="Re: $msg[title]";
	$tcellbg="$tccell1l valign=top";
	$postlist="
	  $tccellh width=150>User</td>
	  $tccellh>Message<tr>
	  $tcellbg><a href=profile.php?id=$user[id]>$user[name]</a>$smallfont<br>
	  Posts: $postnum$user[posts]</td>
	  $tcellbg>".doreplace2($msg[text])."<tr>
	";
    }else $postlist='';
    if($userid) $user=loaduser($userid,1);
    $user[name]=htmlspecialchars($user[name]);
    $subject=htmlspecialchars($subject);
    print "
	 $tccellh width=150>&nbsp</td>
	 $tccellh>&nbsp<tr>
	 $tccell1><b>Send to:</td>	 $tccell2l>$inpt=username value=\"$user[name]\" size=25 maxlength=25><tr>
	 $tccell1><b>Subject:</td>	 $tccell2l>$inpt=subject value=\"$subject\" size=60 maxlength=100><tr>
	 $tccell1><b>Message:</td>	 $tccell2l>$txta='message' rows=20 cols=$numcols>$quotemsg</textarea><tr>
	 $tccell1>&nbsp</td>		 $tccell2l>
	 $inph=action VALUE=sendmsg>
	 $inps=submit VALUE='Send message'>
	 $inps=preview VALUE='Preview message'></td>
	
		 
	$tblend
	</FORM>
	<br>$tblstart
	 $postlist
	$tblend
	$fonttag<a href=index.php>$boardname</a> - <a href=private.php>Private messages</a>
    ";
  }
  if($action=='sendmsg'){
	$username	= stripslashes($_POST['username']);
    $userid=checkusername($username);
    if($userid!=-1 and $subject && $loguserid != 1079){
	$subject=str_replace('<','&lt;',$subject);

	$sign=$loguser[signature];
	$head=$loguser[postheader];
	if($user[postbg]) $head="<div style=background:url($user[postbg]);height=100%>$head";
	$numdays=(ctime()-$loguser[regdate])/86400;
	$message=doreplace($message,$loguser[posts],$numdays,$loguser[name]);
	$rsign=doreplace($sign,$loguser[posts],$numdays,$loguser[name]);
	$rhead=doreplace($head,$loguser[posts],$numdays,$loguser[name]);
	$currenttime=ctime();
	if($submit){
	  $headid=getpostlayoutid($head);
	  $signid=getpostlayoutid($sign);
	  mysql_query("INSERT INTO pmsgs (id,userto,userfrom,date,ip,msgread,headid,signid) VALUES (NULL,$userid,$loguserid,$currenttime,'$userip',0,$headid,$signid)");
	  mysql_query("INSERT INTO pmsgs_text (pid,title,text,tagval) VALUES (".mysql_insert_id().",'$subject','$message','$tagval')") or print mysql_error();
	  print "
		$tccell1>Private message to $username sent successfully!.
		<br>".redirect('private.php','your private message box...',0).$tblend;
	}else{
	  loadtlayout();
	  $ppost=$loguser;
	  $message = stripslashes($message);
	  $username = stripslashes($username);
	  $subject = stripslashes($subject);
	  $ppost[uid]=$loguserid;
	  $ppost[date]=$currenttime;
	  $ppost[headtext]=$rhead;
	  $ppost[signtext]=$rsign;
	  $ppost[text]=$message;
	  if($isadmin) $ip=$userip;
	  print "
		<body onload=window.document.REPLIER.message.focus()>
		$tccellh>Message preview
		$tblend$tblstart
		".threadpost($ppost,1)."
		$tblend<br>$tblstart
		<FORM ACTION=sendprivate.php NAME=REPLIER METHOD=POST>
		$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>Message:</td>
		$tccell2l>$txta=message ROWS=10 COLS=$numcols>$message</TEXTAREA><tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inph=username VALUE=\"".htmlspecialchars($username)."\">
		$inph=subject VALUE=\"$subject\">
		$inph=action VALUE=sendmsg>
		$inps=submit VALUE=\"Send message\">
		$inps=preview VALUE='Preview message'>
		</td></FORM>
	  ";
	}
    }elseif ($loguserid == 1079) {
		print "
		 $tccell1>Couldn't send the message. Too bad.
		 <br>".redirect('private.php','return to the private message box',2);
    }else print "
	 $tccell1>Couldn't send the message. You didn't enter an existing username to send the message to.
	 <br>".redirect('private.php','return to the private message box',2);
  }
  if($action=='delete' and $msg[userto]==$loguserid){
    mysql_query("DELETE FROM pmsgs WHERE id=$id");
    mysql_query("DELETE FROM pmsgs_text WHERE pid=$id");
    print "
      $tccell1>Thank you, $loguser[name], for deleting the message.
      <br>".redirect('private.php','return to the private message box',0).$tblend;
  }
  print $footer;
  printtimedif($startingtime);
?>