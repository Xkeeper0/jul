<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	$trashid = 27;

	$thread  = $sql->fetchq("SELECT forum,closed,title,icon,replies,lastpostdate,lastposter,sticky FROM threads WHERE id=$id");
	$forumid = $thread['forum'];
	$posticons = file('posticons.dat');

	if (@mysql_num_rows($sql->query("SELECT user FROM forummods WHERE forum={$forumid} and user={$loguserid}")))
		$ismod = 1;

	if (!$forumid)
		$ismod = 0;
	elseif ($sql->resultq("SELECT minpower FROM forums WHERE id={$forumid}") > $loguser['powerlevel'])
		$ismod = 0;

	if (!$ismod)
		errorpage("You aren't allowed to edit this thread.",'the thread',"{$GLOBALS['jul_views_path']}/thread.php?id={$id}");

	// Quickmod
	if (substr($_GET['action'], 0, 1) == 'q') {
		switch ($_GET['action']) {
			case 'qstick':   $update = 'sticky=1'; break;
			case 'qunstick': $update = 'sticky=0'; break;
			case 'qclose':   $update = 'closed=1'; break;
			case 'qunclose': $update = 'closed=0'; break;
			default: return header("Location: {$GLOBALS['jul_views_path']}/thread.php?id={$id}");
		}

		$sql->query("UPDATE threads SET {$update} WHERE id={$id}");
		return header("Location: {$GLOBALS['jul_views_path']}/thread.php?id={$id}");
	}
	elseif ($_POST['action'] == "trashthread") {
		$sql->query("UPDATE threads SET sticky=0, closed=1, forum=$trashid WHERE id='$id'");
		$numposts = $thread['replies'] + 1;
		$t1 = $sql->fetchq("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1");
		$t2 = $sql->fetchq("SELECT lastpostdate,lastposter FROM threads WHERE forum=$trashid ORDER BY lastpostdate DESC LIMIT 1");
		$sql->query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
		$sql->query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$trashid");

		// Yeah whatever
		errorpage("Thread successfully trashed.",'return to the thread',"{$GLOBALS['jul_views_path']}/thread.php?id=$id");
	}
	elseif ($_POST['action'] == 'editthread') {
		$posticons[$iconid]=str_replace("\n",'',$posticons[$iconid]);

		$icon=$posticons[$iconid];
		if($custposticon) $icon=$custposticon;
		$sql->query("UPDATE `threads` SET `forum` = '$forummove', `closed` = '$closed', `title` = '$subject', `icon` = '$icon', `sticky` = '$sticky' WHERE `id` = '$id'");
		if($forummove!=$forumid) {
			$numposts=$thread['replies']+1;
			$t1 = $sql->fetchq("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1");
			$t2 = $sql->fetchq("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forummove ORDER BY lastpostdate DESC LIMIT 1");
			$sql->query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
			$sql->query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$forummove");
		}
		errorpage("Thank you, $loguser[name], for editing the thread.",'return to the thread',"{$GLOBALS['jul_views_path']}/thread.php?id=$id");
	}
	// Deletion disallowed for now
/*	elseif ($_POST['action'] == 'deletethread') {
		$sql->query("DELETE FROM threads WHERE id=$id");
		$sql->query("DELETE FROM posts WHERE thread=$id");
		$numdeletedposts=$thread[replies]+1;
		$t1 = $sql->fetchq("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1");
		$sql->query("UPDATE forums SET numposts=numposts-$numdeletedposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
		errorpage("Thank you, $loguser[name], for deleting the thread.",'return to the thread',"{$GLOBALS['jul_views_path']}/thread.php?id=$id");
	} */
	elseif ($_GET['action'] == 'trashthread') {
		print "$header<br>$tblstart
			<form action='{$GLOBALS['jul_views_path']}/editthread.php' name='trashcompactor' method='post'>
				<tr>$tccell1><input type='hidden' value='trashthread' name='action'>
				Are you sure you want to trash this thread?<br>
				<input type='hidden' value='$id' name='id'>
				<input type='submit' value='Trash Thread'> -- <a href='/thread.php?id=$id'>Cancel</a></td></tr>
			</form>$tblend$footer";
	}
	else {
		$thread['icon'] = str_replace("\n","",$thread['icon']);
		$customicon = $thread['icon'];

		for ($i=0;$posticons[$i];) {
			$posticons[$i] = str_replace($br,"",$posticons[$i]);

			if($thread['icon']==$posticons[$i]){
				$checked='checked=1';
				$customicon='';
			}

			$posticonlist.="<INPUT type=radio class=radio name=iconid value=$i $checked>&nbsp;<IMG SRC=$posticons[$i] HEIGHT=15 WIDTH=15>&nbsp; &nbsp;";
			$i++;
			if($i%10==0) $posticonlist.='<br>';
			$checked='';
		}

		if (!$thread['icon'])
			$checked='checked=1';

		$posticonlist .= "
			<br>$radio=iconid value=-1 $checked>&nbsp; None &nbsp; &nbsp;
			Custom: $inpt=custposticon VALUE='$customicon' SIZE=40 MAXLENGTH=100>
		";

		$check1[$thread['closed']]='checked=1';
		$check2[$thread['sticky']]='checked=1';

		$forums = $sql->query("SELECT id,title FROM forums WHERE minpower<='$power' ORDER BY forder");
		while ($forum = $sql->fetch($forums)) {
			$checked='';
			if($thread['forum']==$forum['id']) $checked='selected';
			$forummovelist.="<option value=$forum[id] $checked>$forum[title]</option>";
		}

		print "$header<br><FORM ACTION='{$GLOBALS['jul_views_path']}/editthread.php' NAME=REPLIER METHOD=POST>$tblstart
			<tr>$tccellh width=150>&nbsp;</td>$tccellh>&nbsp;</td></tr>
			<tr>$tccell1><b>Thread title:</b></td>	$tccell2l>$inpt=subject VALUE=\"$thread[title]\" SIZE=40 MAXLENGTH=100></td></tr>
			<tr>$tccell1><b>Thread icon:</b></td>	$tccell2l>$posticonlist</td></tr>
			<tr>$tccell1 rowspan=2>&nbsp;</td>		$tccell2l>$radio=closed value=0 $check1[0]> Open&nbsp; &nbsp;$radio=closed value=1 $check1[1]>Closed</td></tr>
			<tr>									$tccell2l>$radio=sticky value=0 $check2[0]> Normal&nbsp; &nbsp;$radio=sticky value=1 $check2[1]>Sticky</td></tr>
			<tr>$tccell1><b>Forum</b></td>			$tccell2l><select name=forummove>$forummovelist</select>
													<!-- <INPUT type=checkbox class=radio name=delete value=1>Delete thread --></td></tr>
			<tr>$tccell1>&nbsp;</td>				$tccell2l>
													$inph=action VALUE=editthread>$inph=id VALUE=$id>
													$inps=submit VALUE=\"Edit thread\"></td></tr>
		$tblend</FORM>$footer
		";
	}
	printtimedif($startingtime);
?>
