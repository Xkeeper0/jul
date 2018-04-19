<?php
require_once('../lib/function.php');

if ($_POST['edit'] || $_POST['edit2']) {
	if (!$isadmin) die("You aren't an admin!");

	if (isset($_GET['preview']))
		$prevtext = "&preview=" . $_GET['preview'];

	$hidden = (($_POST['hidden']) ? 1 : 0);

	$values .= "`title`          = '$forumtitle',     ";
	$values .= "`description`    = '$description',    ";
	$values .= "`catid`          = '$catid',          ";
	$values .= "`minpower`       = '$minpower',       ";
	$values .= "`minpowerthread` = '$minpowerthread', ";
	$values .= "`minpowerreply`  = '$minpowerreply',  ";
	$values .= "`numthreads`     = '$numthreads',     ";
	$values .= "`numposts`       = '$numposts',       ";
	$values .= "`forder`         = '$forder',         ";
	$values .= "`specialscheme`  = '$edspecialscheme',";
	$values .= "`hidden`         = '$hideforum',      ";
	$values .= "`pollstyle`      = '$pollstyle'       ";

	if ($_GET['id'] <= -1) {
		$sql->query("INSERT INTO `forums` SET $values, `lastpostid` = '0'");
		if (mysql_error()) die(mysql_error());
		$id	= mysql_insert_id();
		trigger_error("Created new forum \"$forumtitle\" with ID $id", E_USER_NOTICE);
	} else {
		$sql->query("UPDATE `forums` SET $values WHERE `id` = '". $_GET['id'] ."'");
		if (mysql_error()) die(mysql_error());
		$id	= $_GET['id'];
		trigger_error("Edited forum ID $id", E_USER_NOTICE);
	}

	if ($_POST['edit'])
		header("Location: ?id=". $id . $prevtext);
	else
		header("Location: ?".substr($prevtext, 1));

	die();
}
elseif ($_POST['delete']) {
	if (!$isadmin)
		die("You aren't an admin!");

	$id      = intval($_GET['delete']);
	$mergeid = intval($_POST['mergeid']);

	if (!isset($_GET['delete']) || $id < 0)
		die("No forum selected to delete.");
	if (!isset($_POST['mergeid']) || $mergeid < 0)
		die("No forum selected to merge to.");

	$counts = $sql->fetchq("SELECT `numthreads`, `numposts` FROM `forums` WHERE `id`='$id'");
	$sql->query("UPDATE `threads` SET `forum`='$mergeid' WHERE `forum`='$id'") or die(mysql_error());
	$sql->query("UPDATE `announcements` SET `forum`='$mergeid' WHERE `forum`='$id'") or die(mysql_error());
	$sql->query("DELETE FROM `forummods` WHERE `forum`='$id'") or die(mysql_error());
	$sql->query("DELETE FROM `forums` WHERE `id`='$id'") or die(mysql_error());

	$lastthread = $sql->fetchq("SELECT * FROM `threads` WHERE `forum`='$mergeid' ORDER BY `lastpostdate` DESC LIMIT 1");
	$sql->query("UPDATE `forums` SET
		`numthreads`=`numthreads`+'{$counts['numthreads']}',
		`numposts`=`numposts`+'{$counts['numposts']}',
		`lastpostdate`='{$lastthread['lastpostdate']}',
		`lastpostuser`='{$lastthread['lastposter']}',
		`lastpostid`='{$lastthread['id']}'
	WHERE `id`='$mergeid'") or die(mysql_error());

	if (isset($_GET['preview']))
		$prevtext = "preview=" . $_GET['preview'];

	trigger_error("DELETED forum ID $id; merged into forum ID $mergeid", E_USER_NOTICE);

	header("Location: ?$prevtext");
	die();
}

$windowtitle = "Editing Forum List";
require_once('../lib/layout.php');

print "$header<br>";

admincheck();
print adminlinkbar("{$GLOBALS['jul_views_path']}/admin-editforums.php");

foreach($pwlnames as $pwl=>$pwlname) {
	if ($pwl < 0) continue;
	$powers[] = $pwlname;
}
$powers[] = '[no access]';

$pollstyles = array(-2 => 'Disallowed',
                    -1 => 'Normal',
                     0 => 'Force Regular',
                     1 => 'Force Influence');


if (isset($_GET['delete'])) {
	$forum = intval($_GET['delete']);

	$forums[-1] = "Choose a forum to merge into...";
	$forumquery = $sql->query("SELECT id,title FROM forums ORDER BY catid,forder");
	while ($f = $sql->fetch($forumquery, MYSQL_ASSOC))
		$forums[$f['id']] = $f['title'];

	if (array_key_exists($forum, $forums)) {
		$fname = $forums[$forum];
		unset($forums[$forum]);
		if (isset($_GET['preview']))
			$prevtext = "&preview=" . $_GET['preview'];

		echo  "
		<form method=\"post\" action=\"?delete=". $forum . "$prevtext\">
		$tblstart
			<tr>
				$tccellh>Deleting <b>$fname</b></td>
			</tr><tr>
				$tccellc>You are about to delete forum ID <b>$forum</b>.<br><br>
				All announcements and threads will be moved to the forum below.<br>
				". dropdownList($forums, -1, "mergeid") . "</td>
			</tr><tr>
				$tccellc><input type=\"submit\" name=\"delete\" value=\"DELETE FORUM\"> or <a href=?>Cancel</a></td>
			</tr>
		</table></form><br>";
	}
}
else if (isset($_GET['id'])) {
	$catquery = $sql->query("SELECT id,name FROM categories ORDER BY id");
	while ($catres = $sql->fetch($catquery))
		$categories[$catres['id']] = $catres['name'];

	$forum = $sql->fetchq("SELECT * FROM `forums` WHERE `id` = '". $_GET['id'] . "'", MYSQL_ASSOC);
	if (!$forum)
		$_GET['id'] = -1;

	if ($forum && !array_key_exists($forum['catid'], $categories))
		$categories[$forum['catid']] = "Unknown category #" . $forum['catid'];

	if (isset($_GET['preview']))
		$prevtext = "&preview=" . $_GET['preview'];

	echo  "
	<form method=\"post\" action=\"?id=". $_GET['id'] . "$prevtext\">
	$tblstart
		<tr>
			$tccellh colspan=6>Editing <b>". ($forum ? htmlspecialchars($forum['title']) : "a new forum") . "</b></td>
		</tr>

		<tr>
			$tccellh>Forum Name</td>
			$tccell1l colspan=4><input type=\"text\" name=\"forumtitle\" value=\"". htmlspecialchars($forum['title']) ."\"  style=\"width: 100%;\" maxlength=\"250\"></td>
			$tccell1l width=10%><input type=\"checkbox\" id=\"hideforums\" name=\"hideforum\" value=\"1\"". ($forum['hidden'] ? " checked" : "") ."> <label for=\"hideforums\">Hidden</label></td>
		</tr>

		<tr>
			$tccellh rowspan=4>Description</td>
			$tccell1l rowspan=4 colspan=3>$txta=description ROWS=4 style=\"width: 100%; resize:none;\">". htmlspecialchars($forum['description']) ."</TEXTAREA></td>
			$tccellh colspan=2>Minimum power needed...</td>
		</tr>

		<tr>
			$tccellh>...to view the forum</td>
			$tccell1l>". dropdownList($powers, $forum['minpower'], "minpower") . "</td>
		</tr>

		<tr>
			$tccellh>...to post a thread</td>
			$tccell1l>". dropdownList($powers, $forum['minpowerthread'], "minpowerthread") . "</td>
		</tr>

		<tr>
			$tccellh>...to reply</td>
			$tccell1l>". dropdownList($powers, $forum['minpowerreply'], "minpowerreply") . "</td>
		</tr>

		<tr>
			$tccellh  width='10%'>Number of Threads</td>
			$tccell1l width='24%'><input type=\"text\" name=\"numthreads\" maxlength=\"8\" size=\"10\" value=\"". ($forum['numthreads'] ? $forum['numthreads'] : "0") ."\" class=\"right\"></td>
			$tccellh  width='10%'>Forum order</td>
			$tccell1l width='23%'><input type=\"text\" name=\"forder\" maxlength=\"8\" size=\"10\" value=\"". ($forum['forder'] ? $forum['forder'] : "0") ."\" class=\"right\"></td>
			$tccellh  width='10%'>Poll Style</td>
			$tccell1l width='23%'>". dropdownList($pollstyles, $forum['pollstyle'], "pollstyle") . "</td>
		</tr>

		<tr>
			$tccellh >Number of Posts</td>
			$tccell1l><input type=\"text\" name=\"numposts\" maxlength=\"8\" size=\"10\" value=\"". ($forum['numposts'] ? $forum['numposts'] : "0") ."\" class=\"right\"></td>
			$tccellh >Special Scheme</td>
			$tccell1l><input type=\"text\" name=\"edspecialscheme\" value=\"". htmlspecialchars($forum['specialscheme']) ."\"  style=\"width: 90%;\" maxlength=\"250\"></td>
			$tccellh >Category</td>
			$tccell1l>". dropdownList($categories, $forum['catid'], "catid") . "</td>
		</tr>

		<tr>
			$tccellc colspan=6><input type=\"submit\" name=\"edit\" value=\"Save and continue\">&nbsp;<input type=\"submit\" name=\"edit2\" value=\"Save and close\"></td>
		</tr>

	</table></form><br>";
}

	$forumlist="
		<tr>
			$tccellh width=90px>Actions</td>
			$tccellh>Forum</td>
			$tccellh width=80>Threads</td>
			$tccellh width=80>Posts</td>
			$tccellh width=15%>Last post</td>
		</tr>
	";

	if (isset($_GET['preview'])) {
		$forumquery = $sql->query("SELECT f.*,u.id AS uid,name,sex,powerlevel FROM forums f LEFT JOIN users u ON f.lastpostuser=u.id WHERE (!minpower OR minpower<=$_GET[preview]) AND f.hidden = '0' ORDER BY catid,forder");
		$catquery = $sql->query("SELECT id,name FROM categories WHERE (!minpower OR minpower<=$_GET[preview]) ORDER BY id");
		$prevtext = "&preview=" . $_GET['preview'];
	}
	else {
		$forumquery = $sql->query("SELECT f.*,u.id AS uid,name,sex,powerlevel FROM forums f LEFT JOIN users u ON f.lastpostuser=u.id ORDER BY catid,forder");
		$catquery = $sql->query("SELECT id,name FROM categories ORDER BY id");
	}

	$modquery = $sql->query("SELECT u.id,name,sex,powerlevel,forum FROM users u INNER JOIN forummods m ON u.id=m.user ORDER BY name");

	$categories	= array();
	$forums		= array();
	$mods		= array();

	while ($res = $sql->fetch($catquery))
		$categories[] = $res;
	while ($res = $sql->fetch($forumquery))
		$forums[] = $res;
	while ($res = $sql->fetch($modquery))
		$mods[] = $res;

	$forumlist .= "<tr><td class='tbl tdbgc center font' colspan=5>&lt; <a href='{$GLOBALS['jul_views_path']}/admin-editforums.php?id=-1$prevtext'>Create a new forum</a> &gt;</td></tr>";

	foreach ($categories as $category) {
		$forumlist.="<tr><td class='tbl tdbgc center font' colspan=5><b>$category[name]</b></td></tr>";

		foreach ($forums as $forumplace => $forum) {
			if ($forum['catid'] != $category['id'])
				continue;

			$m = 0;
			$modlist = "";
			foreach ($mods as $modplace => $mod) {
				if ($mod['forum'] != $forum['id'])
					continue;

				$namecolor=getnamecolor($mod['sex'],$mod['powerlevel']);
				$modlist.=($m++?', ':'')."<a href={$GLOBALS['jul_views_path']}/profile.php?id=$mod[id]><font $namecolor>$mod[name]</font></a>";
				unset($mods[$modplace]);
			}

			if ($m)
				$modlist="$smallfont(moderated by: $modlist)</font>";

			$namecolor = getnamecolor($forum['sex'],$forum['powerlevel']);
			if($forum['numposts']){
				$forumlastpost="<nobr>". date($dateformat,$forum['lastpostdate']+$tzoff);
				$by="$smallfont<br>by <a href={$GLOBALS['jul_views_path']}/profile.php?id=$forum[uid]><font $namecolor>$forum[name]</font></a>". ($forum['lastpostid'] ? " <a href='{$GLOBALS['jul_views_path']}/thread.php?pid=". $forum['lastpostid'] ."#". $forum['lastpostid'] ."'>". $statusicons['getlast'] ."</a>" : "") ."</nobr></font>";
			} else {
				$forumlastpost=getblankdate();
				$by='';
			}

			if($forum['lastpostdate']>$category['lastpostdate']){
				$category['lastpostdate']=$forum['lastpostdate'];
				$category['l']=$forumlastpost.$by;
			}

			if ($forum['hidden'])
				$hidden = " <small><i>(hidden)</i></small>";
			else
				$hidden = "";

			if ($_GET['id'] == $forum['id']) {
				$tc1	= $tccellh;
				$tc2	= $tccellh;
				$tc2l	= $tccellhl;
			}
			else {
				$tc1	= $tccell1;
				$tc2	= $tccell2;
				$tc2l	= $tccell2l;
			}

		  $forumlist.="
			<tr>
				$tc1><small><a href='{$GLOBALS['jul_views_path']}/admin-editforums.php?id=$forum[id]$prevtext'>Edit</a> / <a href='{$GLOBALS['jul_views_path']}/admin-editforums.php?delete=$forum[id]$prevtext'>Delete</a></small></td>
				$tc2l><a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>$forum[title]</a>$hidden<br>
				$smallfont$forum[description]<br>$modlist</td>
				$tc1>$forum[numthreads]</td>
				$tc1>$forum[numposts]</td>
				$tc2><span class='lastpost'>$forumlastpost</span>$by$forumlastuser
			</tr>
		  ";

			unset($forums[$forumplace]);
		}
	}

// Leftover forums
if (!isset($_GET['preview']) && count($forums)) {
	$forumlist.="<tr><td class='tbl tdbgc center font' colspan=5><b><i>These forums are not associated with a valid category ID</i></b></td></tr>";

	foreach ($forums as $forum) {
		$m = 0;
		foreach ($mods as $modplace => $mod) {
			if ($mod['forum'] != $forum['id'])
				continue;

			$namecolor=getnamecolor($mod['sex'],$mod['powerlevel']);
			$modlist.=($m++?', ':'')."<a href={$GLOBALS['jul_views_path']}/profile.php?id=$mod[id]><font $namecolor>$mod[name]</font></a>";
			unset($mods[$modplace]);
		}

		if ($m)
			$modlist="$smallfont(moderated by: $modlist)</font>";

		$namecolor = getnamecolor($forum['sex'],$forum['powerlevel']);
		if($forum['numposts']){
			$forumlastpost="<nobr>". date($dateformat,$forum['lastpostdate']+$tzoff);
			$by="$smallfont<br>by <a href={$GLOBALS['jul_views_path']}/profile.php?id=$forum[uid]><font $namecolor>$forum[name]</font></a>". ($forum['lastpostid'] ? " <a href='{$GLOBALS['jul_views_path']}/thread.php?pid=". $forum['lastpostid'] ."#". $forum['lastpostid'] ."'>". $statusicons['getlast'] ."</a>" : "") ."</nobr></font>";
		} else {
			$forumlastpost=getblankdate();
			$by='';
		}

		if($forum['lastpostdate']>$category['lastpostdate']){
			$category['lastpostdate']=$forum['lastpostdate'];
			$category['l']=$forumlastpost.$by;
		}

		if ($forum['hidden'])
			$hidden = " <small><i>(hidden)</i></small>";
		else
			$hidden = "";

		$forumlist.="
		<tr>
			$tccell1><small><a href='{$GLOBALS['jul_views_path']}/admin-editforums.php?id=$forum[id]$prevtext'>Edit</a> / <a href='{$GLOBALS['jul_views_path']}/admin-editforums.php?delete=$forum[id]$prevtext'>Delete</a></small></td>
			$tccell2l><a href='{$GLOBALS['jul_views_path']}/forum.php?id=$forum[id]'>$forum[title]</a>$hidden<br>
			$smallfont$forum[description]<br>$modlist</td>
			$tccell1>$forum[numthreads]</td>
			$tccell1>$forum[numposts]</td>
			$tccell2><span class='lastpost'>$forumlastpost</span>$by$forumlastuser
		</tr>
		";
	}
}

print "<center><b>Preview forums with powerlevel:</b> ".previewbox()."</center>\n";
print "$tblstart$forumlist$tblend$footer";
printtimedif($startingtime);

function dropdownList($links, $sel, $n) {
	global $tccell1, $tccellc;
	$r	= "<select name=\"$n\">";

	foreach($links as $link => $name) {
		$cell	= $tccell1;
		if ($link == $sel) $cell	= $tccellc;
		$r	.= "<option value=\"$link\"". ($sel == $link ? " selected" : "") .">$name</option>";
	}

	return $r ."</select>";
}

function previewbox(){
	if (isset($_GET['id'])) {
		$idtxt = "id=" . $_GET['id'] . "&";
		$idtxt2 = "?id=" . $_GET['id'];
	}

	return "<form><select onChange=parent.location=this.options[this.selectedIndex].value>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php{$idtxt2}' ".((!$_GET['preview'] || $_GET['preview'] < 0 || $_GET['preview'] > 4) ? 'selected' : '') ."'>Disable</option>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php?{$idtxt}preview=0' ".((isset($_GET['preview']) && $_GET['preview'] == 0) ? 'selected' : '') .">Normal</option>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php?{$idtxt}preview=1' ".($_GET['preview'] == 1 ? 'selected' : '') .">Normal +</option>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php?{$idtxt}preview=2' ".($_GET['preview'] == 2 ? 'selected' : '') .">Moderator</option>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php?{$idtxt}preview=3' ".($_GET['preview'] == 3 ? 'selected' : '') .">Administrator</option>
			<option value='{$GLOBALS['jul_views_path']}/admin-editforums.php?{$idtxt}preview=4' ".($_GET['preview'] == 4 ? 'selected' : '') .">Administrator (hidden)</option>
		</select></form>";
}
?>
