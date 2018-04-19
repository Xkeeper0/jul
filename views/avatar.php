<?php
require_once '../lib/function.php';
require_once '../lib/layout.php';

$a	= array(1 => "neutral", "angry", "tired/upset", "playful", "doom", "delight", "guru", "hope", "puzzled", "whatever", "hyperactive", "sadness", "bleh", "embarrassed", "amused", "afraid");

$me = false;
$form = "<b>Preview mood avatar for user...</b><br>
  <form><select onChange=\"parent.location=this.options[this.selectedIndex].value\" style=\"width:500px;\">
  <option value='{$GLOBALS['jul_views_path']}/avatar.php'>&lt;Select a user&gt;</option>";

$users = $sql->query("SELECT id, name, moodurl FROM users WHERE moodurl != '' ORDER BY id ASC");
while ($u = $sql->fetch($users)) {
  $selected = $fails = '';
  if ($u['id'] == $id) {
    $me = $u;
    $selected = ' selected';
  }
  //if (strpos($u['moodurl'], '$') === FALSE)
  //  $fails = " (improper URL)";
  $form .= "\r\n  <option value='{$GLOBALS['jul_views_path']}/avatar.php?id=$u[id]'$selected>$u[id]: $u[name]$fails</option>";
}
$form .= "\r\n  </select></form>";

if ($me) {
	$script = '
	<script type="text/javascript">
		function avatarpreview(uid,pic) {
			if (pic > 0) {
						var moodav="'.htmlspecialchars($me['moodurl']).'";
						document.getElementById(\'prev\').src=moodav.replace("$", pic);
			}
			else {
				document.getElementById(\'prev\').src="images/_.gif";
			}
		}
	</script>
	';

  $ret = "<tr>$tccellh colspan=2>$me[name]: <i>".htmlspecialchars($me['moodurl'])."</i></td></tr>";
	$ret .= "<tr height=400px>$tccell1l width=200px><b>Mood avatar list:</b><br>";

	foreach($a as $num => $name) {
		$jsclick = "onclick='avatarpreview($me[id],$num)'";
		$selected = (($num == 1) ? ' checked' : '');
		$ret .= "<input type='radio' name='moodid' value='$num' id='mood$num' tabindex='". (9000 + $num) ."' style=\"height: 12px;\" $jsclick $selected>
             <label for='mood$num' style=\"font-size: 12px;\">&nbsp;$num:&nbsp;$name</label><br>\r\n";
	}

	$startimg = htmlspecialchars(str_replace('$', '1', $me['moodurl']));

  $ret .= "</td>$tccell2 width=400px><img src=\"$startimg\" id=prev></td></tr>";

}
else {
	$script = '';
	$ret = '';
}
  print "
<html><head><title>Mood Avatar Preview</title>{$GLOBALS['jul_js_vars']}</head>
$body
$css
$script
<center>
 <table height=100% valign=middle><tr><td>
  $tblstart
   <tr height=50px>$tccellh colspan=2>$form</td></tr>
   $ret
  $tblend
 </td><tr></table></body></html>
";
?>
