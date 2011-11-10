<?php
	require 'lib/function.php';
	require 'lib/layout.php';

	$vd=date('m-d-y', ctime());
	if (!$m && !$d && !$y) {
		$m	= date("m", ctime() - 86400);
		$d	= date("d", ctime() - 86400);
		$y	= date("y", ctime() - 86400);
	}
	if(!$v){
		$v=0;
		$dd		= mktime(0,0,0,substr($vd,0,2),substr($vd,3,2),substr($vd,6,2));// + (3*3600);
		$dd2	= mktime(0,0,0,substr($vd,0,2),substr($vd,3,2)+1,substr($vd,6,2));// + (3*3600);
	}else{
		$dd		= mktime(0,0,0,$m,$d,$y);// + (3*3600);
		$dd2	= mktime(0,0,0,$m,$d+1,$y);// + (3*3600);
	}


	$users			= mysql_query("SELECT u.id,u.name,u.sex,u.powerlevel,COUNT(*) AS cnt FROM users AS u,posts AS p WHERE p.user=u.id AND p.date>=$dd AND p.date<$dd2 AND u.powerlevel >= 0 GROUP BY u.id ORDER BY cnt DESC");
	$i=0;
	if(!$u) $u=1;
	if($u==1) $n	= $loguser[name];
	if($u==2) $n	= $user;
	if(!$view || $view <= 0 || $view > 2) $view=0;
	$ch1[$v]	= 'checked';
	$ch2[$u]	= 'checked';
	$ch3[$view]	= 'checked';
	$tposts		= mysql_result(mysql_query("SELECT COUNT(*) AS cnt FROM posts WHERE posts.date>$dd AND posts.date<$dd2"),0,'cnt');
	$rcount		= ($tposts >= 400 ? 10 : 5);
	$spoints	= ($tposts >= 400 ? 11 : 8);
	$desc="</b><br>$smallfont";
	print "
		$header
		<br><form action=acs.php>
		$tblstart
		$tccellh colspan=2>Currently viewing ".date('m-d-y',$dd)."<tr>
		$tccell1><b>Day:$desc Select the day to view rankings from. (mm-dd-yy format)</td>
		$tccell2l>$radio=v value=0 $ch1[0]> Today &nbsp; $radio=v value=1 $ch1[1]> Other: $inpt=m VALUE=\"$m\" SIZE=2 MAXLENGTH=2>-$inpt=d VALUE=\"$d\" SIZE=2 MAXLENGTH=2>-$inpt=y VALUE=\"$y\" SIZE=2 MAXLENGTH=2><tr>
		$tccell1><b>User:$desc This user will be highlighted.</td>
		$tccell2l>$radio=u value=0 $ch2[0]> None &nbsp; $radio=u value=1 $ch2[1]> You &nbsp; $radio=u value=2 $ch2[2]> Other: $inpt=user VALUE=\"$user\" SIZE=25 MAXLENGTH=25><tr>
		$tccell1><b>View format:</b></td>
		$tccell2l>$radio=view value=0 $ch3[0]> Full rankings &nbsp; $radio=view value=1 $ch3[1]> Rankers &nbsp; $radio=view value=2 $ch3[2]> JCS form<tr>
		$tccell1>&nbsp;</td>
		$tccell2l><input type=submit value=Submit>
		$tblend
		</form>
		$tblstart
		";
	$max=1;
	if($view<2){
		print "
			$tccellh width=30>#</td>
			$tccellh>Name</td>
			$tccellh width=50>Posts
			$tccellh>Total: $tposts
			";
		while($user=mysql_fetch_array($users)){
			if($user[cnt]>$max) $max=$user[cnt];
			$i++;
			if($rp!=$user[cnt]) $r=$i;
			$rp=$user[cnt];
			if($rr<=$rcount && $r>$rcount && $view==0) print "<tr>$tccellc colspan=4><img src='images/_.gif' height='4' width='1'></td>";
			$rr=$r;
			$b='';
			$td=$tccell1;

			if($r>$rcount) $td=$tccell2;
			if($user[name]==$n){
				$td=$tccellc;
				$b='<b>';
			}

			$tdl=str_replace(' center','',$td);
			if($view==0 or ($view==1 and ($r<=$rcount or $n==$user[name]))) {
				print "
					<tr>
					$td>$b$r</b></td>
					$tdl><a href=profile.php?id=$user[id]><font ".getnamecolor($user[sex],$user[powerlevel]).">". (!$_GET['dur'] ? $user[name] : "DU". str_repeat("R", mt_rand(1,25))) ."</font></a></td>
					$td>$b$user[cnt]</b></td>
					$tdl><img src=images/$numdir"."bar-on.gif width=".($user[cnt]*100/$max)."% height=8>
					";
			}
		}
	} else {
		$usersy=mysql_query("SELECT users.id,users.name,users.sex,users.powerlevel,COUNT(posts.id) AS cnt FROM users,posts WHERE posts.user=users.id AND posts.date>".($dd-86400)." AND posts.date<$dd GROUP BY users.id ORDER BY cnt DESC");
		$i=0;
		while($user=mysql_fetch_array($usersy) and $r <= $rcount ) {
			$i++;
			if($rp!=$user[cnt]) $r=$i;
			$rp=$user[cnt];
			if($r<=5) $ranky[$user[id]]=$r;
		}
		$i=0;
		$rp=0;
		$r=0;
		while($user=mysql_fetch_array($users) and $r <= $rcount){
			$i++;
			if($rp!=$user[cnt]){
				$r=$i;
				if($tend) $tie='';
				if($tie) $tend=1;
			}else{
				$tie='T';
				$tend=0;
			}
			$posts[$user[id]]=$user[cnt];
			$ry=$ranky[$user[id]];
			if(!$ry) $ry='NR';
			$rp=$user[cnt];
			$dailyposts		.= $tie . $ndailyposts;
			$dailypoints	.= $tie . $ndailypoints;
//			$ndailyposts	= "$tie$r) ". $user['name'] ." - ". $user['cnt'] ." - ". ($spoints - $r) ."<br>";
//			$ndailyposts	= "$tie$r) ". $user['name'] ." - ". $user['cnt'] ." - ". ($spoints - $r) ."<br>";
			$ndailyposts	= "$r) ". $user['name'] ." - ". $user['cnt'] ."<br>";
			$ndailypoints	= "$r) ". $user['name'] ." - ". ($spoints - $r) ."<br>";
		}
		if($r<=$rcount){
			if($tend) $tie='';
	//			$dailyposts.=$tie.$ndailyposts;
	//			$dailypoints.=$tie.$ndailypoints;
		}
		$lose=$user[cnt];
		@mysql_data_seek($usersy,0);
		$i=0;
		$rp=0;
		$r=0;
		while($user=mysql_fetch_array($usersy) and $r<=$rcount){
			$i++;
			if($rp!=$user[cnt]) $r=$i;
			$rp=$user[cnt];
			if($posts[$user[id]]<=$lose && $r<=$rcount) $offcharts.=($offcharts?', ':'OFF THE CHARTS: ')."$user[name] ($r)";
		}
		print "
			$tccell1l>
			". strtoupper(date('F j',$dd)) ."<br>".
			"---------<br><br>".
			"TOTAL NUMBER OF POSTS: $tposts<br><br>".
			"$dailyposts<br><br>".
			"DAILY POINTS<br>".
			"--------------------<br>".
			"$dailypoints";
	}
	print $tblend.$footer;
	printtimedif($startingtime);
