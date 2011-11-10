function filter(func, list) {
	var retl = [];
	var idx  = 0;
	for (var i = 0; i < list.length; i++) {
		if (func(list[i]) == 1) {
			retl[idx] = list[i];
			idx++;
		}
	}
	return retl;
}

function pluralize(val, str) {
	if (val == 1) {
		return String(val) + " " + str;
	} else if (val == 0 || val >= 2) {
		return String(val) + " " + str + "s";
	}
}

function htmlattrs(attrs) {
	var attrStrings = [];
	for (var i in attrs) {
		attrStrings.push(i + '="' + attrs[i] + '"');
	}
	return attrStrings.join(" ");
}

var gen_row = function (elements, attrs) { return "<tr"+(attrs["row"]!=undefined?" "+htmlattrs(attrs["row"]):"")+">"+elements.join("")+"</tr>"; }
var gen_cell = function (text, attrs) { return "<td"+(attrs!=undefined?" "+htmlattrs(attrs):"")+">"+text+"</td>"; }

function htmlchars(str) {
	return str;
}

function linkify(text, attrs) {
	var attrStrings = [];
	for (var i in attrs) {
		attrStrings.push(i + '="' + htmlchars(attrs[i]) + '"')
	}
	return "<a "+(attrStrings.join(" "))+">"+htmlchars(text)+"</a>";
}

var postlist = function() {$.get("latestposts.php", {raw: 1}, function(data) {
		var ctime = new Date();
		var lines   = data.split("\n");
		var splitter = /"(.*?)"/gi;
		var noCommas = function(e) { return e != ","; }
		var rows    = [];
		for (var i = 0; i < lines.length; i++) {
			rows[i] = filter(noCommas, lines[i].split(splitter));
			rows[i].splice(0,1);
		}
		var html_rows = function() {
			var trows = [];
			var statr1 = {class: "tbl tdbg1 font center"};
			var statr2 = {class: "tbl tdbg2 font"};
			var statr3 = {class: "tbl tdbg1 font"};
			var statr4 = {class: "tbl tdbg2 font center"};
			trows[0] = gen_row([gen_cell("<b>Latest Posts</b>", {class: "tbl tdbgc font center", colspan: "5"})], {});
			trows[1] = gen_row([
				gen_cell("&nbsp;", {class: "tbl tdbgh font center", width: 30}),
				gen_cell("Forum", {class: "tbl tdbgh font center", width: 200}),
				gen_cell("Thread",{class: "tbl tdbgh font center"}),
				gen_cell("User", {class: "tbl tdbgh font center", width: 200}),
				gen_cell("Time", {class: "tbl tdbgh font center", width: 130})], {});
			for (var i = 2; i < rows.length; i++) {
				var trow = rows[i-2];
				var dt = new datetime(trow[8]);
				trows[i] = gen_row([
						gen_cell(trow[0], statr2),
						gen_cell(linkify(trow[1], {href: "/forum.php?id="+trow[2], target: "blank"}),statr4),
						gen_cell(linkify(trow[3], {href: "/thread.php?pid="+trow[0]+"&r=1#"+trow[0], target: "blank"}), statr3),
						gen_cell("<span style='font-weight: bold; color: #"+trow[7]+";'>"+trow[4]+"</span>", statr1),
						gen_cell(dt.since(), statr4)
						], {});
			}
			var html = trows.join("");
			return html;
		};
		$("table[name=latest]").html(html_rows());
});}

function datetime(tstamp) {
	var ctime = new Date();
	this.timestamp = tstamp-10800;
	this.now       = ctime.valueOf()/1000;
	this.since = function() {
		var delta = (this.now - this.timestamp);
		if (delta >= 0 && delta <= 10) {
			return "Just now";
		} else if (delta < 60 && delta > 10) {
			var val = Math.round(delta/60).toString();
			return pluralize(val, "second")
		} else if (delta < 3600 && delta > 59) {
			var val = Math.round(delta/60).toString();
			return pluralize(val, "minute");
		} else if (delta < 86400 && delta > 3599) {
			var val = Math.round(delta/3600).toString();
			return pluralize(val, "hour");
		}
		return delta;
	}
	this.idelta = function() {
		return (this.now - this.timestamp);
	}
}

$(document).ready(function() {
	var UPDATE_TIME = 250;
	postlist();
	setInterval(
		function() {
				postlist();
		}, 30000);
});

