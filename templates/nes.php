<?php
  if(!function_exists(libdec)){die('The required libraries have not been defined.');}
  $layout_decl_title=1;
  $layout_decl_nrpic=1;
  $layout_decl_ntpic=1;
  require_once $root.'/lib/layout.php';
  $css.="</style>";  
  $css.=$basecode;
  if(!$isindex) {$message_auto_notification=$privdisplay;}
  $header1="<html><head><title>$windowtitle</title><LINK REL=SHORTCUTICON HREF=".$bconf[boardurl]."/favicon.ico>
	$css
	</head><META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; CHARSET=utf-8\">
	$body
	<center>
	 $tblstart
	  <form action=$bconf[boardurl]/login.php method=post name=logout><input type=hidden name=action value=logout></form>
	  <td class='tbl tdbg1 center'>$boardtitle</td>
	 $tblend$tblstart
	  <td colspan=3 class='tbl tdbg1 center fonts'>";
  $header2="
	  <tr>
	  <td width=120 class='tbl tdbg2 center fonts'><nobr>Views: $views<br><img src=".$bconf[boardurl]."/images/_.gif width=120 height=1></td>
	  <td width=100% class='tbl tdbg2 center fonts'>$headlinks2</td>
	  <td width=120 class='tbl tdbg2 center fonts'><nobr>".date($dateformat,ctime()+$tzoff)."<br><img src=".$bconf[boardurl]."/images/_.gif width=120 height=1><tr>
	  <td colspan=3 class='tbl tdbg1 center fonts'>$race
	 $tblend
$message_auto_notification
	</center>
  ";
  $footer="
	</textarea></form></embed></noembed></noscript></noembed></embed></table></table>
	<center>$smallfont<br><br><a href=$siteurl>$sitename</a><br>$affiliatelinks
	<a href=\"".$bconf[boardurl]."/versions.php\">AcmlmBoard $version</a><br>$copyright<br><img src=".$bconf[boardurl]."/images/poweredbyacmlm.gif><br><img src=".$bconf[boardurl]."/images/newbadges-1b0.png>
<br><a href=http://www.big-boards.com/highlight/99/><img src=http://www.big-boards.com/img/ltu/99a.png style=\"border:1px solid black\" alt=\"One of the largest message boards on the web!\"></a>
</body></html>
  ";
  function makeheader($header1,$headlinks,$header2){return $header1.$headlinks.$header2;}
  $header=makeheader($header1,$headlinks,$header2);
if($ipbanned) die("$header<br>$tblstart$tccell1>Sorry, but your IP address is banned from this board.$tblend$footer");
$stamptime=1;
?>
