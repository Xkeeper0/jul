<?php
	require_once '../lib/function.php';
	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Private Messages";
	require_once '../lib/layout.php';

	if (!$log)
		errorpage("You need to be logged in to read your private messages.", 'log in (then try again)', "{$GLOBALS['jul_views_path']}/login.php");

	// Viewing someone else?
	$u = $loguserid;
	if ($isadmin && $id) {
		$u = $id;
		$idparam = "id=$id&";
	}

	// Viewing sent messages?
	$to   = 'to';
	$from = 'from';
	if ($view == 'sent') {
		$to   = 'from';
		$from = 'to';
		$viewparam = 'view=sent&';
	}

	if(!$ppp)
		$ppp=50;
	if(!$page)
		$page=1;

	$pmin=($page-1)*$ppp;
	$msgtotal=$sql->resultq("SELECT count(*) FROM pmsgs WHERE user$to=$u");
	$pagelinks='Pages:';
	$p=0;
	for($i=0; $i<$msgtotal; $i+=$ppp) {
		$p++;
		if($p==$page)
			$pagelinks.=" $p";
		else
			$pagelinks.=" <a href={$GLOBALS['jul_views_path']}/private.php?{$idparam}{$viewparam}page={$p}>{$p}</a>";
	}

	// 1252378129
	$pmsgs   = $sql->query("SELECT p.id,user$from uid,date,t.title,msgread,name,sex,powerlevel,aka
		FROM pmsgs p,pmsgs_text t,users u
		WHERE user$to=$u
		AND p.id=pid
		AND user$from=u.id "
		.($loguser['id'] == 175 ? "AND p.id > 8387 " : "")
		."ORDER BY " .($loguser['id'] == 175 ? "user$from DESC, " : "msgread ASC, ")
		."p.id DESC
		LIMIT $pmin,$ppp
	");

	$from[0] = strtoupper($from[0]);

	if(!$view)
		$viewlink="<a href={$GLOBALS['jul_views_path']}/private.php?{$idparam}view=sent>View sent messages</a>";
	else
		$viewlink="<a href={$GLOBALS['jul_views_path']}/private.php?{$idparam}>View received messages</a>";

	print "$header
		<table width=100%><td>$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - "
			.(($u != $loguserid) ? $sql->resultq("SELECT `name` FROM `users` WHERE `id` = '$u'")."'s private messages" : "Private messages")
			." - "
			.((!$view) ? 'Inbox' : 'Outbox').": $msgtotal</td>
		<td align=right>$smallfont$viewlink | <a href={$GLOBALS['jul_views_path']}/sendprivate.php>Send new message</a></table>
		$tblstart<tr>
		$tccellh width=50>&nbsp</td>
		$tccellh>Subject</td>
		$tccellh width=15%>$from</td>
		$tccellh width=180>Sent on</td></tr>
	";

	while($pmsg = $sql->fetch($pmsgs)) {
		$new       = ($pmsg['msgread']?'&nbsp;':$statusicons['new']);
		$namecolor = getuserlink($pmsg, array('id'=>'uid'));
		print "
			<tr style='height:20px;'>
			$tccell1>$new</td>
			$tccell2l><a href={$GLOBALS['jul_views_path']}/showprivate.php?id=$pmsg[id]>$pmsg[title]</td>
			$tccell2>$namecolor</td>
			$tccell2>".date($dateformat,$pmsg['date']+$tzoff)."
			</tr>
		";
	}

	print "$tblend$smallfont$pagelinks$footer";
	printtimedif($startingtime);
?>
