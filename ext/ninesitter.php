<?php

	chdir("../");
//	require_once 'lib/config.php';
	require_once 'lib/function.php';
//	require_once 'lib/config.php';
	header("Content-type: text/plain");

	@mysql_connect($GLOBALS['jul_sql_settings']['host'], $GLOBALS['jul_sql_settings']['user'], $GLOBALS['jul_sql_settings']['pass']) or
		die('Database error.');
	@mysql_select_db($sql_settings['name']) or
		die('Database error.');

	$threads	= explode(",", $_GET['data']);
	$ta			= array();
	foreach($threads as $thread) {
		$thread	= intval($thread);
		if ($thread && count($ta) < 5) $ta[]	= $thread;
		else die("Error!!");
	}

	$out	= null;

	if ($ta) {

		$query	= "SELECT `id`, `forum`, `title` FROM `threads` WHERE `id` IN (". implode(", ", $ta) .")";
		$sql	= mysql_query($query);
		while ($thread = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$minpower	= mysql_result(mysql_query("SELECT `minpower` FROM `forums` WHERE `id` = '". $thread['forum'] ."'"), 0);
			if ($minpower <= 0) {

				$dat	= mysql_query("SELECT `p`.`id`, `p`.`date`, `u`.`name` FROM `posts` `p` LEFT JOIN `users` `u` ON `u`.`id` = `p`.`user` WHERE `p`.`thread` = '". $thread['id'] ."' ORDER BY `p`.`date` DESC LIMIT 5") or print mysql_error();

				while($info = mysql_fetch_array($dat, MYSQL_ASSOC)) {
					$out	.= "$info[id]|$thread[id]|$info[name]|$thread[title]|". date("m-d-y H:i:s", $info['date']) ."\r\n";
				}
			}
		}
	} else die("Error!!");

	print "OK\r\n$out";

?>
