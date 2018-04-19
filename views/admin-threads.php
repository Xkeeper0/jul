<?php

  $windowtitle	= "Thread Repair System";

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


	print adminlinkbar("{$GLOBALS['jul_views_path']}/admin-threads.php");

	if (!$_POST['run']) {
		print "<form action=\"{$GLOBALS['jul_views_path']}/admin-threads.php\" method=\"post\">
			$tblstart
				<tr>$tccellh>Thread Repair System</td></tr>
				<tr>$tccell1>&nbsp;
					<br>This page is intended to repair threads with broken reply counts. Please don't flood it with requests.
					<br>This problem causes \"phantom pages\" (e.g., too few or too many pages displayed).
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
				<tr>$tccellh>Thread Repair System</td></tr>
				<tr>$tccell1>Now running.
				</td></tr>
			$tblend
		<br>
		$tblstart
			<tr>
				$tccellh>id#</td>
				$tccellh>Name</td>
				$tccellh>Reports</td>
				$tccellh>Real</td>
				$tccellh>Err</td>
				$tccellh>Status</td>
			</tr>
		";

	$q	= "SELECT `posts`.`thread`, (COUNT(`posts`.`id`)) AS 'real', ((CAST(COUNT(`posts`.`id`) AS SIGNED) - 1) - CAST(`threads`.`replies` AS SIGNED)) AS 'offset', `threads`.`replies`, `threads`.`title` AS `threadname`  FROM `posts` LEFT JOIN `threads` ON `posts`.`thread` = `threads`.`id` GROUP BY `thread` HAVING `offset` <> 0 OR `offset` IS NULL ORDER BY ISNULL(`threadname`) ASC, `thread` DESC";
	$sql	= mysql_query($q) or die(mysql_error());

	$count	= "";
	while ($data = mysql_fetch_array($sql, MYSQL_ASSOC)) {

		$status	= "";

		if ($data['offset'] != 0 || $data['offset'] === null) {

			if ($data['replies'] === null) {
				$status			= "<font color=\"#ff8080\">Invalid thread</font>";
			} else {
				$status	= mysql_query("UPDATE `threads` SET `replies` = '". ($data['real'] - 1) ."' WHERE `id` = '". $data['thread'] ."'") or "<font color=#ff0000>Error</font>: ". mysql_error();
				if ($status == 1) $status	= "<font color=#80ff80>Updated</font>";
				$count++;
			}

			print "
			<tr>
				$tccell1><a href=\"{$GLOBALS['jul_views_path']}/thread.php?id=". $data['thread'] ."\">". $data['thread'] ."</a></td>
				$tccell2l><a href=\"{$GLOBALS['jul_views_path']}/thread.php?id=". $data['thread'] ."\">". ($data['threadname'] !== null ? $data['threadname'] : "<em>(Deleted thread)</em>") ."</a></td>
				$tccell1>". ($data['replies'] !== null ? $data['replies'] + 1 : "&mdash;") ."</td>
				$tccell1>". ($data['real']) ."</td>
				$tccell2><b>". ($data['offset'] !== null ? $data['offset'] : "&mdash;") ."</b></td>
				$tccell1>$status</td>
			</tr>";

		} else {
			continue;
		}
	}

	if ($count) {
		print "<tr>$tccellc colspan=6>$count thread". ($count != 1 ? "s" : "") ." updated.</td></tr>";
	} else {

		print "		<tr>$tccell1 colspan=6>&nbsp;
					<br>No problems found.
					<br>&nbsp;
				</td></tr>";
	}
 }


  print "$tblend
	$footer
	";
  printtimedif($startingtime);
