<?php
 function srgb($r,$g,$b) {
   return array(r=>$r,g=>$g,b=>$b);
 }
 function fadesch($c,$n,$pct) {
   $pct2=1-$pct;
   $ret=floor($c[$n][r]*$pct2+$c[$n+1][r]*$pct)*65536+
   floor($c[$n][g]*$pct2+$c[$n+1][g]*$pct)*256+
   floor($c[$n][b]*$pct2+$c[$n+1][b]*$pct);
   return $ret;
 }
 $bgimage='images/back09.gif';
 $bgcolor='000000';
 $tableheadtext='FFFFFF';
 $curtime=getdate(ctime()+$tzoff);
 $min=$curtime[hours]*60+$curtime[minutes];
 $tbg1[1]=srgb( 10, 10, 33);
 $tbg2[1]=srgb(  7,  7, 22);
 $thb [1]=srgb( 23, 23, 80);
 $tbd [1]=srgb( 60, 65,166);
 $cbg [1]=srgb( 17, 17, 57);
 $tbg1[2]=srgb( 48,  6, 82);
 $tbg2[2]=srgb( 34,  5, 50);
 $thb [2]=srgb( 70, 30,110);
 $tbd [2]=srgb(118, 66,165);
 $cbg [2]=srgb( 59, 18, 96);
 $tbg1[3]=srgb(  0,  0, 96);
 $tbg2[3]=srgb(  0,  0, 54);
 $thb [3]=srgb(  0, 80,160);
 $tbd [3]=srgb(  0,112,192);
 $cbg [3]=srgb(  0, 40,128);
 $tbg1[4]=srgb( 50, 10,  9);
 $tbg2[4]=srgb( 31,  7,  7);
 $thb [4]=srgb( 96, 24, 13);
 $tbd [4]=srgb(190,106, 32);
 $cbg [4]=srgb( 73, 17, 11);
 $tbg1[5]=$tbg1[1];
 $tbg2[5]=$tbg2[1];
 $thb[5]=$thb[1];
 $tbd[5]=$tbd[1];
 $cbg[5]=$cbg[1];
 $n=floor($min/360)+1;
 $pct=($min-floor($min/360)*360)/360;
 $pct2=1-$pct;
 $tblbg1=fadesch($tbg1, $n, $pct);
 $tblbg2=fadesch($tbg2, $n, $pct);
 $tblhb=fadesch($thb, $n, $pct);
 $tblbd=fadesch($tbd, $n, $pct);
 $catbg=fadesch($cbg, $n, $pct);
 $scr1=floor(192+($tbd[$n][r]*$pct2+$tbd[$n+1][r]*$pct)*0.25)*65536+
       floor(192+($tbd[$n][g]*$pct2+$tbd[$n+1][g]*$pct)*0.25)*256+
       floor(192+($tbd[$n][b]*$pct2+$tbd[$n+1][b]*$pct)*0.25);
 $scr2=floor(128+($tbd[$n][r]*$pct2+$tbd[$n+1][r]*$pct)*0.50)*65536+
       floor(128+($tbd[$n][g]*$pct2+$tbd[$n+1][g]*$pct)*0.50)*256+
       floor(128+($tbd[$n][b]*$pct2+$tbd[$n+1][b]*$pct)*0.50);
 $scr3=floor( 64+($tbd[$n][r]*$pct2+$tbd[$n+1][r]*$pct)*0.75)*65536+
       floor( 64+($tbd[$n][g]*$pct2+$tbd[$n+1][g]*$pct)*0.75)*256+
       floor( 64+($tbd[$n][b]*$pct2+$tbd[$n+1][b]*$pct)*0.75);
 $tablebg1=substr(dechex($tblbg1+16777216),-6);
 $tablebg2=substr(dechex($tblbg2+16777216),-6);
 $tableheadbg=substr(dechex($tblhb+16777216),-6);
 $tableborder=substr(dechex($tblbd+16777216),-6);
 $categorybg=substr(dechex($catbg+16777216),-6);
 $scr1=substr(dechex($scr1+16777216),-6);
 $scr2=substr(dechex($scr2+16777216),-6);
 $scr3=substr(dechex($scr3+16777216),-6);
 $scr4=$tableborder;
 $scr5=$tableheadbg;
 $scr6=$tablebg1;
 $scr7=$tablebg2;
 $bgcolor=$tablebg2;
 $formcss=1;
 $inputborder=$tableheadbg;


 	$statusicons['new']			= '<img src=images/status-classic/new.gif>';
	$statusicons['newhot']		= '<img src=images/status-classic/hotnew.gif>';
	$statusicons['newoff']		= '<img src=images/status-classic/off.gif>';
	$statusicons['newhotoff']	= '<img src=images/status-classic/hotoff.gif>';
	$statusicons['hot']			= '<img src=images/status-classic/hot.gif>';
	$statusicons['hotoff']		= '<img src=images/status-classic/hotoff.gif>';
	$statusicons['off']			= '<img src=images/status-classic/off.gif>';

