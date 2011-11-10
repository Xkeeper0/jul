<?php

	require 'lib/function.php';

	$windowtitle	= "Jul Contributor iSuckatacronyms (or Score)";
	require 'lib/layout.php';


	
	print $header;

//	$users=mysql_query("SELECT u.id,u.name,u.sex,u.powerlevel,COUNT(*) AS cnt FROM users AS u,posts AS p WHERE p.user=u.id AND p.date>=$dd AND p.date<$dd2 AND u.powerlevel >= 0 GROUP BY u.id ORDER BY cnt DESC");

	$n	= date("n-j-y", ctime());
	$d	= explode("-", $n);

	$time1	= mktime(0, 0, 0, $d[0], $d[1], $d[2]);
	print $time1;

	$users	= $sql -> query("SELECT u.`id`, u.`name`, u.`sex`, u.`powerlevel`, COUNT(*) AS cnt FROM `users` u, `posts` p WHERE p.user = u.id AND p.date >= '$time1' AND p.date < '". ($time1 + 86400) ."' GROUP BY u.id ORDER BY cnt DESC");
	print mysql_error();

	while ($u = $sql -> fetch($users)) {
		print $u['name'] ."<br>";
	}