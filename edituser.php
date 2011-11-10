<?php

  require 'lib/function.php';
  require 'lib/layout.php';


  if ($_GET['id'] == 650 && false) {
	  print "$header
		<br>
		$tblstart
			<tr>$tccellh><b>Error</b>
			<tr>$tccell1>&nbsp;<br>
		Sorry, this user is too much of a pompous dickhead to have his profile edited! :(<br><br><a href=/>Back to index!</a>
		<br>&nbsp;$tblend$footer";
	die();
  }

  if(!$isadmin) die();
 

  $user=@mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$id"));
  print $header;
  $check1[$user[powerlevel]+1]='selected';
  $check2[$user[sex]]='checked=1';
  $check3[$user[useranks]]='checked=1';
  $check4[$user[profile_locked]]='checked=1';
  $check5[$user[editing_locked]]='checked=1';
  $checked2[$user[viewsig]]='checked=1';
  $checked3[$user[posttool]]='checked=1';
  $plocking="
	$radio=profile_locked value=1 $check4[1]>Locked
	$radio=profile_locked value=0 $check4[0]>Unlocked";
	$elocking="
	$radio=editing_locked value=1 $check5[1]>Locked
	$radio=editing_locked value=0 $check5[0]>Unlocked";
  $levellist=" 
	<select name=powerlevel>
	<option value=-1 $check1[0]>Banned</option>
	<option value=0 $check1[1]>Normal</option>
	<option value=1 $check1[2]>Normal +</option>
	<option value=2 $check1[3]>Moderator</option>
	<option value=3 $check1[4]>Administrator</option>
	<option value=4 $check1[5]>Administrator (invisible)</option>
	</select>";
  $sexlist="
	$radio=sex value=0 $check2[0]>Male&nbsp &nbsp
	$radio=sex value=1 $check2[1]>Female&nbsp &nbsp
	$radio=sex value=2 $check2[2]>N/A
	$radio=sex value=-378>Raw value<br>
	$inpt=sexn value=$user[sex]>";
  $vsig="
   $radio=viewsig value=0 $checked2[0]>Disabled&nbsp &nbsp
   $radio=viewsig value=1 $checked2[1]>Enabled&nbsp &nbsp
   $radio=viewsig value=1 $checked2[2]>Auto-updating";
  $vtool="
   $radio=posttool value=0 $checked3[0]>Disabled&nbsp &nbsp
   $radio=posttool value=1 $checked3[1]>Enabled";
  $birthday=getdate($user[birthday]);
  if($user[birthday]){
    $month=$birthday[mon];
    $day=$birthday[mday];
    $year=$birthday[year];
  }
  $schemes=mysql_query('SELECT id,name FROM schemes ORDER BY ord');
  while($sch=mysql_fetch_array($schemes)){
    $sel='';
    if($sch[id]==$user[scheme]) $sel=' selected';
    $used=mysql_result(mysql_query("SELECT count(id) as cnt FROM users WHERE scheme=$sch[id]"),0,'cnt');
    $schlist.="<option value=$sch[id]$sel>$sch[name] ($used)";
  }
  $schlist="<select name=sscheme>$schlist</select>";
  $tlayouts=mysql_query('SELECT id,name FROM tlayouts ORDER BY ord');
  while($lay=mysql_fetch_array($tlayouts)){
    $sel="";
    if($lay[id]==$user[layout]) $sel=' selected';
    $used=mysql_result(mysql_query("SELECT count(id) as cnt FROM users WHERE layout=$lay[id]"),0,'cnt');
    $laylist.="<option value=$lay[id]$sel>$lay[name] ($used)";
  }
  $laylist="<select name=tlayout>$laylist</select>";
  $rsets=mysql_query('SELECT id,name FROM ranksets ORDER BY id');
  while($set=mysql_fetch_array($rsets)){
    $sel=($set[id]==$user[useranks]?' selected':'');
    $used=mysql_result(mysql_query("SELECT count(*) FROM users WHERE useranks=$set[id]"),0,0);
    $rsetlist.="<option value=$set[id]$sel>$set[name] ($used)";
  }
  $rsetlist="<select name=useranks>$rsetlist</select>";
  if(!$_POST[action] and $log){
   $lft="<tr>$tccell1><b>";
   $rgt=":</td>$tccell2l>";
   $hlft="<tr>$tccellh>";
   $hrgt="</td>$tccellh>&nbsp;</td>";
   squot(0,$user[name]);
   squot(0,$user[title]);
//   squot(1,$user[minipic]);
//   squot(1,$user[picture]);
   squot(0,$user[realname]);
   squot(0,$user[location]);
//   squot(1,$user[aim]);
//   squot(1,$user[imood]);
//   squot(1,$user[email]);
//   squot(1,$user[homepageurl]);
   squot(0,$user[homepagename]);
   sbr(1, $user[bio]);
   sbr(1, $user[signature]);
   sbr(1, $user[postheader]);
   print "
	<br>
	$tblstart
	<FORM ACTION=edituser.php NAME=REPLIER METHOD=POST>
	$hlft Login information $hrgt
	$lft User name		$rgt$inpt=username VALUE=\"$user[name]\" SIZE=25 MAXLENGTH=25>
	$lft Password		$rgt$inpp=password VALUE=\"\" SIZE=13 MAXLENGTH=32>
	$hlft Administrative bells and whistles $hrgt
	$lft Power level		$rgt$levellist	
	$lft Custom title		$rgt$inpt=usertitle VALUE=\"$user[title]\" SIZE=60 MAXLENGTH=255>
	$lft Rank set		$rgt$rsetlist
	$lft Number of posts	$rgt$inpt=numposts SIZE=5 MAXLENGTH=10 VALUE=$user[posts]>
	$lft Registration time:</b>$smallfont<br>(seconds since ".date($dateformat,$tzoff).")</td>$tccell2l>$inpt=regtime SIZE=10 MAXLENGTH=15 VALUE=$user[regdate]><tr>
	$lft Lock Profile $rgt$plocking
	$lft Restrict Editing $rgt$elocking
	$hlft Appearance		$hrgt			
	$lft Mini picture		$rgt$inpt=minipic VALUE=\"$user[minipic]\" SIZE=60 MAXLENGTH=100>
	$lft User picture		$rgt$inpt=picture VALUE=\"$user[picture]\" SIZE=60 MAXLENGTH=100>
	$lft Mood avatar		$rgt$inpt=moodurl VALUE=\"$user[moodurl]\" SIZE=60 MAXLENGTH=100>
	$lft Post background	$rgt$inpt=postbg VALUE=\"$user[postbg]\" SIZE=60 MAXLENGTH=100>
	$lft Post header		$rgt$txta=postheader ROWS=5 COLS=60>". htmlspecialchars($user[postheader]) ."</TEXTAREA>
	$lft Signature		$rgt$txta=signature ROWS=5 COLS=60>". htmlspecialchars($user[signature]) ."</TEXTAREA>
	$hlft Personal information $hrgt				
	$lft Sex			$rgt$sexlist
	$lft Real name		$rgt$inpt=realname VALUE=\"$user[realname]\" SIZE=40 MAXLENGTH=60>
	$lft Location		$rgt$inpt=location VALUE=\"$user[location]\" SIZE=40 MAXLENGTH=60>
	$lft Birthday		$rgt Month: $inpt=bmonth SIZE=2 MAXLENGTH=2 VALUE=$month> Day: $inpt=bday SIZE=2 MAXLENGTH=2 VALUE=$day> Year: $inpt=byear SIZE=4 MAXLENGTH=4 VALUE=$year>
	$lft Bio			$rgt$txta=bio ROWS=5 COLS=60>". htmlspecialchars($user[bio]) ."</TEXTAREA>	
	$hlft Online services	$hrgt	
	$lft Email address	$rgt$inpt=email VALUE=\"$user[email]\" SIZE=60 MAXLENGTH=60>
	$lft AIM screen name	$rgt$inpt=aim VALUE=\"$user[aim]\" SIZE=30 MAXLENGTH=30>
	$lft ICQ number		$rgt$inpt=icq SIZE=10 MAXLENGTH=10 VALUE=$user[icq]>
	$lft Homepage title	$rgt$inpt=pagename VALUE=\"$user[homepagename]\" SIZE=60 MAXLENGTH=80>
	$lft Homepage URL		$rgt$inpt=homepage VALUE=\"$user[homepageurl]\" SIZE=60 MAXLENGTH=80>
	$hlft Options		$hrgt
	$lft Timezone offset	$rgt$inpt=timezone SIZE=5 MAXLENGTH=5 VALUE=$user[timezone]>
	$lft Posts per page	$rgt$inpt=postsperpage SIZE=5 MAXLENGTH=5 VALUE=$user[postsperpage]>
	$lft Threads per page	$rgt$inpt=threadsperpage SIZE=4 MAXLENGTH=4 VALUE=$user[threadsperpage]>
	$lft Use text toolbar when posting		$rgt$vtool
	$lft View signatures and post headers	$rgt$vsig
	$lft Thread layout				$rgt$laylist
	$lft Color scheme / layout			$rgt$schlist
	$lft &nbsp</td>$tccell2l>
	$inph=action VALUE=saveprofile>
	$inph=userid VALUE=$id>
	$inps=submit VALUE=\"Edit profile\"></td></FORM>
	$tblend
  "; 
  }
  if($_POST[action]=='saveprofile'){
    sbr(0,$signature);
    sbr(0,$bio);
    sbr(0,$postheader);
	$minipic = htmlspecialchars($minipic);
	$avatar = htmlspecialchars($avatar);
    $birthday=@mktime(0,0,0,$bmonth,$bday,$byear);
    if(!$bmonth && !$bday && !$byear) $birthday=0;
    if($password) $passedit=", `password` = '".md5($password)."'";
    //mysql_query("INSERT logs SET useraction ='Edit User ".$user[nick]."(".$user[id]."'");
	if ($sex == -378) {
		$sex = $sexn;
	}
    mysql_query("UPDATE `users` SET `posts` = '$numposts', `regdate` = '$regtime', `name` = '$username'$passedit, `picture` = '$picture', `signature` = '$signature', `bio` = '$bio', `powerlevel` = '$powerlevel', `title` = '$usertitle', `email` = '$email', `icq` = '$icq', `aim` = '$aim', `sex` = '$sex',  `homepageurl` = '$homepage', `timezone` = '$timezone', `postsperpage` = '$postsperpage', `realname` = '$realname', `location` = '$location', `postbg` = '$postbg', `postheader` = '$postheader', `useranks` = '$useranks', `birthday` = '$birthday', `minipic` = '$minipic', `homepagename` = '$pagename', `scheme` = '$sscheme', `threadsperpage` = '$threadsperpage', `viewsig` = '$viewsig', `layout` = '$tlayout', `posttool` = '$posttool', `moodurl` = '$moodurl', `profile_locked` = '$profile_locked', `editing_locked` = '$editing_locked' WHERE `id` = '$userid'") or print mysql_error();
    print "
	$tblstart
	 $tccell1>Thank you, $loguser[name], for editing this user.<br>
	 ".redirect("index.php","return to the board",0)."
	$tblend"; 
  }
  print $footer;
  printtimedif($startingtime);
?>