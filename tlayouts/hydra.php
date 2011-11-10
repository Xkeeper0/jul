<?php
	function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location,lastposttime,lastactivity';}

	function postcode($post,$set){
		global $tzoff, $smallfont, $ip, $quote, $edit, $dateshort, $dateformat, $tlayout, $textcolor, $numdir, $numfil, $tblstart, $hacks, $x_hacks, $loguser;

		$tblend  = "</table>";
		$exp     = calcexp($post[posts],(ctime()-$post[regdate])/86400);
		$lvl     = calclvl($exp);
		$expleft = calcexpleft($exp);

		$reinf=syndrome($post[act]);

		$sincelastpost = "";
		$lastactivity = "";
		$since='Since: '.@date($dateshort,$post[regdate]+$tzoff);

		$postdate  =  date($dateformat,$post[date]+$tzoff);

		if($set[threadlink]) { $threadlink=", in $set[threadlink]"; }

		/* if($post[edited]){
			$set[edited].="<hr>$smallfont$post[edited]";
		}*/

		$sidebars	= array(1, 16, 18, 19, 387);

		return "
		$tblstart
		$set[tdbg] style='width: 20% !important;' rowspan='2'>
			$set[userlink]$smallfont<br>
			<center>$set[userpic]</center><br>
			$post[title]<br><br>
		</td>

		$set[tdbg] height=1>
		<table class='fonts' style='clear: both; width: 100%;'>
			<tr>
				<td>Posted on $postdate$threadline$post[edited]</td>
				<td style='float: right;'>$quote$edit$ip</td>
			</tr>
		</table><tr>
		$set[tdbg] style='overflow: visible; width: 70%;' height=220>$post[headtext]$post[text]$post[signtext]</td></tr>
		$tblend
		<br>";

		if (!$set['picture']) $set['picture']	= "images/_.gif";

		if ($_GET['z']) {
			print_r($st['eq']);
		}
	}
?>
