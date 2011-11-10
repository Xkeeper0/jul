<?php
  return header("Location: ./");
  die();

  require 'lib/function.php';
  $windowtitle="$boardname -- Search";
  require 'lib/layout.php';
  $desc="</b><br>$smallfont</center>";
  $forums=mysql_query("SELECT id,title FROM forums WHERE minpower<=$power ORDER BY forder");
  while($forum=mysql_fetch_array($forums)) $forumlist.="<option value=$forum[id]>$forum[title]</option>";
  if($isadmin)
    $ip="
	$tccell1><b>IP:$desc Search for user via IP, '%' is the wildcard (ex.: 206.172.% for 206.172.*.*)</td>
	$tccell2l>$inpt=qip SIZE=15 MAXLENGTH=15><tr>";
  print "
	$header<br>$tblstart
	<FORM ACTION=thread.php>
	 $tccellh width=150>&nbsp</td>
	 $tccellh>&nbsp<tr>
	 $tccell1><b>User name:$desc Enter the username of the user's posts you want to see (no wildcards).</td>
	 $tccell2l>$inpt=quser SIZE=25 MAXLENGTH=25><tr>
	 $ip
	 $tccell1><b>Post:$desc Search for text in a post. '%' is the wildcard.</td>
	 $tccell2l>$inpt=qmsg SIZE=50 MAXLENGTH=200><tr>
	 $tccell1><b>Date:$desc Search within a date range. (mm-dd-yy format)</td>
	 $tccell2l>
	  $radio=dopt value=0> All posts<br>
	  $radio=dopt value=1 checked> Last $inpt=datedays SIZE=4 MAXLENGTH=4 VALUE=30> days<br>
	  $radio=dopt value=2> From $inpt=d1m SIZE=2 MAXLENGTH=2>-$inpt=d1d SIZE=2 MAXLENGTH=2>-$inpt=d1y SIZE=2 MAXLENGTH=2> to $inpt=d2m SIZE=2 MAXLENGTH=2>-$inpt=d2d SIZE=2 MAXLENGTH=2>-$inpt=d2y SIZE=2 MAXLENGTH=2><tr>
	 $tccell1><b>Post ordering:$desc Disabling this can speed up the search a lot in some cases.</td>
	 $tccell2l>$radio=pord value=0 checked> Disabled &nbsp; $radio=pord value=1> Oldest first &nbsp; $radio=pord value=2> Newest first<tr>
	 $tccell1><b>Forum:$desc Search within a forum.</td>
	 $tccell2l>
	  $radio=fsch value=0 checked> All forums<br>
	  $radio=fsch value=1> Only in <select name=fid>$forumlist</select><tr>
	 $tccell1>&nbsp</td>
	 $tccell2l>
	 $inps=search VALUE=Search></td></FORM>
	$tblend$footer
  ";
  printtimedif($startingtime);
?>