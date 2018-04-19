<?php
	require_once '../lib/function.php';

	$mn=array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');

	$date = getdate(time());
	$year = $date['year'];
	$month = $date['mon'];

	if (!$y && !$m && !$d)
		$day = $date['mday'];
	else {
		if ($y) $year = intval($y);
		if ($m) $month = intval($m);
	  if ($d) $day = intval($d);
	}

	$eventdata = null;
	if ($event)
		$eventdata = $sql->fetchq("SELECT id,d,m,y,user,title,text FROM events WHERE id=$event");

	if ($eventdata) {
		$month = $eventdata['m'];
		$day   = $eventdata['d'];
		$year  = $eventdata['y'];
	}

	$windowtitle = "{$GLOBALS['jul_settings']['board_name']} -- Calendar for {$mn[$month]} {$year}";
	require_once '../lib/layout.php';

	$date = getdate(mktime(0,0,0,$month,1,$year));
	$i    = 1 - $date['wday'];

	$date = getdate(mktime(0,0,0,$month+1,0,$year));
	$max  = $date['mday'];

	$users = $sql->query('SELECT id,name,birthday,sex,powerlevel,aka FROM users WHERE birthday ORDER BY birthday ASC, name ASC');
	while ($user = $sql->fetch($users)) {
		$date = @getdate($user['birthday']);
		if ($date['mon'] != $month) continue;

		$age = $year-$date['year'];
		$userlink = getuserlink($user);
		$bdaytext[$date['mday']].="<br>- {$userlink} turns {$age}";
	}

	$events = $sql->query("SELECT id,d,title FROM events WHERE m=$month AND y=$year ORDER BY id");
	while($event1 = $sql->fetch($events))
		$eventtext[$event1['d']] .= "<br>- <a href='{$GLOBALS['jul_views_path']}/calendar.php?event=$event1[id]'>$event1[title]</a>";

	print "$header$fonttag<a href={$GLOBALS['jul_base_dir']}/index.php>{$GLOBALS['jul_settings']['board_name']}</a> - Calendar
		$tblstart";

	if ($eventdata) {
		$user = $sql->resultq("SELECT name FROM users WHERE id=$eventdata[user]");
		print "<tr>
			{$tccellh} colspan=7><b>$mn[$month] $day, $year: $eventdata[title]</b> - {$user}</td></tr><tr>
			{$tccell1} colspan=7>$eventdata[text]</td>
		</tr>";
	}

	print "<tr>$tccellh colspan=7><font size=5>$mn[$month] $year</font></td></tr>
		<tr>
			$tccellh width=14.3%>S</td>
			$tccellh width=14.3%>M</td>
			$tccellh width=14.3%>T</td>
			$tccellh width=14.3%>W</td>
			$tccellh width=14.3%>T</td>
			$tccellh width=14.3%>F</td>
			$tccellh width=14.2%>S</td>
		</tr>\r\n";


  $attribs = " width=14.3% valign=top height=80><font class=fontt size=0>";
	for(; $i<=$max; $i+=7) {
    print "<tr>\r\n";
    for($dn=0;$dn<=6;$dn++){
      $dd=$i+$dn;
      $daytext="<a href='{$GLOBALS['jul_views_path']}/calendar.php?y=$year&m=$month&d=$dd'>$dd</a>";

      $tccell = $tccell1l;
      if ($dd==$day && $day!=0) $tccell = $tccellcl;
      elseif ($dn==0 || $dn==6) $tccell = $tccell2l;

      if ($dd<1 || $dd>$max)
        print "$tccell$attribs</td>\r\n";
      else
        print "$tccell$attribs$daytext<br>$bdaytext[$dd]$eventtext[$dd]</td>\r\n";
    }
    print "</tr>\r\n";
  }

  for($i=1;$i<=12;$i++){
    if($i==$month) $monthlinks.=" $i";
    else $monthlinks.=" <a href='{$GLOBALS['jul_views_path']}/calendar.php?y=$year&m=$i'>$i</a>";
  }
  for($i=$year-2;$i<=$year+2;$i++){
    if($i==$year) $yearlinks.=" $i";
    else $yearlinks.=" <a href='{$GLOBALS['jul_views_path']}/calendar.php?y=$i'>$i</a>";
  }

  print "<tr>
    $tccell2 colspan=7>$smallfont<center>Month:$monthlinks | Year:$yearlinks
  </td></tr>
	$tblend
	$footer";

  printtimedif($startingtime);
?>
