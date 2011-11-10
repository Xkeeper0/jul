<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print $header;
  if($log && !$_POST[action]){
    $users1=mysql_query('SELECT id,name,posts FROM users ORDER BY name');
    while($user=mysql_fetch_array($users1)){
	$users[$user[id]]=$user;
	$addlist.="<option value=$user[id]>$user[name] -- $user[posts] posts";
    }
    $addlist="
	<select name=add>
	 <option value=0 selected>Do not add anyone
	 $addlist
	</select>
    ";
    $users1=mysql_query("SELECT * FROM postradar WHERE user=$loguserid");
    while($user=mysql_fetch_array($users1)){
	$u=$user[comp];
	$remlist.="<option value=$u>".$users[$u][name].' -- '.$users[$u][posts].' posts';
    }
    $remlist=" 
	 <select name=rem>
	   <option value=0 selected>Do not remove anyone
	   $remlist
	 </select>
    ";
    $prtable="
	$tccellh>&nbsp</td>$tccellh>&nbsp<tr>
	$tccell1><b>Add an user</td>$tccell2l>$addlist<tr>
	$tccell1><b>Remove an user</td>$tccell2l>$remlist<tr>
	$tccellh>&nbsp</td>$tccellh>&nbsp<tr>
	$tccell1>&nbsp</td>$tccell2l>
	$inph=action VALUE=dochanges>
	$inph=userpass VALUE=\"$user[password]\">
	$inps=submit1 VALUE=\"Submit and continue\">
	$inps=submit2 VALUE=\"Submit and finish\"></td></FORM>
    ";
    print "
	<FORM ACTION=postradar.php NAME=REPLIER METHOD=POST>
	$tblstart
	 $prtable
    ";
  }
  if($log && $_POST[action]=='dochanges'){
    $user=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$loguserid"));
    if($rem) mysql_query("DELETE FROM postradar WHERE user=$loguserid and comp=". intval($rem) ."");
    if($add) mysql_query("INSERT INTO postradar (user,comp) VALUES ($loguserid,". intval($add) .")");
    if($submit1){
	$page='postradar';
	$returnmsg='go back to the post radar setup';
    }else{
	$page='index';
	$returnmsg='return to the board';
    }
    print "<br>$tblstart$tccell1>Thank you, $user[name], for editing your post radar.<br>".redirect("$page.php",$returnmsg,0);
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>