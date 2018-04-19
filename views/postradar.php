<?php
  require_once '../lib/function.php';

  $windowtitle = "Editing Post Radar";

  if (!$log) {
		require_once '../lib/layout.php';

		print "$header
		<br>$tblstart$tccell1>You must be logged in to edit your post radar.<br>
		".redirect("{$GLOBALS['jul_base_dir']}/index.php",'return to the board',0).$tblend.$footer;

		printtimedif($startingtime);
		die();
  }

  // Login confirmed from here on out
	// Changes above form to save a redirect
	if ($_POST['action'] == 'dochanges') {
		$user = $sql->resultq("SELECT name FROM users WHERE id=$loguserid");
		if ($rem) $sql->query("DELETE FROM postradar WHERE user=$loguserid and comp=". intval($rem) ."");
		if ($add) $sql->query("INSERT INTO postradar (user,comp) VALUES ($loguserid,". intval($add) .")");
		if ($submit2) {
			require_once '../lib/layout.php';

			print "$header
			<br>$tblstart$tccell1>Thank you, $user, for editing your post radar.<br>
			".redirect("{$GLOBALS['jul_base_dir']}/index.php",'return to the board',0).$tblend.$footer;

			printtimedif($startingtime);
			die();
		}
	}

	// Form
	// Include layout now so post radar on top of page is properly updated
	require_once '../lib/layout.php';

	// Deletions before additions
	$users1 = $sql->query("SELECT p.comp, u.name, u.posts FROM postradar p, users u WHERE u.id=p.comp AND user=$loguserid");

	while($user = $sql->fetch($users1)){
		$remlist.="<option value=$user[comp]>$user[name] -- $user[posts] posts";
		$idlist[] = $user['comp'];
	}
	$remlist="
		<select name=rem>
		<option value=0 selected>Do not remove anyone
		$remlist
		</select>";

	// Remove those already added
	if (count($idlist))
		$qwhere = "AND id NOT IN (". implode(",", $idlist).")";
	else $qwhere = '';

  // Additions
	$users1 = $sql->query("SELECT id,name,posts FROM users WHERE posts > 0 {$qwhere} ORDER BY name");

	while($user = $sql->fetch($users1)){
		$addlist.="<option value=$user[id]>$user[name] -- $user[posts] posts";
	}

	$addlist="
	<select name=add>
	<option value=0 selected>Do not add anyone
	$addlist
	</select>";

	$prtable="
		$tccellh>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>Add an user</td>$tccell2l>$addlist<tr>
		$tccell1><b>Remove an user</td>$tccell2l>$remlist<tr>
		$tccellh>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inph=action VALUE=dochanges>
		$inph=userpass VALUE=\"$user[password]\">
		$inps=submit1 VALUE=\"Submit and continue\">
		$inps=submit2 VALUE=\"Submit and finish\"></td></FORM>
    ";

	print "$header<br>
	<FORM ACTION={$GLOBALS['jul_views_path']}/postradar.php NAME=REPLIER METHOD=POST>
	$tblstart$prtable$tblend$footer";
	printtimedif($startingtime);
?>
