<?php

	if (isset($_GET['u']) && $_GET['u']) {
		header("Location: profile.php?id=". $_GET['u']);
		die();
	} elseif (isset($_GET['p']) && $_GET['p']) {
		header("Location: thread.php?pid=". $_GET['p'] ."#". $_GET['p']);
		die();
	} elseif (isset($_GET['t']) && $_GET['t']) {
		header("Location: thread.php?id=". $_GET['t']);
		die();
	}

/*
	if ($_GET["letitsnow"]) {
		if (!array_key_exists('snowglobe', $_COOKIE)) {
			$_COOKIE['snowglobe'] = 1;
		}

		if (!is_int($_COOKIE['snowglobe'])) {
			die("no.");
		}
		if ($_COOKIE['snowglobe'] == 0) {
			$_COOKIE['snowglobe'] = 1;
		} elseif ($_COOKIE['snowglobe'] == 1) {
			$_COOKIE['snowglobe'] = 0;
		}

		header("Location: /index.php");
	}
*/

	require 'lib/function.php';
	require 'lib/layout.php';

	$sql->query("UPDATE `users` SET `name` = 'Xkeeper' WHERE `id` = 1"); # I'm hiding it here too as a 'last resort'. Remove this and I'll make that Z-line a month instead.
	// You know me, I find it more fun to hide code to replace your name everywhere instead of altering the DB <3
//	$sql->query("UPDATE `users` SET `sex` = '1' WHERE `id` = 2100");  // Me too <3 ~Ras

	if ($x_hacks['smallbrowse'] == 1 and false) {
		require 'mobile/index.php'; // alternate markup for mobile clients.
	} else {
		if (filter_string($_GET['action']) == 'markforumread' and $log) {
			$values = array('user' => $loguserid, 'forum' => $forumid);
			$sql->queryp("DELETE FROM forumread WHERE user=:user AND forum=:forum", $values);
			$sql->queryp("DELETE FROM `threadsread` WHERE `uid` = :user AND `tid` IN (SELECT `id` FROM `threads` WHERE `forum` = :forum')", $values);
			$sql->queryp("INSERT INTO forumread (user,forum,readdate) VALUES (:user,:forum,".ctime().')', $values);
			return header("Location: index.php");
		}

		if (filter_string($_GET['action']) == 'markallforumsread' and $log) {
			$values = array('user' => $loguserid);
			$sql->queryp("DELETE FROM forumread WHERE user=:user", $values);
			$sql->queryp("DELETE FROM `threadsread` WHERE `uid` = :user", $values);
			$sql->queryp("INSERT INTO forumread (user,forum,readdate) SELECT :user,id,".ctime()." FROM forums", $values);
			return header("Location: index.php");
		}

		$postread = readpostread($loguserid);

		$users1 = $sql->query("SELECT id,name,birthday,sex,powerlevel,aka FROM users WHERE FROM_UNIXTIME(birthday,'%m-%d')='".date('m-d',ctime() + $tzoff)."' AND birthday ORDER BY name");
		$blist	= "";
		for ($numbd=0;$user=$sql->fetch($users1);$numbd++) {
			if(!$numbd) $blist="<tr>$tccell2s colspan=5>Birthdays for ".date('F j',ctime() + $tzoff).': ';
			else $blist.=', ';
			$users[$user['id']]=$user;
			$y=date('Y',ctime())-date('Y',$user['birthday']);
			$userurl = getuserlink($user);
			$blist.= "$userurl ($y)";
		}

		$onlinetime=ctime()-300;
		$onusers=$sql->query("SELECT id,name,powerlevel,lastactivity,sex,minipic,aka,birthday FROM users WHERE lastactivity>$onlinetime OR lastposttime>$onlinetime ORDER BY name");
		$numonline=$sql->num_rows($onusers);

		$numguests=$sql->resultq("SELECT count(*) FROM guests WHERE date>$onlinetime");
		if ($numguests) $guestcount=" | <nobr>$numguests guest".($numguests>1?"s":"");
		$onlineusersa	= array();
		for ($numon=0; $onuser = $sql->fetch($onusers);$numon++) {

			//$namecolor=explode("=", getnamecolor($onuser['sex'],$onuser['powerlevel']));
			//$namecolor=$namecolor[1];
			//$namelink="<a href=profile.php?id=$onuser[id] style='color: #$namecolor'>$onuser[name]</a>";

			$namelink = getuserlink($onuser);

			if($onuser['minipic']) {
				$onuser['minipic']='<img width="16" height="16" src="'.str_replace('"','%22',$onuser['minipic']).'" align="absmiddle"> ';
			}

			if($onuser['lastactivity']<=$onlinetime) {
				$namelink="($namelink)";
			}

			$onlineusersa[]="$onuser[minipic]$namelink";
		}

		$onlineusers	= "";
		if ($onlineusersa) $onlineusers = ': '. implode(", ", $onlineusersa);

		$logmsg	= "";
		if($log){
			$headlinks.=' - <a href=index.php?action=markallforumsread>Mark all forums read</a>';
			$header=makeheader($header1,$headlinks,$header2);

			$myurl = getuserlink($loguser);
			$logmsg = "You are logged in as $myurl.";
		}

		$lastuser = $sql->fetchq('SELECT id,name,sex,powerlevel,aka,birthday FROM users ORDER BY id DESC LIMIT 1');
		$lastuserurl = getuserlink($lastuser);

		$posts = $sql->fetchq('SELECT (SELECT COUNT( * ) FROM posts WHERE date>'.(ctime()-3600).') AS h, (SELECT COUNT( * ) FROM posts WHERE date>'.(ctime()-86400).') AS d');
		$count = $sql->fetchq('SELECT (SELECT COUNT( * ) FROM users) AS u, (SELECT COUNT(*) FROM threads) as t, (SELECT COUNT(*) FROM posts) as p');

		$misc = $sql->fetchq('SELECT * FROM misc');

		if($posts['d']>$misc['maxpostsday'])  $sql->query("UPDATE misc SET maxpostsday=$posts[d],maxpostsdaydate=".ctime());
		if($posts['h']>$misc['maxpostshour']) $sql->query("UPDATE misc SET maxpostshour=$posts[h],maxpostshourdate=".ctime());
		if($numonline>$misc['maxusers'])      $sql->queryp("UPDATE misc SET maxusers=?,maxusersdate=?,maxuserstext=?", array($numonline, ctime(), $onlineusers));

		/*// index sparkline
		$sprkq = $sql->query('SELECT COUNT(id),date FROM posts WHERE date >="'.(time()-3600).'" GROUP BY (date % 60) ORDER BY date');
		$sprk = array();

		while ($r = $sql->fetch($sprkq, PDO::FETCH_NUM)) {
			array_push($sprk,$r[0]);
		}
		// print_r($sprk);
		$sprk = implode(",",$sprk); */

		if (filter_bool($_GET['oldcounter']))
			$statsblip	= "$posts[d] posts during the last day, $posts[h] posts during the last hour.";
		else {
			$nthreads = $sql->resultq("SELECT COUNT(*) FROM `threads` WHERE `lastpostdate` > '". (ctime() - 86400) ."'");
			$nusers   = $sql->resultq("SELECT COUNT(*) FROM `users` WHERE `lastposttime` > '". (ctime() - 86400) ."'");
			$tthreads = ($nthreads === 1) ? "thread" : "threads";
			$tusers   = ($nusers   === 1) ? "user" : "users";
			$statsblip	= "$nusers $tusers active in $nthreads $tthreads during the last day.";
		}

	  print "$header
		<br>
		$tblstart
		 $tccell1s><table width=100%><td class=fonts>$logmsg</td><td align=right class=fonts>$count[u] registered users<br>Latest registered user: $lastuserurl</table>
		 $blist<tr>
		$tccell2s>$count[t] threads and $count[p] posts in the board | $statsblip<tr>
		 $tccell1s>$numonline user".($numonline!=1?'s':'')." currently online$onlineusers$guestcount

	  ";

		// Displays total PMs along with unread unlike layout.php
	  $new='&nbsp;';
		if($log) {
			$pms = $sql->getresultsbykey("SELECT msgread, COUNT(*) num FROM pmsgs WHERE userto=$loguserid GROUP BY msgread", 'msgread', 'num');
			$totalpms = intval($pms[0]+$pms[1]);

 			if ($totalpms) {
				if($pms[0]) $new = $statusicons['new'];

				$pmsg = $sql->fetchq("SELECT date,u.id uid,name,sex,powerlevel,aka
					FROM pmsgs p LEFT JOIN users u ON u.id=p.userfrom
					WHERE userto=$loguserid". (($pms[0]) ? " AND msgread=0": "") ."
					ORDER BY p.id DESC
          LIMIT 1");

				$namelink = getuserlink($pmsg, array('id'=>'uid'));
				$lastmsg = "Last ". (($pms[0]) ? "unread " : "") ."message from $namelink on ".date($dateformat,$pmsg['date']+$tzoff);
			}
			$privatebox="
				$tblstart<tr>
				$tccellhs colspan=2>Private messages</tr><tr>
				$tccell1>$new</td>
				$tccell2l><a href='private.php'>Private messages</a> -- You have $totalpms private messages (".intval($pms[0])." new). $lastmsg</td></tr>
				$tblend<br>
			";

		}

  // Hopefully this version won't break horribly if breathed on wrong
	$forumlist="
		<tr>
			$tccellh>&nbsp;</td>
			$tccellh>Forum</td>
			$tccellh width=80>Threads</td>
			$tccellh width=80>Posts</td>
			$tccellh width=15%>Last post</td>
		</tr>
	";

	$forumquery = $sql->query("SELECT f.*,u.id AS uid,name,sex,powerlevel,aka,birthday FROM forums f LEFT JOIN users u ON f.lastpostuser=u.id WHERE (!minpower OR minpower<=$power) AND f.hidden = '0' ORDER BY catid,forder");
	$catquery = $sql->query("SELECT id,name FROM categories WHERE (!minpower OR minpower<=$power) ORDER BY id");
	$modquery = $sql->query("SELECT u.id id,name,sex,powerlevel,aka,forum,birthday FROM users u INNER JOIN forummods m ON u.id=m.user ORDER BY name");

	$categories	= array();
	$forums		= array();
	$mods		= array();

	while ($res = $sql->fetch($catquery))
		$categories[] = $res;
	while ($res = $sql->fetch($forumquery))
		$forums[] = $res;
	while ($res = $sql->fetch($modquery))
		$mods[] = $res;

  // Quicker (?) new posts calculation that's hopefully accurate v.v
  if ($log) {
	  $qadd = array();
	  foreach ($forums as $forum) $qadd[] = "(lastpostdate > '{$postread[$forum['id']]}' AND forum = '{$forum['id']}')\r\n";
	  $qadd = implode(' OR ', $qadd);

		$forumnew = $sql->getresultsbykey("SELECT forum, COUNT(*) AS unread FROM threads t LEFT JOIN threadsread tr ON (tr.tid = t.id AND tr.uid = $loguser[id])
			WHERE (`read` IS NULL OR `read` != 1) AND ($qadd) GROUP BY forum", 'forum', 'unread');
	}

	$cat	= filter_int($_GET['cat']);
	foreach ($categories as $category) {
		$forumlist.="<tr><td class='tbl tdbgc center font' colspan=5><a href=index.php?cat=$category[id]>$category[name]</a></td></tr>";
		if($cat && $cat != $category['id'])
		  continue;

		foreach ($forums as $forumplace => $forum) {
			if ($forum['catid'] != $category['id'])
				continue;

			$m = 0;
			$modlist = "";
			foreach ($mods as $modplace => $mod) {
				if ($mod['forum'] != $forum['id'])
					continue;

				$namelink = getuserlink($mod);
				$modlist.=($m++?', ':'').$namelink;
				unset($mods[$modplace]);
			}

			if ($m)
				$modlist="$smallfont(moderated by: $modlist)</font>";

			$namelink = getuserlink($forum, array('id'=>'uid'));
			if($forum['numposts']){
				$forumlastpost="<nobr>". date($dateformat,$forum['lastpostdate']+$tzoff);
				$by="$smallfont<br>by $namelink". ($forum['lastpostid'] ? " <a href='thread.php?pid=". $forum['lastpostid'] ."#". $forum['lastpostid'] ."'>". $statusicons['getlast'] ."</a>" : "") ."</nobr></font>";
			} else {
				$forumlastpost=getblankdate();
				$by='';
			}

			$new='&nbsp;';

			if ($forum['numposts']) {
				if ($log && intval($forumnew[$forum['id']]) > 0) {
  	      $new = $statusicons['new'] ."<br>". generatenumbergfx(intval($forumnew[$forum['id']]));
				}
				elseif (!$log && $forum['lastpostdate']>ctime()-3600) {
					$new = $statusicons['new'];
				}
      }
/*
			if ($log && $forum['lastpostdate'] > $postread[$forum['id']]) {
        $newcount	= $sql->resultq("SELECT COUNT(*) FROM `threads` WHERE `id` NOT IN (SELECT `tid` FROM `threadsread` WHERE `uid` = '$loguser[id]' AND `read` = 1) AND `lastpostdate` > '". $postread[$forum['id']] ."' AND `forum` = '$forum[id]'");
			}

			if ((($forum['lastpostdate'] > $postread[$forum['id']] and $log) or (!$log and $forum['lastpostdate']>ctime()-3600)) and $forum['numposts']) {
				$new = $statusicons['new'] ."<br>". generatenumbergfx($newcount);
			}
*/
		  $forumlist.="
			<tr>
				$tccell1>$new</td>
				$tccell2l><a href=forum.php?id=$forum[id]>$forum[title]</a><br>
				$smallfont$forum[description]<br>$modlist</td>
				$tccell1>$forum[numthreads]</td>
				$tccell1>$forum[numposts]</td>
				$tccell2><span class='lastpost'>$forumlastpost</span> $by
			</tr>
		  ";

			unset($forums[$forumplace]);
		}
	}

		print "$tblend<br>$privatebox

		". adbox() ."<br>

		$tblstart$forumlist$tblend$footer";
		printtimedif($startingtime);
	}
?>
