//Netscape Resize fix
if (document.layers)
{
  widthCheck = window.innerWidth
  heightCheck = window.innerHeight
}

function resizeFix()
{
  if (widthCheck != window.innerWidth || heightCheck != window.innerHeight)
  {
	document.location.href = document.location.href
  }
}

window.onerror = null;
var DOM;
var bName = navigator.appName;
var bVer = parseInt(navigator.appVersion);
var NS4 = (bName == "Netscape" && bVer >= 4 && bVer <5);
var NS5 = (bName == "Netscape" && bVer >=5);
var IE4 = (bName == "Microsoft Internet Explorer" && bVer >= 4);
var NS3 = (bName == "Netscape" && bVer < 4);
var IE3 = (bName == "Microsoft Internet Explorer" && bVer < 4);

if (document.getElementById)
{
  DOM = true;
  NS4 = false
}
else
{
  DOM= false;
}

var MTOP = 57

var menuActive = 0
var MenuOn = 0
var onLayer
var timeOn = null
var loaded = 0
var openmenu = "";
var closemenu = "";
var activeLayer = "";

var menu1 = new Image();
menu1.src = "images/toolbar/invis.gif"; //Invisibol Placeholder

var menu2 = new Image();
menu2.src = menu1.src;
 
// MENU COLOR VARIABLE
var menuColor = "#000000"

// LAYER SWITCHING CODE
if (NS4 || IE4)
{
  if (navigator.appName == "Netscape")
  {
    layerStyleRef="layer.";
    layerRef="document.layers";
    styleSwitch="";
  }
  else
  {
    layerStyleRef="layer.style.";
    layerRef="document.all";
    styleSwitch=".style";
  }
}

// SHOW MENU
function showLayer(layerName, e)
{
  MenuOn=1
  if (NS4 || IE4 || DOM)
  {
    if (timeOn != null)
    {
      clearTimeout(timeOn)
      hideLayer(onLayer)
    }
    if (NS4 || IE4)
    {
      eval(layerName+'.style.left=e.clientX-e.offsetX');
      eval(layerName+'.style.top=e.clientY');
      eval(layerRef+'["'+layerName+'"]'+styleSwitch+'.visibility="visible"');

      activeLayer=layerName;
    } 
    if (DOM)
    {
      document.getElementById(layerName).style.visibility='visible'
    }
  }
  onLayer = layerName
}

// HIDE MENU
function hideLayer(layerName)
{
  MenuOn=0
  if (menuActive == 0)
  {
    if (NS4 || IE4)
    {
      eval(layerRef+'["'+layerName+'"]'+styleSwitch+'.visibility="hidden"');
    }
    if (DOM)
    {
      document.getElementById(layerName).style.visibility='hidden'
    }
  }
}

// TIMER FOR BUTTON MOUSE OUT
function btnTimer()
{
  if(MenuOn==1)
  {
    timeOn = setTimeout("btnOut()",1000)
  }
}

// BUTTON MOUSE OUT
function btnOut()
{
  hideLayer(onLayer)
}

// MENU MOUSE OVER  
function menuOver(layerName)
{
  if(layerName==activeLayer)
  {
    clearTimeout(timeOn)
    menuActive = 1
    return true;
  }
  else
  {
    return false;
  }
}

// MENU MOUSE OUT 
function menuOut()
{
  menuActive = 0
  timeOn = setTimeout("hideLayer(onLayer)", 400)
}

// Creates menu object
function menuLink(title, url)
{
  this.title = title
  this.url = url
}

// Builds menu table
function menuMaker(menuArray, fontcolor, backcolor, lightcolor, darkcolor)
{
  topTable = ""
  btmTable = ""

  n = ""
  j = eval(menuArray + ".length")-1;

  topTable = "<div ID='" + eval(menuArray + ".id") + "' STYLE='POSITION: absolute; LEFT: 0; TOP: 0; VISIBILITY: hidden; Z-INDEX: 1'>"
  topTable+="<table border='0' cellspacing='0' cellpadding='0'>"
  topTable+="<TR><TD bgcolor='"+lightcolor+"'></TD><TD bgcolor='"+lightcolor+"' ALT='Top Border'></TD><TD bgcolor='"+lightcolor+"' ALT='Top Right Small Background'><img src=images/toolbar/invis.gif height=1 width=1></TD><TD bgcolor='"+lightcolor+"'></TD>"
  topTable+="</TR><TR><TD bgcolor='"+lightcolor+"'><img src=images/toolbar/invis.gif></TD>"
  topTable+="<TD colspan='2'>"
  topTable+="<table border='0' cellspacing='0' cellpadding='0'>"

  btmTable = "</table>"
  btmTable+="</TD><TD bgcolor='"+darkcolor+"'><img src=images/toolbar/invis.gif></TD></TR>"
  //btmTable+="</TR><TR><TD bgcolor='"+darkcolor+"'></TD><TD bgcolor='"+darkcolor+"' ALT='Lower Right Side Border'><img src=images/toolbar/invis.gif></TD></TR>"
  btmTable+="<TR><TD bgcolor='"+darkcolor+"' ALT='Lower Right Corner Border'></TD><TD bgcolor='"+darkcolor+"'></TD><TD bgcolor='"+darkcolor+"'></TD><TD bgcolor='"+darkcolor+"'></TD></TR></TABLE>"
  btmTable+="</div>"
 
  bgnrow = ""
  for( var i = 0; i <=j; i++)
  {
    test=eval(menuArray + "[" + i + "].title")
    bgnrow += "<tr valign=Center><td bgcolor='"+backcolor+"' align=Center width=100%><a onMouseOut='menuOut()' onMouseOver='menuOver(activeLayer)' class='menus' target='_top'><img src='images/toolbar/invis.gif' width=15 height=15 border='0'></a>"
    bgnrow += "<a onClick='" + eval(menuArray + "[" + i + "].url") + "' onMouseOut='menuOut()' onMouseOver='menuOver(activeLayer)' class='menus' target='_top'><font face='MS Sans Serif, Western' size=1 color='"+fontcolor+"'>" + test + "</font></td></tr>"
  }

  n= topTable+bgnrow+btmTable
  return n;
}

function getScale()
{
  var scale;
  var newwidth
  if (bName == "Netscape")
  {
    newwidth =  window.innerWidth
  } 
  else
  {
    newwidth=  document.body.clientWidth
  }
	
  center = ((newwidth-371)/2)+251;

  minWidth = 539
  if (center <minWidth)
  {
    return minWidth;
  }
  else
  {
    return center;
  }
}