<?php

require 'lib/function.php';
$windowtitle = "$boardname - IRC Chat";
require 'lib/layout.php';

	$servers[1]		= "irc.badnik.zone";
	$servers[2]		= "irc.rustedlogic.net";
	$servers[3]		= "irc.tcrf.net";
	if ($server > count($servers) || $server <= -1) $server = 0;

	print "$header<br>";

	print "	$tblstart<tr>
	$tccellh><b>Hold up, it's ". '$' ."YEAR.</b></td></tr>
	<tr>$tccell1>Maybe you want to head straight for our <a href='/thread.php?id=17948'>Discord</a>? 
	$tblend";

print "<br><br>$tblstart<tr>
		$tccellh><b>IRC Chat - BadnikZONE, #tcrf, #x</b></td></tr>
		<tr>$tccell1>Server List: ";

foreach ($servers as $num => $name) {

	if ($num != 1) print " | ";
	if ($server == $num) print "<u>";
	print "<a href=irc.php?server=". $num .">". $name ."</a>";
	if ($server == $num) print "</u>";
	if ($num == 1) print " (preferred)";

}

print "		<tr>$tccell2>";

	if ($server) {

	$badchars = array("~", "&", "@", "?", "!", ".", ",", "=", "+", "%", "*");

	$name = str_replace(" ", "", $loguser[name]);
	$name = str_replace($badchars, "_", $name);
	if (!$name) { 
		$name = "J-Guest";
		$guestmsg	= "<br>Welcome, guest. When you connect to the IRC network, please use the command <tt>/nick NICKNAME</tt>.<br>&nbsp;<br>";
	}
	
print "
	
	<iframe src=\"https://kiwiirc.com/client/". $servers[$server] ."/?nick=". $name ."|?#tcrf,#x\" style=\"border:0;width:100%;height:500px;\"></iframe>";

	} else {

		print "&nbsp;<br>Please choose a server to connect to.<br>&nbsp;";

	}

   print "$tblend
		<br>$tblstart<tr>
		$tccellh><b>Quick Help</b></td></tr>
		<tr>$tccell1l>Commands:
			<br><tt>/nick [name]</tt> - changes your name
			<br><tt>/me [action]</tt> - does an action (try it)
			<br><tt>/msg [name] [message]</tt> - send a private message to another user
			<br><tt>/join [#channel]</tt> - joins a channel
			<br><tt>/part [#channel]</tt> - leaves a channel
			<br><tt>/quit [message]</tt> - obvious
		$tblend
		$footer";

 printtimedif($startingtime);
?>

