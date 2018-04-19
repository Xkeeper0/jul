<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';
	$base = base_dir().'/';

	print "$body
		<title>Hexadecimal color chart</title>
		$css
		<script language=javascript>
		function hex(val){
			colortext	= document.getElementById('hexval');
			colorbox	= document.getElementById('colordisp');
			reg			= /^([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/;

			if (val.match(reg)) {
				colorbox.style.background	= '#' + val;
			}
			colortext.value				= val;
		}
		</script>
		<form name=hexchart>
		<map name=colmap>";

	for($g=0;$g<6;$g++)
		for($r=0;$r<6;$r++)
			for($b=0;$b<6;$b++) {
				$x1=$b*8+$r*48+1;
				$y1=$g*11+1;
				$x2=$x1+8;
				$y2=$y1+11;
				$c=substr(dechex(16777216+$r*51*65536+(5-$g)*51*256+$b*51),-6);
				print "<area shape=rect coords=$x1,$y1,$x2,$y2 href=javascript:hex('$c')>";
			}

	print "</map>
		<table height=100% valign=middle align='center'><td>
		$tblstart
		$tccell1>
		<a><img usemap=#colmap src={$base}images/hexchart.png border=0 width=289 height=67></a><br>
		Click a color to get its HTML color value.<br>
		<span style='border: 1px solid #fff;' id='colordisp'><img src='{$base}images/_.gif' height=20 width=60'></span> - #$inpt=hexval size=6 value='000000' id='hexval' onkeyup='hex(value)' maxlength=6>
		$tblend
		</form>
		$tblend";
