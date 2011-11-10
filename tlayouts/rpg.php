<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit,$dateformat,$tccell1l,$tccell2l,$tableborder,$tablebg2,$tableheadtext,$numdir;
    if($post[picture]) $userpicture="<img width=60 height=60 src=\"$post[picture]\">";
    $postdate=date($dateformat,$post[date]+$tzoff);
    $exp=calcexp($post[posts],(ctime()-$post[regdate])/86400);
    $mp=calcexpgainpost($post[posts],(ctime()-$post[regdate])/86400);
    $lvl=calclvl($exp);
    $expleft=calcexpleft($exp);
    $barwidth=100-round(@($expleft/totallvlexp($lvl))*100);
    if($barwidth<1) $barwidth=0;
    if($barwidth>0) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth% height=8>";
    if($barwidth<100) $baroff="<img src=images/$numdir".'bar-off.gif width='.(100-$barwidth).'% height=8>';
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "
	$tccell1l valign=top rowspan=2>
	  $set[userlink]$smallfont<br>
	  $set[userrank]
	  <table border bordercolor=$tableborder cellspacing=0 cellpadding=0 style='background:$tablebg2'>
	    <td width=60>$userpicture</td>
	    <td width=60 height=60>
		<table cellpadding=0 cellspacing=0 width=100% class=fontt>
		  <td><b><font color=$tableheadtext>LV<br><br>HP<br>MP</font></b></td>
		  <td align=right><b>$lvl<br><br>$post[posts]<br>$mp</b>
		</table>
	    <tr><td colspan=2>
		<table cellpadding=0 cellspacing=0 width=100% class=fontt>
		  <td><b><font color=$tableheadtext>EXP points<br>For next LV</font></b></td>
		  <td align=right><b>$exp<br>$expleft</b><tr>
		  <td colspan=2>$baron$baroff
		</table>
	  </table>
	$tccell1l width=80%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$tccell2l valign=top height=220>$post[headtext]$post[text]$post[signtext]$edited</td>
    ";
  }
?>