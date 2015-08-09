var bgColor;
var ltColor;
var dkColor;

function createButton(image, name, event, backcolor, lightcolor, darkcolor)
{
  var btnTable;

  bgColor=backcolor;
  ltColor=lightcolor;
  dkColor=darkcolor;

  btnTable="<table id='"+name+"' border='0' cellspacing='0' cellpadding='0' width=23 height=17 "+event+">"
  btnTable+="<TR bgcolor="+backcolor+"><TD></TD><TD></TD><TD><img src=images/toolbar/invis.gif></TD><TD></TD>"
  btnTable+="</TR><TR bgcolor="+backcolor+"><TD><img src=images/toolbar/invis.gif></TD>"
  btnTable+="<TD colspan='2'>"
  btnTable+="<table border='0' cellspacing='0' cellpadding='0' bgcolor="+backcolor+" width=100% height=100%>"
  btnTable+="<tr valign=top><td align=center><img name='"+name+"imgtop' src=images/toolbar/invis.gif></td></tr>"
  btnTable+="<tr valign=center><td align=center><img name='"+name+"imgleft' src=images/toolbar/invis.gif>"+image+"<img name='"+name+"imgright' src=images/toolbar/invis.gif></td></tr>"
  btnTable+="<tr valign=bottom><td align=center><img name='"+name+"imgbottom' src=images/toolbar/invis.gif height=2></td></tr>"
  btnTable+="</table>"
  btnTable+="</TD><TD bgcolor="+backcolor+"><img src=images/toolbar/invis.gif></TD></TR>"
  btnTable+="<TR bgcolor="+backcolor+"><TD></TD><TD></TD><TD></TD><TD></TD></TR>"
  btnTable+="</TABLE>"

  return btnTable;
}

function createSeperation(backcolor, lightcolor, darkcolor)
{
  var btnTable;

  btnTable="<table border='0' bgcolor='"+backcolor+"' cellspacing='0' cellpadding='0' width=2 height=17>"
  btnTable+="<TR valign=top><TD><img src=images/toolbar/invis.gif height=1></TD><TD><img src=images/toolbar/invis.gif></TD></TR>"
  btnTable+="<TR><TD bgcolor='"+darkcolor+"' width=1 height=7></TD><TD bgcolor='"+lightcolor+"' width=1></TD></TR>"
  btnTable+="<TR><TD bgcolor='"+darkcolor+"' width=1 height=7></TD><TD bgcolor='"+lightcolor+"' width=1></TD></TR>"
  btnTable+="<TR valign=bottom><TD><img src=images/toolbar/invis.gif height=1></TD><TD><img src=images/toolbar/invis.gif></TD></TR>"
  btnTable+="</TABLE>"

  return btnTable;
}

function buttonOut(name)
{
  eval(name+'.rows[0].style.backgroundColor=bgColor');
  eval(name+'.rows[1].style.backgroundColor=bgColor');
  eval(name+'.rows[1].cells[2].style.backgroundColor=bgColor');
  eval(name+'.rows[2].style.backgroundColor=bgColor');
}

function buttonDown(name)
{
  eval(name+'.rows[0].style.backgroundColor=dkColor');
  eval(name+'.rows[1].style.backgroundColor=dkColor');
  eval(name+'.rows[1].cells[2].style.backgroundColor=ltColor');
  eval(name+'.rows[2].style.backgroundColor=ltColor');
  eval('document.all.'+name+'imgtop.height=2');
  eval('document.all.'+name+'imgleft.width=2');
  eval('document.all.'+name+'imgright.width=0');
}

function buttonUp(name)
{
  eval(name+'.rows[0].style.backgroundColor=ltColor');
  eval(name+'.rows[1].style.backgroundColor=ltColor');
  eval(name+'.rows[1].cells[2].style.backgroundColor=dkColor');
  eval(name+'.rows[2].style.backgroundColor=dkColor');
  eval('document.all.'+name+'imgtop.height=1');
  eval('document.all.'+name+'imgleft.width=1');
  eval('document.all.'+name+'imgright.width=1');
}