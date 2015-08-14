<?php
	// die("Disabled.");
	require 'lib/function.php';
	$thread=$sql->fetchq("SELECT forum, closed, sticky,title,lastposter FROM threads WHERE id=$id");

	// Stop this insanity.  Never index newreply.
	$meta['noindex'] = true;

	if (!$thread) {
		require_once 'lib/layout.php';
		print "
			$header<br>$tblstart
			$tccell1>Nice try. Next time, wait until someone makes the thread <i>before</i> trying to reply to it.<br>".redirect("index.php", 'return to the index page', 0)."
			$tblend$footer
		";
		printtimedif($startingtime);
		die();
	}

	$forumid=intval($thread['forum']);
	$forum=$sql->fetchq("SELECT title,minpower,minpowerreply,id,specialscheme FROM forums WHERE id=$forumid");
	if ($forum['minpower'] && $power < $forum['minpower']) {
		$forum['title'] = '';
		$thread['title'] = '(restricted thread)';
	}
	$specialscheme = $forum['specialscheme'];
	$windowtitle="$boardname -- $forum[title]: $thread[title] -- New Reply";

	$thread['title']=str_replace('<','&lt;',$thread['title']);

	require_once 'lib/layout.php';


	$smilies=readsmilies();
	if(!filter_int($ppp)) $ppp=(!$log?20:$loguser['postsperpage']);
	$fonline=fonlineusers($forumid);
	$header=makeheader($header1,$headlinks,$header2 ."	$tblstart$tccell1s>$fonline$tblend");

	if(mysql_num_rows($sql->query("SELECT user FROM forummods WHERE forum='$forumid' and user='$loguserid'"))) $ismod=1;

	$modoptions	= "";

	if ($ismod) {
		if ($thread['sticky'] == 1) $sticky = "checked";
		$modoptions = "	<tr>$tccell1><b>Moderator Options:</b></td>$tccell2l colspan=2>
		$inpc=\"close\" id=\"close\" value=\"1\"><label for=\"close\">Close</label> -
		$inpc=\"stick\" id=\"stick\" value=\"1\" $sticky><label for=\"stick\">Sticky</label>";
	}

	if ($forum['minpowerreply'] > $power && $forum['minpowerreply'] > 0)
		$restricted		= true;

	$header	= "$header
		$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title]<form action=newreply.php name=replier method=post autocomplete=\"off\"> $tblstart";

	if($log) activitycheck($loguserid);
  // Post preview
	if (($power>=$forum['minpowerreply'] || $forum['minpowerreply']<1) && $id>0) {
		$postlist="<tr>$tccellh colspan=2 style=\"font-weight:bold;\">Thread history</tr><tr>$tccellh width=150>User</td>$tccellh width=*>Post</tr>";
		$qppp = $ppp + 1;
		$posts=$sql->query("SELECT name,posts,sex,powerlevel,user,text,options,num FROM users u,posts p,posts_text WHERE thread=$id AND p.id=pid AND user=u.id ORDER BY p.id DESC LIMIT $qppp");
		$i = 0;

		while($post=$sql->fetch($posts)){
			$bg = ((($i++) & 1) ? 'tdbg2' : 'tdbg1');
			if ($ppp-- > 0){
				$postnum=($post['num']?"$post[num]/":'');
				$tcellbg="<td class='tbl $bg font' valign=top>";
				$namecolor=getnamecolor($post['sex'],$post['powerlevel']);
				$postlist.="<tr>
					$tcellbg<a href=profile.php?id=$post[user]><font $namecolor>$post[name]</font></a>$smallfont<br>
					Posts: $postnum$post[posts]</td>
					$tcellbg".doreplace2(dofilters($post['text']), $post['options'])."</tr>
				";
			}
			else{
				$tcellbg="<td bgcolor=$tablebg1 valign=top colspan=2";
				$postlist.="<tr>$tccellh colspan=2>This is a long thread. Click <a href=thread.php?id=$id>here</a> to view it.</td></tr>";
			}
		}
	}

	if(!filter_string($_POST['action']) && !$thread['closed'] && !($banned && $log)
		&& ($power>=$forum['minpowerreply'] || $forum['minpowerreply']<1) && $id>0) {
		print $header;
		print "";

		if ($log) {
			$username=$loguser['name'];
			$passhint = 'Alternate Login:';
			$altloginjs = "<a href=\"#\" onclick=\"document.getElementById('altlogin').style.cssText=''; this.style.cssText='display:none'\">Use an alternate login</a>
				<span id=\"altlogin\" style=\"display:none\">";
		}
		else {
			$username = '';
			$passhint = 'Login Info:';
			$altloginjs = "<span>";
		}

		$quotemsg	= "";
		if(filter_int($postid)){
			$post=$sql->fetchq("SELECT user,text,thread FROM posts,posts_text WHERE id=$postid AND id=pid");
			$post['text']=str_replace('<br>',$br,$post['text']);
			$u=$post['user'];
			$users[$u]=loaduser($u,1);
			if($post['thread']==$id) $quotemsg="[quote={$users[$u]['name']}]{$post['text']}[/quote]\r\n";
		}

  print "
		<body>
		$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
		$tccell1><b>{$passhint}</td> $tccell2l colspan=2>
		{$altloginjs}
		<b>Username:</b> $inpt=username VALUE=\"".htmlspecialchars($username)."\" SIZE=25 MAXLENGTH=25 autocomplete=\"off\">
		<b>Password:</b> $inpp=password SIZE=13 MAXLENGTH=64 autocomplete=\"off\">
		</span><tr>
		$tccell1><b>Reply:</td>
		$tccell2l width=800px valign=top>".replytoolbar(2)."
		$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\" ".replytoolbar(3).">". htmlspecialchars($quotemsg, ENT_QUOTES) ."</TEXTAREA></td>
	$tccell2l width=*>".moodlist(filter_int($moodid))."</td><tr>
	<tr>
		$tccell1>&nbsp</td>$tccell2l colspan=2>
		$inph=action VALUE=postreply>
		$inph=id VALUE=$id>
		$inph=valid value=\"". md5($_SERVER['REMOTE_ADDR'] . $id ."sillysaltstring") ."\">
		$inps=submit VALUE=\"Submit reply\">
		$inps=preview VALUE=\"Preview reply\"></td>
	<tr>$tccell1><b>Options:</b></td>$tccell2l colspan=2>
		$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\"><label for=\"nosmilies\">Disable Smilies</label> -
		$inpc=\"nolayout\" id=\"nolayout\" value=\"1\"><label for=\"nolayout\">Disable Layout</label> -
		$inpc=\"nohtml\" id=\"nohtml\" value=\"1\"><label for=\"nohtml\">Disable HTML</label></td></tr>
		$modoptions
		$tblend
		<br>
		$tblstart$postlist$tblend
	</table>
		</form>
	$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title]
	".replytoolbar(4);
	} elseif(!$_POST['action']) {
			print $header;
			print "$tccell1>You are not allowed to post in this thread.
			<br>".redirect("index.php", 'return to the index page', 0)."</table>";
	}


	if($_POST['action']=='postreply' && !($banned && $log) && $id>0) {
		if ($log && !$password)
			$userid = $loguserid;
		else
			$userid = checkuser($username,$password);

/*
	if (stripos($message, "i hate metal man!!") !== false) {
		xk_ircsend("1|". xk(4) ."NO BONUS!". xk() ." Seems that ". xk(11) ."'$username'". xk() ." is another rereg, so I've banned his account (". xk(11) ."$userid". xk() .") and IP (". xk(11) ."$userip". xk() .").");
		$sql -> query("UPDATE `users` SET `power` = '-1', `title` = 'Get out.' WHERE `id` = '$userid'");
		$sql -> query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'NO BONUS'");
		die("Winners don't do drugs!");
	}
*/

	$error='';
	if($userid==-1)
		$error="Either you didn't enter an existing username, or you haven't entered the right password for the username.";
	else{
	$user=@$sql->fetchq("SELECT * FROM users WHERE id=$userid");
//      if($thread['lastposter']==$userid && $user['powerlevel']<=2)
//        $error='You already have the last reply in this thread.';
		if($thread['closed'])
		$error='The thread is closed and no more replies can be posted.';
		if($user['powerlevel']<$forum['minpowerreply'])
		$error='Replying in this forum is restricted, and you are not allowed to post in this forum.';
		if(!$message)
		$error="You didn't enter anything in the post.";
	}

	if (!$error) {
	activitycheck($userid);

	$sign	=$user['signature'];
	$head	=$user['postheader'];
	if($user['postbg']) $head="<div style=background:url($user[postbg]);height=100%>$head";

	$numposts		= $user['posts']+ 1;

	$numdays		= (ctime()-$user['regdate'])/86400;
	$tags			= array();
	$message		= doreplace($message,$numposts,$numdays,$username, $tags);
	$tagval			= $sql->escape(json_encode($tags));
	$rsign			= doreplace($sign,$numposts,$numdays,$username);
	$rhead			= doreplace($head,$numposts,$numdays,$username);
	$currenttime	= ctime();
	if (filter_string($_POST['submit'])) {

		if (!(!$x_hacks['host'] && $userid == 715) || true) {

		$sql->query("UPDATE `users` SET `posts` = $numposts, `lastposttime` = '$currenttime' WHERE `id` = '$userid'");

		if ($nolayout) {
			$headid = 0;
			$signid = 0;
		} else {
			$headid=getpostlayoutid($head);
			$signid=getpostlayoutid($sign);
		}


		if ($ismod) {
			if ($close) $close = "`closed` = '1',";
				else $close = "`closed` = '0',";
			if ($stick) $stick = "`sticky` = '1',";
				else $stick = "`sticky` = '0',";
		}

		$sql->query("INSERT INTO posts (thread,user,date,ip,num,headid,signid,moodid) VALUES ($id,$userid,$currenttime,'$userip',$numposts,$headid,$signid,'". $_POST['moodid'] ."')");
		$pid=mysql_insert_id();

		$options = intval($nosmilies) . "|" . intval($nohtml);

		if($pid) $sql->query("INSERT INTO `posts_text` (`pid`,`text`,`tagval`, `options`) VALUES ('$pid','$message','$tagval', '$options')");

		if (in_array($id, array(3424, 3425, 3426, 3816, 4907)) && !$x_hacks['host']) {
			$lastgmpost	= $sql -> resultq("SELECT MAX(`id`) FROM `posts` WHERE `user` = '24' AND `thread` = '$id'");

			if ($id == 3424) {
				$inarray	= "49, 203, 41, 29";
			} elseif ($id == 3425) {
				$inarray	= "3, 25, 14, 22";
			} elseif ($id == 3426) {
				$inarray	= "61, 1, 18, 555";
			} elseif ($id == 3816) {
				$inarray	= "4, 10, 66, 125";
			} elseif ($id == 4907) {
				$inarray	= "18, 19, 21, 2";
			}

			$playerposts	= $sql -> resultq("SELECT COUNT(DISTINCT `user`) FROM `posts` WHERE `user` IN ($inarray) AND `thread` = '$id' AND `id` > '$lastgmpost'");
			if ($playerposts >= 4) $stick .= " `icon` = 'images/piticon-ok.png',";
			else  $stick .= " `icon` = 'images/piticon-wait.png',";

		}

		$sql->query("UPDATE `threads` SET $close $stick `replies` =  `replies` + 1, `lastpostdate` = '$currenttime', `lastposter` = '$userid' WHERE `id`='$id'");
		$sql->query("UPDATE `forums` SET `numposts` = `numposts` + 1, `lastpostdate` = '$currenttime', `lastpostuser` ='$userid', `lastpostid` = '$pid' WHERE `id`='$forumid'");

		$sql->query("UPDATE `threadsread` SET `read` = '0' WHERE `tid` = '$id'");
		$sql->query("REPLACE INTO threadsread SET `uid` = '$userid', `tid` = '$id', `time` = ". ctime() .", `read` = '1'");


		xk_ircout("reply", $user['name'], array(
			'forum'		=> $forum['title'],
			'fid'		=> $forumid,
			'thread'	=> str_replace("&lt;", "<", $thread['title']),
			'pid'		=> $pid,
			'pow'		=> $forum['minpower'],
		));

		if (in_array($id, array(3426, 4907, 6358)) && !$x_hacks['host'] && false) {

			relay_vgg($id, $userid, $username, $message);

		}

		return header("Location: thread.php?pid=$pid#$pid");


		} else {
			print "
			$tccell1>Reply posted successfully!
		<br>".redirect("thread.php?id=$id", $thread['title'], 0) .$tblend;
		}
	}else{

		loadtlayout();
		$message = stripslashes($message);
		$ppost=$user;
		$ppost['uid']=$userid;
		$ppost['num']=$numposts;
		$ppost['posts']++;
		$ppost['lastposttime']=$currenttime;
		$ppost['date']=$currenttime;
		$ppost['moodid']=$_POST['moodid'];
		if (filter_bool($nolayout)) {
			$ppost['headtext'] = "";
			$ppost['signtext'] = "";
		} else {
			$ppost['headtext']=$rhead;
			$ppost['signtext']=$rsign;
		}
		$ppost['text']=$message;
		$ppost['options'] = filter_int($nosmilies) . "|" . filter_int($nohtml);

		if($isadmin) $ip=$userip;

		$chks = array("", "", "");
		if ($nosmilies) $chks[0] = "checked";
		if ($nolayout)  $chks[1] = "checked";
		if ($nohtml)    $chks[2] = "checked";

		print "$header
		<body onload=window.document.REPLIER.message.focus()>
		$tccellh>Post preview
		$tblend$tblstart
		".threadpost($ppost,1)."
		$tblend<br>$tblstart
		<FORM ACTION=newreply.php NAME=REPLIER METHOD=POST>
		$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
		$tccell1><b>Reply:</td>
		$tccell2l width=800px valign=top>$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($message, ENT_QUOTES, "ISO-8859-1") ."</TEXTAREA></td>
	$tccell2l width=*>".moodlist($moodid)."</td><tr>
		$tccell1>&nbsp</td>$tccell2l colspan=2>
		$inps=submit VALUE=\"Submit reply\">
		$inps=preview VALUE=\"Preview reply\"></td>
		$inph=username VALUE=\"".htmlspecialchars($username)."\">
		$inph=password VALUE=\"".htmlspecialchars($password)."\">
		$inph=valid value=\"". md5($_SERVER['REMOTE_ADDR'] . $id ."sillysaltstring") ."\">
		$inph=action VALUE=postreply>
		$inph=id VALUE=$id>
	<tr>$tccell1><b>Options:</b></td>$tccell2l colspan=2>
		$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\" $chks[0]><label for=\"nosmilies\">Disable Smilies</label> -
		$inpc=\"nolayout\" id=\"nolayout\" value=\"1\" $chks[1]><label for=\"nolayout\">Disable Layout</label> -
		$inpc=\"nohtml\" id=\"nohtml\" value=\"1\" $chks[2]><label for=\"nohtml\">Disable HTML</label></td></tr>
		$modoptions
		$tblend
		</FORM>
		$tblstart$postlist$tblend
		</td></FORM>
		";
		}
	}else
	print "$header$tccell1>Couldn't enter the post. $error<br>".redirect("thread.php?id=$id", $thread['title'], 0);
  }
  if($thread['closed'])
	print "
	$tccell1>Sorry, but this thread is closed, and no more replies can be posted in it.
	<br>".redirect("thread.php?id=$id",$thread['title'],0);
  if($banned and $log)
	print "
	$tccell1>Sorry, but you are banned from the board, and can not post.
	<br>".redirect("thread.php?id=$id",$thread['title'],0);

  print $footer;
  printtimedif($startingtime);

function activitycheck($userid){
  global $id,$thread,$header,$tblstart,$tccell1,$tblend,$footer,$loguser,$sql;
  $activity=$sql->resultq("SELECT count(*) FROM posts WHERE user=$userid AND thread=$id AND date>".(ctime()-86400),0,0);
//  if($activity>=(stristr($thread['title'],'ACS ')?5:5000))
//    die("$tblstart$tccell1>You have posted enough in this thread today. Come back later!$tblend$footer");
  $activity=$sql->resultq("SELECT count(*) FROM posts WHERE user=$userid AND date>".(ctime()-300),0,0);
		if($activity && $userid == 1079)
			die("$header$tblstart$tccell1>You can only post once every five minutes! Make it count!$tblend$footer");
}

