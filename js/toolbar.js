  imgFColor = new Image();
  imgFColor.src = "images/toolbar/fcolor.gif";

  imgBGColor = new Image();
  imgBGColor.src = "images/toolbar/bgcolor.gif";

  imgBold = new Image();
  imgBold.src = "images/toolbar/bold.gif";

  imgItalic = new Image();
  imgItalic.src = "images/toolbar/italic.gif";

  imgUnderline = new Image();
  imgUnderline.src = "images/toolbar/underline.gif";

  imgStrike = new Image();
  imgStrike.src = "images/toolbar/strike.gif";

  imgLink = new Image();
  imgLink.src = "images/toolbar/link.gif";

  imgImage = new Image();
  imgImage.src = "images/toolbar/image.gif"

  imgSmiley = new Image();
  imgSmiley.src = "images/toolbar/smiley.gif";

  var mnuFGround = "Menu1";
  var mnuBGround = "Menu2";
  var mnuSmiley = "Menu3";

  var btnfcolorname = "FColor";
  var btnbgcolorname = "BGColor";
  var btnboldname = "bold";
  var btnitalname = "italic";
  var btnundname = "underline";
  var btnstrikename = "strike";
  var btnlinkname = "link";
  var btnimagename = "image";
  var btnsmileyname = "smiley";

  var boldOpen = "[b]";
  var boldClose = "[/b]";
  var boldDown = 0;

  var italOpen = "[i]";
  var italClose = "[/i]";
  var italDown = 0;

  var undOpen = "[u]";
  var undClose = "[/u]";
  var undDown = 0;

  var strkOpen = "[s]";
  var strkClose = "[/s]";
  var strkDown = 0;

  var linkOpen = "[url]";
  var linkClose = "[/url]";
  var linkDown = 0;

  var imageOpen = "[img]";
  var imageClose = "[/img]";
  var imageDown = 0;
  
  function checkBrowser()
  {
    return (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) >= 4); //checks to see if IE is running
  }

  function checkKey(btnNumber, e)
  {
    if(e.keyCode==btnNumber)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function doCode(code)
  {
    window.document.REPLIER.message.value+=code;
  }