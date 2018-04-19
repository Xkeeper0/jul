<?php

  $windowtitle	= "Admin Cruft";

  require_once '../lib/function.php';
  require_once '../lib/layout.php';

if (!in_array($loguser['id'], array(175, 1)) && $loguser['powerlevel'] < 1) {

	print "
		$header<br>
		$tblstart
			<tr>$tccell1>&nbsp;
				<br>No.
				<br>&nbsp;
			</td></tr>
		$tblend

	$footer
	";
	printtimedif($startingtime);
	die();
  }


	$expower = in_array($loguserid, array(175, 1, 2100));

	if ($expower && $_GET['banip'] && $_GET['valid'] == md5($_GET['banip'] . "aglkdgslhkadgshlkgds")) {
		$sql->query("INSERT INTO `ipbans` SET `ip` = '". $_GET['banip'] ."', `reason`='Abusive/unwelcome activity', `date` = '". ctime() ."', `banner` = '$loguserid'") or print mysql_error();
		xk_ircsend("1|". xk(8) . $loguser['name'] . xk(7) ." added IP ban for ". xk(8) . $_GET['banip'] . xk(7) .".");
		return header("Location: ?");
	}

	print "$header<br>";

	$clearbutton = '&nbsp;';
	if ($expower) {
		if ($_POST['clear'])
			$query	= $sql -> query("TRUNCATE `minilog`");
		$clearbutton = "<br><form style='margin: 0px; padding: 0px;' action='?' method='post'>$inps='clear' value='Clear log'></form><br>";
	}

	$banflagnames[    1]	= "union<br>select";
	$banflagnames[16384]	= "acunetix";
	$banflagnames[ 2048]	= "get<br>+";
	$banflagnames[    4]	= "get<br>--";
//	$banflagnames[    8]	= "get<br>;";  // Disabled. Too many false positives.
	$banflagnames[    2]	= "get<br>comment";
	$banflagnames[   16]	= "get<br>exec";
	$banflagnames[   32]	= "get<br>password";
	$banflagnames[ 4096]	= "get<br>script";
	$banflagnames[ 8192]	= "get<br>cookie";
	$banflagnames[   64]	= "cookie<br>comment";
	$banflagnames[  128]	= "cookie<br>exec";
	$banflagnames[  256]	= "cookieban<br>user";
	$banflagnames[  512]	= "cookieban<br>nonuser";
	$banflagnames[ 1024]	= "non-int<br>userid";

	$cells	= count($banflagnames) + 4;
		
	print "
		$tblstart
			<tr>$tccellh>Shitbug detection system</td></tr>
			<tr>$tccell1>&nbsp;
				<br>This page lists denied requests, showing what the reason was.
				<br>$clearbutton
			</td></tr>
		$tblend
		<br>
		$tblstart
	";
			
	$colheaders	= "<tr>$tccellh width='180'>Time</td>$tccellh width='50'>Count</td>$tccellh>IP</td>$tccellh width='50'>&nbsp</td>";

	foreach ($banflagnames as $flag => $name)
		$colheaders	.= "$tccellh width='60'>$name</td>";

	$colheaders	.= "</tr>";
	print $colheaders;

	$query	= $sql -> query("SELECT *, (SELECT COUNT(`ip`) FROM `ipbans` WHERE `ip` = `minilog`.`ip`) AS `banned` FROM `minilog` ORDER BY `time` DESC");
	
	$rowcnt		= 0;
	$lastflag	= 0;
	$combocount	= 0;
	$lastip		= "";
	
	while ($data = $sql -> fetch($query)) {
		if (($lastip != $data['ip'] || $lastflag != $data['banflags']) && $lastflag != 0) {
			$rowcnt++;
			print str_replace("%%%COMBO%%%", ($combocount > 1 ? " &times;$combocount" : ""), $tempout);
			
			if (!($rowcnt % 50))
				print $colheaders;
			elseif ($lastip != $data['ip'])
				print "<tr>$tccellh colspan='$cells'><img src='images/_.gif' height=5 width=5></td></tr>";

			$tempout	= "";
			$combocount	= 0;
		}
		
		$lastip		= $data['ip'];
		$lastflag	= $data['banflags'];
		$combocount++;
		
		if ($combocount == 1) {
			$tempout	= "<tr>$tccell1>". date("m-d-y H:i:s", $data['time']) ."</td>$tccell1>%%%COMBO%%%</td>$tccell1><a href='{$GLOBALS['jul_views_path']}/ipsearch.php?ip=". $data['ip'] ."'>". $data['ip'] ."</a></td>";

			if ($data['banned'])
				$tempout .= "$tccell1s><span style='color: #f88; font-weight: bold;'>Banned</span></td>";
			elseif ($expower)
				$tempout .= "$tccell1s><a href=?banip={$data['ip']}&valid=". md5($data['ip'] . "aglkdgslhkadgshlkgds") .">Ban</a></td>";
			else
				$tempout .= "$tccell1s>&nbsp;</td>";

			foreach ($banflagnames as $flag => $name) {
				if ($data['banflags'] & $flag)
					$tempout	.= "$tccellc width='60'>Hit</td>";
				else
					$tempout	.= "$tccell2 width='60'>&nbsp;</td>";
			}
			$tempout .= "</tr>";
		}
	}
	
	print str_replace("%%%COMBO%%%", ($combocount > 1 ? " &times;$combocount" : ""), $tempout);

	print "$tblend $footer";
	printtimedif($startingtime);
?>
