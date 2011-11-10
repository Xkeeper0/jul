<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if(!$log) errorpage('You must be logged in to edit your profile.');
  if($powerlevel<0) errorpage('Sorry, but banned users aren’t allowed to edit their profile.');
  print $header;
  if(!$_POST[action]){
    print "
	<body onload=window.document.REPLIER.submit.focus()>
	<FORM ACTION=userpic.php NAME=REPLIER METHOD=POST>
	$tblstart
    ";
    if(!$page) $page=1;
    $userpiccategs=mysql_query("SELECT id,name FROM userpiccateg WHERE page=$page");
    while($categ=mysql_fetch_array($userpiccategs)){
	print "<tr>$tccellc><b>$categ[name]</b><tr>$tccell2>";
	$userpics=mysql_query("SELECT id,url FROM userpic WHERE categ=$categ[id]");
	while($pic=mysql_fetch_array($userpics))
	  print "<nobr>$radio=pic value=$pic[id]><img width=60 src=$pic[url]></nobr>&nbsp; &nbsp;";
    }
    $categpages=mysql_query('SELECT page FROM userpiccateg ORDER BY page');
    $lpage=0;
    $pagelinks='Pages:';
    while($p=mysql_fetch_array($categpages)){
	$p=$p[page];
	if($p!=$lpage){
	  $lpage=$p;
	  if($p!=$page) $pagelinks.=" <a href=userpic.php?page=$p>$p</a>";
	  else $pagelinks.=" $p";
	}
    }
    print "
	  <tr>$tccell1s>$pagelinks
	  <tr>$tccell1>
	  $inph=action VALUE=setpicture>
	  $inps=submit VALUE=\"Change picture\">
	  </td></FORM>$tblend
    "; 
  }
  if($_POST[action]=='setpicture'){
    print $tblstart;
    $pic=mysql_query("SELECT url FROM userpic WHERE id=$pic");
    $pic=mysql_fetch_array($pic);
    $pic=$pic[url];
    mysql_query("UPDATE users SET picture='".addslashes($pic)."' WHERE id=$loguserid");
    print "
	$tccell1>Thank you, $loguser[name], for selecting a new user picture.
	<br>".redirect('index.php','return to the board',0).$tblend; 
  }
  print $footer;
  printtimedif($startingtime);
?>