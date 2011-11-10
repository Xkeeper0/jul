<?php
function replytoolbar($part) {
  return;

  global $smilies,$tablebg1,$tablebg2,$scr2,$scr3,$scr4,$loguserid,$loguser;
  if(!$loguser[posttool] and $loguserid) $part=0;
  if($part==1){
    $inumsmilies=numsmilies();
    $mlnk="=new menuLink('<img src=images/toolbar";
    $replmsg="','window.document.REPLIER.message.value+=";
    print "
	<script src=js/password.js></script>
	<script src=js/menu.js></script>
	<script src=js/button.js></script>
	<script src=js/toolbar.js></script>
	<script>
	Array.prototype.id=\"Menu1\";
	Array.prototype.imgname=\"m1\";
	m1Menu=new Array(7);
	m1Menu[0]$mlnk/fred.gif alt=Red$replmsg\"[red]\"');
	m1Menu[1]$mlnk/fyellow.gif alt=Yellow$replmsg\"[yellow]\"');
	m1Menu[2]$mlnk/forange.gif alt=Orange$replmsg\"[orange]\"');
	m1Menu[3]$mlnk/fgreen.gif alt=Green$replmsg\"[green]\"');
	m1Menu[4]$mlnk/fblue.gif alt=Blue$replmsg\"[blue]\"');
	m1Menu[5]$mlnk/fpink.gif alt=Pink$replmsg\"[pink]\"');
	m1Menu[6]$mlnk/fblack.gif alt=\"Normal Color\"$replmsg\"[/color]\"');
	m3Menu=new Array($inumsmilies);
	m3Menu.id=\"Menu3\";
	m3Menu.imgname=\"m3\"
    ";
    for($i=0;$i<$inumsmilies;$i++) print "m3Menu[$i]=new menuLink('<img src=".$smilies[$i][1]." alt=\"".$smilies[$i][0]."\">', 'window.document.REPLIER.message.value+=\"".$smilies[$i][0]."\"');";
    print "
	function defaultButton(image,name,onClick,onMouseOver,onMouseOut) {
	  if(checkBrowser()) {document.write(createButton(image, name, \"onClick='\"+onClick+\"' onMouseOver='\"+onMouseOver+\"' onMouseOut='\"+onMouseOut+\"'\", \"$scr3\",\"$scr2\",\"$scr4\"))}
	}
	</script>
    ";
  }elseif($part==2){
    $scp='<td><script';
    $scp2='>defaultButton("<img src=images/toolbar';
    $output="
	<table border=0 cellpadding=0 cellspacing=0>
	 $scp$scp2/fcolor.gif alt='Text Color'>\", \"FColor\", \"if(MenuOn==0){showLayer(mnuFGround, event); buttonDown(btnfcolorname); MenuOn=1}else{menuOut(); buttonUp(btnfcolorname); MenuOn=0}\", \"if(MenuOn==1){if(!menuOver(mnuFGround)){menuOut()}}else{buttonUp(btnfcolorname)}\", \"buttonOut(btnfcolorname)\")</script></td>
	 $scp>if(checkBrowser()) {document.write(createSeperation(\"#$scr3\",\"#$scr2\",\"#$scr4\"))}</script></td>
	 $scp$scp2/bold.gif alt='Bold Ctrl+B'>\", \"bold\", \"if(boldDown==0){buttonDown(btnboldname); boldDown=1; doCode(boldOpen)}else{buttonUp(btnboldname); boldDown=0; doCode(boldClose)}\", \"if(boldDown==0){buttonUp(btnboldname)}\", \"if(boldDown==0){buttonOut(btnboldname)}\")</script></td>
	 $scp$scp2/italic.gif alt='Italic Ctrl+I'>\", \"italic\", \"if(italDown==0){buttonDown(btnitalname); italDown=1; doCode(italOpen)}else{buttonUp(btnitalname); italDown=0; doCode(italClose)}\", \"if(italDown==0){buttonUp(btnitalname)}\", \"if(italDown==0){buttonOut(btnitalname)}\")</script></td>
	 $scp$scp2/underline.gif alt='Underline Ctrl+U'>\", \"underline\", \"if(undDown==0){buttonDown(btnundname); undDown=1; doCode(undOpen)}else{buttonUp(btnundname); undDown=0; doCode(undClose)}\", \"if(undDown==0){buttonUp(btnundname)}\", \"if(undDown==0){buttonOut(btnundname)}\")</script></td>
	 $scp$scp2/strike.gif alt='Strikethrough Ctrl+S'>\", \"strike\", \"if(strkDown==0){buttonDown(btnstrikename); strkDown=1; doCode(strkOpen)}else{buttonUp(btnstrikename); strkDown=0; doCode(strkClose)}\", \"if(strkDown==0){buttonUp(btnstrikename)}\", \"if(strkDown==0){buttonOut(btnstrikename)}\")</script></td>
	 $scp>if(checkBrowser()) {document.write(createSeperation(\"#$scr3\",\"#$scr2\",\"#$scr4\"))}</script></td>
	 $scp$scp2/link.gif alt='Hyperlink Ctrl+L'>\", \"link\", \"if(linkDown==0){buttonDown(btnlinkname); linkDown=1; doCode(linkOpen)}else{buttonUp(btnlinkname); linkDown=0; doCode(linkClose)}\", \"if(linkDown==0){buttonUp(btnlinkname)}\", \"if(linkDown==0){buttonOut(btnlinkname)}\")</script></td>
	 $scp$scp2/image.gif alt='Image Ctrl+M'>\", \"image\", \"if(imageDown==0){buttonDown(btnimagename); imageDown=1; doCode(imageOpen)}else{buttonUp(btnimagename); imageDown=0; doCode(imageClose)}\", \"if(imageDown==0){buttonUp(btnimagename)}\", \"if(imageDown==0){buttonOut(btnimagename)}\")</script></td>
	 $scp$scp2/smiley.gif alt='Add Smileys'>\", \"smiley\", \"if(MenuOn==0){showLayer(mnuSmiley, event); buttonDown(btnsmileyname); MenuOn=1}else{menuOut(); buttonUp(btnsmileyname); MenuOn=0}\", \"if(MenuOn==1){if(!menuOver(mnuSmiley)){menuOut()}}else{buttonUp(btnsmileyname)}\", \"buttonOut(btnsmileyname)\")</script>
	</table>
    ";
  }elseif($part==3) $output="onKeyDown='if(checkBrowser()){if(event.ctrlKey){if(checkKey(66,event)){if(boldDown==0){buttonDown(btnboldname);boldDown=1;doCode(boldOpen)}else{buttonOut(btnboldname);boldDown=0;doCode(boldClose)} return false} if(checkKey(73,event)){if(italDown==0){buttonDown(btnitalname);italDown=1;doCode(italOpen)}else{buttonOut(btnitalname);italDown=0;doCode(italClose)} return false} if(checkKey(85,event)){if(undDown==0){buttonDown(btnundname);undDown=1;doCode(undOpen)}else{buttonOut(btnundname);undDown=0;doCode(undClose)} return false} if(checkKey(83,event)){if(strkDown==0){buttonDown(btnstrikename);strkDown=1;doCode(strkOpen)}else{buttonOut(btnstrikename);strkDown=0;doCode(strkClose)} return false} if(checkKey(76,event)){if(linkDown==0){buttonDown(btnlinkname);linkDown=1;doCode(linkOpen)}else{buttonOut(btnlinkname);linkDown=0;doCode(linkClose)} return false}if(checkKey(77,event)){if(imageDown==0){buttonDown(btnimagename);imageDown=1;doCode(imageOpen)}else{buttonOut(btnimagename);imageDown=0;doCode(imageClose)} return false}}}'";
  elseif($part==4){
    $output="
	<script>
	  if(checkBrowser()){
		document.write(menuMaker('m1Menu','#$scr6','#$scr3','#$scr2','#$scr4'));
		document.write(menuMaker('m3Menu','#$scr6','#$scr3','#$scr2','#$scr4'));
	  }
	</script>
    ";
  }
  return $output;
}
?>