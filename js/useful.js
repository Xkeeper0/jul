// placeholder for useful shit 8)
$("a.popout").click(function() {window.open(event.target.href, event.target.title, "toolbar=no,scrollbars=no,status=no,width=648,height=480"); event.preventDefault();});
var options = {"fgcolor": "#FFFFFF"};

function amax(a) {
		return Math.max.apply(null, a);
}

function sparkline(e, data) {
	var ctx = e.getContext("2d");
	var inc = Math.round(e.width / data.length);
	var penx = 0;
	var shigh = amax(data); // highest point
	
	ctx.strokeStyle = options["fgcolor"];
	ctx.beginPath();
	ctx.moveTo(0,e.height - Math.round(e.height * (data[0] / shigh)));
	for (var i = 0; i < data.length-1; i++) {
		ctx.lineTo(penx + inc, e.height - Math.round(e.height * (data[i+1] / shigh)));
		penx += inc;
	}
	ctx.stroke();
}

$(".sparkline").each(function(e) {
	var d = $(this).text().split(",");
	var a = d.map(function(q){return Number(q);});
	var c = document.createElement("canvas");
	c.width = $(this).width()
	c.height =$(this).height();
	c.id = "sparkline_"+e;
	$(this).html("");
	$(c).appendTo($(this));
	c = document.getElementById(c.id);
	sparkline(c, a);
	$(this).show();
});