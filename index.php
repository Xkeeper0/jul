<?php

	if ($_GET['u']) {
		header("Location: profile.php?id=". $_GET['u']);
		die();
	} elseif($_GET['p']) {
		header("Location: thread.php?pid=". $_GET['p'] ."#". $_GET['p']);
		die();
	} elseif($_GET['t']) {
		header("Location: thread.php?id=". $_GET['t']);
		die();
	}

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

	require 'lib/function.php';
	require 'lib/layout.php';

	if ($x_hacks['smallbrowse'] == 1 and false) {
		require 'mobile/index.php'; // alternate markup for mobile clients.
	} else {
		if($action=='markforumread' and $log) {
			mysql_query("DELETE FROM forumread WHERE user=$loguserid AND forum='$forumid'");
			mysql_query("DELETE FROM `threadsread` WHERE `uid` = '$loguserid' AND `tid` IN (SELECT `id` FROM `threads` WHERE `forum` = '$forumid')");
			mysql_query("INSERT INTO forumread (user,forum,readdate) VALUES ($loguserid,$forumid,".ctime().')');
			return header("Location: index.php");
		}
		
		if($action=='markallforumsread' and $log) {
			mysql_query("DELETE FROM forumread WHERE user=$loguserid");
			mysql_query("DELETE FROM `threadsread` WHERE `uid` = '$loguserid'");
			mysql_query("INSERT INTO forumread (user,forum,readdate) SELECT $loguserid,id,".ctime().' FROM forums');
			return header("Location: index.php");
		}

		$postread = readpostread($loguserid);

		$users1 = mysql_query("SELECT id,name,birthday,sex,powerlevel FROM users WHERE FROM_UNIXTIME(birthday,'%m-%d')='".date('m-d',ctime() + $tzoff)."' AND birthday ORDER BY name");
		for ($numbd=0;$user=mysql_fetch_array($users1);$numbd++) {
			if(!$numbd) $blist="<tr>$tccell2s colspan=5>Birthdays for ".date('F j',ctime()).': ';
			else $blist.=', ';
			$users[$user[id]]=$user;
			$y=date('Y',ctime())-date('Y',$user[birthday]);
			$namecolor=getnamecolor($user[sex],$user[powerlevel]);
			$blist.="<a href=profile.php?id=$user[id]><font $namecolor>$user[name]</font></a> ($y)"; 
		}
		
		$onlinetime=ctime()-300;
		$onusers=mysql_query("SELECT id,name,powerlevel,lastactivity,sex,minipic FROM users WHERE lastactivity>$onlinetime OR lastposttime>$onlinetime ORDER BY name");
		$numonline=mysql_num_rows($onusers);
		$numguests=mysql_result(mysql_query("SELECT count(*) FROM guests WHERE date>$onlinetime"),0,0);
		if ($numguests) $guestcount=" | <nobr>$numguests guest".($numguests>1?"s":"");
		for ($numon=0; $onuser=mysql_fetch_array($onusers);$numon++) {
			if($numon) { $onlineusers.=', '; }
			
			$namecolor=explode("=", getnamecolor($onuser[sex],$onuser[powerlevel]));
			$namecolor=$namecolor[1];
			
			$namelink="<a href=profile.php?id=$onuser[id] style='color: #$namecolor'>$onuser[name]</a>";

			if($onuser[minipic]) {
				$onuser[minipic]='<img width="16" height="16" src="'.str_replace('"','%22',$onuser[minipic]).'" align="absmiddle"> ';
			}
			
			if($onuser[lastactivity]<=$onlinetime) { 
				$namelink="($namelink)";
			}
			
			$onlineusers.="$onuser[minipic]$namelink";
		}

		if($onlineusers) $onlineusers=': '.$onlineusers;

		if($log){
			$headlinks.=' - <a href=index.php?action=markallforumsread>Mark all forums read</a>';
			$header=makeheader($header1,$headlinks,$header2);
		}

		if($log) $logmsg="You are logged in as $loguser[name].";
		
		$posts[d]=mysql_result(mysql_query('SELECT COUNT(*) FROM posts WHERE date>'.(ctime()-86400)),0,0);
		$posts[h]=mysql_result(mysql_query('SELECT COUNT(*) FROM posts WHERE date>'.(ctime()-3600)),0,0);
		$lastuser=mysql_fetch_array(mysql_query('SELECT id,name,sex,powerlevel FROM users ORDER BY id DESC LIMIT 1'));
		$misc=mysql_fetch_array(mysql_query('SELECT * FROM misc'));
		
		if($posts[d]>$misc[maxpostsday]) mysql_query("UPDATE misc SET maxpostsday=$posts[d],maxpostsdaydate=".ctime());
		if($posts[h]>$misc[maxpostshour]) mysql_query("UPDATE misc SET maxpostshour=$posts[h],maxpostshourdate=".ctime());
		if($numonline>$misc[maxusers]) mysql_query("UPDATE misc SET maxusers=$numonline,maxusersdate=".ctime().",maxuserstext='".addslashes($onlineusers)."'");
		$namecolor=getnamecolor($lastuser[sex],$lastuser[powerlevel]);

		/*// index sparkline
		$sprkq = mysql_query('SELECT COUNT(id),date FROM posts WHERE date >="'.(time()-3600).'" GROUP BY (date % 60) ORDER BY date');
		$sprk = array();
		
		while ($r = mysql_fetch_row($sprkq)) {
			array_push($sprk,$r[0]);
		}
		// print_r($sprk);
		$sprk = implode(",",$sprk); */
		if ($_GET['oldcounter']) {
			$statsblip	= "$posts[d] posts during the last day, $posts[h] posts during the last hour.";
		} else {
			$statsblip	= $sql->resultq("SELECT COUNT(*) FROM `threads` WHERE `lastpostdate` > '". (ctime() - 86400) ."'") ." threads and ". $sql->resultq("SELECT COUNT(*) FROM `users` WHERE `lastposttime` > '". (ctime() - 86400) ."'") ." users active during the last day."; 
		}

	  print "$header
		<br>
		$tblstart
		 $tccell1s><table width=100%><td class=fonts>$logmsg</td><td align=right class=fonts>$count[u] registered users<br>Latest registered user: <a href=profile.php?id=$lastuser[id]><font $namecolor>$lastuser[name]</font></a></table>
		 $blist<tr>
		$tccell2s>$count[t] threads and $count[p] posts in the board | $statsblip<tr>
		 $tccell1s>$numonline user".($numonline>1?'s':'')." currently online$onlineusers$guestcount

	  ";

	  $new='&nbsp;';
	  if($log){
		$pmsgnum=0;
		$pmsgnew=0;
		$maxid=mysql_result(mysql_query("SELECT max(id) FROM pmsgs WHERE userto=$loguserid"),0,0);
		$pmsgs=mysql_query("SELECT userfrom,date,u.id,name,sex,powerlevel FROM pmsgs p,pmsgs_text,users AS u WHERE p.id=0$maxid AND u.id=p.userfrom AND p.id=pid") or print mysql_error();
		if($pmsg=@mysql_fetch_array($pmsgs)){
		$pmsgnum=mysql_result(mysql_query("SELECT COUNT(*) FROM pmsgs WHERE userto=$loguserid"),0,0);
		$pmsgnew=mysql_result(mysql_query("SELECT COUNT(*) FROM pmsgs WHERE userto=$loguserid AND msgread=0"),0,0);
		if($pmsgnew) $new=$statusicons['new'];
		$namecolor=getnamecolor($pmsg[sex],$pmsg[powerlevel]);
		$lastmsg="Last message from <a href='profile.php?id=$pmsg[id]' $namecolor>$pmsg[name]</a> on ".date($dateformat,$pmsg[date]+$tzoff);
		}
		$privatebox="
		$tblstart
		$tccellhs colspan=2>Private messages<tr>
		$tccell1>$new</td>
		$tccell2l><a href='private.php'>Private messages</a> -- You have $pmsgnum private messages ($pmsgnew new). $lastmsg
		$tblend<br>
		";

	}
	
	$forumlist="
		$tccellh>&nbsp;</td>
		$tccellh>Forum</td>
		$tccellh>Threads</td>
		$tccellh>Posts</td>
		$tccellh>Last post
	";
	$categories=mysql_query("SELECT id,name FROM categories WHERE (!minpower OR minpower<=$loguser[powerlevel]) ORDER BY id");
	$forums=mysql_query("SELECT f.*,u.id AS uid,name,sex,powerlevel FROM forums f LEFT JOIN users u ON f.lastpostuser=u.id WHERE (!minpower OR minpower<=$loguser[powerlevel]) ORDER BY catid,forder");
	$mods=mysql_query("SELECT u.id,name,sex,powerlevel,forum FROM users u INNER JOIN forummods m ON u.id=m.user INNER JOIN forums f ON f.id=m.forum WHERE (!minpower OR minpower<=$power) ORDER BY catid,forder,name");
	$forum=mysql_fetch_array($forums);
	$mod=mysql_fetch_array($mods);
	
	while($category=mysql_fetch_array($categories))	{
		$forumlist.="<tr><td class='tbl tdbgc center font' colspan=5><a href=index.php?cat=$category[id]>$category[name]";
		for (;$forum[catid]==$category[id];$modlist='') {
			for ($m=0;$mod[forum]==$forum[id];$m++) {
				$namecolor=getnamecolor($mod[sex],$mod[powerlevel]);
				$modlist.=($m?', ':'')."<a href=profile.php?id=$mod[id]><font $namecolor>$mod[name]</font></a>";
				$mod=mysql_fetch_array($mods);
			}
			
			if($m) $modlist="$smallfont(moderated by: $modlist)</font>";
			
			$namecolor=getnamecolor($forum[sex],$forum[powerlevel]);
			
			if($forum[numposts]){
				$forumlastpost="<nobr>". date($dateformat,$forum[lastpostdate]+$tzoff);
				$by="$smallfont<br>by <a href=profile.php?id=$forum[uid]><font $namecolor>$forum[name]</font></a>". ($forum['lastpostid'] ? " <a href='thread.php?pid=". $forum['lastpostid'] ."#". $forum['lastpostid'] ."'>". $statusicons['getnew'] ."</a>" : "") ."</nobr></font>";
			} else {
				$forumlastpost='-------- --:-- --';
				$by='';
			}
			
			$new='&nbsp;';
			
			if ($log && $forum[lastpostdate]>$postread[$forum[id]]) {
				$newcount	= mysql_result(mysql_query("SELECT COUNT(*) FROM `threads` WHERE `id` NOT IN (SELECT `tid` FROM `threadsread` WHERE `uid` = '$loguser[id]' AND `read` = 1) AND `lastpostdate` > '". $postread[$forum[id]] ."' AND `forum` = '$forum[id]'"), 0);
			}
			
			if ((($forum[lastpostdate]>$postread[$forum[id]] and $log) or (!$log and $forum[lastpostdate]>ctime()-3600)) and $forum[numposts]) {
				$new = $statusicons['new'] ."<br>". generatenumbergfx($newcount);
			}
			
			if($forum[lastpostdate]>$category[lastpostdate]){
				$category[lastpostdate]=$forum[lastpostdate];
				$category[l]=$forumlastpost.$by;
			}
			
			if ($forum[id] == 30 && false) {
				$forum[numthreads] = "&infin;";
				$forum[numposts] = "&infin;";
				$forumlastpost = "Herpin and derpin";
				$by = "";
				$forumlastuser = "";
			}
			
			if($cat=='' or $cat==$category[id])
			  $forumlist.="
				<tr>$tccell1>$new</td>
				$tccell2l><a href=forum.php?id=$forum[id]>$forum[title]</a><br>
				$smallfont$forum[description]<br>$modlist</td>
				$tccell1>$forum[numthreads]</td>
				$tccell1>$forum[numposts]</td>
				$tccell2><span class='lastpost'>$forumlastpost</span>$by$forumlastuser
			  ";
			$forum=mysql_fetch_array($forums);
		}
	}
		print "$tblend<br>$privatebox
		
		". adbox() ."<br>

		$tblstart$forumlist$tblend$footer";
		printtimedif($startingtime);
	}
?>
