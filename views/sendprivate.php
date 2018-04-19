<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	$meta['noindex'] = true; // Never index

	$smilies=readsmilies();

	if(!$log) {
		print "
			$header$tblstart
			$tccell1>Can't send a private message, because you are not logged in.
			<br>".redirect("{$GLOBALS['jul_base_dir']}/index.php", 'return to the index page', 0)."
			$tblend$footer
		";
		printtimedif($startingtime);
		die();
	}

	if($loguser['powerlevel'] <= -2) {
		print "
			$header$tblstart
			$tccell1>You are permabanned and cannot send private messages.
			<br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'your private message box',0)."
			$tblend$footer
		";
		printtimedif($startingtime);
		die();
	}

	if($id) {
		$msg = $sql->fetchq("SELECT * FROM pmsgs,pmsgs_text WHERE id=$id AND id=pid");

		if ($loguserid != $msg['userto']) {
			print "
				$header$tblstart
				$tccell1>Can't reply to this private message, because it was not sent to you.
				<br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'your private message box',0)."
				$tblend$footer
			";
			printtimedif($startingtime);
			die();
		}
	}

	print "$header$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href={$GLOBALS['jul_views_path']}/private.php>Private messages</a>$tblstart";

	if (!$action) {
		print "<body onload=window.document.replier.message.focus()><FORM ACTION={$GLOBALS['jul_views_path']}/sendprivate.php NAME=REPLIER METHOD=POST>";

		if ($log && $id) {
			$user = loaduser($msg['userfrom'],1);
			$quotemsg = "[quote=$user[name]]$msg[text][/quote]\r\n";
			$subject="Re: $msg[title]";

			$tcellbg="$tccell1l valign=top";
			$postlist="
				$tccellh width=150>User</td>
				$tccellh>Message<tr>
				$tcellbg><a href={$GLOBALS['jul_views_path']}/profile.php?id=$user[id]>$user[name]</a>$smallfont<br>
				Posts: $postnum$user[posts]</td>
				$tcellbg>".doreplace2($msg[text])."<tr>
			";
		}
		else
			$postlist='';

		if ($userid)
			$user=loaduser($userid,1);
		$user['name']=htmlspecialchars($user['name']);
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
			<br>$tblstart$postlist$tblend
			$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href={$GLOBALS['jul_views_path']}/private.php>Private messages</a>
		";
	}
	if($action=='sendmsg') {
		$username	= stripslashes($_POST['username']);
		$userid=checkusername($username);

		if ($userid == -1)
			print "$tccell1>Couldn't send the message. You didn't enter an existing username to send the message to.
				<br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'your private message box',2);
		elseif (!$subject)
			print "$tccell1>Couldn't send the message. You didn't enter a subject.
				<br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'your private message box',2);
		else {
			$subject=str_replace('<','&lt;',$subject);

			$sign=$loguser['signature'];
			$head=$loguser['postheader'];
			if($user['postbg'])
				$head="<div style=background:url($user[postbg]);height=100%>$head";

			$numdays=(ctime()-$loguser['regdate'])/86400;
			$message=doreplace($message,$loguser['posts'],$numdays,$loguser['name']);
			$rsign=doreplace($sign,$loguser['posts'],$numdays,$loguser['name']);
			$rhead=doreplace($head,$loguser['posts'],$numdays,$loguser['name']);
			$currenttime=ctime();

			if($submit) {
				$headid = getpostlayoutid($head);
				$signid = getpostlayoutid($sign);

				$sql->query("INSERT INTO pmsgs (id,userto,userfrom,date,ip,msgread,headid,signid) VALUES (NULL,$userid,$loguserid,$currenttime,'$userip',0,$headid,$signid)");
				$sql->query("INSERT INTO pmsgs_text (pid,title,text,tagval) VALUES (".mysql_insert_id().",'$subject','$message','$tagval')");

				print "$tccell1>Private message to $username sent successfully!
					<br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'your private message box',0).$tblend;
			}
			else {
				loadtlayout();
				$ppost=$loguser;
				$message = stripslashes($message);
				$username = stripslashes($username);
				$subject = stripslashes($subject);
				$ppost['uid']=$loguserid;
				$ppost['date']=$currenttime;
				$ppost['headtext']=$rhead;
				$ppost['signtext']=$rsign;
				$ppost['text']=$message;
				if($isadmin) $ip=$userip;
				print "
					<body onload=window.document.replier.message.focus()>
					$tccellh>Message preview
					$tblend$tblstart
					$pollpreview
					$tccell2l><b>". stripslashes($subject) ."</b>
					$tblend$tblstart
					".threadpost($ppost,1)."
					$tblend<br>$tblstart
					<FORM ACTION={$GLOBALS['jul_views_path']}/sendprivate.php NAME=REPLIER METHOD=POST>
					$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
					$tccell1><b>Subject:</td>	 $tccell2l>$inpt=subject value=\"$subject\" size=60 maxlength=100><tr>
					$tccell1><b>Message:</td>
					$tccell2l>$txta=message ROWS=10 COLS=$numcols>$message</TEXTAREA><tr>
					$tccell1>&nbsp</td>$tccell2l>
					$inph=username VALUE=\"".htmlspecialchars($username)."\">
					$inph=action VALUE=sendmsg>
					$inps=submit VALUE=\"Send message\">
					$inps=preview VALUE='Preview message'>
					</td></FORM>
				";
			}
		}
  }
/*if($action=='delete' and $msg[userto]==$loguserid){
    mysql_query("DELETE FROM pmsgs WHERE id=$id");
    mysql_query("DELETE FROM pmsgs_text WHERE pid=$id");
    print "
      $tccell1>Thank you, $loguser[name], for deleting the message.
      <br>".redirect("{$GLOBALS['jul_views_path']}/private.php",'return to the private message box',0).$tblend;
  } */
  print $footer;
  printtimedif($startingtime);
?>
