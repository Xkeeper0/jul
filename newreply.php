<?php

	require 'lib/function.php';
	$thread=$sql->fetchq("SELECT forum, closed, sticky,title,lastposter FROM threads WHERE id=$id");

	// Stop this insanity.  Never index newreply.
	$meta['noindex'] = true;

	$message	= $_POST['message'] ?? null;

	// Give failed replies a last-chance to copy and save their work,
	// as way too often you'll miss and then it's just gone forever
	$lastchance		= null;
	$redirectTime	= 3;
	if (v($_POST['message']) !== null) {
		$lastchance		= "<br><br>You can copy and save what you were <em>going</em> to post, if you want:
		<br><textarea class='newposttextbox' style='margin: 1em auto;'>". htmlspecialchars(stripslashes($_POST['message']), ENT_QUOTES) ."</textarea>";
		$redirectTime	= -1;
	}

	if (!$thread) {
		require_once 'lib/layout.php';
		boardmessage("You can't reply to threads that don't exist!<br>". redirect("index.php", "the forum index", $redirectTime) . $lastchance, "Error");
	}

	$forumid			=intval($thread['forum']);
	$forum				=$sql->fetchq("SELECT title,minpower,minpowerreply,id,specialscheme FROM forums WHERE id=$forumid");

	if ($forum['minpower'] && $power < $forum['minpower']) {
		require_once 'lib/layout.php';
		boardmessage("You aren't allowed to view this thread.<br>". redirect("index.php", "the forum index", $redirectTime) . $lastchance, "Error");
	}

	$specialscheme		= $forum['specialscheme'];
	$windowtitle		= "$boardname -- $forum[title]: $thread[title] -- New reply";

	$thread['title']	= str_replace('<','&lt;',$thread['title']);

	require_once 'lib/layout.php';


	// Do access checks. Can't post while banned...
	if ($power < $forum['minpowerreply'] || $banned) {
		boardmessage("You aren't allowed to reply to this thread.<br>". redirect("thread.php?id=$id", "the thread", $redirectTime) . $lastchance, "Error");
	}

	// ...or in a closed thread
	if ($thread['closed']) {
		boardmessage("You can't reply to this thread because it is closed.<br>". redirect("thread.php?id=$id", "the thread", $redirectTime) . $lastchance, "Error");
	}


	// Check if we are a global moderator, or a local mod of this forum
	$modoptions	= "";
	if ($ismod || mysql_num_rows($sql->query("SELECT user FROM forummods WHERE forum='$forumid' and user='$loguserid'"))) {
		$ismod	= 1;

		$modoptions = "
			<tr>
				$tccell1><strong>Moderator Options:</strong></td>
				$tccell2l>
					$inpc='close' id='close' value='1'><label for='close'>Close</label> -
					$inpc='stick' id='stick' value='1'". ($thread['sticky'] ? "checked" : "") ."><label for='stick'>Sticky</label>
				</td>
			</tr>";
	}


	if(!filter_int($ppp)) $ppp=(!$log?20:$loguser['postsperpage']);
	$smilies	= readsmilies();
	$fonline	= fonlineusers($forumid);
	$header		= makeheader($header1,$headlinks,$header2 ."\t$tblstart$tccell1s>$fonline$tblend");
	$breadcrumb	= "<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - <a href='thread.php?id=$id'>$thread[title]</a> - New Reply";

	$header		.= "$fonttag$breadcrumb";


	// Show the new reply form?
	$showform	= true;
	$usererror	= "";
	$preview	= "";

	if (v($_POST['action']) == 'postreply') {

		if ($log)
			$userid = $loguserid;
		else
			$userid = checkuser($_POST['username'], $_POST['password']);

		if ($userid == -1 || $userid == 0) {
			$usererror	= " <strong style='color: red;'>* Invalid username or password.</strong>";
		} else {
			$user	= @$sql->fetchq("SELECT * FROM users WHERE id='$userid'");
			if (!$user) {
				boardmessage("Something went really weird? Contact an admin: Userid $userid but no user??", "This shouldn't happen");
			}
		}

		if ($user) {

			$sign			= $user['signature'];
			$head			= $user['postheader'];

			$numposts		= $user['posts'] + 1;

			$numdays		= (ctime() - $user['regdate']) / 86400;
			$tags			= array();

			$message		= stripslashes($message);

			$message		= doreplace($message, $numposts, $numdays, $user['name'], $tags);
			$tagval			= $sql->escape(json_encode($tags));
			$rsign			= doreplace($sign, $numposts, $numdays, $user['name']);
			$rhead			= doreplace($head, $numposts, $numdays, $user['name']);
			$currenttime	= ctime();

			// Submitting a post
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

				if($pid) $sql->query("INSERT INTO `posts_text` (`pid`,`text`,`tagval`, `options`) VALUES ('$pid','". $sql->escape($message) ."','$tagval', '$options')");

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

				return header("Location: thread.php?pid=$pid#$pid");


			// Previewing a post
			} else {

				loadtlayout();
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

				$ip					= "";

				$preview			= "
				$tblstart
				<tr>$tccellh><strong>Post preview</strong></td></tr>
				$tblend
				".threadpost($ppost,1)."
				<br>";
			}
		}

	}


	// Totally new reply, with optional quote
	if ($showform) {

		$userlogin		= "";
		if (!$log) {
			$userlogin	= "
			<tr>
				$tccell1><strong>Username:</strong></td>
				$tccell2l>$inpt='username' size='25' maxlength='25' value='". htmlspecialchars(v($_POST['username']), ENT_QUOTES) ."'> $usererror
			</tr>
			<tr>$tccell1><strong>Password:</strong></td>
				$tccell2l>$inpp='password' size='25' maxlength='64' value='". htmlspecialchars(v($_POST['password']), ENT_QUOTES) ."'>
			</tr>
			";
		}

		$quotemsg	= "";
		if (filter_int($postid)) {
			$post			= $sql->fetchq("SELECT user,text,thread FROM posts,posts_text WHERE id=$postid AND id=pid");
			$post['text']	= str_replace('<br>', $br, $post['text']);
			$u				= $post['user'];
			$users[$u]		= loaduser($u, 1);
			if ($post['thread'] == $id) $quotemsg = "[quote={$users[$u]['name']}]{$post['text']}[/quote]\r\n";
		}

		$message	= $quotemsg . stripslashes(v($_POST['message']));

		print "$header
		$preview
		<form action=newreply.php name=replier method=post>
			$tblstartf
				<colgroup>
					<col style='width: 150px;'>
					<col>
				</colgroup>
				<tr>
					$tccellh colspan=2><strong>New reply</strong>
				</tr>
				$userlogin
				<tr>
					$tccell1 style='width: 150px; max-width: 150px;'><strong>Reply:</strong></td>
					$tccell2l>$txta=message class='newposttextbox' autofocus>". htmlspecialchars($message, ENT_QUOTES) ."</TEXTAREA></td>
				<tr>
					$tccell1>&nbsp;</td>$tccell2l>
					$inph=action VALUE=postreply>
					$inph=id VALUE=$id>
					$inph=valid value='". md5($_SERVER['REMOTE_ADDR'] . $id ."sillysaltstring") ."'>
					$inps=submit VALUE='Submit reply'>
					$inps=preview VALUE='Preview reply'></td>
				</tr>
				<tr>
					$tccell1><strong>Mood avatar:</strong></td>
					$tccell2l>". moodlist(filter_int($moodid)) ."</td>
				</tr>
				<tr>
					$tccell1><strong>Options:</strong></td>
					$tccell2l>
						$inpc='nosmilies' id='nosmilies' value='1'". (v($_POST['nosmilies']) ? " checked" : "") ."><label for='nosmilies'>Disable Smilies</label> -
						$inpc='nolayout' id='nolayout' value='1'". (v($_POST['nolayout']) ? " checked" : "") ."><label for='nolayout'>Disable Layout</label> -
						$inpc='nohtml' id='nohtml' value='1'". (v($_POST['nohtml']) ? " checked" : "") ."><label for='nohtml'>Disable HTML</label>
					</td>
				</tr>
				$modoptions
			$tblend
			</form>
			";



		// Thread history view (under the form)
		// (originally had a check for power, but that's accounted for above)
		if (true) {
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

		print "
			$tblstart
				$postlist
			$tblend
		$fonttag
		$breadcrumb";
	}


  print $footer;
  printtimedif($startingtime);

