<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if(!$log) errorpage('You must be logged in to edit your profile.');
  if($banned) errorpage('Sorry, but banned users arent allowed to edit their profile.');
	if($loguser['profile_locked'] == 1) {
		errorpage("You are not allowed to edit your profile.");
	}
  if($loguser[posts]>=500 or ($loguser[posts]>=250 && (ctime()-$loguser[regdate])>=100*86400)) $postreq=1;
  if($loguser[titleoption]==0 || $banned) $titleopt=0;
  if($loguser[titleoption]==1 && ($postreq or $power>0 or $loguser[title])) $titleopt=1;
  if($loguser[titleoption]==2) $titleopt=1;
  if(!$action){
    $birthday=getdate($loguser[birthday]);
    if($loguser[birthday]){
	$month=$birthday[mon];
	$day=$birthday[mday];
	$year=$birthday[year];
    }
    $descbr="</b>$smallfont<br></center>&nbsp;";
    $checked1[$loguser[sex]]='checked=1';
    $checked2[$loguser[viewsig]]='checked=1';
    $checked3[$loguser[posttool]]='checked=1';
    $checked4[$loguser[useranks]]='checked=1';
    $checked5[$loguser[pagestyle]]='checked=1';
    $checked6[$loguser[pollstyle]]='checked=1';
    $sexlist="
	$radio=sex value=0 $checked1[0]> Male &nbsp;&nbsp;
	$radio=sex value=1 $checked1[1]> Female &nbsp;&nbsp;
	$radio=sex value=2 $checked1[2]> N/A";
    $vsig="
	$radio=viewsig value=0 $checked2[0]> Disabled &nbsp;&nbsp;
	$radio=viewsig value=1 $checked2[1]> Enabled &nbsp;&nbsp;
	$radio=viewsig value=2 $checked2[2]> Auto-updating";
    $vtool="
	$radio=posttool value=0 $checked3[0]> Disabled &nbsp;&nbsp;
	$radio=posttool value=1 $checked3[1]> Enabled";
    $pagestyle="
	$radio=pagestyle value=0 $checked5[0]> Inline &nbsp;&nbsp;
	$radio=pagestyle value=1 $checked5[1]> Seperate line";
    $pollstyle="
	$radio=pollstyle value=0 $checked6[0]> Normal &nbsp;&nbsp;
	$radio=pollstyle value=1 $checked6[1]> Influence";
    if($titleopt){
		// this went after this block, which makes it COMPLETELY USELESS
	    squot(0,$loguser[title]);
		$titleoption="
	    $tccell1><b>Custom title:$descbr This title will be shown below your rank.</td>
	    $tccell2l>$inpt=title VALUE=\"$loguser[title]\" SIZE=60 MAXLENGTH=255><tr>
		";
    }
//    squot(1,$loguser[minipic]);
//    squot(1,$loguser[picture]);
    squot(0,$loguser[realname]);
    squot(0,$loguser[location]);
//    squot(1,$loguser[aim]);
//    squot(1,$loguser[imood]);
//    squot(1,$loguser[email]);
//    squot(1,$loguser[homepageurl]);
    squot(0,$loguser[homepagename]);
    sbr(1,$loguser[postheader]);
    sbr(1,$loguser[signature]);
    sbr(1,$loguser[bio]);
    $schemes=mysql_query('SELECT id,name FROM schemes WHERE ord > 0 ORDER BY ord');
    while($sch=mysql_fetch_array($schemes)){
	$sel=($sch[id]==$loguser[scheme]?' selected':'');
	$used=mysql_result(mysql_query("SELECT count(*) FROM users WHERE scheme=$sch[id]"),0,0);
	$schlist.="<option value=$sch[id]$sel>$sch[name] ($used)";
    }
    $schlist="<select name=sscheme>$schlist</select>";
    $tlayouts=mysql_query('SELECT id,name FROM tlayouts ORDER BY ord');
    while($lay=mysql_fetch_array($tlayouts)){
	$sel=($lay[id]==$loguser[layout]?' selected':'');
	$used=mysql_result(mysql_query("SELECT count(*) FROM users WHERE layout=$lay[id]"),0,0);
	$laylist.="<option value=$lay[id]$sel>$lay[name] ($used)";
    }
    $laylist="<select name=tlayout>$laylist</select>";
    for($i=0;$sepn[$i];$i++){
	$sel=($i==$loguser[signsep]?' selected':'');
	$used=mysql_result(mysql_query("SELECT count(*) FROM users WHERE signsep=$i"),0,0);
	$seplist.="<option value=$i$sel>$sepn[$i] ($used)";
    }
    $seplist="<select name=signsep>$seplist</select>";
    $rsets=mysql_query('SELECT id,name FROM ranksets ORDER BY id');
    while($set=mysql_fetch_array($rsets)){
	$sel=($set[id]==$loguser[useranks]?' selected':'');
	$used=mysql_result(mysql_query("SELECT count(*) FROM users WHERE useranks=$set[id]"),0,0);
	$rsetlist.="<option value=$set[id]$sel>$set[name] ($used)";
    }
    $rsetlist="<select name=useranks>$rsetlist</select>";
    print "
	$header<br>$tblstart
	 <FORM ACTION=editprofile.php NAME=REPLIER METHOD=POST>
	 $tccellh>Login information</td>$tccellh>&nbsp<tr>
	 $tccell1><b>User name:</td>$tccell2l>$loguser[name]<tr>
	 $tccell1><b>Password:</b>$descbr You can change your password by entering a new one here.</td>
	 $tccell2l>$inpp=password SIZE=13 MAXLENGTH=32><tr>

	 $tccellh> Appearance</td>$tccellh>&nbsp<tr>
	 $titleoption
	 $tccell1><b>User rank:</b>$descbr You can hide your rank, or choose from different sets.</td>
	 $tccell2l>$rsetlist<tr>
	 $tccell1><b>User picture:$descbr The full URL of the image showing up below your username in posts. Leave it blank if you don't want to use a picture. The limits are 200x200 pixels, and about 100KB; anything over this will be removed.</td>
	 $tccell2l>$inpt=picture VALUE=\"$loguser[picture]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Mood avatar:$descbr The URL of a mood avatar set. '\$' in the URL will be replaced with the mood, e.g. <b>http://your.page/here/\$.png</b>!</td>
	 $tccell2l>$inpt=moodurl VALUE=\"$loguser[moodurl]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Minipic:$descbr The full URL of a small picture showing up next to your username on some pages. Leave it blank if you don't want to use a picture. The picture is resized to 16x16.</td>
	 $tccell2l>$inpt=minipic VALUE=\"$loguser[minipic]\" SIZE=60 MAXLENGTH=100><tr>
	 $tccell1><b>Post background:$descbr The full URL of a picture showing up in the background of your posts. Leave it blank for no background. Please make sure your text is readable on the background!</td>
	 $tccell2l>$inpt=postbg VALUE=\"$loguser[postbg]\" SIZE=60 MAXLENGTH=250><tr>
	 $tccell1><b>Post header:$descbr This will get added before the start of each post you make. This can be used to give a default font color and face to your posts (by putting a <<z>font> tag). This should preferably be kept small, and not contain too much text or images.</td>
	 $tccell2l>$txta=postheader ROWS=8 COLS=60>". htmlspecialchars($loguser[postheader]) ."</TEXTAREA><tr>
	 $tccell1><b>Signature:$descbr This will get added at the end of each post you make, below an horizontal line. This should preferably be kept to a small enough size.</td>
	 $tccell2l>$txta=signature ROWS=8 COLS=60>". htmlspecialchars($loguser[signature]) ."</TEXTAREA><tr>

	 $tccellh>Personal information</td>$tccellh>&nbsp<tr>
	 $tccell1><b>Sex:$descbr Male or female. (or N/A if you don't want to tell it)</td>
	 $tccell2l>$sexlist<tr>
	 $tccell1><b>Real name:$descbr Your real name (you can leave this blank).</td>
	 $tccell2l>$inpt=realname VALUE=\"$loguser[realname]\" SIZE=40 MAXLENGTH=60><tr>
	 $tccell1><b>Location:$descbr Where you live (city, country, etc.).</td>
	 $tccell2l>$inpt=location VALUE=\"$loguser[location]\" SIZE=40 MAXLENGTH=60><tr>
	 $tccell1><b>Birthday:$descbr Your date of birth.</td>
	 $tccell2l>Month: $inpt=bmonth SIZE=2 MAXLENGTH=2 VALUE=$month> Day: $inpt=bday SIZE=2 MAXLENGTH=2 VALUE=$day> Year: $inpt=byear SIZE=4 MAXLENGTH=4 VALUE=$year><tr>
	 $tccell1><b>Bio:$descbr Some information about yourself, showing up in your profile.</td>
	 $tccell2l>$txta=bio ROWS=8 COLS=60>". htmlspecialchars($loguser[bio]) ."</TEXTAREA><tr>

	 $tccellh>Online services</td>$tccellh>&nbsp<tr>
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

	 $tccellh> Options</td>$tccellh>&nbsp<tr>
	 $tccell1><b>Timezone offset:$descbr How many hours you're offset from the time on the board (".date($dateformat,ctime()).").</td>
	 $tccell2l>$inpt=timezone VALUE=$loguser[timezone] SIZE=5 MAXLENGTH=5><tr>
	 $tccell1><b>Date format:$descbr How dates on the board are displayed (uses the <a href='http://php.net/manual/en/function.date.php'>PHP date()</a> function).</td>
	 $tccell2l>$inpt=dateformat VALUE=\"". $user['dateformat'] ."\" SIZE=16 MAXLENGTH=32><tr>
	 $tccell1><b>Short date format:$descbr A short date format displayed on certain pages.</td>
	 $tccell2l>$inpt=dateshort VALUE=\"". $user['dateshort'] ."\" SIZE=8 MAXLENGTH=32><tr>
	 $tccell1><b>Posts per page:$descbr The maximum number of posts you want to be shown in a page in threads.</td>
	 $tccell2l>$inpt=postsperpage SIZE=4 MAXLENGTH=4 VALUE=$loguser[postsperpage]><tr>
	 $tccell1><b>Threads per page:$descbr The maximum number of threads you want to be shown in a page in forums.</td>
	 $tccell2l>$inpt=threadsperpage SIZE=4 MAXLENGTH=4 VALUE=$loguser[threadsperpage]><tr>
	 $tccell1><b>Use textbox toolbar when posting:$descbr You can disable it here, preventing potential slowdowns or other minor problems when posting.</td>
	 $tccell2l>$vtool<tr>
	 $tccell1><b>Signatures and post headers:$descbr You can disable them here, which can make thread pages smaller and load faster.</td>
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

	 $tccellh>&nbsp</td>$tccellh>&nbsp<tr>
	 $tccell1>&nbsp</td>$tccell2l>
	 $inph=action VALUE=saveprofile>
	 $inph=userid VALUE=$userid>
	 $inph=userpass VALUE=\"$loguser[password]\">
	 $inps=submit VALUE=\"Edit profile\"></td></FORM>
	$tblend
    ";
  }
  if($action=='saveprofile'){
    sbr(0,$postheader);
    sbr(0,$signature);
    sbr(0,$bio);
    if(!isset($title) or !$titleopt) $title=$loguser[title];
    if($sex>2) $sex=2;

	$oldtitle	= "";
	while ($oldtitle != $title) {
		$oldtitle = $title;
		$title=preg_replace("'<(b|i|u|s|br)>'si", '[\\1]', $title);
		$title=preg_replace("'</(b|i|u|s|font)>'si", '[/\\1]', $title);
		$title=preg_replace("'<img ([^>].*?)>'si", '[img \\1]', $title);
		$title=preg_replace("'<font ([^>].*?)>'si", '[font \\1]', $title);
	/*    $title=preg_replace("'<[\/\!]*?[^<>]*?>'si", '&lt;\\1&gt;', $title); */
		$title=strip_tags($title);
	/*    $title=preg_replace("'<[\/\!]*?[^<>]*?>'si", '&lt;\\1&gt;', $title); */
		$title=preg_replace("'\[font ([^>].*?)\]'si", '<font \\1>', $title);
		$title=preg_replace("'\[img ([^>].*?)\]'si", '<img \\1>', $title);
		$title=preg_replace("'\[(b|i|u|s|br)\]'si", '<\\1>', $title);
		$title=preg_replace("'\[/(b|i|u|s|font)\]'si", '</\\1>', $title);
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
    $birthday=@mktime(0,0,0,$bmonth,$bday,$byear);
    if(!$bmonth && !$bday && !$byear) $birthday=0;
    if(!$icq) $icq=0;
    if(!$password) $passwordenc=$userpass;
    else{
	$passwordenc=md5($password);
	if($loguser[id]==$loguserid) setcookie('logpassword',shenc($password),2147483647);
    }
    if(!isset($useranks)) $useranks=$loguser[useranks];

    $dateformat = $_POST['dateformat'];
    $dateshort = $_POST['dateshort'];

    mysql_query("UPDATE users SET `password` = '$passwordenc', `picture` = '$picture', `minipic` = '$minipic', `signature` = '$signature', `bio` = '$bio', `email` = '$email', `icq` = '$icq', `title` = '$title', `useranks` = '$useranks', `aim` = '$aim', `sex` = '$sex', `homepageurl` = '$homepage', `homepagename` = '$pagename', `timezone` = '$timezone', `dateformat` = '$dateformat', `dateshort` = '$dateshort', `postsperpage` = '$postsperpage', `realname` = '$realname', `location` = '$location', `postbg` = '$postbg', `postheader` = '$postheader', `birthday` = '$birthday', `scheme` = '$sscheme', `threadsperpage` = '$threadsperpage', `viewsig` = '$viewsig', `layout` = '$tlayout', `moodurl` = '". $_POST['moodurl'] ."', `posttool` = '$posttool', `imood` = '$imood', `signsep` = '$signsep', `pagestyle` = '$pagestyle', `pollstyle` = '$pollstyle' WHERE `id` = '$loguserid' AND `password` = '$userpass'") OR print mysql_error();

    print "$header<br>$tblstart$tccell1>Thank you, $loguser[name], for editing your profile.<br>".redirect("profile.php?id=$loguserid",'view your profile',0).$tblend;
  }

  print $footer;
  printtimedif($startingtime);
?>
