<?php

  $windowtitle	= "Thread Repair System";

  require 'lib/function.php';
  require 'lib/layout.php';

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


	print adminlinkbar("admin-threads.php");

	if (!$_POST['run']) {
		print "<form action=\"admin-threads.php\" method=\"post\">  
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

	$q	= "SELECT `posts`.`thread`, (COUNT(`posts`.`id`)) AS 'real', ((COUNT(`posts`.`id`) - 1) - `threads`.`replies`) AS 'offset', `threads`.`replies`, `threads`.`title` AS `threadname`  FROM `posts` LEFT JOIN `threads` ON `posts`.`thread` = `threads`.`id` GROUP BY `thread` ORDER BY `offset` DESC";
	$sql	= mysql_query($q) or die(mysql_error());

	$count	= "";
	while ($data = mysql_fetch_array($sql, MYSQL_ASSOC)) {

		$status	= "";

		if ($data['offset'] != 0) {

			if ($data['offset'] >= 10000000) { 
				$data['offset']	= ($data['real'] - 1) - $data['replies'];
//				$status			= "<font color=\"#ff8080\">First post missing or otherwise broken</font>";
//				$data['offset']	= "&nbsp;";
			}

			if (!$status) {
				$status	= mysql_query("UPDATE `threads` SET `replies` = '". ($data['real'] - 1) ."' WHERE `id` = '". $data['thread'] ."'") or "<font color=#ff0000>Error</font>: ". mysql_error();
				if ($status == 1) $status	= "<font color=#80ff80>Updated</font>";
//				$status	= "Not updated";
				$count++;
			}

			print "
			<tr>
				$tccell1>". $data['thread'] ."</td>
				$tccell2l><a href=\"thread.php?id=". $data['thread'] ."\">". $data['threadname'] ."</a></td>
				$tccell1r>". $data['replies'] ."</td>
				$tccell1r>". $data['real'] ."</td>
				$tccell2r><b>". $data['offset'] ."</b></td>
				$tccell1l>$status</td>
			</tr>";		

		} else {
			break;
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
?>
