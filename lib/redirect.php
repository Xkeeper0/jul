<?php

require_once 'function.php';

// Checks whether $_GET has a certain variable set, then redirects if so.
// Used for short redirects, e.g. ?u=1234 to profile.php?id=1234.
function short_redir($v, $page, $page_var) {
	if (isset($_GET[$v]) && $_GET[$v]) {
		header("Location: {$GLOBALS['jul_views_path']}/{$page}?{$page_var}=".$_GET[$v]);
		exit;
	}
}

// Redirect u=1234 to user profiles.
short_redir('u', 'profile.php', 'id');
// Redirect p=1234 to a post URL.
short_redir('p', 'thread.php', 'pid');
// Redirect t=1234 to a thread URL.
short_redir('t', 'thread.php', 'id');
