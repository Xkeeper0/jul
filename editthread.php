<?php
	require 'lib/function.php';
	require 'lib/layout.php';
	$threads=mysql_query("SELECT forum,closed,title,icon,replies,lastpostdate,lastposter,sticky FROM threads WHERE id=$id");
	$thread=mysql_fetch_array($threads);
	$forumid=$thread[forum];
	$posticons=file('posticons.dat');
	
	$mods=(mysql_fetch_assoc(mysql_query("SELECT user FROM forummods WHERE forum=$forumid and user=$loguserid")) == false ? false : true);

	print "$header<br>$tblstart";
	
	if ($_POST['action'] == "trashthread") {
		if ($ismod || $mods) {
			mysql_query("UPDATE threads SET sticky=0, closed = 1, forum = 27 WHERE id = '$id'");
			$numposts = $thread[replies] + 1;
			$t1 = mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
			$t2 = mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=27 ORDER BY lastpostdate DESC LIMIT 1"));
			mysql_query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
			mysql_query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$trashid");
			print "THREAD DELETED~! (note to self: add better confirmation page... eventually.)".redirect("thread.php?id=$id", "herp and derp", 0);
		}
	}
	if ($_GET['action'] == 'trashthread') {
		if ($ismod || $mods) {
			print "
				<form action='editthread.php' name='trashcompactor' method='post'>
					<tr>$tccell1><input type='hidden' value='trashthread' name='action'>
					Are you sure you want to trash this thread?<br>
					<input type='hidden' value='$id' name='id'>
					<input type='submit' value='Trash Thread'> -- <a href='/thread.php?id=$id'>Cancel</a></td></tr>
				</form>";
		}
	}
	
	if(@mysql_num_rows($mods)) $ismod=1;
	if(!$action && $ismod) {
		$thread[icon]=str_replace("\n","",$thread[icon]);
		$customicon=$thread[icon];
		
		for($i=0;$posticons[$i];){
			$posticons[$i]=str_replace($br,"",$posticons[$i]);
			
			if($thread[icon]==$posticons[$i]){
				$checked='checked=1';
				$customicon='';
			}
			
			$posticonlist.="<INPUT type=radio class=radio name=iconid value=$i $checked>&nbsp<IMG SRC=$posticons[$i] HEIGHT=15 WIDTH=15>&nbsp &nbsp";
			$i++;
			if($i%10==0) $posticonlist.='<br>';
			$checked='';
		}
		
		if(!$thread[icon]) $checked='checked=1';
		$posticonlist.="
		<br>$radio=iconid value=-1 $checked>&nbsp None &nbsp &nbsp
		Custom: $inpt=custposticon VALUE='$customicon' SIZE=40 MAXLENGTH=100>
		";
		$check1[$thread[closed]]='checked=1';
		$check2[$thread[sticky]]='checked=1';
		$forums=mysql_query("SELECT id,title FROM forums WHERE minpower<='$power' ORDER BY forder");
		while($forum=mysql_fetch_array($forums)){
		$checked='';
		if($thread[forum]==$forum[id]) $checked='selected';
		$forummovelist.="<option value=$forum[id] $checked>$forum[title]</option>";
		}
		print "
		 <FORM ACTION=editthread.php NAME=REPLIER METHOD=POST>
		 $tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		 $tccell1><b>Thread title:</b></td>	$tccell2l>$inpt=subject VALUE=\"$thread[title]\" SIZE=40 MAXLENGTH=100><tr>
		 $tccell1><b>Thread icon:</b></td>	$tccell2l>$posticonlist<tr>
		 $tccell1 rowspan=2>&nbsp</td>	$tccell2l>$radio=closed value=0 $check1[0]> Open&nbsp &nbsp$radio=closed value=1 $check1[1]>Closed<tr>
								$tccell2l>$radio=sticky value=0 $check2[0]> Normal&nbsp &nbsp$radio=sticky value=1 $check2[1]>Sticky<tr>
		 $tccell1><b>Forum</b></td>		$tccell2l><select name=forummove>$forummovelist</select> <INPUT type=checkbox class=radio name=delete value=1>Delete thread<tr>
		 $tccell1>&nbsp</td>$tccell2l>
		 $inph=action VALUE=editthread>$inph=id VALUE=$id>
		 $inps=submit VALUE=\"Edit thread\"></td></FORM>
		$tblend
		";
	}
	


  if($_POST[action]=='editthread'){
    if($ismod){
	print "
	  $tccell1>Thank you, $loguser[name], for editing the thread.<br>
	  ".redirect("forum.php?id=$forumid",'return to the forum',0).$tblend;
	$posticons[$iconid]=str_replace("\n",'',$posticons[$iconid]);
	if(!$delete){
	  $icon=$posticons[$iconid];
	  if($custposticon) $icon=$custposticon;
	  mysql_query("UPDATE `threads` SET `forum` = '$forummove', `closed` = '$closed', `title` = '$subject', `icon` = '$icon', `sticky` = '$sticky' WHERE `id` = '$id'");
	  if($forummove!=$forumid){
	    $numposts=$thread[replies]+1;
	    $t1=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
	    $t2=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forummove ORDER BY lastpostdate DESC LIMIT 1"));
	    mysql_query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
	    mysql_query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$forummove");
	  }
	}else{
	  mysql_query("DELETE FROM threads WHERE id=$id");
// ******** TO DO **********
// make this only hide the posts; deleting unrecoverable information is very very bad
//	  mysql_query("DELETE FROM posts WHERE thread=$id");
	  $numdeletedposts=$thread[replies]+1;
	  $t1=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
	  mysql_query("UPDATE forums SET numposts=numposts-$numdeletedposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
	}
    }else
      print "
	  $tccell1 Couldn't edit the thread. Either you didn't enter an existing username,
	  or you haven't entered the right password for the username, or you are not allowed to edit this thread.<br>
	  ".redirect("thread.php?id=$id",'return to the thread',0);
  }
  print $footer;
  printtimedif($startingtime);
?>