<?php
  if(!function_exists(libdec)){die('The required libraries have not been defined.');}
  require_once $root."/lib/layout.php";
  $css.="
  .topstretch	{height: 257px; width: 100%; background: #ffffff url(".$bconf[boardurl]."/images/alliance/topstretch.jpg);}
  .banner	{height: 257px; width: 800px; background: #ffffff url(".$bconf[boardurl]."/images/alliance/alliancetitlepic2.jpg)}
  .leftstretch	{height: 100%; width: 110px; min-width:110px; background: #4B2742; vertical-align: top; border-right: thin dashed #E6299D}
  .mainthing	{background: #4B2742; vertical-align: top; padding: 1px;}
  .headlinks  {background: #ffffff url(".$bconf[boardurl]."/images/alliance/headlinksbg.jpg); height: 25px; font: 13px arial; color: #DAD218;}
  .headlinks A{color: #DAD218; text-decoration: none; font: 13px arial}
  .headlinks A:visited{color: #DAD218; text-decoration: none; font: 13px arial}
  .headlinks A:hover{color: #DAD218; text-decoration: none; font: 13px arial}
  .headlinks A:active{color: #DAD218; text-decoration: none; font: 13px arial}
  .myplaceshead {background: #52814F url(".$bconf[boardurl]."/images/alliance/myplaces.jpg); height: 17px; width:100%; background-repeat: no-repeat;}
  .myplaces	{background: #4B2742; width:100%; vertical-align: top; font:13px tahoma; color:#0B3708}
  .myplaces A{color: #ffffff; text-decoration: none; font: 13px arial}
  .myplaces A:visited{color: #ffffff; text-decoration: none; font: 13px arial}
  .myplaces A:hover{color: #ffffff; text-decoration: none; font: 13px arial}
  .myplaces A:active{color: #ffffff; text-decoration: none; font: 13px arial}
  .boardinfo	{background: #4B2742; width:100%; vertical-align: bottom; font:10px tahoma; color:#ffffff}
  .boardinfo A{color: #ffffff; text-decoration: none; font: 13px arial}
  .boardinfo A:visited{color: #ffffff; text-decoration: none; font: 13px arial}
  .boardinfo A:hover{color: #ffffff; text-decoration: none; font: 13px arial}
  .boardinfo A:active{color: #ffffff; text-decoration: none; font: 13px arial}
</STYLE>";

  $css.=$basecode;
  $header1="<html><head>$css<title>$windowtitle</title><LINK REL=SHORTCUTICON HREF=".$bconf[boardurl]."/favicon.ico>
  </head><META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; CHARSET=utf-8\">
  <body>
<table cellpadding=0 cellspacing=0 width=100% height=100%>
	<td class=topstretch align=left>
		<table cellpadding=0 cellspacing=0>
			<td class=banner>
				<form action=$bconf[boardurl]/login.php method=post name=logout><input type=hidden name=action value=logout></form>
			</td>
  		</table>
  	</td>
	<tr>	
	<td class=headlinks>
		<table cellpadding=0 cellspacing=0 width=100%>
			<td align=left class=headlinks>
				Views: $views
			</td>
			<td align=center class=headlinks>";
	if($loguser[powerlevel]>=$bconf[searchfunction]){
		$searchlink="<a href=".$bconf[boardurl]."/search.php>Search</a>";
  	}
  $header2=nav2mod("
			</td>
			<td align=right class=headlinks>
				".date($dateformat,ctime()+$tzoff)."
			</td>
		</table>
	</td>
	<tr>
	<td>
	  	<table cellpadding=0 cellspacing=0 width=100% height=100%>
	  		<td class=leftstretch>
	  			<table cellpadding=0 cellspacing=0 width=100% height=100%>
	  				<td class=myplaceshead valign=top>
	  				</td>
	  				<tr>
	  				<td class=myplaces>","<br>","</td>
				</table>
	  		</td>
	  		<td>
	  			<table width=100% height=100%>
					<td class=mainthing>");
  $footer="
	</textarea></form></embed></noembed></noscript></noembed></embed>
					</td>
					<tr>
					<td colspan=3 class='table tb2 tdbg1 center fonts'>$race<br><br>
					<center>
					<a href=$siteurl>$sitename</a><br>
					<a href=\"".$bconf[boardurl]."/versions.php\">AcmlmBoard $version</a><br>
					$copyright<br>
					<img src=".$bconf[boardurl]."/images/newbadges-1b0.png>
					</center>
					</td>
				</table>
			</td>
		</table>
	</td>
</table>
</body></html>
  ";
  function makeheader($header1,$headlinks,$header2){return $header1.$headlinks.$header2;}
  $header=makeheader($header1,$headlinks,$header2);
if($ipbanned) die("$header<br>$tblstart$tccell1>Sorry, but your IP address is banned from this board.$tblend$footer");

?>
