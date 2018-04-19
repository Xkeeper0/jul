<?php

  require_once '../lib/function.php';
  require_once '../lib/layout.php';

  if($_POST['deluser'] and $isadmin) { //($loguserid==1 or $loguserid==2)){

		foreach($_POST['deluser'] as $id => $junk) {

			$query = "SELECT id,name,posts,sex,powerlevel FROM users WHERE id=$id";
			$user2 = mysql_query($query);

			while ($user=mysql_fetch_array($user2)) {
			$id	= $user['id'];

				$name=$user[name];
				$namecolor=getnamecolor($user[sex],$user[powerlevel]);
				mysql_query("INSERT INTO `delusers` ( SELECT * FROM `users` WHERE `id` = '$id' )");
				$line="<br><br>===================<br>[Posted by <font $namecolor><b>". addslashes($name) ."</b></font>]<br>";
				$ups=mysql_query("SELECT id FROM posts WHERE user=$id");
				while($up=mysql_fetch_array($ups)) mysql_query("UPDATE posts_text SET signtext=CONCAT_WS('','$line',signtext) WHERE pid=$up[id]") or print mysql_error();
				mysql_query("UPDATE threads SET user=89 WHERE user=$id");
				mysql_query("UPDATE threads SET lastposter=89 WHERE lastposter=$id");
				mysql_query("UPDATE pmsgs SET userfrom=89 WHERE userfrom=$id");
				mysql_query("UPDATE pmsgs SET userto=89 WHERE userto=$id");
				mysql_query("UPDATE posts SET user=89,headid=0,signid=0 WHERE user=$id");
				mysql_query("UPDATE `users` SET `posts` = -1 * (SELECT COUNT(*) FROM `posts` WHERE `user` = '89') WHERE `id` = '89'");
				mysql_query("DELETE FROM userratings WHERE userrated=$id OR userfrom=$id");
				mysql_query("DELETE FROM pollvotes WHERE user=$id");
				mysql_query("DELETE FROM users WHERE id=$id");
				mysql_query("DELETE FROM users_rpg WHERE uid=$id");

			$delusertext	.= "\r\n<tr>$tccell1 width=120>$id</td>$tccell2l><font $namecolor><b>$user[name]</b></font></td></tr>";
			$delusercnt		++;
		  }
	  }

	$deltext	= "$tblstart
	<tr><td class='tbl tdbgc font center' colspan=2><b>$delusercnt user(s) deleted.</b></td></tr>$delusertext
	$tblend<br>";


	}


  if (!$_POST['sortpowerlevel']) $_POST['sortpowerlevel'] = "ab";
  if (!$_POST['sortord']) $_POST['sortord']	= 0;
  $powerselect[$_POST['sortpowerlevel']]	= 'selected';
  $sortsel[$_POST['sorttype']]				= 'selected';
  $ordsel[$_POST['sortord']]				= 'checked';

  print "
    $header<br>

$deltext

	<form action='{$GLOBALS['jul_views_path']}/del.php' method=post>
	$tblstart
		<tr>$tccellh colspan=2>Sort Options</td></tr>
		<tr>$tccell1 width=300><b>User Search:</b></td>
			$tccell2l>$inpt=searchname size=30 maxlength=15 value='". $_POST['searchname'] ."'></td></tr>
		<tr>$tccell1 width=300><b>IP Search:</b></td>
			$tccell2l>$inpt=searchip size=30 maxlength=15 value='". $_POST['searchip'] ."'></td></tr>
		<tr>$tccell1 width=300><b>Show users with less than:</b></td>
			$tccell2l>$inpt=maxposts size=15 maxlength=9 value='". $_POST['maxposts'] ."'> posts</td></tr>
		<tr>$tccell1><b>Powerlevel:</b></td>
			$tccell2l><select name='sortpowerlevel'>
				<option value='aa' ". $powerselect['aa'] .">* Any powerlevel</option>
				<option value='ab' ". $powerselect['ab'] .">* All banned</option>
				<option value='s3' ". $powerselect['s3'] .">Administrator</option>
				<option value='s2' ". $powerselect['s2'] .">Moderator</option>
				<option value='s1' ". $powerselect['s1'] .">Local Moderator</option>
				<option value='s0' ". $powerselect['s0'] .">Normal User</option>
				<option value='s-1' ". $powerselect['s-1'] .">Banned</option>
				<option value='s-2' ". $powerselect['s-2'] .">Permabanned</option>
			</select></td></tr>
		<tr>$tccell1 width=300><b>Sort by:</b></td>
			$tccell2l>
			<select name='sorttype'>
				<option value='0' $sortsel[0]> Last activity </option>
				<option value='1' $sortsel[1]> Register date </option>
				<option value='2' $sortsel[2]> Posts </option>
				<option value='3' $sortsel[3]> Powerlevel </option>
				<option value='4' $sortsel[4]> IP address</option>
			</select>,
				$radio=sortord value='0' $ordsel[0]> Descending&nbsp;&nbsp;
				$radio=sortord value='1' $ordsel[1]> Ascending
			</td></tr>
		<tr>$tccell1>&nbsp;</td>$tccell2l><input type=submit value='Apply filters'></td></tr>
	$tblend
	</form>
  ";


//	print_r($_POST);
	$sqlquery	= "";

	if ($_POST['maxposts'])
		$sqlquery	= "`posts` <= '". $_POST['maxposts'] ."'";

	if ($_POST['searchip']) {
		if ($sqlquery)	$sqlquery	.= " AND ";
		$sqlquery	.= "`lastip` LIKE '". $_POST['searchip'] ."%'";
	}

	if ($_POST['searchname']) {
		if ($sqlquery)	$sqlquery	.= " AND ";
		$sqlquery	.= "`name` LIKE '%". $_POST['searchname'] ."%'";
	}

	if ($_POST['sortpowerlevel'] != "aa") {
		if ($sqlquery)	$sqlquery	.= " AND ";

		if ($_POST['sortpowerlevel'] == "ab")
			$sqlquery	.= "`powerlevel` < '0'";
		else
			$sqlquery	.= "`powerlevel` = '". str_replace("s", "", $_POST['sortpowerlevel']) ."'";
	}

	switch ($_POST['sorttype']) {
		case 0:
			$sortfield	= "lastactivity";
			break;
		case 1:
			$sortfield	= "regdate";
			break;
		case 2:
			$sortfield	= "posts";
			break;
		case 3:
			$sortfield	= "powerlevel";
			break;
		case 4:
			$sortfield	= "lastip";
			break;
		default:
			$sortfield	= "lastactivity";
			break;
	}

	if ($_POST['sortord'] == 0) $sortorder	= "DESC";
		else $sortorder	= "ASC";
	if ($sqlquery) $sqlquery	= "WHERE ". $sqlquery;
	$sqlquery	.= " ORDER BY `$sortfield` $sortorder";


/*  if(!$p) $p=0;
  if ($ip) $q = "lastip = '$ip'";
	else $q = "posts=$p";
*/
	$users		= mysql_query("SELECT * FROM `users` $sqlquery");
	$usercount	= mysql_num_rows($users);
  print "
	<form action='{$GLOBALS['jul_views_path']}/del.php' method=post>
    $tblstart
	<tr><td class='tbl tdbgc font center' colspan=8><b>$usercount user(s) found.</b></td></tr>
	<tr>
	$tccellh>&nbsp;</td>
	$tccellh>Name</td>
	$tccellh>Posts</td>
	$tccellh>Regdate</td>
	$tccellh>Last post</td>
	$tccellh width=200>Last activity</td>
	$tccellh>Last URL</td>
	$tccellh>IP
  ";
  while($user=mysql_fetch_array($users)){
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    $lastpost='-';
    if($user['lastposttime']) $lastpost		= date($dateshort, $user['lastposttime'] - $tzoff);
		else $lastpost		= '-';
    if($user['lastactivity'] != $user['regdate']) $lastactivity	= date($dateformat, $user['lastactivity'] - $tzoff);
		else $lastactivity	= '-';
    if($user['regdate']) $regdate			= date($dateshort, $user['regdate'] - $tzoff);
		else $regdate		= '-';

	$textid	= str_pad($user['id'], 4, "x", STR_PAD_LEFT);
	$textid	= str_replace("x", "<font color=#606060>0</font>", $textid);
	$textid	= str_replace("</font><font color=#606060>", "", $textid);

    print "
      <tr>
      $tccell1><input type=checkbox name=deluser[". $user['id'] ."] value='1'>
      $tccell2l>$textid - <a href={$GLOBALS['jul_views_path']}/profile.php?id=$user[id]><font $namecolor>$user[name]
	  $tccell1 width=0>$user[posts]
	$tccell1 width=120>$regdate
	$tccell1 width=120>$lastpost
	$tccell1 width=120>$lastactivity
      $tccell2l>$user[lasturl]&nbsp;
      $tccell2>$user[lastip]
    ";
  }

  echo "		<tr>$tccell1l colspan=8>$inps=submit value=Submit></td></tr>";
  print $tblend ."</form>". $footer;
  printtimedif($startingtime);
?>
