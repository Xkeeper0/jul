<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print "$header<br>$tblstart";
  if(!$id){
    $userlist="
	$tccell1s colspan=3>Sex: <a href=photo.php?sex=m>Male</a> | <a href=photo.php?sex=f>Female</a> | <a href=photo.php?sex=n>N/A</a> | <a href=photo.php>All</a><tr>
	$tccell2 colspan=3>Click on the pictures to view them in full size.<tr>
	$tccellh width=60>Picture</td>
	$tccellh>Username</td>
	$tccellh>Real name
    ";
    $where=($sex?'WHERE sex=':'').($sex=='m'?'0':'').($sex=='f'?'1':'').($sex=='n'?'2':'');
    $users=mysql_query("SELECT id,name,realname,sex,powerlevel FROM users $where ORDER BY name");
    $numusers=mysql_num_rows($users);
    for($usercount=0;$user=mysql_fetch_array($users);){
      $i=$user[id];
      if(file_exists("photo/$i"."s.jpg")){
        $usercount++;
        $userlist.='<tr>';
        $userpicture="<a href=photo.php?id=$i><img width=60 height=60 src=photo/$i"."s.jpg border=0>";
        $namecolor=getnamecolor($user[sex],$user[powerlevel]);
        $userlist.="
		$tccell2 width=60>$userpicture</td>
		$tccell2l><a href=profile.php?id=$i><font $namecolor>$user[name]</td>
		$tccell2l>$user[realname]&nbsp;
        ";
      }
    }
    print "$tccellh colspan=3>$usercount users found.<tr>$userlist";
  }else{
    $user=mysql_fetch_array(mysql_query("SELECT id,name,realname FROM users WHERE id=$id"));
    print "
	$tccellc>$user[name] -- $user[realname]<tr>
	$tccell2><img src=photo/$id.jpg><tr>
	$tccell1><a href=photo.php>Back to Photo Album
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>