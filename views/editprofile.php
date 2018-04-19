<?php
  require_once '../lib/function.php';
  require_once '../lib/layout.php';
  if(!$log) errorpage('You must be logged in to edit your profile.');
  if($_GET['lol'] || ($loguserid == 1420)) errorpage('<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;"><object width="100%" height="100%"><param name="movie" value="http://www.youtube.com/v/lSNeL0QYfqo&hl=en_US&fs=1&color1=0x2b405b&color2=0x6b8ab6&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/lSNeL0QYfqo&hl=en_US&fs=1&color1=0x2b405b&color2=0x6b8ab6&autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="100%" height="100%"></embed></object></div>');
  if($banned) errorpage('Sorry, but banned users aren\'t allowed to edit their profile.');
	if($loguser['profile_locked'] == 1) {
		errorpage("You are not allowed to edit your profile.");
	}
  if($loguser['posts']>=500 or ($loguser[posts]>=250 && (ctime()-$loguser[regdate])>=100*86400)) $postreq=1;
  if($loguser['titleoption']==0 || $banned) $titleopt=0;
  if($loguser['titleoption']==1 && ($postreq or $power>0 or $loguser[title])) $titleopt=1;
  if($loguser['titleoption']==2) $titleopt=1;
  if(!$action){
    $birthday=getdate($loguser['birthday']);
    if($loguser['birthday']){
			$month=$birthday['mon'];
			$day=$birthday['mday'];
			$year=$birthday['year'];
    }

		if ($loguser['sex'] == 255)
			$loguser['sex'] = $loguser['oldsex'];

    $descbr="</b>$smallfont<br>";
    $checked1[$loguser['sex']]='checked=1';
    $checked2[$loguser['viewsig']]='checked=1';
//    $checked3[$loguser['posttool']]='checked=1';
    $checked4[$loguser['useranks']]='checked=1';
    $checked5[$loguser['pagestyle']]='checked=1';
    $checked6[$loguser['pollstyle']]='checked=1';
    $sexlist="
	$radio=sex value=0 $checked1[0]> <strong style='color: #". getnamecolor(0, $loguser['powerlevel'], false) ."'>Male</strong> &nbsp;&nbsp;
	$radio=sex value=1 $checked1[1]> <strong style='color: #". getnamecolor(1, $loguser['powerlevel'], false) ."'>Female</strong> &nbsp;&nbsp;
	$radio=sex value=2 $checked1[2]> <strong style='color: #". getnamecolor(2, $loguser['powerlevel'], false) ."'>Other / N/A</strong>";
	if ($loguser['sex'] > 2)
		$sexlist .= "$radio=sex value=$loguser[sex] checked style=\"display:none;\">";

    $vsig="
	$radio=viewsig value=0 $checked2[0]> Disabled &nbsp;&nbsp;
	$radio=viewsig value=1 $checked2[1]> Enabled &nbsp;&nbsp;
	$radio=viewsig value=2 $checked2[2]> Auto-updating";
//    $vtool="
//	$radio=posttool value=0 $checked3[0]> Disabled &nbsp;&nbsp;
//	$radio=posttool value=1 $checked3[1]> Enabled";
    $pagestyle="
	$radio=pagestyle value=0 $checked5[0]> Inline &nbsp;&nbsp;
	$radio=pagestyle value=1 $checked5[1]> Seperate line";
    $pollstyle="
	$radio=pollstyle value=0 $checked6[0]> Normal &nbsp;&nbsp;
	$radio=pollstyle value=1 $checked6[1]> Influence";
    if($titleopt){
		// this went after this block, which makes it COMPLETELY USELESS
	    squot(0,$loguser['title']);
		$titleoption="
	    $tccell1><b>Custom title:$descbr This title will be shown below your rank.</td>
	    $tccell2l>$inpt=title VALUE=\"$loguser[title]\" SIZE=60 MAXLENGTH=255><tr>
		";
    }
    $loguser['minipic'] = htmlspecialchars($loguser['minipic'], ENT_QUOTES);
    $loguser['picture'] = htmlspecialchars($loguser['picture'], ENT_QUOTES);
    $loguser['moodurl'] = htmlspecialchars($loguser['moodurl'], ENT_QUOTES);
    squot(0,$loguser['realname']);
//    squot(0,$loguser['aka']);
    squot(0,$loguser['location']);
//    squot(1,$loguser['aim']);
//    squot(1,$loguser['imood']);
    squot(0,$loguser['email']);
//    squot(1,$loguser['homepageurl']);
    squot(0,$loguser['homepagename']);
    sbr(1,$loguser['postheader']);
    sbr(1,$loguser['signature']);
    sbr(1,$loguser['bio']);

    $schemes=$sql->query('SELECT s.id as id, s.name, COUNT(u.scheme) as used FROM schemes s LEFT JOIN users u ON (u.scheme = s.id) WHERE ord > 0 GROUP BY u.scheme ORDER BY s.ord');
    while($sch=$sql->fetch($schemes)){
			$sel=($sch['id']==$loguser['scheme']?' selected':'');
			$schlist.="<option value=$sch[id]$sel>$sch[name] ($sch[used])";
    }
    $schlist="<select name=sscheme>$schlist</select>";

    $tlayouts=$sql->query('SELECT tl.id as id, tl.name, COUNT(u.layout) as used FROM tlayouts tl LEFT JOIN users u ON (u.layout = tl.id) GROUP BY u.layout ORDER BY tl.ord');
    while($lay=$sql->fetch($tlayouts)){
			$sel=($lay['id']==$loguser['layout']?' selected':'');
			$laylist.="<option value=$lay[id]$sel>$lay[name] ($lay[used])";
    }
    $laylist="<select name=tlayout>$laylist</select>";

    $used = $sql->getresultsbykey('SELECT signsep, count(*) as cnt FROM users GROUP BY signsep', 'signsep', 'cnt');
    for($i=0;$sepn[$i];$i++){
			$sel=($i==$loguser['signsep']?' selected':'');
			$seplist.="<option value=$i$sel>$sepn[$i] ($used[$i])";
    }
    $seplist="<select name=signsep>$seplist</select>";

    $rsets = $sql->query('SELECT rs.id as id, rs.name, COUNT(u.useranks) as used FROM ranksets rs LEFT JOIN users u ON (u.useranks = rs.id) GROUP BY u.useranks ORDER BY rs.id');
    while($set=$sql->fetch($rsets)){
			$sel=($set['id']==$loguser['useranks']?' selected':'');
			$rsetlist.="<option value=$set[id]$sel>$set[name] ($set[used])";
    }
    $rsetlist="<select name=useranks>$rsetlist</select>";

    print "
	$header<br>
    <FORM ACTION='{$GLOBALS['jul_views_path']}/editprofile.php' NAME=REPLIER METHOD=POST autocomplete=off>
    $tblstart
	 $tccellh colspan='2'>Login information</td><tr>
	 $tccell1 style='width: 40%;'><b>User name:$descbr If you want to change this, ask an admin.</td>$tccell2l style='width: 60%;'>$loguser[name]<tr>
	 $tccell1><b>Password:</b>$descbr You can change your password by entering a new one here.</td>
	 $tccell2l>$inpp=password SIZE=13 MAXLENGTH=64 autocomplete=off><tr>

	 $tccellh colspan='2'>Appearance</td><tr>
	 $titleoption
	 $tccell1><b>User rank:</b>$descbr You can hide your rank, or choose from different sets.</td>
	 $tccell2l>$rsetlist<tr>
	 $tccell1><b>Avatar:$descbr The full URL of the image showing up below your username in posts. Leave it blank if you don't want to use a avatar. Anything over 200&times;200 pixels will be removed.</td>
	 $tccell2l>$inpt=picture VALUE=\"$loguser[picture]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Mood avatar:$descbr The URL of a mood avatar set. '\$' in the URL will be replaced with the mood, e.g. <b>http://your.page/here/\$.png</b>!</td>
	 $tccell2l>$inpt=moodurl VALUE=\"$loguser[moodurl]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Minipic:$descbr The full URL of a small picture showing up next to your username on some pages. Leave it blank if you don't want to use a picture. The picture is resized to 16x16.</td>
	 $tccell2l>$inpt=minipic VALUE=\"$loguser[minipic]\" SIZE=60 MAXLENGTH=100><tr>
	 ". ($loguser['postbg'] ? "$tccell1><b>Post background:$descbr The full URL of a picture showing up in the background of your posts. Leave it blank for no background. Please make sure your text is readable on the background!</td>
	 $tccell2l>$inpt=postbg VALUE=\"$loguser[postbg]\" SIZE=60 MAXLENGTH=250><tr>
     " : "") ."
	 $tccell1><b>Post header:$descbr HTML added here will come before your post.</td>
	 $tccell2l>$txta=postheader ROWS=8 COLS=60 style='width: 100%;'>". htmlspecialchars($loguser['postheader']) ."</TEXTAREA><tr>
	 $tccell1><b>Footer/Signature:$descbr HTML and text added here will be added to the end of your post.</td>
	 $tccell2l>$txta=signature ROWS=8 COLS=60 style='width: 100%;'>". htmlspecialchars($loguser['signature']) ."</TEXTAREA><tr>

	 $tccellh colspan='2'>Personal information</td><tr>
     $tccell1><b>Gender/Name color:$descbr This mostly determines your name color.</td>
	 $tccell2l>$sexlist<tr>
     $tccell1><b>Pronouns:$descbr You can put your pronouns here (e.g. they/them, he/him, she/her, etc).</td>
	 $tccell2l>$inpt=pronouns VALUE=\"". htmlspecialchars($loguser['pronouns']) ."\" SIZE=40 MAXLENGTH=50><tr>
<!--	 $tccell1><b>Also known as:$descbr If you go by an alternate alias (or are constantly subjected to name changes), enter it here.  It will be displayed in your profile if it doesn't match your current username.</td>
	 $tccell2l>$inpt=aka VALUE=\"$loguser[aka]\" SIZE=25 MAXLENGTH=25><tr> -->
	 $tccell1><b>Real name:$descbr Your real name (you can leave this blank).</td>
	 $tccell2l>$inpt=realname VALUE=\"$loguser[realname]\" SIZE=40 MAXLENGTH=60><tr>
	 $tccell1><b>Location:$descbr Where you live (city, country, etc.).</td>
	 $tccell2l>$inpt=location VALUE=\"$loguser[location]\" SIZE=40 MAXLENGTH=60><tr>
	 $tccell1><b>Birthday:$descbr Your date of birth.</td>
	 $tccell2l>Month: $inpt=bmonth SIZE=2 MAXLENGTH=2 VALUE=$month> Day: $inpt=bday SIZE=2 MAXLENGTH=2 VALUE=$day> Year: $inpt=byear SIZE=4 MAXLENGTH=4 VALUE=$year><tr>
	 $tccell1><b>Bio:$descbr Some information about yourself, showing up in your profile. Accepts HTML.</td>
	 $tccell2l>$txta=bio ROWS=8 COLS=60 style='width: 100%;'>". htmlspecialchars($loguser['bio']) ."</TEXTAREA><tr>

	 $tccellh colspan='2'>Online services</td><tr>
	 $tccell1><b>Email address:$descbr This is only shown in your profile; you don't have to enter it if you don't want to.</td>
	 $tccell2l>$inpt=email VALUE=\"$loguser[email]\" SIZE=60 MAXLENGTH=60><tr>
	 $tccell1><b>AIM screen name:$descbr Your AIM screen name, if you have one.</td>
	 $tccell2l>$inpt=aim VALUE=\"$loguser[aim]\" SIZE=30 MAXLENGTH=30><tr>
	 $tccell1><b>ICQ number:$descbr Your ICQ number, if you have one.</td>
	 $tccell2l>$inpt=icq VALUE=$loguser[icq] SIZE=10 MAXLENGTH=10><tr>
	 $tccell1><b>imood:$descbr If you have a imood account, you can enter the account name (email) for it here.</td>
	 $tccell2l>$inpt=imood VALUE=\"$loguser[imood]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Homepage URL:$descbr Your homepage URL (must start with the \"http://\"), if you have one.</td>
	 $tccell2l>$inpt=homepage VALUE=\"$loguser[homepageurl]\" SIZE=60 MAXLENGTH=80><tr>
	 $tccell1><b>Homepage name:$descbr Your homepage name, if you have a homepage.</td>
	 $tccell2l>$inpt=pagename VALUE=\"$loguser[homepagename]\" SIZE=60 MAXLENGTH=100><tr>

	 $tccellh colspan='2'> Options</td><tr>
	 $tccell1><b>Custom date format:$descbr Change how dates are displayed. Uses <a href='http://php.net/manual/en/function.date.php'>date()</a> formatting. Leave blank to use the default.</td>
	 $tccell2l>$inpt=eddateformat value=\"$dateformat\" size=16 maxlength=32><tr>
	 $tccell1><b>Custom short date format:$descbr Change how abbreviated dates are displayed. Uses the same formatting. Leave blank to reset.</td>
	 $tccell2l>$inpt=eddateshort value=\"$dateshort\" size=8 maxlength=16><tr>
	 $tccell1><b>Timezone offset:$descbr How many hours you're offset from the time on the board (".date($dateformat,ctime()).").</td>
	 $tccell2l>$inpt=timezone VALUE=$loguser[timezone] SIZE=5 MAXLENGTH=5><tr>
	 $tccell1><b>Posts per page:$descbr The maximum number of posts you want to be shown in a page in threads.</td>
	 $tccell2l>$inpt=postsperpage SIZE=4 MAXLENGTH=4 VALUE=$loguser[postsperpage]><tr>
	 $tccell1><b>Threads per page:$descbr The maximum number of threads you want to be shown in a page in forums.</td>
	 $tccell2l>$inpt=threadsperpage SIZE=4 MAXLENGTH=4 VALUE=$loguser[threadsperpage]><tr>".
//	 $tccell1><b>Use textbox toolbar when posting:$descbr You can disable it here, preventing potential slowdowns or other minor problems when posting.</td>
//	 $tccell2l>$vtool<tr>
	"$tccell1><b>Post layouts:$descbr You can disable them here, which can make thread pages smaller and load faster.</td>
	 $tccell2l>$vsig<tr>

	 $tccell1><b>Forum page list style:$descbr Inline (Title - Pages ...) or Seperate Line (shows more pages)</td>
	 $tccell2l>$pagestyle<tr>
	 $tccell1><b>Poll vote system:$descbr Normal (based on users) or Influence (based on levels)</td>
	 $tccell2l>$pollstyle<tr>

	 $tccell1><b>Thread layout:$descbr You can choose from a few thread layouts here.</td>
	 $tccell2l>$laylist<tr>
	 $tccell1><b>Signature separator:$descbr You can choose from a few signature separators here.</td>
	 $tccell2l>$seplist<tr>
	 $tccell1><b>Color scheme / layout:$descbr You can select from a few color schemes here.</td>
	 $tccell2l>$schlist<tr>

	 $tccellh colspan='2'>&nbsp;</td><tr>
	 $tccell1>&nbsp;</td>$tccell2l>
	 $inph=action VALUE=saveprofile>
	 $inph=userid VALUE=$userid>
	 $inps=submit VALUE=\"Edit profile\"></td></FORM>
	$tblend
    ";
  }
  if($action=='saveprofile'){

      if (stripos($_POST['pronouns'], "helicopter") !== false) {
          // A wise guy, ey?
          // Real original, asshole.
          header("Location: https://www.youtube.com/embed/0WrFZAf6EEE?autoplay=1");
          die();
      }

    if ($eddateformat == $GLOBALS['jul_settings']['date_format_long']) $eddateformat = '';
    if ($eddateshort  == $GLOBALS['jul_settings']['date_format_short']) $eddateshort  = '';

    sbr(0,$postheader);
    sbr(0,$signature);
    sbr(0,$bio);
    if(!isset($title) or !$titleopt) $title=$loguser['title'];
    if($sex>2 && $sex != $loguser['sex'] && $sex != $loguser['oldsex'])
      $sex=2;

	$oldtitle	= "";
	while ($oldtitle != $title) {
		$oldtitle = $title;
		$title=preg_replace("'<(b|i|u|s|small|br)>'si", '[\\1]', $title);
		$title=preg_replace("'</(b|i|u|s|small|font)>'si", '[/\\1]', $title);
		$title=preg_replace("'<img ([^>].*?)>'si", '[img \\1]', $title);
		$title=preg_replace("'<font ([^>].*?)>'si", '[font \\1]', $title);
	/*    $title=preg_replace("'<[\/\!]*?[^<>]*?>'si", '&lt;\\1&gt;', $title); */
		$title=strip_tags($title);
	/*    $title=preg_replace("'<[\/\!]*?[^<>]*?>'si", '&lt;\\1&gt;', $title); */
		$title=preg_replace("'\[font ([^>].*?)\]'si", '<font \\1>', $title);
		$title=preg_replace("'\[img ([^>].*?)\]'si", '<img \\1>', $title);
		$title=preg_replace("'\[(b|i|u|s|small|br)\]'si", '<\\1>', $title);
		$title=preg_replace("'\[/(b|i|u|s|small|font)\]'si", '</\\1>', $title);
		$title=preg_replace("'(face|style|class|size|id)=\"([^ ].*?)\"'si", '', $title);
		$title=preg_replace("'(face|style|class|size|id)=\'([^ ].*?)\''si", '', $title);
		$title=preg_replace("'(face|style|class|size|id)=([^ ].*?)'si", '', $title);
	}
	$bio=preg_replace("'<iframe'si", '&lt;iframe', $bio);
    $bio=preg_replace("'<script'si", '&lt;script', $bio);
    $bio=preg_replace("'onload'si", 'o<z>nload', $bio);
    $bio=preg_replace("'onfail'si", 'o<z>nfail', $bio);
    $bio=preg_replace("'onhover'si", 'o<z>nhover', $bio);
    $bio=preg_replace("'javascript'si", 'java<z>script', $bio);
    $birthday=@mktime(12,0,0,$bmonth,$bday,$byear);
    if(!$bmonth && !$bday && !$byear) $birthday=0;
    if(!$icq) $icq=0;
    if(!isset($useranks)) $useranks=$loguser['useranks'];

		if ($_POST['password']) {
			$hash = getpwhash($_POST['password'], $loguserid);
			$passwordenc = "`password` = '$hash', ";

			if ($loguser['id'] == $loguserid) {
				$verifyid = intval(substr($_COOKIE['logverify'], 0, 1));
				$verify = create_verification_hash($verifyid, $hash);
				setcookie('logverify',$verify,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);
			}
		}
		else // Sneaky!  But no.
			$passwordenc = '';

    $sql->query("UPDATE users
      SET		$passwordenc
      `picture` = '$picture',
      `minipic` = '$minipic',
      `signature` = '$signature',
      `bio` = '$bio',
      `email` = '$email',
      `icq` = '$icq',
      `title` = '$title',
      `useranks` = '$useranks',
      `aim` = '$aim',
      `sex` = '$sex',
      `homepageurl` = '$homepage',
      `homepagename` = '$pagename',
      `timezone` = '$timezone',
      `dateformat` = '$eddateformat',
      `dateshort` = '$eddateshort',
      `postsperpage` = '$postsperpage',".
//      `aka` = '$aka',
     "`realname` = '$realname',
      `location` = '$location',
      `postbg` = '$postbg',
      `postheader` = '$postheader',
      `birthday` = '$birthday',
      `scheme` = '$sscheme',
      `threadsperpage` = '$threadsperpage',
      `viewsig` = '$viewsig',
      `layout` = '$tlayout',
      `moodurl` = '". $_POST['moodurl'] ."',
      `imood` = '$imood',
      `pronouns` = '{$_POST['pronouns']}',
      `signsep` = '$signsep',
      `pagestyle` = '$pagestyle',
      `pollstyle` = '$pollstyle'
    WHERE `id` = '$loguserid'") OR print mysql_error();

    print "$header<br>$tblstart$tccell1>Thank you, $loguser[name], for editing your profile.<br>".redirect("{$GLOBALS['jul_views_path']}/profile.php?id=$loguserid",'view your profile',0).$tblend;
  }

  print $footer;
  printtimedif($startingtime);
