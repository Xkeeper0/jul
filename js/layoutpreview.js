// Live layout preview 0.1
// by Jesper, for AcmlmBoard.

isIE = false;
var sep = "";
var fooElement = document.createElement('style');
fooElement.type = 'text/css';
tn = document.createTextNode("foo");
if ((document.all)&&(navigator.appVersion.indexOf("Mac")!=-1)&&(navigator.userAgent.indexOf("Gecko")==-1)&&(navigator.userAgent.indexOf("KHTML")==-1)) { isIE = true; }
try {
  fooElement.appendChild(tn);  
} catch(e) {
  if (e == "[object Error]") { // we have IE
    isIE = true;
  }
}

function init(d,s) {
  if (!isIE) {
    thedoc = d;
    sep = s;
//    d.getElementById("noscript").style.display = "none";
    
/*    d.getElementById("show-blockquote").onclick = refreshLayoutPreview;
    d.getElementById("show-link").onclick = refreshLayoutPreview;
    d.getElementById("show-stretchimage").onclick = refreshLayoutPreview;
    d.getElementById("show-stretchtext").onclick = refreshLayoutPreview;
    d.getElementById("postbg").onfocus = refreshLayoutPreview;
    d.getElementById("postsign").onfocus = refreshLayoutPreview;
    d.getElementById("postheader").onfocus = refreshLayoutPreview; */
    d.getElementById("preview-button").onclick = refreshLayoutPreview;
    
    d.getElementById("dataloaderhouse").innerHTML += "<iframe name=\"dataloader\" id=\"dataloader\" src=\"about:blank\" style=\"width: 0px; height: 0px; background: white; border: 0; -moz-opacity: 0; filter:alpha(opacity=0); opacity: 0; \"></iframe>";
    return 1;
  } else { return 0; }
}

var thedoc;
var foo;
var theStyle;
function refreshLayoutPreview() {
if (!isIE) {
  var sampleText = "(sample text)";
  
  d = thedoc;

  var cnv = d.getElementById("layout-preview");
  
  var pbg = d.getElementById("postbg");
  var phd = d.getElementById("postheader");
  var psg = d.getElementById("postsign");
  
  var sbq = d.getElementById("show-blockquote");
  var sln = d.getElementById("show-link");
  var str = d.getElementById("show-stretchimage");
  var stt = d.getElementById("show-stretchtext");
  
  var ssp = d.getElementById("signsep");

  var text = cnv.innerHTML+"";
  
//  thedoc = d;
  foo = "doodad";
  
//  alert("foo");
  var formm = d.createElement("form");
  formm.setAttribute("method","POST");
  formm.setAttribute("action","/layoutpreviewfilter.php");
  formm.setAttribute("target","dataloader");
  formm.setAttribute("id","theform");
  formm.setAttribute("name","theform");
//  formm.setAttribute("onsubmit","alert('submitting')");
  var i1 = d.createElement("input");
  var i2 = d.createElement("input");
  var i3 = d.createElement("input");
  var i4 = d.createElement("input");
  i1.setAttribute("type","hidden");
  i2.setAttribute("type","hidden");
  i3.setAttribute("type","hidden");
  i4.setAttribute("type","submit");
  i1.setAttribute("name","headtext");
  i2.setAttribute("name","text");
  i3.setAttribute("name","signtext");
  i4.setAttribute("name","dosubmit");
  i4.setAttribute("id","submitbutton");
  i4.style.display = "none";
  i4.setAttribute("onclick","alert(document.body.innerHTML);");
  
  if (sln.checked) {
    sampleText = "(<a href=\"#\" onclick=\"return false\">sample</a> text)";
  }
  text = sampleText;
  if (sbq.checked) {
    text = "<blockquote><font class=fonts><i>Originally posted by Someone</i></font><hr>" + sampleText + "<hr></blockquote>" + sampleText;
  }
  if (str.checked) {
    text = text + "<img src=\"images/stretchtest.gif\">";
  }
  if (stt.checked) {
    text = text + "<br><br>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam luctus augue blandit nisl. Etiam id lacus sed ante pulvinar euismod. Maecenas neque quam, scelerisque sed, semper id, vulputate a, eros. Suspendisse mauris erat, condimentum in, pellentesque nec, lobortis vitae, elit. Nunc nec elit quis augue viverra consequat. In eget augue. Aliquam erat volutpat. Nulla blandit massa sed velit. Quisque nonummy consectetuer lacus. Aliquam egestas augue sit amet nulla. In diam leo, lacinia eget, convallis sed, pellentesque eu, velit. Nullam sem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<br><br>" + "Morbi rhoncus lectus id leo lacinia blandit. Fusce felis dolor, ullamcorper id, venenatis at, rhoncus feugiat, odio. Suspendisse et nulla eget lectus iaculis elementum. Suspendisse at felis non lectus blandit commodo. Morbi volutpat. Sed eget elit nec libero lobortis consequat. Duis eget magna gravida odio pellentesque venenatis. Maecenas ligula lorem, pellentesque ut, consequat et, commodo et, est. In dictum purus ac lorem. Vestibulum vel felis. In erat. Mauris sit amet est elementum ligula adipiscing vulputate. Curabitur ultrices dolor sagittis neque. Aenean adipiscing odio non lorem. Integer non odio. Nam libero. Vivamus posuere, lorem rutrum iaculis aliquet, metus nibh elementum neque, lacinia eleifend wisi libero vitae tellus. Aenean varius mauris in ipsum.<br><br>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam luctus augue blandit nisl. Etiam id lacus sed ante pulvinar euismod. Maecenas neque quam, scelerisque sed, semper id, vulputate a, eros. Suspendisse mauris erat, condimentum in, pellentesque nec, lobortis vitae, elit. Nunc nec elit quis augue viverra consequat. In eget augue. Aliquam erat volutpat. Nulla blandit massa sed velit. Quisque nonummy consectetuer lacus. Aliquam egestas augue sit amet nulla. In diam leo, lacinia eget, convallis sed, pellentesque eu, velit. Nullam sem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<br><br>" + "Morbi rhoncus lectus id leo lacinia blandit. Fusce felis dolor, ullamcorper id, venenatis at, rhoncus feugiat, odio. Suspendisse et nulla eget lectus iaculis elementum. Suspendisse at felis non lectus blandit commodo. Morbi volutpat. Sed eget elit nec libero lobortis consequat. Duis eget magna gravida odio pellentesque venenatis. Maecenas ligula lorem, pellentesque ut, consequat et, commodo et, est. In dictum purus ac lorem. Vestibulum vel felis. In erat. Mauris sit amet est elementum ligula adipiscing vulputate. Curabitur ultrices dolor sagittis neque. Aenean adipiscing odio non lorem. Integer non odio. Nam libero. Vivamus posuere, lorem rutrum iaculis aliquet, metus nibh elementum neque, lacinia eleifend wisi libero vitae tellus. Aenean varius mauris in ipsum.";
  }
  
  i2.setAttribute("value",text);
  
  if (pbg.value != "") {
    i1.setAttribute("value","<div style=\"background-image: url(" + pbg.value + ")\">" + phd.value);
    if (psg.value != "") { 
      i3.setAttribute("value",sep + psg.value + "</div>");
    } else {
      i3.setAttribute("value",psg.value + "</div>");
    }
  } else {
    i1.setAttribute("value",phd.value);
    if (psg.value != "") { 
      i3.setAttribute("value",sep + psg.value);
    } else {
      i3.setAttribute("value",psg.value);
    }
  }
  
  formm.appendChild(i1);
  formm.appendChild(i2);
  formm.appendChild(i3);
  formm.appendChild(i4);
//  alert("bar: " + i3.getAttribute('value')); 
  
  var IFrameDoc;
  if (d.getElementById("dataloader").contentDocument) {
    // For NS6
    IFrameDoc = d.getElementById("dataloader").contentDocument; 
  } else if (d.getElementById("dataloader").contentWindow) {
    // For IE5.5 and IE6
    IFrameDoc = d.getElementById("dataloader").contentWindow.document;
  } else if (d.getElementById("dataloader").document) {
    // For IE5
    IFrameDoc = d.getElementById("dataloader").document;
  } 
  
/*	while(IFrameDoc.body.hasChildNodes() == true)
	{
		IFrameDoc.body.removeChild(IFrameDoc.body.childNodes[0]);
	}*/
  if (d.getElementById("theform")) {
    d.body.removeChild(d.getElementById("theform"));
  }
  d.body.appendChild(formm);
  
//  alert("b: " + IFrameDoc.body.innerHTML);  

//IFrameDoc.theform.submit();
  setTimeout('submitFormStuff()',10);
//  formm.submit();
  
  
  
/*  cnvs = doFilter(phd.value) + cnvs + ssp.value + doFilter(psg.value);
  if (pbg.value != "") {
    cnvs = "<div style=\"background-image: url(" + pbg.value + ")\">" + cnvs + "</div>";
  }
//  alert(cnvs);
  d.getElementById("layout-preview").innerHTML = cnvs;*/
return false;
} else { return true; }
}

function submitFormStuff() {
  var IFrameDoc;
//  alert("submitformstuff");  
  if (thedoc.getElementById("dataloader").contentDocument) {
    // For NS6
    IFrameDoc = thedoc.getElementById("dataloader").contentDocument; 
  } else if (thedoc.getElementById("dataloader").contentWindow) {
    // For IE5.5 and IE6
    IFrameDoc = thedoc.getElementById("dataloader").contentWindow.document;
  } else if (thedoc.getElementById("dataloader").document) {
    // For IE5
    IFrameDoc = thedoc.getElementById("dataloader").document;
  } 
//  alert("before submit: " + IFrameDoc.body.innerHTML);
//  alert(IFrameDoc.getElementById("theform").innerHTML+"");
//  IFrameDoc.getElementById("theform").submit();
//  IFrameDoc.getElementById("submitbutton").click();
//  alert("after: " + IFrameDoc.body.innerHTML);
//  alert(foo);
  thedoc.getElementById("theform").submit();
}

function setPreview(n,s) {
//  alert("howdy");
//    n = "<style>" + s + "</style>" + n;
    thedoc.getElementById("layout-preview").innerHTML = n;
    
    var styleElement = document.createElement('style');
    styleElement.type = 'text/css';
    tn = document.createTextNode(s);
    try {
      styleElement.appendChild(tn);  
    } catch(e) {
      if (e == "[object Error]") { // we have IE
        styleElement.innerHTML = s;
      }
    }
    if (!theStyle) {
      theStyle = document.getElementsByTagName('head')[0].appendChild(styleElement);
    } else {
      theStyle = document.getElementsByTagName('head')[0].replaceChild(styleElement,theStyle);
    }
    
/*  try {
  // IE can't handle inline stylesheets, so let's add them 'the right way' - replacing if we're reloading
    if (csscreated) { css = document.styleSheets[document.styleSheets.length-1]; }
    else { css = document.createStyleSheet(); csscreated = "1"; }
    css.cssText = s;
    thedoc.getElementById("layout-preview").innerHTML = n;
  } catch(e) {
    return;
  }*/
//  alert(thedoc.getElementById("layout-preview").innerHTML);
}