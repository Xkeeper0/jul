<?php

// Used in early errors, such as SQL connection problems.
function early_html_die($reason, $use_mysql_error=false) {
  $sql_error = $use_mysql_error ? "<br><font style=\"color: #f55;\">". mysql_error() ."</font>" : '';
  die("<title>Damn</title>
    <body style=\"background: #000 url('images/bombbg.png'); color: #f00;\">
      <font style=\"font-family: Verdana, sans-serif;\">
      <center>
      <br><br><font style=\"color: #f88; size: 175%;\"><b>{$reason}</b></font>
      <br>
      {$sql_error}
      <br>
      <br><small>This is not a hack attempt; it is a server problem.</small>
    ");
}

function suspicious_die() {
  header("HTTP/1.1 403 Forbidden");

  die("<title>Error</title>
    <body style=\"background: #000; color: #fff;\">
      <font style=\"font-family: Verdana, sans-serif;\">
      <center>
      Suspicious request detected (e.g. bot or malicious tool).
    ");
}

function unexpected_downtime_die() {
  die("
  <title>Damn</title>
    <body style=\"background: #000 url('images/bombbg.png'); color: #f00;\">
      <font style=\"font-family: Verdana, sans-serif;\">
      <center>
      <br><font style=\"color: #f88; size: 175%;\"><b>The board has been taken offline for a while.</b></font>
      <br>
      <br><font style=\"color: #f55;\">This is probably because:
      <br>&bull; we're trying to prevent something from going wrong,
      <br>&bull; abuse of the forum was taking place and needs to be stopped,
      <br>&bull; some idiot thought it'd be fun to disable the board
      </font>
      <br>
      <br>The forum should be back up within a short time. Until then, please do not panic;
      <br>if something bad actually happened, we take backups often.
    ");
}

function downtime_die($reason, $reason_extended) {
  header("HTTP/1.1 503 Service Unavailable");
  ?><html><head><title><?= $GLOBALS['jul_settings']['board_name'] ?> -- Temporarily down</title>
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

  	 <div class="fonts" style="position: fixed; width: 600px; margin-left: -300px; top: 40%; left: 50%;">
  	 <table class="table font" cellspacing=0>
  	  <tr>
  		  <td class='tbl tdbgh center' style="padding: 3px;"><b>
  			<?= $reason; ?>
  		  </b></td>
  	  </tr>
  	  <tr>
  		  <td class='tbl tdbg1 center'>
  			&nbsp;<br>
  				<?= $reason_extended; ?>
  				<br>
  			<br>&nbsp;
  		  </td>
  	  </tr>
  	</table>
  	</body>
  </body></html>
  <?php
  	die();
}
