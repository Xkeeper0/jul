<?php

	/**************************************************************************
	  PROTIP

	  You can leave values commented out to just let the default one take effect.

	**************************************************************************/
	
	$formcss		= 1;		# Makes form and inputs white on black, set to 0 if you want to custom style them (use css_extra below)
	$numcols		= 100;		# Width of text entry, just use css extra again

	# Banner; comment for default
	$GLOBALS['jul_settings']['board_title']		= '<img src="images/garbg/garbanner.png" title="IT\'S CRAP">';

	# Page background color, background image, and text color
	$bgcolor		= '000810';
	$bgimage		= 'images/garbg/garbg.png';
	$textcolor		= 'EEEEEE';	

	# Links
	$linkcolor		= 'B8DEFE';	# Unvisited link
	$linkcolor2		= '8BA8C0'; # Visited
	$linkcolor3		= 'CCE8FF'; # Active
	$linkcolor4		= 'CCE8FF'; # Hover

	$tableborder	= '000011'; # Border color for tables
	$tableheadtext	= '002549'; # Table header text color
	$tableheadbg	= '000921'; # Table header background (you can use images)
	$categorybg		= '002864'; # Category BG
	$tablebg1		= '001E4B'; # Table cell 1 background
	$tablebg2		= '001638'; # Table cell 2 (the darker one, usually)

	# Scrollbar colors...
	$scr1			= 'bbccdd';	# top-left outer highlight
	$scr2			= 'bbccdd'; # top-left inner highlight
	$scr3			= 'ffffff'; # middle face
	$scr4			= 'bbccdd'; # bottom-right inner shadow
	$scr5			= 'bbccdd'; # bottom-right outer shadow
	$scr6			= '000000'; # button arrows
	$scr7			= '687786';

	#								 Banned    Normal   Normal+   Moderator   Admin
/*
	$nmcol[0]		= array('-1' => '888888', '000066', '333388', '227722', '8E8252', );	# M
	$nmcol[1]		= array('-1' => '888888', '662244', '884455', '992277', '6D1F58', );	# F
	$nmcol[2]		= array('-1' => '888888', '442266', '554477', '336633', '876D09', );	# N/A
*/

	# Images for New Poll, New Thread etc.
/*
	$newthreadpic	= '<img src="images/ccs/newthread.png" align="absmiddle">';
	$newreplypic	= '<img src="images/ccs/newreply.png" align="absmiddle">';
	$newpollpic		= '<img src="images/ccs/newpoll.png" align="absmiddle">';
	$closedpic		= '<img src="images/ccs/threadclosed.png" align="absmiddle">';
*/

	# Number graphics (leave these alone unless you know what you're doing)
/*
	$numdir			= 'ccs/';																# /numgfx/<dir>/ for number images
	$numfil			= 'numpurple';															# numgfx graphic set
*/

	# Status icons for threads, should be self-explanatory
/*
	$statusicons['new']			= '<img src="images/ccs/new.png">';
	$statusicons['newhot']		= '<img src="images/ccs/newhot.png">';
	$statusicons['newhotoff']	= '<img src="images/ccs/newhotoff.png">';
	$statusicons['hot']			= '<img src="images/ccs/hot.png">';
	$statusicons['hotoff']		= '<img src="images/ccs/hotoff.png">';
	$statusicons['off']			= '<img src="images/ccs/off.png">';
*/

	# Extra CSS included at the bottom of a page

	$css_extra		= "
		";
	