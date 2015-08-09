<?php

	header("Content-type: text/plain");
	$userid	= intval($_GET['u']);

	if (!$userid) die("No userid specified.");
	chdir("..");
	require 'lib/function.php';

	print $sql -> resultq("SELECT `posts` FROM `users` WHERE `id` = '$userid'");


