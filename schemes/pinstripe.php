<?php
    $formcss        = 0;        # formcss makes forms black with tableborder borders; using cssextra below is easier
    $numcols        = 100;        # same thing, more or less

    $bgimage        = 'images/pinstripe/bluebg.png';

    $bgcolor        = '1d2b4e';   
    $textcolor        = 'e1eef7';   

    $linkcolor        = '87a9ff';    # Link
    $linkcolor2        = 'aabae2'; # visited
    $linkcolor3        = 'aabae2'; # active
    $linkcolor4        = 'aabae2'; # hover

    $tableborder    = '000000'; 
    $tableheadtext    = 'e1eef7';   
    $tableheadbg    = '324a82;background:rgba(255,255,255,0.2)';   
    $categorybg        = '1d2b4e;background:rgba(255,255,255,0.1)';   
    $tablebg1        = '18243f;background:rgba(0,0,0,0.4)';   
    $tablebg2        = '131c33;background:rgba(0,0,0,0.6)';   

    # Scrollbar colors...
    $scr1            = '263863';    # top-left outer highlight
    $scr2            = '1d2b4e'; # top-left inner highlight
    $scr3            = '1a2644'; # middle face
    $scr4            = '111a2d'; # bottom-right inner shadow
    $scr5            = '000000'; # bottom-right outer shadow
    $scr6            = 'ffffff'; # button arrows
    $scr7            = '213359';

    $newthreadpic    = '<img src="images/newthread.png" align="absmiddle">';
    $newreplypic    = '<img src="images/newreply.png" align="absmiddle">';
    $newpollpic        = '<img src="images/newpoll.png" align="absmiddle">';
    $closedpic        = '<img src="images/threadclosed.png" align="absmiddle">';

    $numdir            = 'jul/';                                                                # /numgfx/<dir>/ for number images
#    $numfil            = 'numpurple';                                                            # numgfx graphic set

    # Extra CSS included at the bottom of a page
    $css_extra        = "
center > table.table td { padding: 4px; }

center > table.table td.tdbg2 a { font-size: 11px; }

center + div + br + table td { padding: 2px; }
center + div + br + table td table { margin: 0px; }

center + a + table td, center + a + script + table td { padding: 2px; }

table[width='100%'] { margin: 1px; }
body > table[width='100%'] { margin: 4px 0px; }

body > table[width='100%'] td[align='right'] { visibility: hidden; font-size: 4px; }

body > table[width='100%'] td[align='right'] a {
  visibility: visible;
  font-size: 10px;
  border: 1px solid #4060aa;
  border-right: 1px solid #314880;
  border-bottom: 1px solid #314880;
  padding: 0px 3px 1px 3px;
  background: #375291;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 3px;
  -moz-box-shadow: 1px 1px 6px #000;
  -webkit-box-shadow: 1px 1px 6px #000;
  box-shadow: 1px 1px 6px #000;
}

body > table[width='100%'] td[align='right'] a:hover, body > table[width='100%'] td[align='right'] a:active {
  background: #4263af;
}

.tdbgc, .tdbgh { padding: 3px; font-weight: bold; font-size: 11px; }

textarea,input,select{
  border: #000 solid 1px;
  background: #2e3f68;
  color: #bfcef2;
  font: 8pt verdana;
  padding: 2px;
  }
input[type=\"radio\"], .radio {
  border: none;
  background: #2e3f68;
  color: #bfcef2;
  font: 10pt verdana;}
.submit{
  border: #000 solid 2px;
  font: 8pt verdana;
  padding: 1px 6px;}

table.table[cellspacing='0'] td.tbl.font[height='220'] { padding: 1px; }
        ";