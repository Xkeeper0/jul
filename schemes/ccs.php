<?php
	$formcss		= 0;		# formcss makes forms black with tableborder borders; using cssextra below is easier
	$numcols		= 100;		# same thing, more or less

	$bgimage		= 'images/ccs/bg.png';
	$GLOBALS['jul_settings']['board_title']		= '<img src="images/ccs/banner.png" title="The Horrible Forced Scheme 4/01/2009">';	# comment this out for normal banner

	$bgcolor		= 'ffbbc5';   
	$textcolor		= '000000';   

	$linkcolor		= '905070';	# Link
	$linkcolor2		= '806070'; # visited
	$linkcolor3		= '905070'; # active
	$linkcolor4		= '000000'; # hover

	$tableborder	= 'ffffff'; 
	$tableheadtext	= '402030';   
	$tableheadbg	= 'ddaabe';   
	$categorybg		= 'ddaabe';   
	$tablebg1		= 'ffddee';   
	$tablebg2		= 'eeccdd';   

	# Scrollbar colors...
	$scr1			= 'ddbbcc';	# top-left outer highlight
	$scr2			= 'ddbbcc'; # top-left inner highlight
	$scr3			= 'ffffff'; # middle face
	$scr4			= 'ddbbcc'; # bottom-right inner shadow
	$scr5			= 'ddbbcc'; # bottom-right outer shadow
	$scr6			= '000000'; # button arrows
	$scr7			= '886677';

	#								 Banned    Normal   Normal+   Moderator   Admin
	$nmcol[0]		= array('-1' => '888888', '000066', '333388', '227722', '8E8252', );	# M
	$nmcol[1]		= array('-1' => '888888', '662244', '884455', '992277', '6D1F58', );	# F
	$nmcol[2]		= array('-1' => '888888', '442266', '554477', '336633', '876D09', );	# N/A

	$newthreadpic	= '<img src="images/ccs/newthread.png" align="absmiddle">';
	$newreplypic	= '<img src="images/ccs/newreply.png" align="absmiddle">';
	$newpollpic		= '<img src="images/ccs/newpoll.png" align="absmiddle">';
	$closedpic		= '<img src="images/ccs/threadclosed.png" align="absmiddle">';

	$numdir			= 'ccs/';																# /numgfx/<dir>/ for number images
#	$numfil			= 'numpurple';															# numgfx graphic set

	# Status icons for threads, should be self-explanatory
	$statusicons['new']			= '<img src="images/ccs/new.png">';
	$statusicons['newhot']		= '<img src="images/ccs/newhot.png">';
	$statusicons['newoff']		= '<img src="images/ccs/newoff.png">';
	$statusicons['newhotoff']	= '<img src="images/ccs/newhotoff.png">';
	$statusicons['hot']			= '<img src="images/ccs/hot.png">';
	$statusicons['hotoff']		= '<img src="images/ccs/hotoff.png">';
	$statusicons['off']			= '<img src="images/ccs/off.png">';


	# Extra CSS included at the bottom of a page
	$css_extra		= "
		textarea,input,select{
		  border:		1px solid #a89;
		  background:	#fff;
		  color:		#000;
		  font:	10pt $font;
		  }
		input[type=\"radio\"], .radio {
		  border:	none;
		  background: #fff0f8;
		  color:	#ffffff;
		  font:	10pt $font;}
		.submit{
		  border:	#000 solid 2px;
		  font:	10pt $font;}
		a {
/*			text-shadow: 0px 0px 3px #fff;
*/			}
		";
	