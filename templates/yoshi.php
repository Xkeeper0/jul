<?php
  if(!function_exists(libdec)){die('The required libraries have not been defined.');}
  require_once $root.'/lib/layout.php';
  $css.="
	.headLinks A:link { color: #F3DE4F; text-decoration:none;font:11px arial;}
	.headLinks A:visited { color: #F3DE4F; text-decoration:none;font:11px arial;}
	.headLinks A:active { color: #F3DE4F; text-decoration:none;font:11px arial;}
	.headLinks A:hover { color: #F3DE4F; text-decoration:none;font:11px arial;}
	.tdbghead {color:#8D751D;
		   font:13px arial;
		   vertical-align:bottom
		  }
	.boardNavBg {background: #210207}
	.pagecontainer {width:700px}
	.leftmargin {background-image:url(".$bconf[boardurl]."/images/leftmargin.jpg); background-attachment:fixed;background-repeat:no-repeat; width:100}
	.rightmargin {background-image:url(".$bconf[boardurl]."/images/rightmargin.jpg); background-attachment:fixed;background-repeat:no-repeat}; width:100}";

  $css.="</style>";
  
  $css.=$basecode;
  $header1="<html><head><title>$windowtitle</title><LINK REL=SHORTCUTICON HREF=".$bconf[boardurl]."/favicon.ico>
	$css
	</head><META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; CHARSET=utf-8\">
	$body
	<center>
	$tblstart
	  <center>
	  <table class='pageContainer'>
	  <td>
	<table cellspacing=0 width=100%>
	  <td class='tdbghead center headLinks right'background=".$bconf[boardurl]."/images/yoshischeme/acebanner2.jpg width=100% height=200>
	  <form action=$bconf[boardurl]/login.php method=post name=logout><input type=hidden name=action value=logout></form>";
  $header2="
	  <br>
	$headlinks2
	</td>
	$tblend
  ";
  $footer="
	</textarea></form></embed></noembed></noscript></noembed></embed></table></td></table></table>
	<center>$smallfont<br><br><a href=$siteurl>$sitename</a><br>$affiliatelinks
	<a href=\"".$bconf[boardurl]."/versions.php\">AcmlmBoard $version</a><br>$copyright<br><img src=".$bconf[boardurl]."/images/poweredbyacmlm.gif><br><img src=".$bconf[boardurl]."/images/newbadges-1b0.png>
<br><a href=http://www.big-boards.com/highlight/99/><img src=http://www.big-boards.com/img/ltu/99a.png style=\"border:1px solid black\" alt=\"One of the largest message boards on the web!\"></a>
</body></html>
  ";
  function makeheader($header1,$headlinks,$header2){return $header1.$headlinks.$header2;}
  $header=makeheader($header1,$headlinks,$header2);
if($ipbanned) die("$header<br>$tblstart$tccell1>Sorry, but your IP address is banned from this board.$tblend$footer");

?>
