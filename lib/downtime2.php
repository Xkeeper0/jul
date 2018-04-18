<?php

	http_response_code(500);

?><html><head><title>Jul is offline for now</title>
	<link rel="shortcut icon" href="/images/favicons/favicon3.ico" type="image/x-icon">
		<style>
		a:link,a:visited,a:active,a:hover{text-decoration:none;font-weight:bold}
		a {
			color: #BEBAFE;
		}
		a:visited {
			color: #9990c0;
		}
		a:active {
			color: #CFBEFF;
		}
		a:hover {
			color: #CECAFE;
		}
		img { border:none; }
		pre br { display: none; }
		body {
			scrollbar-face-color:		7d7bc1;
			scrollbar-track-color:		000020;
			scrollbar-arrow-color:		210456;
			scrollbar-highlight-color:	a9a7d6;
			scrollbar-3dlight-color:	d4d3eb;
			scrollbar-shadow-color:	524fad;
			scrollbar-darkshadow-color:	312d7d;
			color: #DDDDDD;
			font:13px verdana;
			background: #000F1F url('<?= dir_images('starsbg.png'); ?>');
		}
		.font 	{font:13px verdana}
		.fonth	{font:13px verdana;color:FFEEFF}
		.fonts	{font:10px verdana}
		.fontt	{font:10px tahoma}
		.tdbg1	{background:#111133}
		.tdbg2	{background:#11112B}
		.tdbgc	{background:#2F2F5F}
		.tdbgh	{background:#302048}
		.center	{text-align:center}
		.right	{text-align:right}
		.table	{empty-cells:	show;
				 border-top:	#000000 1px solid;width:100%;
				 border-left:	#000000 1px solid;width:100%;}
		td.tbl	{border-right:	#000000 1px solid;
				 border-bottom:	#000000 1px solid}
		code {
			overflow:		auto;
			width:			100%;
			white-space:	pre;
			display:		block;
		}
		code br { display: none; }

		textarea,input,select{
		  border:	#663399 solid 1px;
		  background:#000000;
		  color:	#DDDDDD;
		  font:	10pt verdana;}
		.radio{
		  border:	none;
		  background:none;
		  color:	#DDDDDD;
		  font:	10pt verdana;}
		.submit{
		  border:	#663399 solid 2px;
		  font:	10pt verdana;}
		</style>

	</head>
	<body>
	<center>

	 <div class="fonts" style="position: fixed; width: 600px; margin-left: -300px; margin-top: -200px; top: 50%; left: 50%;">
	 <table class="table font" cellspacing=0>
		<tr>
			<td class='tbl tdbgh center' style="padding: 3px;"><b>
				Down for maintenance
			</b></td>
		</tr>
	  <tr>
		<td class='tbl tdbg1 center' style="padding: 1em;">
			We'll be back later.
		</td>
	  </tr>
	</table>
	</body>
</body></html>
<?php

	die();

?>
