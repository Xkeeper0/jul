<?php
  require "function.php";
  require "layout.php";

function aslash($text) {
 $text2=str_replace("\"","&#34",$text);
 $text2=stripslashes($text2);
 return $text2;
}

  $forums=readforums();
  $fonline=fonlineusers($id);
  $users1=mysql_query("SELECT id,posts,regdate,name,password,powerlevel,signature,postheader,postbg FROM users");
  while($user=mysql_fetch_array($users1)) $users[$user[id]]=$user;
  $ranks=readranks();
  $smilies=readsmilies();
  $inumsmilies=numsmilies();
  $tccellha="<td bgcolor=$tableheadbg";
  $tccellhb="><center>$fonthead";
  print $header;
  replytoolbar(1);
  $forumid=$id;
  if(!$action and $forumid>-1){
  print "$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>".$forums[$forumid][title]."</a>
   <table bgcolor=$tableborder width=$tablewidth cellpadding=0 cellspacing=0>
    <td>
     <FORM ACTION=newpoll.php NAME=REPLIER METHOD=POST>
     <table cellpadding=2 cellspacing=1 width=100%>
  ";
  $posticons=file("posticons.dat");
  $i=0;
  while($posticons[$i]){
    $posticonlist.="$radio=iconid value=$i>&nbsp<IMG SRC=$posticons[$i] HEIGHT=15 WIDTH=15>&nbsp &nbsp";
    $i++;
    if (round($i/10)==($i/10)) {$posticonlist.="<br>";}
  }
  $posticonlist.="
    <br>$radio=iconid value=-1 checked=1>&nbsp;None&nbsp &nbsp &nbsp
    Custom: $inpt=custposticon SIZE=40 MAXLENGTH=100>
  ";
  if($logpwenc and $loguserid and $forums[$id][minpowerthread]>$loguser[7]){
    print "$tccell1 Sorry, but you are not allowed to post";
    if($loguser[7]==-1) print ", because you are banned from this board.<br>".redirect("forum.php?id=$id","return to the forum",0); 
    else print " in this restricted forum.<br>".redirect("index.php","return to the board",0);
  }else{
    print "
	<body onload=window.document.REPLIER.subject.focus()>
	$tccellha width=150$tccellhb&nbsp</td>$tccellh&nbsp<tr>
	$tccell1<b>User name:</td>	$tccell2l$inpt=username VALUE=\"$loguser[2]\" SIZE=25 MAXLENGTH=25><tr>
	$tccell1<b>Password:</td>	$tccell2l$inpp=password VALUE=\"$logpassword\" SIZE=13 MAXLENGTH=32><tr>
	$tccell1<b>Thread title:</td>	$tccell2l$inpt=subject SIZE=40 MAXLENGTH=100><tr>
	$tccell1<b>Thread icon:</td>	$tccell2l$posticonlist<tr>
	$tccell1<b>Post:</td>		$tccell2l".replytoolbar(2)."$txta=message ROWS=20 COLS=$numcols ".replytoolbar(3)."></TEXTAREA><tr>
	$tccell1&nbsp</td>$tccell2l
	$inph=action VALUE=postthread>
	$inph=id VALUE=$id>
	$inps=submitth VALUE=\"The thread is done, now for the poll \">
	$inps=preview VALUE=\"Preview thread\"></td></FORM>
    ";
  }
  print "
     </table>
   </table>
   $fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>".$forums[$forumid][title]."</a>
   ".replytoolbar(4);
  }
  if($action=="postthread"){
    print "
    <table bgcolor=$tableborder width=$tablewidth cellpadding=0 cellspacing=0>
     <td>
      <table cellpadding=2 cellspacing=1 width=100%>
    ";
    $userid=checkuser($username,$password);
    $user=$users[$userid];
    if($user[powerlevel]<0) $userid=-1;
    if($userid!=-1 and $subject and $message and $user[powerlevel]>=$forums[$forumid][minpowerthread] and $forums[$forumid][title]){   
      $message=str_replace("\x22","&quot;",$message);
      $msg=$message;
      $message=str_replace("&quot;","\x22",$message);
      $posticons=file("posticons.dat");
      $posticon=$posticons[$iconid];
      if($iconid==-1) $posticon="";
      if($custposticon) $posticon=$custposticon;
      if($user[signature]) $sign="<br><br>--------------------<br>$user[signature]";
      if($user[postheader]) $head=$user[postheader];
      if($user[postbg]) $head="<div style=background:url($user[postbg]);height=100%>".$head;
      $numposts=$user[posts]+1;
      $numdays=(ctime()-$user[regdate])/86400;
      $head=doreplace($head,$numposts,$numdays,$username);
      $message=doreplace($message,$numposts,$numdays,$username);
      $sign=doreplace($sign,$numposts,$numdays,$username);
      $t--;
      $s=0;
      while($smilies[$s][0]){
        $smilie=$smilies[$s];
        $smile=$smilie[0];
        $message=str_replace($smile,"<img src=$smilie[1]>",$message);
        $head=str_replace($smile,"<img src=$smilie[1]>",$head);
        $sign=str_replace($smile,"<img src=$smilie[1]>",$sign);
        $s++;
      }
      $message=str_replace($br,"<br>",$message);
      if($submit){
        $currenttime=ctime();
        $postnum=$user[posts]+1;
       if ($doublevote == "yes") { $doublevote = 1; } else { $doublevote=0; }
        mysql_query("UPDATE users SET posts=posts+1,lastposttime=$currenttime WHERE id=$userid");
        mysql_query("INSERT INTO poll (id,question,briefing,closed,doublevote) VALUES (NULL,'".addslashes($pollquestion)."','".addslashes($pollbriefing)."',0,$doublevote)");
        $p=mysql_insert_id();
        foreach ($pollchoices as $chid => $data) {
          $ccolor = $data[color];
          print "<!-- $chid» color: $ccolor | name: $data[choice] | poll: $p -->";
          $cname = $data[choice];
          mysql_query("INSERT INTO poll_choices (id,poll,choice,color) VALUES (NULL,$p,'".addslashes($cname)."','".addslashes($ccolor)."')");
          $moo = mysql_affected_rows();
          print "<!-- $chid» affected rows: $moo -->";
        }
        mysql_query("INSERT INTO threads (id,forum,user,views,closed,title,icon,replies,lastpostdate,lastposter,pollid) VALUES (NULL,$id,$userid,0,0,'".addslashes($subject)."','".addslashes($posticon)."',0,$currenttime,$userid,$p)");
        $t=mysql_insert_id();
        mysql_query("INSERT INTO posts (id,thread,user,date,ip,text,num,headtext,signtext) VALUES (NULL,$t,$userid,$currenttime,'".addslashes($userip)."','".addslashes($message)."',$postnum,'".addslashes($head)."','".addslashes($sign)."')");
        mysql_query("UPDATE forums SET numthreads=numthreads+1,numposts=numposts+1,lastpostdate=$currenttime,lastpostuser=$userid WHERE id=$id");
        print "
		$tccell1 Thank you, $user[name], for submitting your new thread and poll.
		<br>".redirect("thread.php?id=$t","go to the thread",0)."</table></table>";
      }elseif($preview){
        if($posticon) $posticon1="<img src=$posticon height=15>";
        print "
		<body onload=window.document.REPLIER.submitth.focus()>
		$tccell1 This is a preview of your post. Once you're done previewing the post, go back to the previous page to make changes to it, or click on the button below to go on to preparing the poll.<tr>
		$tccell2l$posticon1 <b>$subject</b><hr>$head$message$sign<tr>
		$tccell1
		<FORM ACTION=newpoll.php NAME=REPLIER METHOD=POST>
		$inph=username VALUE=\"$username\">
		$inph=password VALUE=\"$password\">
		$inph=subject VALUE=\"$subject\">
		$inph=message VALUE=\"$msg\">
		$inph=iconid VALUE=$iconid>
		$inph=custposticon VALUE=\"$custposticon\">
		$inph=action VALUE=postthread>
		$inph=id VALUE=$id>
		$inps=submitth VALUE=\"Submit thread and setup the poll\"></td></FORM>
		</table></table>
        ";
      }elseif($submitth || $removechoice || $addchoice){
//        $foo=array_keys($pollchoices);
        if ($addchoice) {
          $pollchoices[] = array('color' => $polladdcolor,
                                 'choice' => $polladdchoice);
        }
        if ($removechoice) {
          $pc2 = $pollchoices;
          foreach($pc2 as $ccid=>$data) {
            if ($ccid != $torem) {
              $pc3[$ccid] = $data;
            }
          }
          $pollchoices = $pc3; 
/*          $key_index = array_keys(array_keys($pollchoices), array_pop($removechoice));
          array_splice($pollchoices, $key_index[0], 1); */
        }        
	print "<body onload=window.document.REPLIER.pollquestion.focus()>
	$tccellha width=250$tccellhb&nbsp</td>$tccellh&nbsp<tr>
         $tccell1a colspan=2>$fonttag Now the thread itself is ready for posting, but before you do
                  that, you will have to enter a few details about the poll and enter choices. <!-- '$foo' '$removechoice' --> <!--removechoice:";
                  print_r($removechoice); print "--> <!--removechoice keys:";
                  print_r($foo); print "--> 
		<FORM ACTION=newpoll.php NAME=REPLIER METHOD=POST>
		$inph=username VALUE=\"$username\">
		$inph=password VALUE=\"$password\">
		$inph=subject VALUE=\"$subject\">
		$inph=message VALUE=\"$msg\">
		$inph=iconid VALUE=$iconid>
		$inph=torem VALUE=\"\">
		$inph=custposticon VALUE=\"$custposticon\">
		$inph=action VALUE=postthread>
		$inph=id VALUE=$id>";
        
        if ($polldbl == "yes") { 
          $doublevote = "$radio=polldbl VALUE=\"yes\" CHECKED> Allow double voting
                         $radio=polldbl VALUE=\"no\"> Don't allow double voting";
        } else {
          $doublevote = "$radio=polldbl VALUE=\"yes\"> Allow double voting
                         $radio=polldbl VALUE=\"no\" CHECKED> Don't allow double voting";
        }
        $tcheader = "$tccellha colspan=2 $tccellhb";
        $halfcols = $numcols/2;
        print "<tr>$tcheader Poll setup<tr>
               $tccell1<b>Poll question: $tccell2l$inpt=pollquestion VALUE=\"".aslash($pollquestion)."\"><tr>
               $tccell1<b>Poll briefing: $tccell2l$txta=pollbriefing ROWS=10 COLS=".$halfcols.">$pollbriefing</textarea><tr>
               $tccell1<b>Poll options: $tccell2l$doublevote<tr>
               $tcheader Choice setup<tr>";

        if (is_array($pollchoices)) {
          $m = 0;
          foreach($pollchoices as $cid => $data) {
          $m++;
            $choicescode .= "$tccell1<b>Choice ".$m."$tccell2l$inpt=\"pollchoices[$cid][choice]\" VALUE=\"".aslash($data['choice'])."\"> Color: $inpt=\"pollchoices[$cid][color]\" VALUE=\"".aslash($data['color'])."\"> $inps=\"removechoice\" onClick=\"window.document.REPLIER.torem.value='$cid';\" VALUE=\"Remove\"><tr>";
          }
        }
          print "$choicescode
                 $tccell1<b>Add choice$tccell2l$inpt=\"polladdchoice\" VALUE=\"\"> Color: $inpt=\"polladdcolor\" VALUE=\"\"> $inps=addchoice VALUE=\"Add choice\"><tr>
                 $tccell1&nbsp$tccell2l$inps=submit VALUE=\"Submit poll and thread\"></td></form></table></table>";
      }
    }else{
      print "
	  $tccell1 Couldn't enter the post. Either you didn't enter an existing username,
	  or you haven't entered the right password for the username, or you haven't entered a subject.
	  <br>".redirect("forum.php?id=$id","return to the forum",0)."</table></table>";
    }
  }
  print $footer;
  printtimedif($startingtime);
?>