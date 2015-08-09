<?php

require 'lib/function.php';
$windowtitle = "$boardname - IRC Chat";
require 'lib/layout.php';

	$servers[1]		= "irc.badnik.net";
	if ($server > count($servers) || $server <= -1) $server = 0;



print "		$header<br>$tblstart<tr>
		$tccellh><b>Java IRC Chat - BadnikNET, #x</b></td></tr>
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
	
	<applet code=\"IRCApplet.class\" codebase=\"irc/\"  
	archive=\"irc.jar,pixx.jar\" width=\"100%\" height=500>
	<param name=\"CABINETS\" value=\"irc.cab,securedirc.cab,pixx.cab\">
	
	<param name=\"nick\" value=\"". $name ."\">
	<param name=\"alternatenick\" value=\"". $name ."_??\">
	<param name=\"fullname\" value=\"Jul Java IRC User\">
	<param name=\"host\" value=\"". $servers[$server] ."\">
	<param name=\"gui\" value=\"pixx\">
	<param name=\"authorizedcommandlist\" value=\"all-server-s\">
	<param name=\"authorizedleavelist\" value=\"all-#x\">
	<param name=\"authorizedjoinlist\" value=\"all\">

	<param name=\"quitmessage\" value=\"JulIRC - http://jul.rustedlogic.net/\">
	<param name=\"autorejoin\" value=\"true\">
	
	<param name=\"style:bitmapsmileys\" value=\"false\">
	<param name=\"style:backgroundimage\" value=\"false\">
	<param name=\"style:backgroundimage1\" value=\"none+Channel all 2 background.png.gif\">
	<param name=\"style:sourcecolorrule1\" value=\"all all 0=000000 1=ffffff 2=0000ff 3=00b000 4=ff4040 5=c00000 6=c000a0 7=ff8000 8=ffff00 9=70ff70 10=00a0a0 11=80ffff 12=a0a0ff 13=ff60d0 14=a0a0a0 15=d0d0d0\">
	
	<param name=\"pixx:timestamp\" value=\"true\">
	<param name=\"pixx:highlight\" value=\"true\">
	<param name=\"pixx:highlightnick\" value=\"true\">
	<param name=\"pixx:nickfield\" value=\"false\">
	<param name=\"pixx:styleselector\" value=\"true\">
	<param name=\"pixx:setfontonstyle\" value=\"true\">

	". ($_GET['channel'] != "retro" ? "<param name=\"command1\" value=\"/join #x\"><param name=\"command1\" value=\"/join #tcrf\">" : "") ."
	". ($_GET['channel'] ? "<param name=\"command1\" value=\"/join #". $_GET['channel'] ."\">" : "") ."
	
	</applet>";

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

