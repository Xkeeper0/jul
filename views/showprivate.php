<?php
	require_once '../lib/function.php';
	if (!$id)
		return header("Location: {$GLOBALS['jul_views_path']}/private.php");
	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Private Messages";
	$meta['noindex'] = true;

	$msg = $sql->fetchq("SELECT * FROM pmsgs,pmsgs_text WHERE id=$id AND id=pid");

	if (!$log) {
		require_once '../lib/layout.php';
		errorpage("Couldn't get the private message.  You are not logged in.",'log in (then try again)',"{$GLOBALS['jul_views_path']}/login.php");
	}
	elseif (!$msg || (($msg['userto'] != $loguserid && $msg['userfrom'] != $loguserid) && !$isadmin)) {
		require_once '../lib/layout.php';
		errorpage("Couldn't get the private message.  It either doesn't exist or was not sent to you.",'your private message inbox',"{$GLOBALS['jul_views_path']}/private.php");
	}

	if ($isadmin && $msg['userto'] != $loguserid)
		$pmlinktext = "<a href='{$GLOBALS['jul_views_path']}/private.php?id=$msg[userto]'>".$sql->resultq("SELECT name FROM users WHERE id=$msg[userto]") . '\'s private messages</a>';
	else $pmlinktext = "<a href={$GLOBALS['jul_views_path']}/private.php>Private messages</a>";

	$user = $sql->fetchq("SELECT * FROM users WHERE id=$msg[userfrom]");
	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Private Messages: $msg[title]";
	require_once '../lib/layout.php';

	$top = "<table width=100%><td align=left>$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - <a href={$GLOBALS['jul_views_path']}/private.php>$pmlinktext</a> - $msg[title]</table>";
	if ($msg['userto'] == $loguserid)
		$sql->query("UPDATE pmsgs SET msgread=1 WHERE id=$id");

	loadtlayout();
	$post = $user;
	$post['uid']    = $user['id'];
	$post['date']   = $msg['date'];
	$post['headid'] = $msg['headid'];
	$post['signid'] = $msg['signid'];
	$post['text']   = $msg['text'];
	$post['tagval'] = $msg['tagval'];
	if($loguser['viewsig']==2){
		$post['headtext'] = $user['postheader'];
		$post['signtext'] = $user['signature'];
	}
	else {
		$post['headtext'] = $msg['headtext'];
		$post['signtext'] = $msg['signtext'];
	}

	if ($msg['userto'] == $loguserid)
		$quote = "<a href={$GLOBALS['jul_views_path']}/sendprivate.php?id=$id>Reply</a>";
	if ($isadmin)
		$ip = (($quote) ? ' | ' : '') . "IP: <a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip=$msg[ip]'>$msg[ip]</a>";

	print $header.$top.$tblstart.threadpost($post,1).$tblend.$top.$footer;
	printtimedif($startingtime);
?>
