var snow = new Array();
var captions = new Array();
var halt = 0;

captions[ 0]	= "happy 100000th post!";

var snowfnam	= new Array();
snowfnam[ 0]	= "/images/confetti/confetti_blue.png";
snowfnam[ 1]	= "/images/confetti/confetti_yellow.png";
snowfnam[ 2]    = "/images/confetti/confetti_green.png";
snowfnam[ 3]    = "/images/confetti/confetti_red.png";

var MaxFPS = 60;

function update_snow() {
	if (halt == 1) { return null; }
	var innerHeight = window.innerHeight;
	var innerWidth  = window.innerWidth;
	for (var i = 0; i < snow.length; i++) {
		var thisflake      = snow[i]; // local referencing.
		var _posy = thisflake.posy;
		var _posx = thisflake.posx;
		if ((_posy + 8) > innerHeight || (_posx + 8) > innerWidth || (_posx - 8) < 0) {
			_posy = 0;
			left = Math.random()*innerWidth;
			thisflake.style.left = left;
			_posx = left;
			thisflake.scrollfactor_x = Math.random()*-5;
			thisflake.scrollfactor_y = (Math.random()*15) + .5;
			if (Math.round(Math.random()*10) % 5 == 0) {
				thisflake.scrollfactor_x *= -1;
			}
		}
		_posy += thisflake.scrollfactor_y;
		_posx += thisflake.scrollfactor_x;
		thisflake.style.top = _posy;
		thisflake.style.left = _posx;
		thisflake.posy = _posy;
		thisflake.posx = _posx;
	}
}

function snow_halt() {
	halt = 1;
}

function snow_unhalt() {
	halt = 0;
}
function drawsnow() {
	var flakes = 25;
	
	var element = document.createElement("div");
	element.id = "snow_canvas";
	document.body.appendChild(element);
	for (var i = 0; i < flakes; i++) {
		var flakeimg = document.createElement("img");
		flakeimg.style.display = "block";
		flakeimg.style.position = "fixed";
		flakeimg.style.zIndex   = 1;
		pleft = Math.random()*window.innerWidth;
		ptop  = Math.random()*window.innerHeight;
		flakeimg.style.left = pleft + "px";
		flakeimg.style.top = ptop + "px";
		flakeimg.scrollfactor_x = (Math.random() * 2 - 1);
		flakeimg.scrollfactor_y = (Math.random()*10) + 1;
//		if (Math.round(Math.random()*10) % 5 == 0) {
//			flakeimg.scrollfactor_x *= -1;
//		}
		flakeimg.posx = pleft;
		flakeimg.posy = ptop;
		flakeimg.src = snowfnam[Math.round(Math.random()*3)];
		flakeimg.title	= captions[0];
		temp	= Math.round(Math.random() * 4 + 3);
		flakeimg.width	= temp;
		flakeimg.height	= temp;
		snow.push(flakeimg);
		element.appendChild(flakeimg);
	}
	setInterval(update_snow, 66);
}
window.addEventListener("load", function() {drawsnow();}, false);
window.addEventListener("blur", function() {snow_halt()}, false);
window.addEventListener("focus", function() {snow_unhalt()}, false);
