<?php
  require_once '../lib/function.php';
  require_once '../lib/layout.php';
  if(!$f) $f=0;
  if(@mysql_num_rows(mysql_query("SELECT user FROM forummods WHERE forum=$f and user=$loguserid"))) $ismod=1;
  $canpost=($isadmin or ($ismod && $f>0));
  if($_GET[action]=='edit' or $_POST[action]=='editannc'){
    $annc=mysql_fetch_array(mysql_query("SELECT * FROM announcements WHERE id=$id"));
    if($annc[forum]>0 && $ismod) $canpost=true;
  }
  $smilies=readsmilies();
  if(!$action){
    loadtlayout();
    $ppp=($log?$loguser[postsperpage]:20);
    $min=$ppp*$page;
	if ($loguser['id'] && $f == 0) {
		mysql_query("UPDATE `users` SET `lastannouncement` = (SELECT MAX(`id`) FROM `announcements` WHERE `forum` = 0) WHERE `id` = '". $loguser['id'] ."'");
	}
	$anncs=mysql_query("SELECT a.*,u.*,a.title atitle,u.id uid FROM announcements a,users u WHERE forum=$f AND a.user=u.id ORDER BY a.id DESC LIMIT $min,$ppp");
    $annctotal=@mysql_result(mysql_query("SELECT count(*) FROM announcements WHERE forum=$f"),0,0);
    $pagelinks=$smallfont.'Pages:';
    for($i=0;$i<($annctotal/$ppp);$i++){
	if($i==$page) $pagelinks.=' '.($i+1);
	else $pagelinks.=" <a href='{$GLOBALS['jul_views_path']}/announcement.php?f=$f&page=$i'>".($i+1).'</a>';
    }
    $annclist="$tccellh width=150>User</td>$tccellh colspan=2>Announcement<tr>";
    while($annc=mysql_fetch_array($anncs)){
	if($annccount) $annclist.='<tr>';
	$annccount++;
	$bg=$bg%2+1;
	$edit='&nbsp;';
	if($isadmin or ($ismod && $f)){
	  $edit="<a href='{$GLOBALS['jul_views_path']}/announcement.php?id=$annc[0]&action=edit'>Edit</a> | <a href='{$GLOBALS['jul_views_path']}/announcement.php?id=$annc[0]&action=delete&f=$f'>Delete</a>";
	  if($isadmin) $ip=" | IP: $annc[3]";
	}
	if($loguser[viewsig]==2){
	  $annc[headtext]=$annc[postheader];
	  $annc[signtext]=$annc[signature];
	}
	$annc[text]="<center><b>$annc[atitle]</b></center><hr>$annc[text]";
	$annclist.=threadpost($annc,$bg);
    }
  }
  if($canpost){
    if($_GET[action]=='delete'){
	mysql_query("DELETE FROM announcements WHERE id=$id");
	$annclist.="
	  $tccell1>Announcement deleted.
	  <br>".redirect("{$GLOBALS['jul_views_path']}/announcement.php?f=$f",'return to the announcements',0);
    }
    if($_GET[action]=='new'){
	$annclist="
	  <FORM ACTION='{$GLOBALS['jul_views_path']}/announcement.php' NAME=REPLIER METHOD=POST>
	  $tccellh width=150>&nbsp</td>$tccellh>&nbsp;<tr>
	  $tccell1><b>Announcement title:</b></td>$tccell2l>$inpt=subject SIZE=70 MAXLENGTH=100><tr>
	  $tccell1><b>Announcement:</b></td>	$tccell2l>$txta=message ROWS=20 COLS=$numcols></TEXTAREA><tr>
	  $tccell1>&nbsp;</td>				$tccell2l>$inph=action VALUE=postannc>$inph=f VALUE=$f>
	  $inps=submit VALUE=\"Post announcement\">
	  $inps=preview VALUE=\"Preview announcement\"></td></FORM>
	";
    }
    if($_GET[action]=='edit'){
	if(!$annc[headid]) $head=$annc[headtext];
	else $head=mysql_result(mysql_query("SELECT text FROM postlayouts WHERE id=$annc[headid]"),0,0);
	if(!$annc[signid]) $sign=$annc[signtext];
	else $sign=mysql_result(mysql_query("SELECT text FROM postlayouts WHERE id=$annc[signid]"),0,0);
	sbr(1,$annc[text]);
	sbr(1,$head);
	sbr(1,$sign);
	$annclist="
	  <FORM ACTION='{$GLOBALS['jul_views_path']}/announcement.php' NAME=REPLIER METHOD=POST>
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
	  $inps=preview VALUE=\"Preview announcement\"></td></FORM>
	";
    }
    if($_POST[action]=='postannc'){
	$userid = $loguserid;
	$user   = $loguser;
	if($userid!=-1){
	  $sign=$user[signature];
	  $head=$user[postheader];
	  if($user[postbg]) $head="<div style=background:url($user[postbg]);height=100%>$head";
	  $numposts=$user[posts];
	  $numdays=(ctime()-$user[regdate])/86400;
	  $message=doreplace($message,$numposts,$numdays,$user['name']);
	  $rsign=doreplace($sign,$numposts,$numdays,$user['name']);
	  $rhead=doreplace($head,$numposts,$numdays,$user['name']);
	  squot(0,$subject);
	  $currenttime=ctime();
	  if($submit){
	    if(!$f) $f=0;
	    $headid=getpostlayoutid($head);
	    $signid=getpostlayoutid($sign);
	    mysql_query("INSERT INTO `announcements` (`user`, `date`, `ip`, `title`, `forum`, `text`, `headid`, `signid`, `tagval`) VALUES ('$userid', '$currenttime', '$userip', '$subject', '$f', '$message', '$headid', '$signid', '$tagval')");
	    $annclist="
		$tccell1>Thank you, $user[name], for posting your announcement.<br>
	     ".redirect("{$GLOBALS['jul_views_path']}/announcement.php?f=$f","the announcements",0)."</table></table>";
	  }else{
	    loadtlayout();
	    $ppost=$user;
	    $ppost[uid]=$userid;
	    $ppost[date]=$currenttime;
	    $ppost[headtext]=$rhead;
	    $ppost[signtext]=$rsign;
	    $ppost[text]="<center><b>". stripslashes($subject) ."</b></center><hr>". stripslashes($message);
	    if($isadmin) $ip=$userip;
	    $annclist="
		  <body onload=window.document.replier.message.focus()>
		  $tccellh>Announcement preview
		  $tblend$tblstart
		  ".threadpost($ppost,1)."
		  $tblend<br>$tblstart
		  <FORM ACTION='{$GLOBALS['jul_views_path']}/announcement.php' NAME=REPLIER METHOD=POST>
		  $tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		  $tccell1><b>Announcement title:</td>$tccell2l>$inpt=subject SIZE=70 MAXLENGTH=100 VALUE=\"". stripslashes($subject) ."\"><tr>
		  $tccell1><b>Announcement:</td>	$tccell2l>$txta=message ROWS=10 COLS=$numcols>". stripslashes($message) ."</TEXTAREA><tr>
		  $tccell1>&nbsp</td>$tccell2l>
		  $inps=submit VALUE=\"Submit announcement\">
		  $inps=preview VALUE=\"Preview announcement\">
		  $inph=action VALUE=postannc>
		  $inph=f VALUE=$f>
		  </td></FORM>
	    ";
	  }
	}else
	  $annclist="
	    $tccell1>Couldn't enter the announcement. You haven't entered the right username or password.
	    ".redirect("{$GLOBALS['jul_views_path']}/announcement.php",'return to the announcements',0);
    }
    if($_POST[action]=='editannc'){
      print $tblstart;
	$numposts=$loguser[posts];
	$numdays=(ctime()-$loguser[regdate])/86400;
	$message=doreplace($message,$numposts,$numdays,$loguser[name]);

	$namecolor = getnamecolor($loguser['sex'], $loguser['powerlevel']);
	$edited ="<a href={$GLOBALS['jul_views_path']}/profile.php?id=$loguser[id]><font $namecolor>$loguser[name]</font></a>";

	if($submit){
	  $headid=@mysql_result(mysql_query("SELECT id FROM postlayouts WHERE text='$head' LIMIT 1"),0,'id');
	  $signid=@mysql_result(mysql_query("SELECT id FROM postlayouts WHERE text='$sign' LIMIT 1"),0,'id');
	  if($headid) $head=''; else $headid=0;
	  if($signid) $sign=''; else $signid=0;
	  mysql_query("UPDATE announcements SET title='$subject', text='$message', headtext='$head', signtext='$sign', edited='$edited', editdate='".ctime()."',headid=$headid,signid=$signid WHERE id=$id");
	  $annclist="
	    $tccell1>Thank you, ".$loguser[name].", for editing the announcement.<br>
	    ".redirect("{$GLOBALS['jul_views_path']}/announcement.php?f=$f","go to the announcements",0);
	}else{
	  loadtlayout();
	  $annc=mysql_fetch_array(mysql_query("SELECT * FROM announcements WHERE id=$id"));
	  $ppost=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$annc[user]"));
	$subject = stripslashes($subject);
	$message = stripslashes($message);
	$head = stripslashes($head);
	$sign = stripslashes($sign);
	  $ppost['uid']=$annc[user];
	  $ppost['date']=$annc[date];
	  $ppost['tagval']=$annc[tagval];
	  $ppost['headtext']=$head;
	  $ppost['signtext']=$sign;
	  $ppost['text']="<center><b>$subject</b></center><hr>$message";

	  $ppost['edited']   = $edited;
	  $ppost['editdate'] = ctime();

	  if($isadmin) $ip=$annc['ip'];
	  $annclist="
		<body onload=window.document.replier.message.focus()>
		$tccellh>Announcement preview
		$tblend$tblstart
		".threadpost($ppost,1)."
		$tblend<br>$tblstart
		<FORM ACTION='{$GLOBALS['jul_views_path']}/announcement.php' NAME=REPLIER METHOD=POST>
		$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>Announcement title:</b></td>$tccell2l>$inpt=subject VALUE=\"$subject\" SIZE=70 MAXLENGTH=100><tr>
		$tccell1><b>Header:</td>	 $tccell2l>$txta=head ROWS=4 COLS=$numcols>$head</TEXTAREA><tr>
		$tccell1><b>Announcement:</td> $tccell2l>$txta=message ROWS=6 COLS=$numcols>$message</TEXTAREA><tr>
		$tccell1><b>Signature:</td>	 $tccell2l>$txta=sign ROWS=4 COLS=$numcols>$sign</TEXTAREA><tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inps=submit VALUE=\"Edit announcement\">
		$inps=preview VALUE=\"Preview announcement\">
		$inph=action VALUE=editannc>
		$inph=id VALUE=$id>
		$inph=f VALUE=$f>
		</td></FORM>
	  ";
	}
    }
    $postnew="<a href='{$GLOBALS['jul_views_path']}/announcement.php?action=new&f=$f'>Post new announcement</a>";
  }
  print "$header
	<table width=100%><td align=left>$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - Announcements</td><td align=right>$smallfont$postnew</table>
	$pagelinks$tblstart$annclist$tblend$pagelinks$footer
  ";
  printtimedif($startingtime);
?>
