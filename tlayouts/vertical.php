<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location,lastposttime,lastactivity,imood';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit,$dateshort,$dateformat,$tlayout,$textcolor,$numdir,$numfil;
    $exp=calcexp($post[posts],(ctime()-$post[regdate])/86400);
    $lvl=calclvl($exp);
    $expleft=calcexpleft($exp);

    if($post[picture]) $set[userpic] = "<img width=60 height=60 src=\"$post[picture]\">&nbsp;";
		else $set[userpic] = "<img width=60 height=60 src=\"images/_.gif\">&nbsp;";

    if($quote) $quote='['.$quote.']';
    $edit=str_replace(' | ','',$edit);
    $edit=str_replace('><','> | <',$edit);
    if($edit != null) $edit='['. $edit .']';

$ip=str_replace('| I','I',$ip);

	$level=$lvl;
	$postnum="$post[num]/";
	$posttotal=$post[posts];
	$experience="EXP: $exp ($expleft for next)";
	$totalwidth=96;
	$barwidth=$totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);

	if($barwidth<1) $barwidth=0;
	if($barwidth>0) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
	$bar="<img src=images/$numdir"."barleft.gif width=2 height=8>$baron$baroff<img src=images/$numdir".'barright.gif width=2 height=8>';

	$numgfx = "<img src=numgfx.php?f=$numfiln&n=";

    if(!$post[num]){
	$postnum='';
	if($postlayout==1) $posttotal="<img src=numgfx.php?n=$post[posts]&f=$numfil&l=4 height=8>";
    }
    $postdate=date($dateformat,$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "
	$set[tdbg]><table cellpadding=0 cellspacing=0 border=0><tr><td width=60 height=60 rowspan=2>$set[userpic]&nbsp;</td>
	<td class=font>$set[userlink]</td></tr><tr>
	<td class=fontt><img src=images/$numdir" . "level.gif height=8>$numgfx$lvl&l=8&f=$numfil height=8><br>$bar<br><img src=images/$numdir" . "posts.gif height=8>$numgfx$postnum$posttotal&l=9&f=$numfil height=8><br><img src=images/$numdir" . "exp.gif   height=8>$numgfx$exp&l=10&f=$numfil height=8><br><img src=images/$numdir" . "fornext.gif height=8>$numgfx$expleft&l=7&f=$numfil height=8></td></tr></table></td>
	$set[tdbg] align=right>$smallfont
	    <nobr>Posted: $postdate
	    <br><br>$threadlink $quote
	    <br>$edit
            <br>$ip</font></nobr>
	  <tr>
	$set[tdbg] colspan=2 height=50>$post[headtext]$post[text]$post[signtext]$set[edited]<br>&nbsp;</td>
    ";
  }

?>