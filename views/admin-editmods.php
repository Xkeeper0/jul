<?php

require_once '../lib/function.php';
$windowtitle = "{$GLOBALS['jul_settings']['board_name']} - Forum Moderators";
require_once '../lib/layout.php';

print $header."<br>";

admincheck();
print adminlinkbar("{$GLOBALS['jul_views_path']}/admin-editmods.php");

$donotprint = false;
if ($action) {
//  print "DEBUG: Asked to ".$action." a moderator of forum: ".${$action."modforum"}." and user: ".${$action."moduser"};
	$donotprint = true;
	switch($action) {
		case "remove":
			$removemod = explode("|", $removemod);
			$removemoduser = $removemod[1];
			$removemodforum = $removemod[0];

			$sql->query("DELETE FROM forummods WHERE user='$removemoduser' AND forum='$removemodforum'");
			if(($err=mysql_error()) != "")
				print "$tblstart$tccell1> ERROR: $err.";
			else {
				$sql->query("INSERT INTO actionlog (atime, adesc, aip) VALUES (".ctime().", \"User ".$loguserid." removed mod $removemoduser from forum $removemodforum\", \"$userip\")");
				print "$tblstart$tccell1> You successfully deleted user $removemoduser from forum $removemodforum.<br>".redirect("{$GLOBALS['jul_views_path']}/admin-editmods.php",'go back to Edit Mods',0);
			}
		break;
		case "add":
			$sql->query("INSERT INTO forummods VALUES('$addmodforum', '$addmoduser')");
			if(($err=mysql_error()) != "")
				print "$tblstart$tccell1> ERROR: $err.";
			else {
 				$sql->query("INSERT INTO actionlog (atime, adesc, aip) VALUES (".ctime().", \"User ".$loguserid." added mod $addmoduser to forum $addmodforum\", \"$userip\")");
				print "$tblstart$tccell1> You successfully added user $addmoduser to forum $addmodforum.<br>".redirect("{$GLOBALS['jul_views_path']}/admin-editmods.php",'go back to Edit Mods',0);
			}
		break;
		default:
			print "No, doofus.";
  }
}

if (!$donotprint) {
	$forums=$sql->query("SELECT id,title,description,catid FROM forums ORDER BY catid");
	$fa="";
	$forumselect="<option value=\"0\">Select a forum...</option>\r\n";
	$forumselectforrem = "<option value=\"0|0\">Select a forum and moderator...</option>\r\n";
  while($forum=$sql->fetch($forums)) {
		$m=0;
		$modlist="";
		$forumselect.="<option value=\"$forum[id]\">$forum[title]</option>";
		$mods=$sql->query("SELECT user FROM forummods WHERE forum=$forum[id]");
		if($mods) {
			while($mod=$sql->fetch($mods)) {
				$usermod=$sql->fetchq("SELECT aka,sex,powerlevel,name,id from users where id=$mod[user]");
				if($m) $modlist.=", ";
				$modlist .= getuserlink($usermod);
				$forumselectforrem.="<option value=\"$forum[id]|$usermod[id]\">$forum[title] -- $usermod[name]</option>\r\n";
				$m++;
			}
		}
		if ($m)
    $fa.="
			<tr>
      <td class='tbl tdbg2 center fonts'>$forum[id]</td>
			<td class='tbl tdbg1 center fonts'>$forum[title]</td>
			<td colspan=3 class='tbl tdbg2 left fonts'>$modlist</td>
      </tr>
		";
	}

	$userlist = "<option value=\"0\">Select a user...</option>\r\n";
	$users1=$sql->query("SELECT `id`, `name` FROM `users` WHERE `powerlevel` > '0' ORDER BY `name`");
	while($user=$sql->fetch($users1))
		$userlist.="<option value=$user[id]>$user[name]</option>\r\n";

print "

$tblstart
<tr><td class='tbl tdbgh center fonts' width=50>ID</td>
<td class='tbl tdbgh center fonts' width=30%>Forum Name</td>
<td class='tbl tdbgh center fonts' width=65%>Moderators</td></tr>$fa$tblend

<form action=\"{$GLOBALS['jul_views_path']}/admin-editmods.php\" method=\"POST\">$inph=\"action\" value=\"add\"><br>$tblstart".
/*            <tr>
	 $tccellh><b>$smallfont Delete a mod.</td>
         $tccellh><b>$smallfont Add Moderator.</td>

 </td>
<tr>
	$tccell1> User ID: <input type=\"text\" name=\"dm_uid\"></td>
$tccell1>            User ID: <input type=\"text\" name=\"nm_uid\"></td>

<tr>
	$tccell1> Forum ID: <input type=\"text\" name=\"dm_fid\"></td>
$tccell1>            Forum ID: <input type=\"text\" name=\"nm_fid\">

<tr>
	    $tccell1> <input type=\"submit\" name=\"action\" value=\"Delete Mod\"></td>
$tccell1>            <input type=\"submit\" name=\"action\" value=\"Add Mod\">*/
"<tr>$tccellh colspan=\"2\">Add Moderator:</td></tr>
<tr>$tccell1 width=15%>Forum:</td>$tccell2l width=85%><select name=\"addmodforum\" size=\"1\">$forumselect</select></td></tr>
<tr>$tccell1 width=15%>User:</td>$tccell2l width=85%><select name=\"addmoduser\" size=\"1\">$userlist</select> $smallfont(note: this only shows Member+ and above)</font></td></tr>
<tr>$tccell1 width=15%>&nbsp;</td>$tccell2l width=85%>$inps=\"addmodsubmit\" value=\"Add Moderator\"></td></tr>$tblend</form>"
.

/*            <tr>
	 $tccellh><b>$smallfont Delete a mod.</td>
         $tccellh><b>$smallfont Add Moderator.</td>

 </td>
<tr>
	$tccell1> User ID: <input type=\"text\" name=\"dm_uid\"></td>
$tccell1>            User ID: <input type=\"text\" name=\"nm_uid\"></td>

<tr>
	$tccell1> Forum ID: <input type=\"text\" name=\"dm_fid\"></td>
$tccell1>            Forum ID: <input type=\"text\" name=\"nm_fid\">

<tr>
	    $tccell1> <input type=\"submit\" name=\"action\" value=\"Delete Mod\"></td>
$tccell1>            <input type=\"submit\" name=\"action\" value=\"Add Mod\">*/
($forumselectforrem!=""?"<form action=\"{$GLOBALS['jul_views_path']}/admin-editmods.php\" method=\"POST\">$inph=\"action\" value=\"remove\">$tblstart"."<tr>$tccellh colspan=\"2\">Remove Moderator:</td></tr>
<tr>$tccell1 width=15%>Forum and Moderator:</td>$tccell2l width=85%><select name=\"removemod\" size=\"1\">$forumselectforrem</select></td></tr>
<tr>$tccell1 width=15%>&nbsp;</td>$tccell2l width=85%>$inps=\"removemodsubmit\" value=\"Remove Moderator\"></td></tr>$tblend</form>":"");

}

print $footer;
printtimedif($startingtime);

?>
