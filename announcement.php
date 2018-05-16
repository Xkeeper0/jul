<?php
	require 'lib/function.php';
	require 'lib/layout.php';
	
	$f = (int) $f;
	if ($f && !$sql->resultp("SELECT COUNT(*) FROM forums WHERE id = ? AND minpower <= ?", array($f, $loguser['powerlevel'])))
		errorpage("This forum does not exist.", 'the index page', 'index.php');
	if (!$ismod && $loguserid)
		$ismod = (int) $sql->resultq("SELECT COUNT(*) FROM forummods WHERE forum = ? and user = ?", array($f, $loguser['id']));
	$canpost = ($isadmin || ($ismod && $f > 0));
	if ($_GET['action'] == 'edit' || $_POST['action'] == 'editannc') {
		$annc = $sql->fetch($sql->query("SELECT * FROM announcements WHERE id=$id"));
		// if($annc[forum]>0 && $ismod) $canpost=true;
	}
	$smilies = readsmilies();
	if (!$action) {
		loadtlayout();
		$ppp = ($log ? $loguser['postsperpage'] : 20);
		$min = $ppp * $page;
		if ($loguser['id'] && $f == 0) {
			$sql->query("UPDATE `users` SET `lastannouncement` = (SELECT MAX(`id`) FROM `announcements` WHERE `forum` = 0) WHERE `id` = '" . $loguser['id'] . "'");
		}
		$anncs     = $sql->queryp("SELECT a.*,u.*,a.title atitle,u.id uid FROM announcements a,users u WHERE forum=? AND a.user=u.id ORDER BY a.id DESC LIMIT $min,$ppp", array($f));
		$annctotal = @$sql->resultp("SELECT count(*) FROM announcements WHERE forum=?", array($f));
		//--
		$pagelinks = $smallfont . 'Pages:';
		for ($i = 0; $i < ($annctotal / $ppp); $i++) {
			if ($i == $page)
				$pagelinks .= ' ' . ($i + 1);
			else
				$pagelinks .= " <a href=announcement.php?f=$f&page=$i>" . ($i + 1) . '</a>';
		}
		//--
		$annclist = "$tccellh width=150>User</td>$tccellh colspan=2>Announcement<tr>";
		while ($annc = $sql->fetch($anncs)) {
			if ($annccount)
				$annclist .= '<tr>';
			$annccount++;
			$bg   = $bg % 2 + 1;
			$edit = '&nbsp;';
			if ($canpost) {
				$edit = "<a href=announcement.php?id=$annc[0]&action=edit>Edit</a> | <a href=announcement.php?id=$annc[0]&action=delete&f=$f>Delete</a>";
				if ($isadmin)
					$ip = " | IP: $annc[3]";
			}
			if ($loguser['viewsig'] == 2) {
				$annc['headtext'] = $annc['postheader'];
				$annc['signtext'] = $annc['signature'];
			}
			$annc['text'] = "<center><b>$annc[atitle]</b></center><hr>$annc[text]";
			$annclist    .= threadpost($annc, $bg);
		}
	}
	if ($canpost) {
		if ($_GET['action'] == 'delete') {
			$sql->queryp("DELETE FROM announcements WHERE id=?", array($id));
			$annclist .= "$tccell1>Announcement deleted.
			<br>" . redirect("announcement.php?f=$f", 'return to the announcements', 0);
		}
		if ($_GET['action'] == 'new') {
			$annclist = "
			<FORM ACTION=announcement.php NAME=REPLIER METHOD=POST>
			$tccellh width=150>&nbsp</td>$tccellh>&nbsp;<tr>
			$tccell1><b>Announcement title:</b></td>$tccell2l>$inpt=subject SIZE=70 MAXLENGTH=100><tr>
			$tccell1><b>Announcement:</b></td>	$tccell2l>$txta=message ROWS=20 COLS=$numcols></TEXTAREA><tr>
			$tccell1>&nbsp;</td>				$tccell2l>$inph=action VALUE=postannc>$inph=f VALUE=$f>
			$inps=submit VALUE=\"Post announcement\">
			$inps=preview VALUE=\"Preview announcement\"></td>
			</FORM>";
		}
		if ($_GET['action'] == 'edit') {
			if (!$annc['headid']) $head = $annc['headtext'];
			else $head = $sql->resultq("SELECT text FROM postlayouts WHERE id=$annc[headid]");
			if (!$annc['signid']) $sign = $annc['signtext'];
			else $sign = $sql->resultq("SELECT text FROM postlayouts WHERE id=$annc[signid]");
			sbr(1, $annc['text']);
			sbr(1, $head);
			sbr(1, $sign);
			$annclist = "
			<FORM ACTION=announcement.php NAME=REPLIER METHOD=POST>
			$tccellh width=150>&nbsp</td>$tccellh>&nbsp;<tr>
			$tccell1><b>Announcement title:</b></td>$tccell2l>$inpt=subject VALUE=\"$annc[title]\" SIZE=70 MAXLENGTH=100><tr>
			$tccell1><b>Header:</b></td>		$tccell2l>$txta=head ROWS=8 COLS=$numcols>$head</TEXTAREA><tr>
			$tccell1><b>Announcement:</b></td>	$tccell2l>$txta=message ROWS=12 COLS=$numcols>$annc[text]</TEXTAREA><tr>
			$tccell1><b>Signature:</b></td>		$tccell2l>$txta=sign ROWS=8 COLS=$numcols>$sign</TEXTAREA><tr>
			$tccell1>&nbsp</td>				$tccell2l>
			$inph=action VALUE=editannc>
			$inph=f VALUE=$annc[forum]>
			$inph=id VALUE=$id>
			$inph=edited VALUE=\"$annc[edited]\">
			$inps=submit VALUE=\"Edit announcement\">
			$inps=preview VALUE=\"Preview announcement\"></td>
			</FORM>";
		}
		if ($_POST['action'] == 'postannc') {
			$userid = $loguserid;
			$user   = $loguser;
			if ($userid != -1) {
				$sign = $user['signature'];
				$head = $user['postheader'];
				/*
				if ($user['postbg'])
					$head = "<div style=background:url($user[postbg]);height=100%>$head";
				*/
				$numposts = $user['posts'];
				$numdays  = (ctime() - $user['regdate']) / 86400;
				$tags     = array();
				$message  = doreplace($message, $numposts, $numdays, $user['name'], $tags);
				$tagval   = json_encode($tags);
				$rsign    = doreplace($sign, $numposts, $numdays, $user['name']);
				$rhead    = doreplace($head, $numposts, $numdays, $user['name']);
				$subject  = htmlspecialchars($subject);
				$currenttime = ctime();
				if ($submit) {
					if (!$f) $f = 0;
					$headid = getpostlayoutid($head);
					$signid = getpostlayoutid($sign);
					$values = array(
						'user'   => $userid,
						'date'   => $currenttime,
						'ip'     => $userip,
						'title'  => $subject,
						'forum'  => $f,
						'text'   => $message,
						'headid' => $headid,
						'signid' => $signid,
						'tagval' => $tagval
					);
					$sql->queryp("INSERT INTO `announcements` SET " . mysql::phs($values), $values);
					$annclist = "
					$tccell1>Thank you, $user[name], for posting your announcement.<br>
					 " . redirect("announcement.php?f=$f", "the announcements", 0) . "</table></table>";
				} else {
					loadtlayout();
					$ppost             = $user;
					$ppost['uid']      = $userid;
					$ppost['date']     = $currenttime;
					$ppost['headtext'] = $rhead;
					$ppost['signtext'] = $rsign;
					$ppost['text']     = "<center><b>" . $subject . "</b></center><hr>" . $message;
					if ($isadmin)
						$ip = $userip;
					$annclist = "
					<body onload=window.document.REPLIER.message.focus()>
					$tccellh>Announcement preview
					$tblend$tblstart
					" . threadpost($ppost, 1) . "
					$tblend<br>$tblstart
					<FORM ACTION=announcement.php NAME=REPLIER METHOD=POST>
					$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
					$tccell1><b>Announcement title:</td>$tccell2l>$inpt=subject SIZE=70 MAXLENGTH=100 VALUE=\"" . $subject . "\"><tr>
					$tccell1><b>Announcement:</td>	$tccell2l>$txta=message ROWS=10 COLS=$numcols>" . htmlspecialchars($message) . "</TEXTAREA><tr>
					$tccell1>&nbsp</td>$tccell2l>
					$inps=submit VALUE=\"Submit announcement\">
					$inps=preview VALUE=\"Preview announcement\">
					$inph=action VALUE=postannc>
					$inph=f VALUE=$f>
					</td>
					</FORM>";
				}
			} else {
				$annclist = "
				$tccell1>Couldn't enter the announcement. You haven't entered the right username or password.
				" . redirect('announcement.php', 'return to the announcements', 0);
			}
		}
		if ($_POST['action'] == 'editannc') {
			print $tblstart;
			$numposts = $loguser['posts'];
			$numdays  = (ctime() - $loguser['regdate']) / 86400;
			$message  = doreplace($message, $numposts, $numdays, $loguser['name']);
			$edited   = getuserlink($loguser);
			$subject  = htmlspecialchars($subject);
			if ($submit) {
				$headid = @$sql->resultp("SELECT id FROM postlayouts WHERE text=? LIMIT 1", array($head));
				$signid = @$sql->resultp("SELECT id FROM postlayouts WHERE text=? LIMIT 1", array($sign));
				if ($headid) $head = '';
				else         $headid = 0;
				if ($signid) $sign = '';
				else         $signid = 0;
				
				$values = array(
					'title'    => $subject,
					'text'     => $message,
					'headid'   => $headid,
					'signid'   => $signid,
					'headtext' => $head,
					'signtext' => $sign,
					'edited'   => $edited,
					'editdate' => ctime()
				);
				$sql->queryp("UPDATE announcements SET " . mysql::phs($values) . " WHERE id=$id", $values);
				$annclist = "
				$tccell1>Thank you, " . $loguser['name'] . ", for editing the announcement.<br>
				" . redirect("announcement.php?f=$f", "go to the announcements", 0);
			} else {
				loadtlayout();
				$annc              = $sql->fetchp("SELECT * FROM announcements WHERE id=?", array($id));
				$ppost             = $sql->fetchp("SELECT * FROM users WHERE id=?", array($annc['user']));
				$ppost['uid']      = $annc['user'];
				$ppost['date']     = $annc['date'];
				$ppost['tagval']   = $annc['tagval'];
				$ppost['headtext'] = $head;
				$ppost['signtext'] = $sign;
				$ppost['text']     = "<center><b>$subject</b></center><hr>$message";
				
				$ppost['edited']   = $edited;
				$ppost['editdate'] = ctime();
				
				if ($isadmin)
					$ip = $annc['ip'];
				$annclist = "
				<body onload=window.document.REPLIER.message.focus()>
				$tccellh>Announcement preview
				$tblend$tblstart
				" . threadpost($ppost, 1) . "
				$tblend<br>$tblstart
				<FORM ACTION=announcement.php NAME=REPLIER METHOD=POST>
				$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
				$tccell1><b>Announcement title:</b></td>$tccell2l>$inpt=subject VALUE=\"$subject\" SIZE=70 MAXLENGTH=100><tr>
				$tccell1><b>Header:</td>$tccell2l>$txta=head ROWS=4 COLS=$numcols>". htmlspecialchars($head). "</TEXTAREA><tr>
				$tccell1><b>Announcement:</td>$tccell2l>$txta=message ROWS=6 COLS=$numcols>". htmlspecialchars($message). "</TEXTAREA><tr>
				$tccell1><b>Signature:</td>$tccell2l>$txta=sign ROWS=4 COLS=$numcols>". htmlspecialchars($sign). "</TEXTAREA><tr>
				$tccell1>&nbsp</td>$tccell2l>
				$inps=submit VALUE=\"Edit announcement\">
				$inps=preview VALUE=\"Preview announcement\">
				$inph=action VALUE=editannc>
				$inph=id VALUE=$id>
				$inph=f VALUE=$f>
				</td>
				</FORM>";
			}
		}
		$postnew = "<a href=announcement.php?action=new&f=$f>Post new announcement</a>";
	}
	print "$header
	<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - Announcements</td><td align=right>$smallfont$postnew</table>
	$pagelinks$tblstart$annclist$tblend$pagelinks$footer
	";
	printtimedif($startingtime);
	