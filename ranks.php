<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if(!$set) $set=1;
  if(!$showall) $showall=0;
  $rsets=mysql_query('SELECT * FROM ranksets WHERE id>0 ORDER BY id');
  while($rset=mysql_fetch_array($rsets))
    $ranksetlist.="<option value=$rset[id] ".($rset[id]==$set?'selected':'').">$rset[name]";
  $ch[$showall]='checked';
  print "
	$header
	<FORM ACTION=ranks.php NAME=REPLIER>
	$tblstart
	$tccellh colspan=2>&nbsp;<tr>
	$tccell1><b>Rank set</b></td>
	$tccell2l><select name=set>$ranksetlist</select><tr>
	$tccell1><b>Users to show</b></td>
	$tccell2l>
		$radio=showall value=0 $ch[0]> Selected rank set only
		&nbsp; &nbsp;
		$radio=showall value=1 $ch[1]> All users
	<tr>
	$tccellh colspan=2>&nbsp;<tr>
	$tccell1>&nbsp;</td>
	$tccell2l><input type=submit class=submit value=View></td>
	</FORM>
	$tblend
	<br>
	$tblstart
	$tccellh width=150>Rank</td>
	$tccellh width=60>Posts</td>
	$tccellh width=60>Ranking</td>
	$tccellh colspan=2>Users on that rank
  ";
  $useranks=($showall?'':"AND useranks=$set");
  $btime=ctime()-86400*30;
  $ranks=mysql_query("SELECT * FROM ranks WHERE rset=$set ORDER BY num");
  $rank=mysql_fetch_array($ranks);
  for($i=0;$i<mysql_num_rows($ranks);$i++){
    $rankn=mysql_fetch_array($ranks);
    if(!$rankn[num]) $rankn[num]=8388607;
    $userlisting='';
    $usercount=mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE posts>=$rank[num] AND posts<$rankn[num] $useranks"),0,0);
    $usertotal=mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE posts>=$rank[num]"),0,0);
    $users=mysql_query("SELECT id,name,sex,powerlevel FROM users WHERE posts>=$rank[num] AND posts<$rankn[num] $useranks AND (lastactivity>$btime OR lastposttime>$btime) ORDER BY name");
    for($u=0;$user=mysql_fetch_array($users);$u++){
	$namecolor=getnamecolor($user[sex],$user[powerlevel]);
	$userlisting.=($u?', ':'')."<a href=profile.php?id=$user[id]><font $namecolor>$user[name]</font></a>";
    }
    $dif=$usercount-mysql_num_rows($users);
    if($dif) $userlisting.=($userlisting?', ':'')."$dif inactive";
    if(!$userlisting) $userlisting='&nbsp;';
    if($usercount or ($ismod or $rank[num]<=$loguser[posts])){
	print "<tr>
	 $tccell2ls width=150>$rank[text]</td>
	 $tccell1>$rank[num]</td>
	 $tccell1>$usertotal</td>
	 $tccell1>$usercount</td>
	 $tccell2s>$userlisting";
    }else{
	print "<tr>
	 $tccell2>? ? ?</td>
	 $tccell2>???</td>
	 $tccell2>?</td>
	 $tccell2>?</td>
	 $tccell2s>?";
    }
    $rank=$rankn;
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>