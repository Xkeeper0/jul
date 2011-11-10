<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location,lastposttime,lastactivity,imood';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit,$opts,$dateshort,$dateformat,$tlayout,$textcolor,$numdir,$numfil;
    $exp=calcexp($post[posts],(ctime()-$post[regdate])/86400);
    $lvl=calclvl($exp);
    $expleft=calcexpleft($exp);
    if($tlayout==9){
    $level="Level: $lvl";
    $poststext="Posts: ";
    $postnum="$post[num]/";
    $posttotal=$post[posts];
    $experience="EXP: $exp<br />For next: $expleft";
    }else{
	$level="<img src=".$bconf[boardurl]."/images/$numdir"."level.gif width=36 height=8><img src=".$bconf[boardurl]."/numgfx.php?n=$lvl&l=3&f=$numfil height=8>";
	$experience="<img src=".$bconf[boardurl]."/images/$numdir"."exp.gif width=20 height=8><img src=".$bconf[boardurl]."/numgfx.php?n=$exp&l=5&f=$numfil height=8><br><img src=".$bconf[boardurl]."/images/$numdir"."fornext.gif width=44 height=8><img src=".$bconf[boardurl]."/numgfx.php?n=$expleft&l=2&f=$numfil height=8>";
	$poststext="<img src=".$bconf[boardurl]."/images/_.gif height=2><br><img src=".$bconf[boardurl]."/images/$numdir"."posts.gif width=28 height=8>";
	$postnum="<img src=".$bconf[boardurl]."/numgfx.php?n=$post[num]/&l=5&f=$numfil height=8>";
	$posttotal="<img src=".$bconf[boardurl]."/numgfx.php?n=$post[posts]&f=$numfil".($post[num]?'':'&l=4')." height=8>";
	$totalwidth=56;
	$barwidth=$totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);
	if($barwidth<1) $barwidth=0;
	if($barwidth>0) $baron="<img src=".$bconf[boardurl]."/images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=".$bconf[boardurl]."/images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
	$bar="<br><img src=".$bconf[boardurl]."/images/$numdir"."barleft.gif width=2 height=8>$baron$baroff<img src=".$bconf[boardurl]."/images/$numdir".'barright.gif width=2 height=8>';
    }
    if(!$post[num]){ $postnum='';}
    if($post[icq]) $icqicon="<a href=http://wwp.icq.com/$post[icq]#pager><img src=http://wwp.icq.com/scripts/online.dll?icq=$post[icq]&img=5 border=0 width=13 height=13 align=absbottom></a>";
    if($post[imood]) $imood="<img src=http://www.imood.com/query.cgi?email=$post[imood]&type=1&fg=$textcolor&trans=1 height=15 align=absbottom>";
    $reinf=syndrome($post[act]);
    $statustime=ctime()-300;
	$random = rand(0, 5);
	$dumbphrases = array("<b><font color=FF0000>Your Mother</font></b>", "<b><font color=FF9000>Registered</font></b>", "<b><font color=888888>Banned</font></b>", "<b><font color=00FF40>". htmlspecialchars($post[name]) ."</font></b>", "<b><font color=FF9040>a Waffle</font></b>", "<b><font color=FFFFFF><blink>WOW</blink></font></b>");
$status = htmlspecialchars($post[name])." is ". $dumbphrases[$random];
// if($post[lastactivity]<$statustime){
//  $status=<font color=FF0000><b>Offline</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
// }elseif($post[lastactivity]>$statustime){
//  $status=htmlspecialchars($post[name])." is <font color=FFFF00><b>The Writer Of This Post</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//}
    $sincelastpost='Since last post: '.timeunits(ctime()-$post[lastposttime]);
    $lastactivity='Last activity: '.timeunits(ctime()-$post[lastactivity]);
    $since='Since: '.@date($dateshort,$post[regdate]+$tzoff);
    $postdate=date($dateformat,$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "
    $set[tdbg] rowspan=2>
      $set[userlink]</a>$smallfont<br />
      $set[userrank]$reinf<br />
        $level$bar<br />
      $set[userpic]<br>
      $poststext$postnum$posttotal<br />
      $experience<br /><br />
      $since$set[location]<br /><br />
      $sincelastpost<br />$lastactivity<br />
      $icqicon$imood</font>
 </td>
        $set[tdbg] height=1 width=80%>
          <table cellspacing=0 cellpadding=2 width=100% class=fonts>
            <td>Posted on $postdate$threadlink</td>
            <td width=255><nobr>$quickeditlink$quote$edit$ip
          </table><tr>
        $set[tdbg] height=220>$post[headtext]$post[text]$post[signtext]$set[edited]</td>
         <tr>
          $set[tdbg] >$smallfont<b>Status</b>: $status</td>
        $set[tdbg] width=80%>$smallfont&nbsp;<b>Options</b>: <b>None</b>, as all of the old options here were totally useless and lost in the move to 1.92 <!-- $opts --></td>
          
    ";
  }
?>