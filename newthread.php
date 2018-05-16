<?php
	require 'lib/function.php';
	$forum = $sql->fetchp("SELECT * FROM forums WHERE id=?", array($id));
	$windowtitle = "$boardname -- $forum[title] -- New Thread";
	$specialscheme	= $forum['specialscheme'];

	// Stop this insanity.  Never index newthread.
	$meta['noindex'] = true;

	require 'lib/layout.php';

	$forumid=$id;
	$fonline=fonlineusers($forumid);
	$header=makeheader($header1,$headlinks,$header2 ."	$tblstart$tccell1s>$fonline$tblend");

	$smilies=readsmilies();
	replytoolbar(1);

/*
	if (false && $id == 27) { //trash forum
		print "$header
			<br>
			$tblstart
				$tccell1>
					No. Stop that, you idiot.
				</td>
			$tblend
		$footer";
		die();
	}
	if ($forum['nopolls'] && $poll) {
		print "$header
			<br>
			$tblstart
				$tccell1>
					A for effort, but F for still failing.
				</td>
			$tblend
		$footer";
		die();
	}
*/

	print $header;
	if($poll) {
		$c=1;
		$d=0;
		while($chtext[$c+$d] || $c < $_POST['count']) {
			if($remove[$c+$d]) $d++;
			else {
				$choices.="Choice $c: $inpt=chtext[$c] SIZE=30 MAXLENGTH=255 VALUE=\"".htmlspecialchars($chtext[$c+$d])."\"> &nbsp Color: $inpt=chcolor[$c] SIZE=7 MAXLENGTH=25 VALUE=\"".htmlspecialchars($chcolor[$c+$d])."\"> &nbsp <INPUT type=checkbox class=radio name=remove[$c] value=1> Remove<br>";
				$c++;
			}
		}
		$choices.="Choice $c: $inpt=chtext[$c] SIZE=30 MAXLENGTH=255> &nbsp Color: $inpt=chcolor[$c] SIZE=7 MAXLENGTH=25><br>$inps=paction VALUE=\"Submit changes\"> and show $inpt=count size=4 maxlength=2 VALUE=\"".htmlspecialchars(($_POST['count']) ? $_POST['count'] : $c)."\"> options";
		if($mltvote) $checked1='checked';
		else $checked0='checked';
	}
	$posticons=file('posticons.dat');
	for($i=0;$posticons[$i];$i++) {
		if($iconid==$i) $checked='checked';
		$posticonlist.="$radio=iconid value=$i $checked>&nbsp<IMG SRC=$posticons[$i] HEIGHT=15 WIDTH=15>&nbsp; &nbsp;";
		$checked='';
		if(($i+1)%10==0) $posticonlist.='<br>';
	}
	if(!$iconid or $iconid==-1) $checked='checked';
	$posticonlist.="
		<br>$radio=iconid value=-1 $checked>&nbsp;None&nbsp; &nbsp; &nbsp;
		Custom: $inpt=custposticon SIZE=40 MAXLENGTH=100 VALUE=\"". htmlspecialchars($custposticon) ."\">
	";
	$subject=htmlspecialchars($subject);
	$question=htmlspecialchars($question);

	if ($nosmilies)	$nosmilieschk	= " checked";
	if ($nohtml)	$nohtmlchk	= " checked";
	if ($nolayout)	$nolayoutchk	= " checked";

	$form=(!$poll?"
		<tr>$tccell1><b>Thread icon:</td>	$tccell2l colspan=2>$posticonlist</td></tr>
		<tr>$tccell1><b>Thread title:</td>$tccell2l colspan=2>$inpt=subject SIZE=40 MAXLENGTH=100 VALUE=\"". $subject ."\"></td></tr>
		<tr>$tccell1><b>Post:</td>$tccell2l width=800px valign=top>".replytoolbar(2)."
		$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". $message ."</TEXTAREA></td>
		$tccell2l width=*>".moodlist($moodid)."</td></tr>
		<tr>$tccell1>&nbsp</td>$tccell2l colspan=2>
		$inph=action VALUE=postthread>
		$inph=id VALUE=$id>
		$inps=submit VALUE=\"Submit thread\">
		$inps=preview VALUE=\"Preview thread\"></td></tr>
		<tr>
		  $tccell1><b>Options:</b></td>$tccell2l colspan=2>
			$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\"$nosmilieschk><label for=\"nosmilies\">Disable Smilies</label> -
			$inpc=\"nolayout\" id=\"nolayout\" value=\"1\"$nolayoutchk><label for=\"nolayout\">Disable Layout</label> -
			$inpc=\"nohtml\" id=\"nohtml\" value=\"1\"$nohtmlchk><label for=\"nohtml\">Disable HTML</label></td></tr><tr>
		<!-- </FORM> -->
	":"
		<tr>$tccell1><b>Poll icon:</td>	$tccell2l colspan=2>$posticonlist</td></tr>
		<tr>$tccell1><b>Poll title:</td>	$tccell2l colspan=2>$inpt=subject SIZE=40 MAXLENGTH=100 VALUE=\"". $subject ."\"></td></tr>
		<tr>$tccell1><b>Question:</td>	$tccell2l colspan=2>$inpt=question SIZE=60 MAXLENGTH=255 VALUE=\"". $question ."\"></td></tr>
		<tr>$tccell1><b>Briefing:</td>	$tccell2l colspan=2>$txta=briefing ROWS=2 COLS=$numcols style=\"resize:vertical;\">". htmlspecialchars($briefing) ."</TEXTAREA></td></tr>
		<tr>$tccell1><b>Multi-voting:</td>$tccell2l colspan=2>$radio=mltvote value=0 $checked0> Disabled &nbsp $radio=mltvote value=1 $checked1> Enabled</td></tr>
		<tr>$tccell1><b>Choices:</td>	$tccell2l colspan=2>$choices</td></tr>
		<tr>$tccell1><b>Post:</td>$tccell2l width=800px valign=top>".replytoolbar(2)."
		$txta=message ROWS=21 COLS=$numcols style=\"width: 100%; max-width: 800px; resize:vertical;\">". htmlspecialchars($message) ."</TEXTAREA></td>
		$tccell2l width=*>".moodlist($moodid)."</td></tr>

		<tr>
		$tccell1>&nbsp</td>$tccell2l colspan=2>
		$inph=action VALUE=postthread>
		$inph=id VALUE=$id>
		$inph=poll VALUE=1>
		$inps=submit VALUE=\"Submit poll\">
		$inps=preview VALUE=\"Preview poll\"></td>
		<tr>
		  $tccell1><b>Options:</b></td>$tccell2l colspan=2>
			$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\"$nosmilieschk><label for=\"nosmilies\">Disable Smilies</label> -
			$inpc=\"nolayout\" id=\"nolayout\" value=\"1\"$nolayoutchk><label for=\"nolayout\">Disable Layout</label> -
			$inpc=\"nohtml\" id=\"nohtml\" value=\"1\"$nohtmlchk><label for=\"nohtml\">Disable HTML</label></td></tr><tr>
		<!-- </FORM> -->
	");
	if(!$_POST['action'] or $_POST['paction']) {
		print "
			$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>".$forum[title]."</a>
			<form action=newthread.php name=replier method=post autocomplete=\"off\">
			$tblstart
		";
		if($log and $forums[$id][minpowerthread]>$power) {
			print "$tccell1>Sorry, but you are not allowed to post";
			if($banned) print ", because you are banned from this board.<br>".redirect("forum.php?id=$id",'return to the forum',0);
			else print ' in this restricted forum.<br>'.redirect('index.php','return to the board',0);
		}
		else {
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

			print "
				<body onload=window.document.REPLIER.subject.focus()>
				$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
				$tccell1><b>{$passhint}</td> $tccell2l colspan=2>
					{$altloginjs}
					<b>Username:</b> $inpt=username VALUE=\"".htmlspecialchars($username)."\" SIZE=25 MAXLENGTH=25 autocomplete=\"off\">

					<!-- Hack around autocomplete, fake inputs (don't use these in the file) -->
					<input style=\"display:none;\" type=\"text\"     name=\"__f__usernm__\">
					<input style=\"display:none;\" type=\"password\" name=\"__f__passwd__\">

					<b>Password:</b> $inpp=password SIZE=13 MAXLENGTH=64 autocomplete=\"off\">
				</span><tr>";
			print $form;
		}
		print "
			</table>
			</table>
			</form>
			$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>".$forum[title]."</a>
			".replytoolbar(4);
	}
	if($_POST['action']=='postthread' and !$_POST['paction']) {
		print "<br>$tblstart";
		if ($log && !$password)
			$userid = $loguserid;
		else
			$userid = checkuser($username,escape_password($password));

		$user=$sql->fetchq("SELECT * FROM users WHERE id=$userid");
		if($user['powerlevel']<0) $userid=-1;

		// can't be posting too fast now
		$limithit = $user['lastposttime'] < (ctime()-30);
		// can they post in this forum?
		$authorized = $user['powerlevel'] >= $forum['minpowerthread'];
		// does the forum exist?
		$forumexists = $forum['title'];

		if($userid!=-1 && $subject && $message && $forumexists && $authorized && $limithit) {
			$msg=$message;
			// squot(0,$msg);
			$sign=$user['signature'];
			$head=$user['postheader'];

			// improved post backgrounds
			if($user['postbg']) {
				$head = "<table width=100% height=100% border=0 cellpadding=0 cellspacing=0><td valign=top background=\"$user[postbg]\">$head";
				$sign = "$sign</td></table>";
			}

			$numposts = $user['posts'] + 1;
			$numdays = (ctime()-$user['regdate'])/86400;
			$tags	= array();
			$msg = doreplace($msg, $numposts, $numdays, $username, $tags);
			$rsign = doreplace($sign, $numposts, $numdays, $username);
			$rhead = doreplace($head, $numposts, $numdays, $username);
			$tagval	= json_encode($tags);
			$posticons = file('posticons.dat');
			$posticon = $posticons[$iconid];
			$currenttime = ctime();
			$postnum = $numposts;
			if($iconid == -1) $posticon='';
			if($custposticon) $posticon = $custposticon;

			if($submit) {
				$values = array(
					'posts'         => $numposts,
					'lastposttime'  => $currenttime,
				);
				$where = array('id' => $id);
				$qstr  = mysql::phs($values, $where);
				$sql->queryp("UPDATE `users` SET {$qstr} WHERE `id` = :id", $values);
				
				if (filter_bool($nolayout)) {
					$headid = 0;
					$signid = 0;
				} else {
					$headid=getpostlayoutid($head);
					$signid=getpostlayoutid($sign);
				}
				
				// create the thread
				$values = array(
					'forum'         => $id,
					'user'          => $userid,
					'views'         => 0,
					'closed'        => 0,
					'title'         => $subject,
					'icon'          => $posticon,
					'replies'       => 0,
					'firstpostdate' => $currenttime,
					'lastpostdate'  => $currenttime, 
					'lastposter'    => $userid,
				);
				$sql->queryp("INSERT INTO `threads` SET ".mysql::phs($values), $values);
				$t = $sql->insert_id();
				// create the post
				$values = array(
					'thread' => $t,
					'user'   => $userid,
					'date'   => $currenttime,
					'ip'     => $userip,
					'num'    => $postnum,
					'headid' => $headid,
					'signid' => $signid,
					'moodid' => $_POST['moodid'],
				);
				$sql->queryp("INSERT INTO `posts` SET ".mysql::phs($values), $values);
				$pid = $sql->insert_id();
				// create the post text
				if ($pid) {
					$values = array(
						'pid'     => $pid,
						'text'    => $msg,
						'tagval'  => $tagval,
						'options' => filter_int($_POST['nosmilies']) . "|" . filter_int($_POST['nohtml'])
					);
					$sql->queryp("INSERT INTO `posts_text` SET ".mysql::phs($values), $values);
				}
				
				$values = array(
					'lastpostdate' => $currenttime, 
					'lastpostuser' => $userid,
					'lastpostid'   => $pid,
				);
				$where = array('id' => $id);
				$qstr = mysql::phs($values, $where);
				$sql->queryp("UPDATE `forums` SET {$qstr}, `numthreads` = `numthreads` + 1, `numposts` = `numposts` + 1 WHERE id = :id", $values);

				if(!$poll) {
					print "
						$tccell1>Thread posted successfully!
						<br>".redirect("thread.php?id=$t", $subject, 0).$tblend;

					xk_ircout("thread", $user['name'], array(
						'forum'		=> $forum['title'],
						'fid'		=> $forum['id'],
						'thread'	=> str_replace("&lt;", "<", $subject),
						'pid'		=> $pid,
						'pow'		=> $forum['minpower'],
					));
				}
				else {
					// create poll data
					$values = array(
						'question'   => $_POST['question'],
						'briefing'   => $_POST['briefing'],
						'closed'     => 0,
						'doublevote' => (int) $_POST['mltvote']
					);
					$sql->queryp("INSERT INTO `poll` SET ".mysql::phs($values), $values);
					$p=$sql->insert_id();
					$sql->query("UPDATE `threads` SET `poll` = '$p' where `id` = '$t'");
					// create poll choices
					$makechoice = $sql->prepare("INSERT INTO `poll_choices` (`poll`, `choice`, `color`) VALUES (?,?,?)");
					$c=1;
					while ($chtext[$c]) {
						$sql->execute($makechoice, array($p, $chtext[$c], $chcolor[$c]));
						$c++;
					}
					print "
						$tccell1>Poll created successfully!
						<br>".redirect("thread.php?id=$t", $subject, 0).$tblend;

					xk_ircout("poll", $user['name'], array(
						'forum'		=> $forum['title'],
						'fid'		=> $forum['id'],
						'thread'	=> str_replace("&lt;", "<", $subject),
						'pid'		=> $pid,
						'pow'		=> $forum['minpower'],
					));
				}
			}
			else {
				if($posticon) $posticon1="<img src=\"". htmlspecialchars($posticon) ."\" height=15 align=absmiddle>";

				if($poll) {
					for($c=1;$chtext[$c];$c++) {
						$hchoices.="$inph=chtext[$c] VALUE=\"".htmlspecialchars($chtext[$c])."\">$inph=chcolor[$c] VALUE=\"".htmlspecialchars($chcolor[$c]).'">';
						$pchoices.="
							$tccell1l width=20%>$chtext[$c]</td>
							$tccell2l width=60%><table cellpadding=0 cellspacing=0 width=50% bgcolor='$chcolor[$c]'><td>&nbsp</table></td>
							$tccell1 width=20%>$fonttag ? votes, ??.?%<tr>
						";
					}
					$mlt=($mltvote?'enabled':'disabled');
					$pollpreview="
						<td colspan=3 class='tbl tdbgc center font'><b>$question<tr>
						$tccell2ls colspan=3>$briefing<tr>
						$pchoices
						$tccell2ls colspan=3>Multi-voting is $mlt.
						$tblend<br>$tblstart
					";
					$subject = htmlspecialchars($subject);
					$question = htmlspecialchars($question);
					$briefing = htmlspecialchars($briefing);
				}
				loadtlayout();
				$ppost=$user;
				$ppost['uid']=$userid;
				$ppost['num']=$postnum;
				$ppost['posts']++;
				$ppost['lastposttime']=$currenttime;
				$ppost['date']=$currenttime;
				//$ppost['headtext']=$rhead;
				//$ppost['signtext']=$rsign;
				if ($nolayout) {
					$ppost['headtext'] = "";
					$ppost['signtext'] = "";
				}
				else {
					$ppost['headtext']=$rhead;
					$ppost['signtext']=$rsign;
				}
				$ppost['moodid']=$_POST['moodid'];
				$ppost['text']=$message;
				$ppost['options'] = $_POST['nosmilies'] . "|" . $_POST['nohtml'];
				if($isadmin) $ip=$userip;
				$threadtype=($poll?'poll':'thread');
				print "
					<body onload=window.document.REPLIER.message.focus()>
					$tccellh>".($poll?'Poll':'Thread')." preview
					$tblend$tblstart
					$pollpreview
					$tccell2l>$posticon1 <b>". $subject ."</b>
					$tblend$tblstart
					".threadpost($ppost,1)."
					$tblend<br>$tblstart
					<FORM ACTION=newthread.php NAME=REPLIER METHOD=POST>
					$tccellh width=150>&nbsp</td>$tccellh colspan=2>&nbsp<tr>
					$inph=username VALUE=\"".htmlspecialchars($username)."\">
					$inph=password VALUE=\"".htmlspecialchars($password)."\">
					$form
					</td></FORM>
					$tblend
				";
			}
		}
		else {
			$reason = "You haven't entered your username and password correctly.";
			if (!$limithit) $reason = "You are trying to post too rapidly.";
			if (!$message) $reason = "You haven't entered a message.";
			if (!$subject) $reason = "You haven't entered a subject.";
			if (!$authorized) $reason = "You aren't allowed to post in this forum.";

			print "
				$tccell1>Couldn't post the thread. $reason
				<br>".redirect("forum.php?id=$id", $forum[title], 2).$tblend;
		}
	}

	print $footer;
	printtimedif($startingtime);
