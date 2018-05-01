<?php

	chdir("../");
//	require 'lib/config.php';
	require 'lib/function.php';
//	require 'lib/config.php';
	header("Content-type: text/plain");

	$sql->connect($sqlhost, $sqluser, $sqlpass, $sqldb) or 
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
		$sql	= $sql->query($query);
		while ($thread = $sql->fetch($sql, PDO::FETCH_ASSOC)) {
			$minpower	= $sql->resultq("SELECT `minpower` FROM `forums` WHERE `id` = '". $thread['forum'] ."'");
			if ($minpower <= 0) {

				$dat	= $sql->query("SELECT `p`.`id`, `p`.`date`, `u`.`name` FROM `posts` `p` LEFT JOIN `users` `u` ON `u`.`id` = `p`.`user` WHERE `p`.`thread` = '". $thread['id'] ."' ORDER BY `p`.`date` DESC LIMIT 5") or print $sql->error();

				while($info = $sql->fetch($dat, PDO::FETCH_ASSOC)) {
					$out	.= "$info[id]|$thread[id]|$info[name]|$thread[title]|". date("m-d-y H:i:s", $info['date']) ."\r\n";
				}
			}
		}
	} else die("Error!!");

	print "OK\r\n$out";

?>