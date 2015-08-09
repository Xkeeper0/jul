<?php
  if(!function_exists(libdec)){die('The required libraries have not been defined.');}
  $layout_decl_nppic=1;
  $layout_decl_nrpic=1;
  $layout_decl_ntpic=1;
  require_once $root."/lib/layout.php";
  $css.="
  .topstretch	{height: 200px; width: 100%; background: #ffffff url(".$bconf[boardurl]."/images/twilight/princess.jpg);background-repeat: no-repeat; background-position: right}
  .banner	{height: 200px; width: 500px; background: #ffffff url(".$bconf[boardurl]."/images/twilight/twilighttitlepic.jpg)}
  .leftstretch	{height: 100%; width: 110px; background: #ffffff; vertical-align: top; border-right: thin dashed #A5D1A}
  .mainthing	{background: #52814F; vertical-align: top;}
  .headlinks  {background: #ffffff url(".$bconf[boardurl]."/images/twilight/headlinksbg.jpg); height: 25px; font: 13px arial; color: #0B3708;}
  .headlinks A{color: #0B3708; text-decoration: none; font: 13px arial}
  .headlinks A:visited{color: #0B3708; text-decoration: none; font: 13px arial}
  .headlinks A:hover{color: #0B3708; text-decoration: none; font: 13px arial}
  .headlinks A:active{color: #0B3708; text-decoration: none; font: 13px arial}
  .myplaceshead {background: #52814F url(".$bconf[boardurl]."/images/twilight/myplaces.jpg); height: 17px; width:100%; background-repeat: no-repeat;}
  .myplaces	{background: #ffffff; width:100%; vertical-align: top; font:13px tahoma; color:#0B3708}
  .myplaces A{color: #0B3708; text-decoration: none; font: 13px arial}
  .myplaces A:visited{color: #0B3708; text-decoration: none; font: 13px arial}
  .myplaces A:hover{color: #0B3708; text-decoration: none; font: 13px arial}
  .myplaces A:active{color: #0B3708; text-decoration: none; font: 13px arial}
  .boardinfo	{background: #ffffff; width:100%; vertical-align: bottom; font:10px tahoma; color:#0B3708}
  .boardinfo A{color: #0B3708; text-decoration: none; font: 13px arial}
  .boardinfo A:visited{color: #0B3708; text-decoration: none; font: 13px arial}
  .boardinfo A:hover{color: #0B3708; text-decoration: none; font: 13px arial}
  .boardinfo A:active{color: #0B3708; text-decoration: none; font: 13px arial}
</STYLE>";

	$nmcol[0]=array('-1'=>'5B5B5B','3460E0','D8E8FE','AFFABE','FFEA95');
	$nmcol[1]=array('-1'=>'5B5B5B','E936A6','FFB3F3','9912D3','C53A9E');
	$nmcol[2]=array('-1'=>'5B5B5B','5F468C','D86366','2D7225','F0C40F');
  
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
	  			<table cellpadding=0 cellspacing=0 height=100%>
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
					<td colspan=3 class='table tbl tdbg1 center fonts'>$race<br>
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
