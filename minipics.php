<?php
  require "lib/function.php";
  require "lib/layout.php";
  $cell="$tccellh width=12>&nbsp</td>$tccellh width=34%>Username";
  print "$header<br>$tblstart$cell</td>$cell</td>$cell<tr>";
  $i=-3;
  $users=mysql_query("SELECT id,name,sex,powerlevel,minipic FROM users WHERE minipic!='' ORDER BY name");
  while($user=mysql_fetch_array($users)){
    $i++;
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    print "
	$tccell2><img width=16 height=16 src=$user[minipic]></td>
	$tccell2ls><a href=profile.php?id=$user[id]><font $namecolor>$user[name]</td>
    ";
    if(!$i){
	$i=-3;
	print "<tr>";
    }
  }
  while($i){
    $i++;
    print "$tccell2ls&nbsp</td>$tccell2ls&nbsp</td>";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>