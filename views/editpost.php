<?php
	// (fat catgirl here)
	require_once '../lib/function.php';

	// Stop this insanity.  Never index editpost...
	$meta['noindex'] = true;

	if (!$log) {
		require_once '../lib/layout.php';
		errorpage("You are not logged in.",'log in (then try again)',"{$GLOBALS['jul_views_path']}/login.php");
	}
	if ($loguser['editing_locked'] == 1) {
		require_once '../lib/layout.php';
		errorpage("You are not allowed to edit your posts.",'return to the board','index.php');
	}

	$post     = $sql->fetchq("SELECT * FROM posts,posts_text WHERE id='$id 'AND id=pid");
	if (!$post) {
		require_once '../lib/layout.php';
		errorpage("Post ID #{$id} doesn't exist.",'return to the board','index.php');
	}

	$threadid = $post['thread'];
	$thread   = $sql->fetchq("SELECT forum,closed,title FROM threads WHERE id=$threadid");
	$options  = explode("|", $post['options']);

	$thread['title'] = str_replace('<','&lt;',$thread['title']);
	$thread['title'] = str_replace('>','&gt;',$thread['title']);

	$smilies = readsmilies();

	$forum = $sql->fetchq("SELECT * FROM forums WHERE id=$thread[forum]");
	$specialscheme = $forum['specialscheme'];
	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- $forum[title]: $thread[title] -- Editing Post";

	require_once '../lib/layout.php';
	print $header;

	if (@mysql_num_rows($sql->query("SELECT user FROM forummods WHERE forum=$forum[id] and user=$loguserid")))
		$ismod = 1;

	print "$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - ". ($forum['minpower'] <= $loguser['powerlevel'] ? "<a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>".$forum['title']."</a> - <a href='{$GLOBALS['jul_views_path']}/thread.php?pid=$id#$id'>$thread[title]</a> - Edit post" : "Restricted thread") ."
		$tblstart
		<FORM ACTION='{$GLOBALS['jul_views_path']}/editpost.php' NAME=REPLIER METHOD=POST>";

	if(!$action && $log && ($ismod || ($loguserid==$post['user'] && $loguser['powerlevel'] > -1 && !$thread['closed'])) && (!$forum['minpower'] or $power>=$forum['minpower'])) {
		$message=$post['text'];
		if(!$post['headid']) $head=$post['headtext'];
		else $head=$sql->resultq("SELECT text FROM postlayouts WHERE id=$post[headid]",0,0);
		if(!$post['signid']) $sign=$post['signtext'];
		else $sign=$sql->resultq("SELECT text FROM postlayouts WHERE id=$post[signid]",0,0);

		sbr(1,$message);
		sbr(1,$head);
		sbr(1,$sign);

    $chks = array();
		if ($options[0]) $chks[0] = "checked";
		if ($options[1]) $chks[1] = "checked";

		$user=$sql->fetchq("SELECT name FROM users WHERE id=$post[user]");

		print "
			$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
			$tccell1><b>Header:</td>	 $tccell2l width=800px valign=top>$txta=head ROWS=8 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($head) ."</textarea>
			$tccell2l width=* rowspan=3>".moodlist($post['moodid'])."</td><tr>
			$tccell1><b>Post:</td>		 $tccell2l width=800px valign=top>$txta=message ROWS=12 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($message) ."</textarea><tr>
			$tccell1><b>Signature:</td>	 $tccell2l width=800px valign=top>$txta=sign ROWS=8 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($sign) ."</textarea><tr>
			$tccell1>&nbsp</td>$tccell2l colspan=2>
			$inph=action VALUE=editpost>
			$inph=id VALUE=$id>
			$inps=submit VALUE=\"Edit post\">
			$inps=preview VALUE=\"Preview post\"></td>
			<tr>$tccell1><b>Options:</b></td>$tccell2l colspan=2>
			$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\" $chks[0]><label for=\"nosmilies\">Disable Smilies</label> -
			$inpc=\"nohtml\" id=\"nohtml\" value=\"1\" $chks[1]><label for=\"nohtml\">Disable HTML</label></td></tr>
			</FORM>
		$tblend$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>".$forum['title']."</a> - $thread[title]
		";
	}
	elseif (!$action) {
		print "
		$tccell1>You are not allowed to edit this post.<br>
		".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$threadid","the thread",0);
	}

	if($_POST['action']=='editpost') {
		$poptions = intval($nosmilies) . "|" . intval($nohtml);

		print $tblstart;
		if(($ismod or ($loguserid==$post[user] && $loguser['powerlevel'] >= 0)) and (!$forum['minpower'] or $power>=$forum['minpower']) && !$thread['closed']) {
			$user = $sql->fetchq("SELECT posts,regdate FROM users WHERE id=$loguserid");
			$numposts=$user['posts'];
			$numdays=(ctime()-$user['regdate'])/86400;
			$message=doreplace($message,$numposts,$numdays,$loguser['name']);

			$edited = str_replace('\'', '\\\'', getuserlink($loguser));

			if($submit) {
				if ($loguserid == 1162) {
					xk_ircsend("1|The jceggbert5 dipshit tried to edit another post: ". $id);
				}
				elseif (($message == "COCKS" || $head == "COCKS" || $sign == "COCKS") || ($message == $head && $head == $sign)) {
					mysql_query("INSERT INTO `ipbans` SET `reason` = 'Idiot hack attempt', `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."'");
					die("NO BONUS");
				}
				else {
					$headid=@$sql->resultq("SELECT `id` FROM `postlayouts` WHERE `text` = '$head' LIMIT 1",0,0);
					$signid=@$sql->resultq("SELECT `id` FROM `postlayouts` WHERE `text` = '$sign' LIMIT 1",0,0);
					if($headid) $head=''; else $headid=0;
					if($signid) $sign=''; else $signid=0;
					$sql->query("UPDATE `posts_text` SET `options` = '$poptions', `headtext` = '$head', `text` = '$message', `signtext` = '$sign', `edited` = '$edited', `editdate` = '".ctime()."' WHERE `pid` = '$id'");
					$sql->query("UPDATE `posts` SET `headid` = '$headid', `signid` = '$signid', `moodid` = '". $_POST['moodid'] ."' WHERE `id` = '$id'");
				}

				//$ppp=($log?$loguser['postsperpage']:20);
				//$page=floor($sql->query("SELECT COUNT(*) FROM `posts` WHERE `thread` = '$threadid' AND `id` < '$id'",0,0)/$ppp);

				print "
					$tccell1>Post edited successfully.<br>
					".redirect("{$GLOBALS['jul_views_path']}/thread.php?pid=$id#$id",'return to the thread',0).'</table></table>';
			}
			else {
				loadtlayout();
				$ppost=$sql->fetchq("SELECT * FROM users WHERE id=$post[user]");
				$head = stripslashes($head);
				$sign = stripslashes($sign);
				$message = stripslashes($message);
				$ppost['uid']=$post['user'];
				$ppost['num']=$post['num'];
				$ppost['date']=$post['date'];
				$ppost['tagval']=$post['tagval'];
				$ppost['headtext']=$head;
				$ppost['signtext']=$sign;
				$ppost['text']=$message;
				$ppost['options']=$poptions;

				// Edited notice
				$ppost['edited']   = $edited;
				$ppost['editdate'] = ctime();

				$chks = array();
				if ($nosmilies) $chks[0] = "checked";
				if ($nohtml) $chks[1] = "checked";

				if($isadmin) $ip=$post['ip'];
				print "
					<body onload=window.document.replier.message.focus()>
					$tccellh>Post preview
					$tblend$tblstart
					".threadpost($ppost,1)."
					$tblend<br>$tblstart
					$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
					$tccell1><b>Header:</td>	 $tccell2l width=800px valign=top>$txta=head ROWS=8 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($head) ."</textarea>
					$tccell2l width=* rowspan=3>".moodlist($moodid)."</td><tr>
					$tccell1><b>Post:</td>		 $tccell2l width=800px valign=top>$txta=message ROWS=12 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($message) ."</textarea><tr>
					$tccell1><b>Signature:</td>	 $tccell2l width=800px valign=top>$txta=sign ROWS=8 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($sign) ."</textarea><tr>
					$tccell1>&nbsp</td>$tccell2l colspan=2>
					$inph=action VALUE=editpost>
					$inph=id VALUE=$id>
					$inps=submit VALUE=\"Edit post\">
					$inps=preview VALUE=\"Preview post\"></td>
					<tr>$tccell1><b>Options:</b></td>$tccell2l colspan=2>
					$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\" $chks[0]><label for=\"nosmilies\">Disable Smilies</label> -
					$inpc=\"nohtml\" id=\"nohtml\" value=\"1\" $chks[1]><label for=\"nohtml\">Disable HTML</label></td></tr>
					</FORM>
					$tblend$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>".$forum[title]."</a> - $thread[title]
				";
			}
		}
		else print "
			$tccell1>You are not allowed to edit this post.<br>
			".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$threadid","the thread",0);
		print $tblend;
	}

	elseif ($action=='noob') {
		die();
		/*if ($loguser['powerlevel'] >= 1) {
			mysql_query("UPDATE `posts` SET `noob` = '1' - `noob` WHERE `id` = '$id'");
			print "
				$tblstart$tccell1>Post n00bed!<br>
				".redirect("{$GLOBALS['jul_views_path']}/thread.php?pid=$id&r=1#$id",'the post',0).'</table></table>';
		}*/
	}

	elseif ($action=='delete'){
		if (!$_POST['reallydelete'])
			$txt	= "Are you sure you want to <b>DELETE</b> this post?<br><br><form action='{$GLOBALS['jul_views_path']}/editpost.php' method='post'>$inps=reallydelete value='Delete post'>$inph=action value='delete'>$inph=id value='$id'></form> - <a href='{$GLOBALS['jul_views_path']}/thread.php?pid=$id#$id'>Cancel</a>";
		else {
			if ($loguserid == 1162) { // not like it matters since he's banned anyway <:3
				xk_ircsend("1|The jceggbert5 dipshit tried to delete another post: ". $id);
				$txt="Thank you, $loguser[name], for deleting the post.<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$threadid","the thread",0);
			}
			elseif ($ismod || ($loguserid == $post['user'] && $loguser['powerlevel'] >= 0)) {
				$sql->query("DELETE FROM posts WHERE id='$id'");
				$sql->query("DELETE FROM posts_text WHERE pid='$id'");
				$p = $sql->fetchq("SELECT id,user,date FROM posts WHERE thread=$threadid ORDER BY date DESC");
				$sql->query("UPDATE threads SET replies=replies-1, lastposter=$p[user], lastpostdate=$p[date] WHERE id=$threadid");
				$sql->query("UPDATE forums SET numposts=numposts-1 WHERE id=$forum[id]");
				$txt="Thank you, $loguser[name], for deleting the post.<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$threadid","return to the thread",0);
			}
			else
				$txt="Couldn't delete the post. You are not allowed to delete this post.<br>".redirect("{$GLOBALS['jul_views_path']}/thread.php?id=$threadid","the thread",0);
	  }
		print "$tblstart$tccell1>$txt$tblend";
	}

	print $footer;
	printtimedif($startingtime);
?>
