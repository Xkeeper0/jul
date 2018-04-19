<?php
	// die("Disabled.");
	require_once '../lib/function.php';
	$thread=$sql->fetchq("SELECT forum, closed, sticky,title,lastposter FROM threads WHERE id=$id");

	// Stop this insanity.  Never index newreply.
	$meta['noindex'] = true;

	if (!$thread) {
		require_once '../lib/layout.php';
		print "
			$header<br>$tblstart
			$tccell1>Nice try. Next time, wait until someone makes the thread <i>before</i> trying to reply to it.<br>".redirect("{$GLOBALS['jul_base_dir']}/index.php", 'return to the index page', 0)."
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
	$windowtitle="{$GLOBALS['jul_settings']['board_name']} -- $forum[title]: $thread[title] -- New Reply";

	$thread['title']=str_replace('<','&lt;',$thread['title']);

	require_once '../lib/layout.php';


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
		$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forumid'>$forum[title]</a> - $thread[title]<form action={$GLOBALS['jul_views_path']}/newreply.php name=replier method=post autocomplete=\"off\"> $tblstart";

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
					$tcellbg<a href={$GLOBALS['jul_views_path']}/profile.php?id=$post[user]><font $namecolor>$post[name]</font></a>$smallfont<br>
					Posts: $postnum$post[posts]</td>
					$tcellbg".doreplace2(dofilters($post['text']), $post['options'])."</tr>
				";
			}
			else{
				$tcellbg="<td bgcolor=$tablebg1 valign=top colspan=2";
				$postlist.="<tr>$tccellh colspan=2>This is a long thread. Click <a href={$GLOBALS['jul_views_path']}/thread.php?id=$id>here</a> to view it.</td></tr>";
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

			<!-- Hack around autocomplete, fake inputs (don't use these in the file) -->
			<input style=\"display:none;\" type=\"text\"     name=\"__f__usernm__\">
			<input style=\"display:none;\" type=\"password\" name=\"__f__passwd__\">

			<b>Password:</b> $inpp=password SIZE=13 MAXLENGTH=64 autocomplete=\"off\">
			</span><tr>
			$tccell1><b>Reply:</td>
			$tccell2l width=800px valign=top>
			$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($quotemsg, ENT_QUOTES) ."</TEXTAREA></td>
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
		$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forumid'>$forum[title]</a> - $thread[title]";
	} elseif(!$_POST['action']) {
		print $header;
		print "$tccell1>You are not allowed to post in this thread.
		<br>".redirect("{$GLOBALS['jul_base_dir']}/index.php", 'return to the index page', 0)."</table>";
	}


	if ($_POST['action'] == 'postreply' && !($banned && $log) && $id > 0) {
		if ($log && !$password)
			$userid = $loguserid;
		else
			$userid = checkuser($username,$password);


		$error='';

		if ($userid == -1) {
			$error	= "Either you didn't enter an existing username, or you haven't entered the right password for the username.";
		} else {
			$user	= @$sql->fetchq("SELECT * FROM users WHERE id='$userid'");
			if ($thread['closed'])
				$error	= 'The thread is closed and no more replies can be posted.';
			if ($user['powerlevel']<$forum['minpowerreply'])
				$error	= 'Replying in this forum is restricted, and you are not allowed to post in this forum.';
			if (!$message)
				$error	= "You didn't enter anything in the post.";
		}

		if (!$error) {

			$sign	= $user['signature'];
			$head	= $user['postheader'];
			// @TODO: Remove this code
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

				$sql->query("UPDATE `users` SET `posts` = $numposts, `lastposttime` = '$currenttime' WHERE `id` = '$userid'");

				if (filter_bool($nolayout)) {
					$headid = 0;
					$signid = 0;
				} else {
					$headid=getpostlayoutid($head);
					$signid=getpostlayoutid($sign);
				}


				$closeq	= "";
				$stickq	= "";
				if ($ismod) {
					if (filter_bool($_POST['close'])) $closeq = "`closed` = '1',";
						else $closeq = "`closed` = '0',";
					if (filter_bool($_POST['stick'])) $stickq = "`sticky` = '1',";
						else $stickq = "`sticky` = '0',";
				}

				$sql->query("INSERT INTO posts (thread,user,date,ip,num,headid,signid,moodid) VALUES ($id,$userid,$currenttime,'$userip',$numposts,$headid,$signid,'". $_POST['moodid'] ."')");
				$pid=mysql_insert_id();

				$options = filter_int($nosmilies) . "|" . filter_int($nohtml);

				if($pid) $sql->query("INSERT INTO `posts_text` (`pid`,`text`,`tagval`, `options`) VALUES ('$pid','$message','$tagval', '$options')");

				$sql->query("UPDATE `threads` SET $closeq $stickq `replies` =  `replies` + 1, `lastpostdate` = '$currenttime', `lastposter` = '$userid' WHERE `id`='$id'");
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

				return header("Location: {$GLOBALS['jul_views_path']}/thread.php?pid=$pid#$pid");



			} else {

				loadtlayout();
				$message				= stripslashes($message);
				$ppost					= $user;
				$ppost['posts']++;
				$ppost['uid']			= $userid;
				$ppost['num']			= $numposts;
				$ppost['lastposttime']	= $currenttime;
				$ppost['date']			= $currenttime;
				$ppost['moodid']		= $_POST['moodid'];

				if (filter_bool($nolayout)) {
					$ppost['headtext'] = "";
					$ppost['signtext'] = "";
				} else {
					$ppost['headtext']=$rhead;
					$ppost['signtext']=$rsign;
				}

				$ppost['text']		= $message;
				$ppost['options']	= filter_int($nosmilies) . "|" . filter_int($nohtml);

				if($isadmin) $ip=$userip;

				$chks = array("", "", "");
				if ($nosmilies) $chks[0] = "checked";
				if ($nolayout)  $chks[1] = "checked";
				if ($nohtml)    $chks[2] = "checked";

				print "$header
				<body onload=window.document.replier.message.focus()>
				$tccellh>Post preview
				$tblend$tblstart
				".threadpost($ppost,1)."
				$tblend<br>$tblstart
				<FORM ACTION={$GLOBALS['jul_views_path']}/newreply.php NAME=REPLIER METHOD=POST>
				$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
				$tccell1><b>Reply:</td>
				$tccell2l width=800px valign=top>$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($message, ENT_QUOTES) ."</TEXTAREA></td>
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
		} else {
			print "$header$tccell1>Couldn't enter the post. $error<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$id", $thread['title'], 0);
		}
	}

	if ($thread['closed']) {
		print "
		$tccell1>Sorry, but this thread is closed, and no more replies can be posted in it.
		<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$id",$thread['title'],0);
	} elseif($banned and $log) {
		print "
		$tccell1>Sorry, but you are banned from the board, and can not post.
		<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$id",$thread['title'],0);
	}
  print $footer;
  printtimedif($startingtime);
