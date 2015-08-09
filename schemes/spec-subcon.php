<?php
	$bgimage='http://xkeeper.shacknet.nu:5/img/fragment2.png';
	$bgcolor='070722';   
	$textcolor='cccccc';   
	$linkcolor='80ff80';   
	$linkcolor2='70cc70';   
	$linkcolor3='a0ffa0';   
	$linkcolor4='c0ffc0';   
	$tablebg1='101040';   
	$tablebg2='080830';   
	$categorybg='101050';   
	$tableheadtext='ccccff';   
	$tableheadbg='202060';   
	$tableborder='000000';   
	$scr1='d4d3eb';
	$scr2='a9a7d6';
	$scr3='7d7bc1';
	$scr4='524fad';
	$scr5='312d7d';
	$scr6='210456';
	$scr7='000020';

	$accesslist	= array(49, 18);

		if (in_array($loguserid, $accesslist)) {
		$power		= 3;
		$isadmin	= true;
		$loguser['powerlevel'] = 3;
	}

//	$boardtitle='<img src="images/attitudebarn.png">';

?>