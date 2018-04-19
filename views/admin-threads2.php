<?php

  $windowtitle	= "Thread Repair System II";

  require_once '../lib/function.php';
  require_once '../lib/layout.php';

  print "$header<br>";

  if (!$isadmin) {

	print "
		$tblstart
			$tccell1>This feature is restricted.</td>
		$tblend

	$footer
	";
	printtimedif($startingtime);
	die();
  }

	print adminlinkbar("{$GLOBALS['jul_views_path']}/admin-threads2.php");

	if (!$_POST['run']) {
		print "<form action=\"{$GLOBALS['jul_views_path']}/admin-threads2.php\" method=\"post\">
			$tblstart
				<tr>$tccellh>Thread Repair System II</td></tr>
				<tr>$tccell1>&nbsp;
					<br>This page is intended to repair threads with broken 'last reply' times/users.
					<br>This problem causes bumped threads that shouldn't be, especially with badly deleted posts.
					<br>&nbsp;
					<br>$inps=\"run\" value=\"Start\">
					<br>&nbsp;
				</td></tr>
			$tblend
			</form>
		";
	} else {

		print "
			$tblstart
				<tr>$tccellh>Thread Repair System II</td></tr>
				<tr>$tccell1>Now running.
				</td></tr>
			$tblend
		<br>
		$tblstart
			<tr>
				$tccellh>id#</td>
				$tccellh>Name</td>
				$tccellh>Reported Date</td>
				$tccellh>Real Date</td>
				$tccellh>Difference</td>
				$tccellh>Status</td>
			</tr>
		";



	$q	= "SELECT `threads`.`id`, `threads`.`title` , `threads`.`lastpostdate` , `posts`.`date` as realdate FROM `threads` LEFT JOIN (SELECT MAX(`date`) as `date`, `thread` FROM `posts` GROUP BY `thread`) as `posts`  ON `posts`.`thread` = `threads`.`id` ORDER BY `threads`.`id` DESC";
	$sql	= mysql_query($q) or die(mysql_error());

	$count	= "";
	while ($data = mysql_fetch_array($sql, MYSQL_ASSOC)) {

		$status	= "";

		if ($data['lastpostdate'] != $data['realdate']) {

			if ($data['lastpostdate'] == "0" && $data['realdate'] === null) {
				$status	= "<font color=#ff8888>Broken thread</font>";
			} else {

				$userd	= mysql_fetch_array(mysql_query("SELECT `date`, `user` FROM `posts` WHERE `thread` = '". $data['id'] ."' ORDER BY `date` DESC LIMIT 1"), MYSQL_ASSOC);
				$status	= mysql_query("UPDATE `threads` SET `lastposter` = '". $userd['user'] ."', `lastpostdate` = '". $userd['date'] ."' WHERE `id` = '". $data['id'] ."'") or "<font color=#ff0000>Error</font>: ". mysql_error();
				if ($status == 1) $status	= "<font color=#80ff80>Updated</font>";
				$count++;
			}
		}

		if ($status) {

			print "
			<tr>
				$tccell1>". $data['id'] ."</td>
				$tccell2l><a href=\"{$GLOBALS['jul_views_path']}/thread.php?id=". $data['id'] ."\">". $data['title'] ."</a></td>
				$tccell1>". ($data['lastpostdate'] ? date($dateformat, $data['lastpostdate'] + $tzoff) : "-") ."</td>
				$tccell1>". ($data['realdate'] ? date($dateformat, $data['realdate'] + $tzoff) : "-") ."</td>
				$tccell1>". timeunits2($data['lastpostdate'] - $data['realdate']) ."</td>
				$tccell2l>$status</td>
			</tr>";
		}
	}

	if ($count) {
		print "<tr>$tccellc colspan=6>$count thread". ($count != 1 ? "s" : "") ." updated.</td></tr>";
	} else {
		print "<tr>$tccellc colspan=6>Nothing to repair.</td></tr>";
	}
 }


  print "$tblend
	$footer
	";
  printtimedif($startingtime);
