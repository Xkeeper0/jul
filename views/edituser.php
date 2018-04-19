<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	if(!$isadmin) die();

	$user=@$sql->fetchq("SELECT * FROM users WHERE id=$id");
	print $header;

	$check1[$user['powerlevel']]='selected';
	$check2[($user['sex'] > 2) ? 378 : $user['sex']]='checked=1';
	$check3[$user['useranks']]='checked=1';
	$check4[$user['profile_locked']]='checked=1';
	$check5[$user['editing_locked']]='checked=1';
	$check6[$user['titleoption']]='checked=1';

	$checked2[$user['viewsig']]='checked=1';
	$checked3[$user['posttool']]='checked=1';

	$plocking="
		$radio=profile_locked value=1 $check4[1]>Locked
		$radio=profile_locked value=0 $check4[0]>Unlocked";
	$elocking="
		$radio=editing_locked value=1 $check5[1]>Locked
		$radio=editing_locked value=0 $check5[0]>Unlocked";
	$ctpriv="
		$radio=titleoption value=0 $check6[0]>Revoked
		$radio=titleoption value=1 $check6[1]>Determine by rank/posts
		$radio=titleoption value=2 $check6[2]>Enabled";
	$sexlist="
		$radio=sex value=0 $check2[0]>Male&nbsp &nbsp
		$radio=sex value=1 $check2[1]>Female&nbsp &nbsp
		$radio=sex value=2 $check2[2]>N/A&nbsp &nbsp
		$radio=sex value=-378 $check2[378]>Custom: $inpt=sexn value=$user[sex] maxlength=3 size=3>";
	$vsig="
		$radio=viewsig value=0 $checked2[0]>Disabled&nbsp &nbsp
		$radio=viewsig value=1 $checked2[1]>Enabled&nbsp &nbsp
		$radio=viewsig value=1 $checked2[2]>Auto-updating";

	$birthday=getdate($user['birthday']);
	if($user['birthday']){
		$month=$birthday['mon'];
		$day=$birthday['mday'];
		$year=$birthday['year'];
	}

	foreach ($pwlnames as $pwl=>$pwlname) {
		$levellist .= "<option value={$pwl} {$check1[$pwl]}>{$pwlname}</option>";
	}
	$levellist="<select name=powerlevel>{$levellist}</select>";

	$schemes=$sql->query('SELECT id,name FROM schemes ORDER BY ord');
	while($sch=$sql->fetch($schemes)){
		$sel='';
		if($sch['id']==$user['scheme']) $sel=' selected';
		$used=$sql->resultq("SELECT count(id) as cnt FROM users WHERE scheme=$sch[id]",0,'cnt');
		$schlist.="<option value=$sch[id]$sel>$sch[name] ($used)";
	}
	$schlist="<select name=sscheme>$schlist</select>";


	$tlayouts=$sql->query('SELECT id,name FROM tlayouts ORDER BY ord');
	while($lay=$sql->fetch($tlayouts)){
		$sel="";
		if($lay['id']==$user['layout']) $sel=' selected';
		$used=$sql->resultq("SELECT count(id) as cnt FROM users WHERE layout=$lay[id]",0,'cnt');
		$laylist.="<option value=$lay[id]$sel>$lay[name] ($used)";
	}
	$laylist="<select name=tlayout>$laylist</select>";

	$rsets=$sql->query('SELECT id,name FROM ranksets ORDER BY id');
	while($set=$sql->fetch($rsets)) {
		$sel=($set['id']==$user['useranks']?' selected':'');
		$used=$sql->resultq("SELECT count(*) FROM users WHERE useranks=$set[id]",0,0);
		$rsetlist.="<option value=$set[id]$sel>$set[name] ($used)";
	}
	$rsetlist="<select name=useranks>$rsetlist</select>";

	if(!$_POST['action'] and $log){
   $lft="<tr>$tccell1><b>";
   $rgt=":</td>$tccell2l>";
   $hlft="<tr>$tccellh>";
   $hrgt="</td>$tccellh>&nbsp;</td>";
   squot(0,$user['name']);
   squot(0,$user['title']);
    $user['minipic'] = htmlspecialchars($user['minipic'], ENT_QUOTES);
    $user['picture'] = htmlspecialchars($user['picture'], ENT_QUOTES);
    $user['moodurl'] = htmlspecialchars($user['moodurl'], ENT_QUOTES);
   squot(0,$user['realname']);
   squot(0,$user['aka']);
   squot(0,$user['location']);
//   squot(1,$user['aim']);
//   squot(1,$user['imood']);
//   squot(1,$user['email']);
//   squot(1,$user['homepageurl']);
   squot(0,$user['homepagename']);
   sbr(1, $user['bio']);
   sbr(1, $user['signature']);
   sbr(1, $user['postheader']);

	print "
		<br>
			$tblstart
			<FORM ACTION='{$GLOBALS['jul_views_path']}/edituser.php' NAME=REPLIER METHOD=POST autocomplete=\"off\">

				$hlft Login information $hrgt
				$lft User name		$rgt$inpt=username VALUE=\"$user[name]\" SIZE=25 MAXLENGTH=25 autocomplete=\"off\">
				$lft Also known as		$rgt$inpt=aka VALUE=\"$user[aka]\" SIZE=25 MAXLENGTH=25 autocomplete=\"off\">

				<!-- Hack around autocomplete, fake inputs (don't use these in the file)
				Web browsers think they're smarter than the web designer, so they ignore demands to not use autocomplete.
				This is STUPID AS FUCK when you're working on another user, and not YOURSELF. -->
				<input style=\"display:none;\" type=\"text\"     name=\"__f__usernm__\">
				<input style=\"display:none;\" type=\"password\" name=\"__f__passwd__\">

				$lft Password		$rgt$inpp=password VALUE=\"\" SIZE=13 MAXLENGTH=64 autocomplete=\"new-password\">

				$hlft Administrative bells and whistles $hrgt
				$lft Power level		$rgt$levellist
				$lft Custom title		$rgt$inpt=usertitle VALUE=\"$user[title]\" SIZE=60 MAXLENGTH=255>
				$lft Rank set		$rgt$rsetlist
				$lft Number of posts	$rgt$inpt=numposts SIZE=5 MAXLENGTH=10 VALUE=$user[posts]>
				$lft Registration time:</b>$smallfont<br>(seconds since ".date($dateformat,$tzoff).")</td>$tccell2l>$inpt=regtime SIZE=10 MAXLENGTH=15 VALUE=$user[regdate]><tr>
				$lft Lock Profile $rgt$plocking
				$lft Restrict Editing $rgt$elocking
				$lft Custom Title Privileges $rgt$ctpriv

				$hlft Appearance		$hrgt
				$lft Mini picture		$rgt$inpt=minipic VALUE=\"$user[minipic]\" SIZE=60 MAXLENGTH=100>
				$lft User picture		$rgt$inpt=picture VALUE=\"$user[picture]\" SIZE=60 MAXLENGTH=100>
				$lft Mood avatar		$rgt$inpt=moodurl VALUE=\"$user[moodurl]\" SIZE=60 MAXLENGTH=100>
				$lft Post background	$rgt$inpt=postbg VALUE=\"$user[postbg]\" SIZE=60 MAXLENGTH=100>
				$lft Post header		$rgt$txta=postheader ROWS=5 COLS=60>". htmlspecialchars($user['postheader']) ."</TEXTAREA>
				$lft Signature		$rgt$txta=signature ROWS=5 COLS=60>". htmlspecialchars($user['signature']) ."</TEXTAREA>

				$hlft Personal information $hrgt
				$lft Sex			$rgt$sexlist
				$lft Pronouns		$rgt$inpt=pronouns VALUE=\"$user[pronouns]\" SIZE=40 MAXLENGTH=50>
				$lft Real name		$rgt$inpt=realname VALUE=\"$user[realname]\" SIZE=40 MAXLENGTH=60>
				$lft Location		$rgt$inpt=location VALUE=\"$user[location]\" SIZE=40 MAXLENGTH=60>
				$lft Birthday		$rgt Month: $inpt=bmonth SIZE=2 MAXLENGTH=2 VALUE=$month> Day: $inpt=bday SIZE=2 MAXLENGTH=2 VALUE=$day> Year: $inpt=byear SIZE=4 MAXLENGTH=4 VALUE=$year>
				$lft Bio			$rgt$txta=bio ROWS=5 COLS=60>". htmlspecialchars($user['bio']) ."</TEXTAREA>

				$hlft Online services	$hrgt
				$lft Email address    $rgt $inpt=email VALUE=\"$user[email]\" SIZE=60 MAXLENGTH=60>
				$lft AIM screen name  $rgt $inpt=aim VALUE=\"$user[aim]\" SIZE=30 MAXLENGTH=30>
				$lft ICQ number       $rgt $inpt=icq SIZE=10 MAXLENGTH=10 VALUE=$user[icq]>
				$lft Homepage title   $rgt $inpt=pagename VALUE=\"$user[homepagename]\" SIZE=60 MAXLENGTH=80>
				$lft Homepage URL     $rgt $inpt=homepage VALUE=\"$user[homepageurl]\" SIZE=60 MAXLENGTH=80>

				$hlft Options		$hrgt
				$lft Custom date format               $rgt $inpt=eddateformat value=\"$user[dateformat]\" size=16 maxlength=32>
				$lft Custom short date format         $rgt $inpt=eddateshort value=\"$user[dateshort]\" size=8 maxlength=32>
				$lft Timezone offset                  $rgt $inpt=timezone SIZE=5 MAXLENGTH=5 VALUE=$user[timezone]>
				$lft Posts per page                   $rgt $inpt=postsperpage SIZE=5 MAXLENGTH=5 VALUE=$user[postsperpage]>
				$lft Threads per page                 $rgt $inpt=threadsperpage SIZE=4 MAXLENGTH=4 VALUE=$user[threadsperpage]>
				$lft View signatures and post headers $rgt $vsig
				$lft Thread layout                    $rgt $laylist
				$lft Color scheme / layout            $rgt $schlist

				$lft &nbsp</td>$tccell2l>
				$inph=action VALUE=saveprofile>
				$inph=userid VALUE=$id>
				$inps=submit VALUE=\"Edit profile\"></td></FORM>
			$tblend
	";
	}

	if($_POST['action']=='saveprofile') {
		if ($eddateformat == $GLOBALS['jul_settings']['date_format_long']) $eddateformat = '';
		if ($eddateshort  == $GLOBALS['jul_settings']['date_format_short']) $eddateshort  = '';

		sbr(0,$signature);
		sbr(0,$bio);
		sbr(0,$postheader);

		$minipic = htmlspecialchars($minipic);
		$avatar = htmlspecialchars($avatar);

		$birthday=@mktime(0,0,0,$bmonth,$bday,$byear);
		if(!$bmonth && !$bday && !$byear) $birthday=0;

    //$sql->query("INSERT logs SET useraction ='Edit User ".$user[nick]."(".$user[id]."'");

		if ($password) {
			$passedit="`password` = '".getpwhash($password, $userid)."', ";
		}

		if ($sex == -378) {
			$sex = $sexn;
		}

		if ($userid == 1 && $loguserid != 1) {
			xk_ircsend("1|". xk(7) ."Someone (*cough{$loguserid}cough*) is trying to be funny...");
		}

	$sql->query("UPDATE `users` SET
		`posts` = '$numposts',
		`regdate` = '$regtime',
		`name` = '$username',
		$passedit
		`picture` = '$picture',
		`signature` = '$signature',
		`bio` = '$bio',
		`powerlevel` = '$powerlevel',
		`title` = '$usertitle',
		`email` = '$email',
		`icq` = '$icq',
		`aim` = '$aim',
		`aka` = '$aka',
		`sex` = '$sex',
		`homepageurl` = '$homepage',
		`timezone` = '$timezone',
		`dateformat`		= '$eddateformat',
		`dateshort`			= '$eddateshort',
		`postsperpage` = '$postsperpage',
		`realname` = '$realname',
		`location` = '$location',
		`postbg` = '$postbg',
		`postheader` = '$postheader',
		`useranks` = '$useranks',
		`birthday` = '$birthday',
		`minipic` = '$minipic',
		`homepagename` = '$pagename',
		`scheme` = '$sscheme',
		`threadsperpage` = '$threadsperpage',
		`viewsig` = '$viewsig',
		`layout` = '$tlayout',".
//	`posttool` = '$posttool',
	 "`moodurl` = '$moodurl',
		`profile_locked` = '$profile_locked',
		`editing_locked` = '$editing_locked',
		`pronouns` = '$pronouns',
		`titleoption` = '$titleoption'
	WHERE `id` = '$userid'") or print mysql_error();

	print "
	$tblstart
	 $tccell1>Thank you, $loguser[name], for editing this user.<br>
	 ".redirect("{$GLOBALS['jul_views_path']}/profile.php?id=$userid","view $username's profile",0)."
	$tblend";
  }
  print $footer;
  printtimedif($startingtime);
?>
