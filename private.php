<?php 
	require 'lib/function.php';
	require 'lib/layout.php';
	$u   = $loguserid;
	if($isadmin and $id){
		$u=$id;
		$idparam="id=$id&";
	}

	$to='to';
	$from='from';

	if($view=='sent'){
		$to='from';
		$from='to';
		$viewparam='view=sent&';
	}
	if(!$ppp) {
		$ppp=50;
	}
	if(!$page) { 
		$page=1;
	}
	$pmin=($page-1)*$ppp;
	$msgtotal=mysql_result(mysql_query("SELECT count(*) FROM pmsgs WHERE user$to=$u"),0,0);
	$pagelinks='Pages:';
	$p=0;
	for($i=0; $i<$msgtotal; $i+=$ppp) {
		$p++;
		if($p==$page) {
			$pagelinks.=" $p";
		} else {
			$pagelinks.=" <a href=private.php?$idparam$viewparam"."page=$p>$p</a>";
		}
	}

//	1252378129
	$pmsgs   = mysql_query("SELECT p.id,user$from u,date,t.title,msgread,name,sex,powerlevel FROM pmsgs p,pmsgs_text t,users u WHERE user$to=$u AND p.id=pid AND user$from=u.id ". ($loguser['id'] == 175 && true ? "AND p.id > 8387 " : "") ."ORDER BY ". ($loguser['id'] == 175 && true ? "user$from DESC, " : "msgread ASC, ") ."p.id DESC LIMIT $pmin,$ppp");
	$from[0] = strtoupper($from[0]);

	if(!$view) { 
		$viewlink="<a href=private.php?$idparam"."view=sent>View sent messages</a>";
	} else {
		$viewlink="<a href=private.php?$idparam>View received messages</a>";
	}
	print "
	$header
	<table width=100%><td>$fonttag<a href=index.php>$boardname</a> - Private messages: $msgtotal</td>
	<td align=right>$smallfont$viewlink | <a href=sendprivate.php>Send new message</table>
	$tblstart
	$tccellh>&nbsp</td>
	$tccellh>Subject</td>
	$tccellh>$from</td>
	$tccellh width=150>Sent on
	";
	while($pmsg=mysql_fetch_array($pmsgs)){
		$new       = ($pmsg[msgread]?'&nbsp;':$statusicons['new']);
		$uid       = $pmsg[u];
		$namecolor = getnamecolor($pmsg[sex],$pmsg[powerlevel]);
		print "
		<tr>
		$tccell1>$new</td>
		$tccell2l><a href=showprivate.php?id=$pmsg[id]>$pmsg[title]</td>
		$tccell2><a href=profile.php?id=$pmsg[u]><font $namecolor>$pmsg[name]</td>
		$tccell2>".date($dateformat,$pmsg[date]+$tzoff)
		;
	}
	print "$tblend$smallfont$pagelinks$footer";
	printtimedif($startingtime);
?>
