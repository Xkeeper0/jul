<?php
	require_once '../lib/function.php';
	$user=@$sql->fetchq("SELECT * FROM users WHERE id=$id");
	$windowtitle="{$GLOBALS['jul_settings']['board_name']} -- Profile for $user[name]";
	require_once '../lib/layout.php';

//	if ($_GET['id'] == 1 && !$x_hacks['host']) {
//		print "$header<br><center><img src='http://earthboundcentral.com/wp-content/uploads/2009/01/m3deletede.png'></center><br>$footer";
//		printtimedif($startingtime);
//		die();
//	}

	if (!$user) {
		print "$header<br>$tblstart$tccell1>The specified user doesn't exist.$tblend$footer";
		printtimedif($startingtime);
		die();
	}

	$maxposts=$sql->resultq("SELECT posts FROM users ORDER BY posts DESC LIMIT 1");

/*  User ratings (disabled)
	$users1=$sql->query("SELECT id,posts,regdate FROM users");
	while($u=$sql->fetch_array($users1)){
		$u['level']=calclvl(calcexp($u['posts'],(ctime()-$u['regdate'])/86400));
		if ($u['posts']<0 or $u['regdate']>ctime()) $u['level']=1;
		$users[$u['id']]=$u;
	}

	$ratescore=0;
	$ratetotal=0;
	$ratings=$sql->query("SELECT userfrom,rating FROM userratings WHERE userrated=$id");
	while($rating=@$sql->fetch($ratings)){
		$ratescore+=$rating['rating']*$users[$rating['userfrom']]['level'];
		$ratetotal+=$users[$rating['userfrom']]['level'];
	}
	$ratetotal*=10;
	$numvotes=mysql_num_rows($ratings);
	if($ratetotal) {
		$ratingstatus=(floor($ratescore*1000/$ratetotal)/100)." ($ratescore/$ratetotal, $numvotes votes)";
	} else {
		$ratingstatus="None";
	}
	if($loguserid and $logpwenc and $loguserid!=$id)
		$ratelink=" | <a href=rateuser.php?id=$id>Rate user</a>";
*/

	$userrank=getrank($user['useranks'],$user['title'],$user['posts'],$user['powerlevel']);
	$threadsposted=$sql->resultq("SELECT count(id) AS cnt FROM threads WHERE user=$id",0,"cnt");

	$i=0;
	$lastpostdate="None";
	if($user['posts']) {
		$lastpostdate=date($dateformat,$user['lastposttime']+$tzoff);

		//$postsfound=$sql->resultq("SELECT count(id) AS cnt FROM posts WHERE user=$id",0,"cnt");
		$post = @$sql->fetchq("SELECT id, thread FROM posts WHERE user=$id AND date=$user[lastposttime]");

		if ($post && $thread = $sql->fetchq("SELECT title,forum FROM threads WHERE id=$post[1]")) {
			$forum = $sql->fetchq("SELECT id,title,minpower FROM forums WHERE id=$thread[forum]");
			$thread[0]=str_replace("<","&lt",$thread[0]);
			if ($forum['minpower']>$loguser['powerlevel'] and $forum['minpower'])
				$lastpostlink=", in a restricted forum";
			else
				$lastpostlink=", in <a href='{$GLOBALS['jul_views_path']}/thread.php?pid=$post[0]#$post[0]'>$thread[0]</a> (<a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>$forum[title]</a>)";
		}
	}

	if($log) {
		$sendpmsg=" | <a href='{$GLOBALS['jul_views_path']}/sendprivate.php?userid=$id'>Send private message</a>";
		if($isadmin){
			if($user['lastip'])
				$lastip=" <br>with IP: <a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip={$user['lastip']}' style='font-style:italic;'>$user[lastip]</a>";
			$sneek="<tr>$tccell1s colspan=2><a href='{$GLOBALS['jul_views_path']}/private.php?id={$id}' style='font-style:italic;'>View private messages</a> |"
				." <a href='{$GLOBALS['jul_views_path']}/forum.php?fav=1&user={$id}' style='font-style:italic;'>View favorites</a> |"
				//." <a href='{$GLOBALS['jul_views_path']}/rateuser.php?action=viewvotes&id={$id}' style='font-style:italic;'>View votes</a> |"
				." <a href='{$GLOBALS['jul_views_path']}/edituser.php?id={$id}' style='font-style:italic;'>Edit user</a>";
		}
	}

	$aim=str_replace(" ","+",$user['aim']);
	$schname=$sql->resultq("SELECT name FROM schemes WHERE id=$user[scheme]");
	$numdays=(ctime()-$user['regdate'])/86400;

	$user['signature']=doreplace($user['signature'],$user['posts'],$numdays,$user['name']);
	//  $user['signature']=doreplace2($user['signature'],$user['posts'],$numdays,$user['name']);
	$user['postheader']=doreplace($user['postheader'],$user['posts'],$numdays,$user['name']);
	//  $user['postheader']=doreplace2($user['postheader'],$user['posts'],$numdays,$user['name']);

	if ($user['picture']) $picture = "<img src=\"$user[picture]\">";
	if ($user['moodurl']) $moodavatar = " | <a href='{$GLOBALS['jul_views_path']}/avatar.php?id=$id' class=\"popout\" target=\"_blank\">Preview mood avatar</a>";

	$icqicon="<a href=http://wwp.icq.com/$user[icq]#pager><img src=http://wwp.icq.com/scripts/online.dll?icq=$user[icq]&img=5 border=0></a>";

	if(!$user['icq']){
		$user['icq']="";
		$icqicon="";
	}

	$tccellha="<td bgcolor=$tableheadbg";
	$tccellhb="><center>$fonthead";

	// Todo: This is a clear hack. Remove it.
	$birthday = (date('m-d', $user['birthday']) == date('m-d',ctime() + $tzoff));
	$rsex = (($birthday) ? 255 : $user['sex']);
	$namecolor = getnamecolor($rsex,$user['powerlevel'], false);

	$tzoffset=$user['timezone'];
	$tzoffrel=$tzoffset-$loguser['timezone'];
	$tzdate=date($dateformat,ctime()+$tzoffset*3600);
	if($user['birthday']){
		$birthday=date("l, F j, Y",$user['birthday']);
		$age="(".floor((ctime()-$user['birthday'])/86400/365.2425)." years old)";
	}

	// RPG fun shit
	$exp=calcexp($user['posts'],(ctime()-$user['regdate'])/86400);
	$lvl=calclvl($exp);
	$expleft=calcexpleft($exp);
	$expstatus="Level: $lvl<br>EXP: $exp (for next level: $expleft)";
	if($user['posts'] > 0) $expstatus.="<br>Gain: ".calcexpgainpost($user['posts'],(ctime()-$user['regdate'])/86400)." EXP per post, ".calcexpgaintime($user['posts'],(ctime()-$user['regdate'])/86400)." seconds to gain 1 EXP when idle";
	$postavg=sprintf("%01.2f",$user['posts']/(ctime()-$user['regdate'])*86400);
	$totalwidth=116;
	$barwidth=@floor(($user['posts']/$maxposts)*$totalwidth);
	if($barwidth<0) $barwidth=0;
	if($barwidth) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=images/$numdir"."bar-off.gif width=".($totalwidth-$barwidth)." height=8>";
	$bar="<img src=images/$numdir"."barleft.gif>$baron$baroff<img src=images/$numdir"."barright.gif><br>";
	if(!$topposts) $topposts=5000;

	if($user['posts']) $projdate=ctime()+(ctime()-$user['regdate'])*($topposts-$user['posts'])/($user['posts']);
	if(!$user['posts'] or $user['posts']>=$topposts or $projdate>2000000000 or $projdate<ctime()) $projdate="";
	else $projdate=" -- Projected date for $topposts posts: ".date($dateformat,$projdate+$tzoff);

	if($user['minipic']) $minipic="<img src=\"". htmlspecialchars($user['minipic']) ."\" width=16 height=16 align=absmiddle> ";
	$homepagename=$user['homepageurl'];
	if($user['homepagename']) $homepagename="$user[homepagename]</a> - $user[homepageurl]";
	if($user['postbg']) $postbg="<div style='background:url($user[postbg]);' height=100%>";

	loadtlayout();
	$user['headtext']=$user['postheader'];
	$user['signtext']=$user['signature'];
	$user['text'] = "Sample text. [quote=fhqwhgads]A sample quote, with a <a href=about:blank>link</a>, for testing your layout.[/quote]This is how your post will appear.";
	$user['uid']	= $_GET['id'];
	$user['date']	= ctime();

	// so that layouts show up regardless of setting (for obvious reasons)
	$loguser['viewsig'] = 1;

	// shop/rpg such
	$shops=$sql->query('SELECT * FROM itemcateg ORDER BY corder');
	$eq=$sql->fetchq("SELECT * FROM users_rpg WHERE uid=$id");
	$itemids=array_unique(array($eq['eq1'], $eq['eq2'], $eq['eq3'], $eq['eq4'], $eq['eq5'], $eq['eq6'], $eq['eq7']));
	$itemids=implode(',', $itemids);
	$items = array();
	$shoplist='';
	if (!empty($itemids)) {
		$eqitems=$sql->query("SELECT * FROM items WHERE id IN ({$itemids})");
		while($item=$sql->fetch($eqitems)) $items[$item['id']]=$item;
		while($shop=$sql->fetch($shops))
			$shoplist.="
				<tr>
				$tccell1s>$shop[name]</td>
				$tccell2s width=100%>".$items[$eq['eq'.$shop['id']]]['name']."&nbsp;</td>
			";
	}

	/* extra munging for whatever reason */
	$user['email'] = urlencode($user['email']);

	// AKA
	if ($user['aka'] && $user['aka'] != $user['name'])
		$aka = "$tccell1l width=150><b>Also known as</td>			$tccell2l>$user[aka]<tr>";
	else $aka='';

  print "
	$header
	<div>$fonttag Profile for <b>$minipic<span style='color:#{$namecolor}'>$user[name]</span></b></div>
<table cellpadding=0 cellspacing=0 border=0>
<td width=100% valign=top>
$tblstart
	$tccellh colspan=2><center>General information<tr>
	<!-- $tccell1l width=150><b>Username</td>			$tccell2l>$user[name]<tr> -->
	$aka
	$tccell1l width=150><b>Total posts</td>			$tccell2l>$user[posts] ($postavg per day) $projdate<br>$bar<tr>
	$tccell1l width=150><b>Total threads</td>		$tccell2l>$threadsposted<tr>
	$tccell1l width=150><b>EXP</td>					$tccell2l>$expstatus<tr>
". (false ? "	$tccell1l width=150><b>User rating</td>			$tccell2l>$ratingstatus<tr>" : "") ."
	$tccell1l width=150><b>Registered on</td>		$tccell2l>".@date($dateformat,$user['regdate']+$tzoff)." (".floor((ctime()-$user['regdate'])/86400)." days ago)<tr>
	$tccell1l width=150><b>Last post</td>			$tccell2l>$lastpostdate$lastpostlink<tr>
	$tccell1l width=150><b>Last activity</td>		$tccell2l>".date($dateformat,$user['lastactivity']+$tzoff)."$lastip<tr>
$tblend
<br>$tblstart
	$tccellh colspan=2><center>Contact information<tr>
	$tccell1l width=150><b>Email address</td>		$tccell2l><a href='mailto:$user[email]'>$user[email]</a>&nbsp;<tr>
	$tccell1l width=150><b>Homepage</td>			$tccell2l><a href='$user[homepageurl]'>$homepagename</a>&nbsp;<tr>
	$tccell1l width=150><b>ICQ number</td>			$tccell2l>$user[icq] $icqicon&nbsp;<tr>
	$tccell1l width=150><b>AIM screen name</td>		$tccell2l><a href='aim:goim?screenname=$aim'>$user[aim]</a>&nbsp;<tr>
$tblend
<br>$tblstart
	$tccellh colspan=2><center>User settings<tr>
	$tccell1l width=150><b>Timezone offset</td>		$tccell2l>$tzoffset hours from the server, $tzoffrel hours from you (current time: $tzdate)<tr>
	$tccell1l width=150><b>Items per page</td>		$tccell2l>". $user['threadsperpage'] ." threads, ". $user['postsperpage'] ." posts<tr>
	$tccell1l width=150><b>Color scheme</td>		$tccell2l>".$schname."<tr>
$tblend
</td><td>&nbsp;&nbsp;&nbsp;</td><td valign=top>
$tblstart
	$tccellh><center>RPG status<tr>
	$tccell1l><img src='{$GLOBALS['jul_views_path']}/status.php?u=$id'>
$tblend
<br>$tblstart
	$tccellh colspan=2><center>Equipped Items<tr>
	$shoplist
$tblend
</td></table>
<br>$tblstart
	$tccellh colspan=2><center>Personal information<tr>
	$tccell1l width=150><b>Real name</td>			$tccell2l>$user[realname]&nbsp;<tr>
	$tccell1l width=150><b>Pronouns</td>			$tccell2l>". htmlspecialchars($user['pronouns']) ."&nbsp;<tr>
	$tccell1l width=150><b>Location</td>			$tccell2l>$user[location]&nbsp;<tr>
	$tccell1l width=150><b>Birthday</td>			$tccell2l>$birthday $age&nbsp;<tr>
	$tccell1l width=150><b>User bio</td>			$tccell2l>". dofilters(doreplace2(doreplace($user['bio'], $user['posts'], (ctime()-$user['regdate'])/86400, $user['name']))) ."&nbsp;<tr>
$tblend
<br>$tblstart
	$tccellh colspan=2><center>Sample post<tr>
	". threadpost($user, 1) ."
$tblend
<br>$tblstart
	$tccellhs colspan=2><center>Options<tr>
	$tccell2s colspan=2>
	<a href='{$GLOBALS['jul_views_path']}/thread.php?user=$id'>Show posts</a> |
	<a href='{$GLOBALS['jul_views_path']}/forum.php?user=$id'>View threads by this user</a>
	$sendpmsg
  $ratelink
  $moodavatar
  <tr>
	$tccell2s colspan=2>
	<a href='{$GLOBALS['jul_views_path']}/postsbyuser.php?id=$id'>List posts by this user</a> |
	<a href='{$GLOBALS['jul_views_path']}/postsbytime.php?id=$id'>Posts by time of day</a> |
	<a href='{$GLOBALS['jul_views_path']}/postsbythread.php?id=$id'>Posts by thread</a> |
	<a href='{$GLOBALS['jul_views_path']}/postsbyforum.php?id=$id'>Posts by forum</td>$sneek
	$tblend$footer
  ";

  printtimedif($startingtime);
?>
