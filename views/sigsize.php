<?php
  $windowtitle	= ($_GET['bio'] ? "Bio" : "Layout") ." size comparison";
  require_once '../lib/function.php';
  require_once '../lib/layout.php';
  print "
	$header
	$fonttag
		Show: <a href=\"?\">layout sizes</a> - <a href=\"?bio=1\">bio sizes</a>
	<br>$tblstart
	$tccellh>&nbsp;</td>
	$tccellh colspan=2>User</td>
	".  ($_GET['bio'] ? "$tccellh>Bio</td>" : "$tccellh>Header</td>
	$tccellh>Signature</td>
	$tccellh>Total</td>");
  $users=mysql_query('SELECT id,name, minipic, powerlevel, sex, '. ($_GET['bio'] ? "LENGTH(bio) AS tsize, bio as postheader" : "LENGTH(postheader) AS hsize,LENGTH(signature) AS ssize,LENGTH(postheader)+LENGTH(signature) AS tsize, postheader") .' FROM users ORDER BY tsize DESC');

  for ($i = 1; $u=mysql_fetch_array($users); $i++) {
	if (!$u['tsize']) break;
	if ($last['tsize'] != $u['tsize']) $r = $i;
	$last	= $u;
	$max	= max($u['tsize'], $max);

	if (strpos($u['postheader'], "<style>.loclass") !== false) $lm	= true;
	else $lm	= false;

	print "<tr>
	$tccell2>". ($lm ? "<img src=\"images/smilies/denied.gif\" title=\"Say no to the layout maker!\" align=absmiddle> $r <img src=\"images/smilies/denied.gif\" title=\"Say no to the layout maker!\" align=absmiddle>" : "$r") ."</td>
	$tccell2 width=16>". ($u['minipic'] ? "<img src=\"". htmlspecialchars($u['minipic']) ."\" width=16 height=16>" : "") ."</td>
	$tccell1><a href={$GLOBALS['jul_views_path']}/profile.php?id=$u[id]><font ". getnamecolor($u['sex'], $u['powerlevel']) .">$u[name]</font></a></td>
	". (!$_GET['bio'] ? "$tccell2 width=100>". number_format($u['hsize']) ."</td>
	$tccell2 width=100>". number_format($u['ssize']) ."</td>" : "") ."
	$tccell1 width=100><b>". number_format($u['tsize']) ."</b><br><img src=images/minibar.png width=\"". number_format($u['tsize'] / $max * 200) ."\" align=left height=3></td></tr>";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>
