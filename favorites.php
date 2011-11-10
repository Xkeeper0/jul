<?php
	require "lib/function.php";
	require "lib/layout.php";
	if (!$loguser) {
		die("Registered users only, bud.");
	}
	
	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		$ppp = (!$log?20:$loguser["postsperpage"]);
		$tpp = (!$log?50:$loguser["threadsperpage"]);
		$min = $page*$ppp;

		$favorited = mysql_query("SELECT threads.*,u1.name AS name1,u1.sex AS sex1,u1.powerlevel AS power1,u2.name AS name2,u2.sex AS sex2,u2.powerlevel AS power2,minpower FROM threads,users AS u1,users AS u2,forums,favorites WHERE u1.id=threads.user AND u2.id=threads.lastposter AND favorites.thread=threads.id AND favorites.user=$loguserid AND forums.id=forum ORDER BY sticky DESC,lastpostdate DESC LIMIT $min,$tpp");
		
		$lastreplied = mysql_query("SELECT COUNT(threads.id) AS posted_count, threads.id, threads.title, threads.lastpostdate, posts.user, posts.thread, posts.date, posts.id FROM threads LEFT JOIN posts on posts.thread=threads.id WHERE posts.user=$loguserid GROUP BY threads.id ORDER BY posts.id DESC LIMIT $min,$tpp");
		if (IS_AJAX_REQUEST == true) {
			header("Content-Type: text/plain");
			$out = Array();
			while ($res = mysql_fetch_assoc($favorited)) {
				$threadid = '"id":"'.$res["id"].'"';
				$title    = '"title":"'.str_replace("\\","\\\\",$res["title"]).'"';
				$out[] = '{'.implode(",",Array($threadid,$title)).'}';
			}
			print "[".implode(",",$out)."]";
		} else {
			print $header;
			$TEMPLATE_VARS = array("lastreplied" => $lastreplied, "favorited" => $favorited);
			include "templates/favorites.php";
			print array_key_exists("HTTP-X-HTTP-REQUESTED-WITH", $_SERVER);
			print IS_AJAX_REQUEST==true;
			print $footer;
		}
	} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	}
	
