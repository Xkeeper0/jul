<?php
  require 'lib/function.php';
  require 'lib/rpg.php';
  require 'lib/layout.php';
  function sortbyexp($a,$b){
    if($a[2]=='NAN' && $a[2]!='0') $a[2]=-1;
    if($b[2]=='NAN' && $b[2]!='0') $b[2]=-1;
    return($b[2]-$a[2]);
  }
  function sortbyrating($a,$b){return($b[1]-$a[1]);}
  if($sex) $qsex="&sex=$sex";
  if($pow) $qpow="&pow=$pow";
  if($page) $qpag="&page=$page";
  if($bio) $qbio="&bio=$bio";
  if($ppp) $qppp="&ppp=$ppp";
  if($rpg) $qrpg="&rpg=1";
  $q=$qppp.$qpag.$qbio.$qrpg;
  if(!$ppp) $ppp=50;
  if(!$page) $page=0;
  $lnk='<a href=memberlist.php?sort';
//  $clmn="$tccellhs>$lnk=$sort$qsex$qpow$qpag$qppp&bio=1>Show bio</a></td>";
//  if($bio) $clmn="$tccellh>User bio $smallfont($lnk=$sort$qsex$qpow$qpag$qppp>hide</a>)</td>";
  if($sort=='rating') $clmn="$tccellh colspan=3>User rating</td>";
  if($sex=='m') $where='WHERE sex=0';
  if($sex=='f') $where='WHERE sex=1';
  if($sex=='n') $where='WHERE sex=2';
  if($pow!=''){
	$pow	= intval($pow);
	if (($pow == 1 || $pow == 0) && $loguser['powerlevel'] < 3) {
		$pow	= "IN (0, 1)";
	} else {
		$pow	= "= '$pow'";
	}
    if($where) $where.=" AND powerlevel $pow";
    else $where="WHERE powerlevel $pow";
  }
  $query='SELECT id,posts,regdate,name,minipic,bio,powerlevel,sex,r.* FROM users LEFT JOIN users_rpg r ON id=uid ';
  if($sort=='posts' or $sort=='')		$users1=mysql_query("$query$where ORDER BY posts DESC");
  if($sort=='name')				$users1=mysql_query("$query$where ORDER BY name");
  if($sort=='reg')				$users1=mysql_query("$query$where ORDER BY regdate DESC");
  if($sort=='exp' or $sort=='rating')	$users1=mysql_query("$query$where");
  if($sort=='age'){
    if($sex=='m') $where='AND sex=0';
    if($sex=='f') $where='AND sex=1';
    if($sex=='n') $where='AND sex=2';
    $where.=($where?' AND birthday':'WHERE birthday');
    $users1=mysql_query("$query $where ORDER BY birthday") or print mysql_error();
  }
  $numusers=mysql_num_rows($users1);
  for($i=0;$user=mysql_fetch_array($users1);$i++){
    $user[days]=(ctime()-$user[regdate])/86400;
    $user[exp]=calcexp($user[posts],$user[days]);
    $user[lvl]=calclvl($user[exp]);
    $users[$user[id]]=$user;
    $rate[$i][0]=$user[id];
    $rate[$i][2]=$user[exp];
  }
  if($sort=='rating' && false){
    mysql_data_seek($users1,0);
    for($i=0;$user=mysql_fetch_array($users1);$i++){
	$ratescore=0;
	$ratetotal=0;
	$ratings=mysql_query("SELECT userfrom,userrated,rating FROM userratings WHERE userrated=$user[id]");
	while($rating=@mysql_fetch_array($ratings)){
	  $l=$users[$rating[userfrom]][lvl];
	  if($l<1) $l=1;
	  $ratescore+=$rating[rating]*$l;
	  $ratetotal+=10*$l;
	}
	$numvotes=@mysql_num_rows($ratings);
	$s=($numvotes>1?'s':'');
	if($numvotes){
	  $rate[$i][0]=$user[id];
	  $rate[$i][1]=$ratescore*100000/$ratetotal;
	  $users[$user[id]][5]='<center><b>'.(sprintf('%01.2f',$rate[$i][1]/10000))."</b></td>$tccell2>$ratescore / $ratetotal</td>$tccell2>$numvotes vote$s</td>";
	  $rate[$i][1]=$rate[$i][1]/10+10000;
	}else{
	  $numusers--;
	  $i--;
	}
    }
    usort($rate,'sortbyrating');
  }
  if($sort=='exp') usort($rate,'sortbyexp');
  $pagelinks=$smallfont.'Pages:';
  for($i=0;$i<($numusers/$ppp);$i++){
    $pagelinks.=($i==$page?' '.($i+1):" <a href=memberlist.php?sort=$sort$qsex$qpow$qbio$qrpg$qppp&page=$i>".($i+1).'</a>');
  }
  if($numusers>1) $s="s";
  print "
	$header<br>$tblstart
	$tccellh colspan=2>$numusers user$s found.<tr>
	$tccell1s>	Sort by:
	$tccell2s>
	 $lnk=posts$q$qpow$qsex>Total posts</a> | 
	 $lnk=exp$q$qpow$qsex>EXP</a> | 
	 $lnk=name$q$qpow$qsex>User name</a> | 
	 $lnk=reg$q$qpow$qsex>Registration date</a> | 
	 $lnk=age$q$qpow$qsex>Age</a> ". (false ? "| 
	 $lnk=rating$q$qpow$qsex>Rating</a>" : "") ."<tr>
	$tccell1s>	Sex:
	$tccell2s>
	 $lnk=$sort$q$qpow&sex=m>Male</a> | 
	 $lnk=$sort$q$qpow&sex=f>Female</a> | 
	 $lnk=$sort$q$qpow&sex=n>N/A</a> | 
	 $lnk=$sort$q$qpow>All</a><tr>
	$tccell1s>	Powerlevel:
	$tccell2s>
	 $lnk=$sort$q$qsex&pow=-1>Banned</a> | 
	 $lnk=$sort$q$qsex&pow=0>Normal</a> | 
	 ". ($loguser['powerlevel'] >= 3 ? "$lnk=$sort$q$qsex&pow=1>Normal +</a> | " : "") ."
	 $lnk=$sort$q$qsex&pow=2>Moderator</a> | 
	 $lnk=$sort$q$qsex&pow=3>Administrator</a> | 
	 $lnk=$sort$q$qsex>All</a>
	$tblend<br>$tblstart
	$tccellh width=30>#</td>
	$tccellh width=16><img src=images/_.gif width=16 height=8></td>
	$tccellh>Username</td>
  ";
  if(!$rpg){
    print "
	$clmn
	$tccellh width=150>Registered on</td>
	$tccellh width=60>Posts</td>
	$tccellh width=30>Level</td>
	$tccellh width=100>EXP<tr>
    ";
  }else{
    print "$tccellh width=30>Level</td>";
    for($i=0;$i<9;$i++) print "$tccellh width=6%>".$stat[$i].'</td>';
    print "$tccellh width=9%><img src=images/coin.gif><tr>";
  }
  $i=$ppp*$page;
  for($u=0;$rate[$i] && $i<($ppp*($page+1)) && $i<$numusers;$u++){
    if($u) $ulist.='<tr>';
    $user=$users[$rate[$i][0]];
    $t=$user[0];
    $user[picture]=str_replace('>','%3E',$user[minipic]);
    $userpicture="<img width=16 height=16 src=\"$user[minipic]\">";
    if(!$user[picture]) $userpicture='&nbsp';
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
//    if(!$bio and $sort!='rating') // $user[5]='&nbsp;';
    if($rpg){
	$eqitems=mysql_query("SELECT * FROM items");
	while($item=mysql_fetch_array($eqitems)) $items[$item[id]]=$item;
	$stats=getstats($user,$items);
    }
    $ulist.="
	$tccell2>".($i+1).".</td>
	$tccell1l>$userpicture</td>
	$tccell2l><a href=profile.php?id=$t><font $namecolor>$user[3]</font></a></td>
    ";
    if(!$rpg){
	$ulist.="
	  $tccell2>".date($dateformat,$user[2]+$tzoff)."</td>
	  $tccell1>$user[1]</td>
	  $tccell1>$user[lvl]</td>
	  $tccell1>$user[exp]
	";
    }else{
	$ulist.="$tccell1>$user[lvl]</td>";
	for($k=0;$k<9;$k++) $ulist.="$tccell1>".$stats[$stat[$k]].'</td>';
	$ulist.="$tccell1>$stats[GP]</td>";
    }
    $i++;
  }
  print "$ulist$tblend$pagelinks$footer";
  printtimedif($startingtime);
?>