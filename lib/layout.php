<?php


	// UTF-8 time?
	header("Content-type: text/html; charset=utf-8');");


	// cache bad
	header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
	header('Pragma: no-cache');

	$userip = $_SERVER['REMOTE_ADDR'];

	if (!($clientip    = filter_var(getenv("HTTP_CLIENT_IP"),       FILTER_VALIDATE_IP))) $clientip =    "XXXXXXXXXXXXXXXXX";
	if (!($forwardedip = filter_var(getenv("HTTP_X_FORWARDED_FOR"), FILTER_VALIDATE_IP))) $forwardedip = "XXXXXXXXXXXXXXXXX";
//	$clientip=(getenv("HTTP_CLIENT_IP") == "" ? "XXXXXXXXXXXXXXXXX" : getenv("HTTP_CLIENT_IP"));
//	$forwardedip=(getenv("HTTP_X_FORWARDED_FOR") == "" ? "XXXXXXXXXXXXXXXXX" : getenv("HTTP_X_FORWARDED_FOR"));

	if(!isset($windowtitle)) $windowtitle=$GLOBALS['jul_settings']['board_name'];
	require_once 'colors.php';
	if($specialscheme) include "schemes/spec-$specialscheme.php";
	$home = base_dir().'/';
	$GLOBALS['jul_settings']['board_title']	= "<a href='{$home}'>{$GLOBALS['jul_settings']['board_title']}</a>";

	//$GLOBALS['jul_settings']['board_title'] = "<a href='./'><img src=\"images/christmas-banner-blackroseII.png\" title=\"Not even Christmas in July, no. It's May.\"></a>";

	// PONIES!!!
	// if($forumid==30) $GLOBALS['jul_settings']['board_title'] = "<a href='./'><img src=\"images/poniecentral.gif\" title=\"YAAAAAAAAAAY\"></a>";
	// end PONIES!!!

	$race=$loguserid ? postradar($loguserid) : "";

	$tablewidth='100%';
	$fonttag='<font class="font">';
	$fonthead='<font class="fonth">';
	$smallfont='<font class="fonts">';
	$tinyfont='<font class="fontt">';

	foreach(array('1','2','c','h') as $celltype){
		$cell="<td class='tbl tdbg$celltype font";
		$celln="tccell$celltype";
		$$celln     =$cell." center'";
		${$celln.'s'} =$cell."s center'";
		${$celln.'t'} =$cell."t center'";
		${$celln.'l'} =$cell."'";
		${$celln.'r'} =$cell." right'";
		${$celln.'ls'}=$cell."s'";
		${$celln.'lt'}=$cell."t'";
		${$celln.'rs'}=$cell."s right'";
		${$celln.'rt'}=$cell."t right'";
	}

	$inpt='<input type="text" name';
	$inpp='<input type="password" name';
	$inph='<input type="hidden" name';
	$inps='<input type="submit" class=submit name';
	$inpc="<input type=checkbox name";
	$radio='<input type=radio class=radio name';
	$txta='<textarea wrap=virtual name';
	$tblstart='<table class="table" cellspacing=0>';
	$tblend='</table>';
	$sepn=array('Dashes','Line','Full horizontal line','None');
	$sep=array('<br><br>--------------------<br>',
		 '<br><br>____________________<br>',
		 '<br><br><hr>',
		 '<br><br>');
	$br="\n";

	if (isset($bgimage) && $bgimage != "") {
		$bgimage = " url('$bgimage')";
	} else { $bgimage = ''; }

	if (isset($nullscheme) && $nullscheme == 1) {
		// special "null" scheme.
		$css = "";
	} elseif (isset($schemetype) && $schemetype == 1) {
		$css = "<link rel='stylesheet' href='{$GLOBALS['jul_base_dir']}/css/basics.css' type='text/css'><link rel='stylesheet' type='text/css' href='/css/$schemefile.css'>";
		// possibly causes issue #19 - not sure why this was here
		// likely irrelevant after addition of custom date formats
		// (remove this later)
		//$dateformat = "m/d/y h:i";
		//$dateshort  = "m/d/y";

		// backwards compat
		global $bgcolor, $linkcolor;
		$bgcolor = "000";
		$linkcolor = "FFF";
	} else {
		$css="
			<link rel='stylesheet' href='{$GLOBALS['jul_base_dir']}/css/base.css' type='text/css'>
			<style type='text/css'>
			a			{	color: #$linkcolor;	}
			a:visited	{	color: #$linkcolor2;	}
			a:active	{	color: #$linkcolor3;	}
			a:hover		{	color: #$linkcolor4;	}
			body {
				scrollbar-face-color:		#$scr3;
				scrollbar-track-color:		#$scr7;
				scrollbar-arrow-color:		#$scr6;
				scrollbar-highlight-color:	#$scr2;
				scrollbar-3dlight-color:	#$scr1;
				scrollbar-shadow-color:	#$scr4;
				scrollbar-darkshadow-color:	#$scr5;
				color: #$textcolor;
				font:13px $font;
				background: #$bgcolor$bgimage;
			}
			div.lastpost { font: 10px $font2 !important; white-space: nowrap; }
			div.lastpost:first-line { font: 13px $font !important; }
			.sparkline { display: none; }
			.font 	{font:13px $font}
			.fonth	{font:13px $font;color:$tableheadtext}	/* is this even used? */
			.fonts	{font:10px $font2}
			.fontt	{font:10px $font3}
			.tdbg1	{background:#$tablebg1}
			.tdbg2	{background:#$tablebg2}
			.tdbgc	{background:#$categorybg}
			.tdbgh	{background:#$tableheadbg; color:$tableheadtext}
			.table	{empty-cells:	show; width: $tablewidth;
					 border-top:	#$tableborder 1px solid;
					 border-left:	#$tableborder 1px solid;}
			td.tbl	{border-right:	#$tableborder 1px solid;
					 border-bottom:	#$tableborder 1px solid}
		";
	}

	$numcols=(filter_int($numcols) ? $numcols : 60);

	if($formcss){
		$numcols=80;
		if (!isset($formtextcolor)) {
			$formtextcolor = $textcolor;
		}
		$css.="
		textarea,input,select{
		  border:	#$inputborder solid 1px;
		  background:#000000;
		  color:	#$formtextcolor;
		  font:	10pt $font;}
		textarea:focus {
		  border:	#$inputborder solid 1px;
		  background:#000000;
		  color:	#$formtextcolor;
		  font:	10pt $font;}
		.radio{
		  border:	none;
		  background:none;
		  color:	#$formtextcolor;
		  font:	10pt $font;}
		.submit{
		  border:	#$inputborder solid 2px;
		  font:	10pt $font;}
		";
	}

	// April 1st page flip
	//$css .= "
  //	body {
	//		transform:			scale(-1, 1);
	//		-o-transform:		scale(-1, 1);
	//		-moz-transform:		scale(-1, 1);
	//		-webkit-transform:	scale(-1, 1);
	//	}
	//	.tbl {
	//		transform:			scale(-1, 1);
	//		-o-transform:		scale(-1, 1);
	//		-moz-transform:		scale(-1, 1);
	//		-webkit-transform:	scale(-1, 1);
	//	}
	//";

	// 10/18/08 - hydrapheetz: added a small hack for "extra" css goodies.
	if (!isset($nullscheme) && !isset($schemetype)) {
		if (isset($css_extra)) {
			$css .= $css_extra . "\n";
		}
		$css.='</style>';
	}

	// $css	.= "<!--[if IE]><style type='text/css'>#f_ikachan, #f_doomcounter, #f_mustbeblind { display: none; }</style><![endif]-->	";

	$headlinks = '';
	if($loguserid) {
		if($isadmin)
			$headlinks.="<a href=\"{$GLOBALS['jul_views_path']}/admin.php\" style=\"font-style:italic;\">Admin</a> - ";

		if($power >= 1)
			$headlinks.="<a href='{$GLOBALS['jul_views_path']}/shoped.php' style=\"font-style:italic;\">Shop Editor</a> - ";

		$headlinks.="
		<a href=\"javascript:document.logout.submit()\">Logout</a>
		- <a href=\"{$GLOBALS['jul_views_path']}/editprofile.php\">Edit profile</a>
		- <a href=\"{$GLOBALS['jul_views_path']}/postradar.php\">Post radar</a>
		- <a href=\"{$GLOBALS['jul_views_path']}/shop.php\">Item shop</a>
		- <a href=\"{$GLOBALS['jul_views_path']}/forum.php?fav=1\">Favorites</a>";
	} else {
		$headlinks.="
		  <a href=\"{$GLOBALS['jul_views_path']}/register.php\">Register</a>
		- <a href=\"{$GLOBALS['jul_views_path']}/login.php\">Login</a>";
	}

	if (in_array($loguserid,array(1,5,2100))) {
		$xminilog	= $sql -> fetchq("SELECT COUNT(*) as count, MAX(`time`) as time FROM `minilog`");
		if ($xminilog['count']) {
			$xminilogip	= $sql -> fetchq("SELECT `ip`, `banflags` FROM `minilog` ORDER BY `time` DESC LIMIT 1");
			$GLOBALS['jul_settings']['board_title']	.= "<br><a href='{$GLOBALS['jul_views_path']}/shitbugs.php'><span class=font style=\"color: #f00\"><b>". $xminilog['count'] ."</b> suspicious request(s) logged, last at <b>". date($dateformat, $xminilog['time'] + $tzoff) ."</b> by <b>". $xminilogip['ip'] ." (". $xminilogip['banflags'] .")</b></span></a>";
		}
		$xminilog	= $sql -> fetchq("SELECT COUNT(*) as count, MAX(`time`) as time FROM `pendingusers`");
		if ($xminilog['count']) {
			$xminilogip	= $sql -> fetchq("SELECT `username`, `ip` FROM `pendingusers` ORDER BY `time` DESC LIMIT 1");
			$GLOBALS['jul_settings']['board_title']	.= "<br><span class='font' style=\"color: #ff0\"><b>". $xminilog['count'] ."</b> pending user(s), last <b>'". $xminilogip['username'] ."'</b> at <b>". date($dateformat, $xminilog['time'] + $tzoff) ."</b> by <b>". $xminilogip['ip'] ."</b></span>";
		}
	}


	$headlinks2 = array();
	foreach ($GLOBALS['jul_settings']['top_menu_items'] as $row) {
		$rowlinks = array();
		foreach ($row as $item) {
			$rowlinks[] = '<a href="'.to_route($item[0]).'">'.$item[1].'</a>';
		}
		$headlinks2[] = implode(' - ', $rowlinks);
	}
	$headlinks2 = implode('<br>', $headlinks2);


	$ipbanned	= $torbanned = 0;

	$checkips = "INSTR('$userip',ip)=1";
	if ($forwardedip !== "XXXXXXXXXXXXXXXXX")
		$checkips .= " OR INSTR('$forwardedip',ip)=1";
	if ($clientip !== "XXXXXXXXXXXXXXXXX")
		$checkips .= " OR INSTR('$clientip',ip)=1";

	if($sql->resultq("SELECT count(*) FROM ipbans WHERE $checkips")) $ipbanned=1;
	if($sql->resultq("SELECT count(*) FROM `tor` WHERE `ip` = '". $_SERVER['REMOTE_ADDR'] ."' AND `allowed` = '0'")) $torbanned=1;

	if ($ipbanned || $torbanned)
		$windowtitle = $GLOBALS['jul_settings']['board_name'];

	if($ipbanned) {
		$url .=' (IP banned)';
	}

	if ($torbanned) {
		$url .=' (Tor proxy)';
		$sql->query("UPDATE `tor` SET `hits` = `hits` + 1 WHERE `ip` = '". $_SERVER['REMOTE_ADDR'] ."'");
	}

	$views=$sql->resultq('SELECT views FROM misc')+1;

	if (!$ipbanned && !$torbanned && (!defined("IS_AJAX_REQUEST") || !IS_AJAX_REQUEST)) {
		// Don't increment the view counter for bots
		// Todo: Actually check for bots and disable it because hdurfs
		$sql->query("UPDATE misc SET views=$views");

		if($views%10000000>9999000 or $views%10000000<1000) {
			$u=($loguserid?$loguserid:0);
			$ct = ctime();
			$sql->query("INSERT INTO hits VALUES ({$views},{$u},'{$userip}',{$ct})");
		}

		// Print out a message to IRC whenever a 10-million-view milestone is hit
		if ($views%10000000>9999994 || ($views % 10000000 >= 9991000 && $views % 1000 == 0) || ($views % 10000000 >= 9999900 && $views % 10 == 0) || ($views > 5 && $views % 10000000 < 5)) {
			xk_ircsend("0|View ". xk(11) . str_pad(number_format($views), 10, " ", STR_PAD_LEFT) . xk() ." by ". ($loguser['id'] ? xk(11) . str_pad($loguser['name'], 25, " ") : xk(12) . str_pad($_SERVER['REMOTE_ADDR'], 25, " ")) . xk() . ($views % 1000000 > 500000 ? " (". xk(12) . str_pad(number_format(1000000 - ($views % 1000000)), 5, " ", STR_PAD_LEFT) . xk(2) ." to go" . xk() .")" : ""));

		}
	}

	// Dailystats update in one query
	$sql->query("INSERT INTO dailystats (date, users, threads, posts, views) " .
	             "VALUES ('".date('m-d-y',ctime())."', (SELECT COUNT( * ) FROM users), (SELECT COUNT(*) FROM threads), (SELECT COUNT(*) FROM posts), $views) ".
	             "ON DUPLICATE KEY UPDATE users=VALUES(users), threads=VALUES(threads), posts=VALUES(posts), views=$views");

//	$sql->query("INSERT INTO dailystats (date) VALUES ('".date('m-d-y',ctime())."')");
//	$sql->query("UPDATE dailystats SET users=$count[u],threads=$count[t],posts=$count[p],views=$views WHERE date='".date('m-d-y',ctime())."'");

	//No gunbound rankset here (yet), stop futily trying to update it
	//updategb();

	$new='&nbsp;';
	$privatebox = "";
  // Note that we ignore this in private.php (obviously) and the index page (it handles PMs itself)
  // This box only shows up when a new PM is found, so it's optimized for that
	if ($log && strpos($PHP_SELF, "private.php") == false && strpos($PHP_SELF, "index.php") == 0) {
    $newmsgquery = $sql->query("SELECT date,u.id uid,name,sex,powerlevel,aka FROM pmsgs p LEFT JOIN users u ON u.id=p.userfrom WHERE userto=$loguserid AND msgread=0 ORDER BY p.id DESC");
		if ($pmsg = $sql->fetch($newmsgquery)) {
			$namelink = getuserlink($pmsg, array('id'=>'uid'));
			$lastmsg  = "Last unread message from $namelink on ".date($dateformat,$pmsg['date']+$tzoff);

      $numnew = mysql_num_rows($newmsgquery);
			if ($numnew > 1) $ssss = "s";

			$privatebox = "<tr><td colspan=3 class='tbl tdbg2 center fonts'>$newpic <a href={$GLOBALS['jul_views_path']}/private.php>You have $numnew new private message$ssss</a> -- $lastmsg</td></tr>";
		}
	}

  // Pass on some PHP variables to JS.
	$base_json = json_encode($GLOBALS['jul_base_dir']);
	$views_json = json_encode($GLOBALS['jul_views_path']);
	$settings_json = json_encode($GLOBALS['jul_settings']);
	$GLOBALS['jul_js_vars'] = "
	<script>
	window.jul_base_dir = {$base_json};
	window.jul_views_path = {$views_json};
	window.jul_settings = {$settings_json};
	</script>
	";

	$jscripts = '';
	if ($GLOBALS['jul_settings']['display_ikachan']) { // Ikachan! :D!
		//$ikachan = 'images/ikachan/vikingikachan.png';
		//$ikachan = 'images/sankachan.png';
		//$ikachan = 'images/ikamad.png';
		$ikachan = 'images/squid.png';

		$ikaquote = 'Capturing turf before it was cool';
		//$ikaquote = 'Someone stole my hat!';
		//$ikaquote = 'If you don\'t like Christmas music, well... it\'s time to break out the earplugs.';
		//$ikaquote = 'This viking helmet is stuck on my head!';
		//$ikaquote = 'Searching for hats to wear!  If you find any, please let me know...';
		//$ikaquote = 'What idiot thought celebrating a holiday five months late was a good idea?';
		//$ikaquote = 'Back to being a fixture now, please stop bitching.';
		//$ikaquote = 'I just want to let you know that you are getting coal this year. You deserve it.';

		$yyy = "<img id='f_ikachan' src='$ikachan' style=\"z-index: 999999; position: fixed; left: ". mt_rand(0,100) ."%; top: ". mt_rand(0,100) ."%;\" title=\"$ikaquote\">";
	}

	/*if ($_GET['w']) {
		$yyy	= "<img src=images/wave/squid.png style=\"position: fixed; left: ". mt_rand(0,100) ."%; top: ". mt_rand(0,100) ."%;\" title=\"Ikachaaaan!\">";
		$yyy	.= "<img src=images/wave/cheepcheep.png style=\"position: fixed; left: ". mt_rand(0,100) ."%; top: ". mt_rand(0,100) ."%;\" title=\"cheep tricks\">";
		$yyy .= "<img src=images/wave/chest.png style=\"position: fixed; right: 20px; bottom: 0px;\" title=\"1\">";

		for ($xxx = rand(0,5); $xxx < 20; $xxx++) {
			$yyy .= "<img src=images/wave/seaweed.png style=\"position: fixed; left: ". mt_rand(0,100) ."%; bottom: -". mt_rand(24,72) ."px;\" title=\"weed\">";
		}
	}*/

	$dispviews = $views;
//	if (($views % 1000000 >= 999000) && ($views % 1000000 < 999990))
//		$dispviews = substr((string)$views, 0, -3) . "???";

	// :shepicide:
	$body="<body>";

	if (!isset($meta)) {
		$meta	= array();
	}

	$metatag = '';

	if (filter_bool($meta['noindex']))
		$metatag .= "<meta name=\"robots\" content=\"noindex,follow\" />";

	if (isset($meta['description']))
		$metatag .= "<meta name=\"description\" content=\"{$meta['description']}\" />";

	if (isset($meta['canonical'])) {
		$metatag	.= "<link rel='canonical' href='{$meta['canonical']}'>";
	}

	$header1="<html><head><meta http-equiv='Content-type' content='text/html; charset=utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>$windowtitle</title>
	{$GLOBALS['jul_js_vars']}
	$metatag
	<link rel=\"shortcut icon\" href=\"/images/favicons/favicon". (!$x_hacks['host'] ? rand(1,8) ."" : "" ) .".ico\" type=\"image/x-icon\">
	$css
	</head>
	$body
	$yyy
	<center>
	 $tblstart
	  <form action='{$GLOBALS['jul_views_path']}/login.php' method='post' name='logout'><input type='hidden' name='action' value='logout'></form>
	  <td class='tbl tdbg1 center' colspan=3>{$GLOBALS['jul_settings']['board_title']}";
  $header2="
	  ". (!$x_hacks['smallbrowse'] ? "
	  </td><tr>
		  <td width='120px' class='tbl tdbg2 center fonts'><nobr>Views: $dispviews<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=120 height=1></td>
		  <td width='100%' class='tbl tdbg2 center fonts'>$headlinks2</td>
		  <td width='120px' class='tbl tdbg2 center fonts'><nobr>".  date($dateformat,ctime()+$tzoff) ."<br><img src={$GLOBALS['jul_base_dir']}/images/_.gif width=120 height=1><tr>"
		: "<br>$dispviews views, ". date($dateformat,ctime()+$tzoff) ."
		  </td><tr>
			<td width=100% class='tbl tdbg2 center fonts' colspan=3>$headlinks2</td><tr>") ."
	  <td colspan=3 class='tbl tdbg1 center fonts'>$race
	  $privatebox
	 $tblend
	</center>";

	$headlinks = "$smallfont<br>$headlinks";

	function makeheader($header1,$headlinks,$header2) {
		global $loguser, $PHP_SELF;
		$header	= $header1.$headlinks.$header2;
		if (!$loguser['id'] && strpos($PHP_SELF, "index.php") === false) {
			$header .= adbox() ."<br>";
		}
		return $header;
	}

	$ref=filter_string($_SERVER['HTTP_REFERER']);
	$url=getenv('SCRIPT_URL');

	if(!$url) $url=str_replace('/etc/board','',getenv('SCRIPT_NAME'));
	$q=getenv('QUERY_STRING');

	if($q) $url.="?$q";

	if($ref && substr($ref,7,7)!="jul.rus") $sql->query("INSERT INTO referer (time,url,ref,ip) VALUES (". ctime() .", '".addslashes($url)."', '".addslashes($ref)."', '". $_SERVER['REMOTE_ADDR'] ."')");

	$sql->query("DELETE FROM guests WHERE ip='$userip' OR date<".(ctime()-300));

	if($log) {
		/*
			$ulastip=mysql_result(mysql_query("SELECT lastip FROM users WHERE id=$loguserid"),0,0);
			$aol1=(substr($userip,0,7)=='152.163' or substr($userip,0,7)=='205.188' or substr($userip,0,6)=='64.12.' or substr($userip,0,6)=='195.93' or substr($userip,0,6)=='198.81');
			$aol2=(substr($ulastip,0,7)=='152.163' or substr($ulastip,0,7)=='205.188' or substr($ulastip,0,6)=='64.12.' or substr($ulastip,0,6)=='195.93' or substr($ulastip,0,6)=='198.81');
			if($userip!=$ulastip && !($aol1 && $aol2)){
			$fpnt=fopen('ipchanges.log', 'a');
			$r=fputs($fpnt, "User $loguserid IP changed from $ulastip to $userip, on ".date($dateformat,ctime())."
		");
			$r=fclose($fpnt);
			}
		*/
		//if ($loguserid != 3 && $loguserid != 2)
		if (($loguser['powerlevel'] <= 5) && (!defined("IS_AJAX_REQUEST") || !IS_AJAX_REQUEST)) {
			$influencelv=calclvl(calcexp($loguser['posts'],(ctime()-$loguser['regdate'])/86400));

      // Alart #defcon?
			if ($loguser['lastip'] != $_SERVER['REMOTE_ADDR']) {
				$ip1 = explode(".", $loguser['lastip']);
				$ip2 = explode(".", $_SERVER['REMOTE_ADDR']);
				for ($diff = 0; $diff < 3; ++$diff)
					if ($ip1[$diff] != $ip2[$diff]) break;
				if ($diff == 0) $color = xk(4);
				else            $color = xk(8);
				$diff = "/".($diff+1)*8;

				xk_ircsend("102|". xk(7) ."User $loguser[name] (id $loguserid) changed from IP ". xk(8) . $loguser['lastip'] . xk(7) ." to ". xk(8) . $_SERVER['REMOTE_ADDR'] .xk(7). " ({$color}{$diff}" .xk(7). ")");
			}

			$sql->query("UPDATE users SET lastactivity=".ctime().",lastip='$userip',lasturl='".addslashes($url)."',lastforum=0,`influence`='$influencelv' WHERE id=$loguserid");
		}

	} else {
		$sql->query("INSERT INTO guests (ip,date,useragent,lasturl) VALUES ('$userip',".ctime().",'".addslashes($_SERVER['HTTP_USER_AGENT']) ."','". addslashes($url) ."')");
	}




	$header=makeheader($header1,$headlinks,$header2);

	$footer="	</textarea></form></embed></noembed></noscript></noembed></embed></table></table>
<br>". 	($loguser['id'] && strpos($PHP_SELF, "index.php") === false ? adbox() ."<br>" : "") ."
<center>

<!--
<img src='{$GLOBALS['jul_views_path']}/adnonsense.php?m=d' title='generous donations to the first national bank of bad jokes and other dumb crap people post' style='margin-left: 44px;'><br>
<img src='{$GLOBALS['jul_views_path']}/adnonsense.php' title='hotpod fund' style='margin: 0 22px;'><br>
<img src='{$GLOBALS['jul_views_path']}/adnonsense.php?m=v' title='VPS slushie fund' style='margin-right: 44px;'>
-->
<br>
	$smallfont
	<br><br><a href={$GLOBALS['jul_settings']['site_url']}>{$GLOBALS['jul_settings']['site_name']}</a>
	<br>". filter_string($affiliatelinks) ."
	<br>
	<table cellpadding=0 border=0 cellspacing=2><tr>
		<td>
			<img src={$GLOBALS['jul_base_dir']}/images/poweredbyacmlm.gif>
		</td>
		<td>
			{$smallfont}
			Acmlmboard - <a href='https://github.com/Xkeeper0/jul'>". (file_exists('version.txt') ? file_get_contents("version.txt") : shell_exec("git log --format='commit %h [%ad]' --date='short' -n 1")) ."</a>
			<br>&copy;2000-". date("Y") ." Acmlm, Xkeeper, Inuyasha, et al.
			</font>
		</td>
	</tr></table>
	". ($x_hacks['mmdeath'] >= 0 ? "<div style='position: absolute; top: -100px; left: -100px;'>Hidden preloader for doom numbers:
		<img src='numgfx/death/0.png'> <img src='numgfx/death/1.png'> <img src='numgfx/death/2.png'> <img src='numgfx/death/3.png'> <img src='numgfx/death/4.png'> <img src='numgfx/death/5.png'> <img src='numgfx/death/6.png'> <img src='numgfx/death/7.png'> <img src='numgfx/death/8.png'> <img src='numgfx/death/9.png'>" : "") ."
<!-- Piwik -->
<script type=\"text/javascript\">
var pkBaseURL = ((\"https:\" == document.location.protocol) ? \"https://stats.tcrf.net/\" : \"http://stats.tcrf.net/\");
document.write(unescape(\"%3Cscript src='\" + pkBaseURL + \"piwik.js' type='text/javascript'%3E%3C/script%3E\"));
</script><script type=\"text/javascript\">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + \"piwik.php\", 4);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src=\"http://stats.tcrf.net/piwik.php?idsite=4\" style=\"border:0\" alt=\"\" /></p></noscript>
<!-- End Piwik Tag -->
<!--<script type=\"text/javascript\" src=\"http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.6.min.js\"></script>
<script type=\"text/javascript\" src=\"js/useful.js\"></script> -->

</body></html>
  ";
	if($ipbanned) {
		if ($loguser['title'] == "Banned; account hijacked. Contact admin via PM to change it.") {
			$reason	= "Your account was hijacked; please contact Xkeeper to reset your password and unban your account.";
		} elseif ($loguser['title']) {
			$reason	= "Ban reason: ". $loguser['title'] ."<br>If you think have been banned in error, please contact Xkeeper.";
		} else {
			$reason	= $sql->resultq("SELECT `reason` FROM ipbans WHERE $checkips",0,0);
			$reason	= ($reason ? "Reason: $reason" : "<i>(No reason given)</i>");
		}
		die("$header<br>$tblstart$tccell1>
		You are banned from this board.
		<br>". $reason ."
		<br>
		<br>If you think you have been banned in error, please contact the administrator:
		<br>E-mail: xkeeper@gmail.com
		$tblend$footer");
	}
	if($torbanned) die("$header<br>$tblstart$tccell1>
	You appear to be using a Tor proxy. Due to abuse, Tor usage is forbidden.
	<br>If you have been banned in error, please contact Xkeeper.
	<br>
	<br>E-mail: xkeeper@gmail.com
	$tblend$footer");
