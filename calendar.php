<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $mn=array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
  $date=getdate(time());
  $year=$date[year];
  $month=$date[mon];
  $day=$date[mday];
  if($y) $year=$y;
  if($m){
    $month=$m;
    $day=0;
  }
  if($d) $day=$d;
  if($event){
    $event=mysql_fetch_array(mysql_query("SELECT id,d,m,y,user,title,text FROM events WHERE id=$event"));
    $month=$event[m];
    $day=$event[d];
    $year=$event[y];
    $user=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$event[user]"));
    $eventbox="
     $tccellh colspan=7><b>$mn[$month] $day, $year: $event[title]</b> - $user[name]<tr>
     $tccell1 colspan=7>$event[text]<tr><tr>
    ";
  }
  $date=getdate(mktime(0,0,0,$month,1,$year));
  $i=1-$date[wday];
  $date=getdate(mktime(0,0,0,$month+1,0,$year));
  $max=$date[mday];
  $users=mysql_query('SELECT id,name,birthday,sex,powerlevel FROM users WHERE birthday ORDER BY name');
  while($user=mysql_fetch_array($users)){
    $date=@getdate($user[birthday]);
    if($date[mon]==$month){
	$dd=$date[mday];
	$age=$year-$date[year];
	$namecolor=getnamecolor($user[sex],$user[powerlevel]);
	$bdaytext[$dd].="<br>- <a href=profile.php?id=$user[id]><font $namecolor>$user[name]</font></a> turns $age";
    }
  }
  $events=mysql_query("SELECT id,d,title FROM events WHERE m=$month AND y=$year ORDER BY id");
  while($event1=mysql_fetch_array($events))
    $eventtext[$event1[d]].="<br>- <a href=calendar.php?event=$event1[id]>$event1[title]</a>";
  print "$header$fonttag<a href=index.php>$boardname</a> - Calendar
	$tblstart
	 $eventbox
	 $tccellh colspan=7><font size=5>$mn[$month] $year</font><tr>
	 $tccellh width=14.3%>S</td>
	 $tccellh width=14.3%>M</td>
	 $tccellh width=14.3%>T</td>
	 $tccellh width=14.3%>W</td>
	 $tccellh width=14.3%>T</td>
	 $tccellh width=14.3%>F</td>
	 $tccellh width=14.2%>S<tr>";
  for(;$i<=$max;$i+=7){
    for($dn=0;$dn<=6;$dn++){
      $dd=$i+$dn;
      $daytext="<a href=calendar.php?y=$year&m=$month&d=$dd>$dd</a>";
      if($dd<1 or $dd>$max) $daytext="";
      $tccell=$tccell1l;
      $width=" width=14.3%";
      $x=" valign=top height=80><font class=fontt size=0>";
      $end="</td>";
      if($dn==0 or $dn==6) $tccell=$tccell2l;
      if($dd==$day and $day!=0) $tccell=$tccellcl;
      if($dn==6) $end='<tr>';
      print "$tccell$width$x$daytext<br>$bdaytext[$dd]$eventtext[$dd]$end";
    }
  }
  for($i=1;$i<=12;$i++){
    if($i==$month) $monthlinks.=" $i";
    else $monthlinks.=" <a href=calendar.php?y=$year&m=$i>$i</a>";
  }
  for($i=$year-2;$i<=$year+2;$i++){
    if($i==$year) $yearlinks.=" $i";
    else $yearlinks.=" <a href=calendar.php?y=$i>$i</a>";
  }
  print "<tr> 
	 $tccell2 colspan=7>$smallfont<center>Month:$monthlinks | Year:$yearlinks
	$tblend
	$footer";
  printtimedif($startingtime);
?>