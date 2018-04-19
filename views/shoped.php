<?php

#	die();
	require_once '../lib/function.php';
	$stats		= array(
		0	=> "sHP",
		1	=> "sMP",
		2	=> "sAtk",
		3	=> "sDef",
		4	=> "sInt",
		5	=> "sMDf",
		6	=> "sDex",
		7	=> "sLck",
		8	=> "sSpd",
		);
	$effects	= array("None", "1: Forces female gender", "2: Forces male gender", "3: Forces catgirl status", "4: Other");

	if ($loguser['powerlevel'] < 1) {
		$windowtitle	= "nope.avi";
		require_once "../lib/layout.php";
		print $header ."<br>$tblstart
			$tccell1>No.</td><table>". $footer;
		die();
	}

	$hiddeneditok	= in_array($loguser['id'], array(1, 18));


	if ($_POST['edit']) {
		$q	=
			"`name` = '". $_POST['name'] ."', ".
			"`desc` = '". $_POST['desc'] ."', ".
			"`cat` = '". $_POST['cat'] ."', ".
			"`type` = '". $_POST['type'] ."', ".
			"`effect` = '". $_POST['effect'] ."', ".
			"`coins` = '". $_POST['coins'] ."', ".
			($hiddeneditok ? "`hidden` = '". $_POST['hidden'] ."', " : "").
			"`gcoins` = '". $_POST['gcoins'] ."', ";

		foreach($stats as $stat) {
			if ($_POST['m'. $stat] == "m") $_POST[$stat] *= 100;
			$q		.= "`$stat` = '". $_POST[$stat] ."', ";
			$stypes	.= $_POST['m'. $stat];
		}
		$q	.= "`stype` = '$stypes'";

		if ($_POST['coins'] < 0 || $_POST['gcoins'] < 0) {
			// $sql -> query("UPDATE `users` SET `powerlevel` = -1, `title` = 'Next time, read the goddamn warning before doing something stupid'");
			die("You don't pay warnings much heed, do you?");
		}

		if ($_GET['id'] <= -1) {
			$sql -> query("INSERT INTO `items` SET $q, `user` = '". $loguser['id'] ."'");
			if (mysql_error()) die(mysql_error());
			$id	= mysql_insert_id();
		} else {
			$sql -> query("UPDATE `items` SET $q WHERE `id` = '". $_GET['id'] ."'");
			if (mysql_error()) die(mysql_error());
			$id	= $_GET['id'];
		}

		header("Location: ?cat=". $_POST['cat'] ."&id=". $id . ($_GET['type'] ? "&type=". $_POST['type'] : ""));
		die($q);
	}


	$windowtitle	= "Shop Editor";
	require_once "../lib/layout.php";
	print $header ."<br>";

	echo "$tblstart<tr>$tccellh><b>WARNING</b></td></tr><tr>$tccell1>
		MAKE AN ITEM WITH A NEGATIVE COST AND YOU <span style=\"border-bottom: 1px dotted #f00;font-style:italic;\" title=\"did you mean: won't really (but don't try it anyway, it won't work)\">WILL</span> GET BANNED</td></tr></table><br>";

	$categories	= array(
		1	=> "Weapons",
		2	=> "Armor",
		3	=> "Shields",
		4	=> "Helmets",
		5	=> "Boots",
		6	=> "Accessories",
		7	=> "Usable",
		99	=> "Special",
		);
	$cat	= ($_GET['cat'] ? $_GET['cat'] : "1");
	echo linkbar($categories, $cat);

//	$stats	= array("sHP", "sMP", "sAtk", "sDef", "sInt", "sMDf", "sDex", "sLck", "sSpd");

	$types	= $sql -> query("SELECT `id`, `name` FROM `itemtypes` WHERE `id` IN (SELECT DISTINCT(`type`) FROM `items` WHERE `cat` = '$cat') ORDER BY `ord` ASC");
	$typerow[0]		= "";
	while ($type	= $sql -> fetch($types)) {
		$typerow[$type['id']] = "<tr>$tccellc colspan=\"16\"><a href=\"?cat=". $_GET['cat'] . ($_GET['item'] ? "&item=". $_GET['item'] : "") . ($_GET['type'] == $type['id'] ? "" : "&type=". $type['id']) ."\">". $type['name'] ."</a></td></tr>";
	}

	if ($_GET['id']) {

		$typesq	= $sql -> query("SELECT `id`, `name` FROM `itemtypes` ORDER BY `ord` ASC");
		$alltypes[255]	= "Unknown";
		while($typex = $sql -> fetch($typesq)) {
			$alltypes[$typex['id']]	= $typex['name'];
		}

		$item	= $sql -> fetchq("SELECT * FROM `items` WHERE `id` = '". $_GET['id'] ."'" . ($hiddeneditok ? "" : " AND `hidden` = '0'"));
		if (!$item) {
			$item['cat']	= $cat;
			$_GET['id']		= -1;
		}

		foreach ($stats as $n => $stat) {
			if ($item['stype']{$n} == "m") {
				$optionbox		= "<select name=\"m$stat\"><option value=\"m\" selected>x</option><option value=\"a\">+/-</option></select>";
				$val			= number_format($item[$stat] / 100, 2);
			} else {
				$optionbox		= "<select name=\"m$stat\"><option value=\"m\">x</option><option value=\"a\" selected>+/-</option></select>";
				$val			= $item[$stat];
			}
			$stbox[$stat]	= "
				$tccellh width=\"11%\">". substr($stat, 1) ."</td>
				$tccell1l width=\"22%\"><input type=\"text\" name=\"$stat\" maxlength=\"8\" size=\"5\" value=\"$val\" class=\"right\"> $optionbox</td>";
		}
		echo "

		<form method=\"post\" action=\"?cat=1&id=". $_GET['id'] . ($_GET['type'] ? "&type=". $_GET['type'] : "") ."\">
		$tblstart
			<tr>
				$tccellh colspan=6>Editing <b>". ($_GET['id'] >= 1 ? $item['name'] : "New item") ."</b></td>
			</tr>
			<tr>
				$tccellh>Name</td>
				$tccell1l colspan=3><input type=\"text\" name=\"name\" value=\"". $item['name'] ."\"  style=\"width: 100%;\" maxlength=\"255\">
					". ($hiddeneditok ? "<br><input type=\"checkbox\" id=\"hiddenitem\" name=\"hidden\" value=\"1\"". ($item['hidden'] ? " checked" : "") ."> <label for=\"hiddenitem\">Hidden item</label>" : "") ."
					</td>
				$tccellh>Category</td>
				$tccell1l>". linkbar($categories, $item['cat'], 1, "cat") ." / ". linkbar($alltypes, $item['type'], 1, "type") ."</td>
			</tr>
			<tr>
				$tccellh>Desc</td>
				$tccell1l colspan=3><input type=\"text\" name=\"desc\" value=\"". $item['desc'] ."\" style=\"width: 100%;\"></td>
				$tccellh>Effect <small>(wip)</small></td>
				$tccell1l>". linkbar($effects, $item['effect'], 1, "effect") ."</td>
			</tr>

			<tr>$tccellc colspan=6><img src=\"images/_.gif\" height=6 width=6></td></tr>
			<tr>". $stbox['sHP'] . $stbox['sMP'] . $stbox['sLck'] ."</tr>
			<tr>". $stbox['sAtk'] . $stbox['sInt'] . $stbox['sDex'] ."</tr>
			<tr>". $stbox['sDef'] . $stbox['sMDf'] . $stbox['sSpd'] ."</tr>
			<tr>$tccellc colspan=6><img src=\"images/_.gif\" height=6 width=6></td></tr>

			<tr>
				$tccellc colspan=2><input type=\"submit\" name=\"edit\" value=\"Save\"></td>
				$tccellh> Coins </td>
				$tccell1l><input type=\"text\" name=\"coins\" maxlength=\"8\" size=\"10\" value=\"". $item['coins'] ."\" class=\"right\"> <img src=\"images/coin.gif\" align=\"absmiddle\"></td>
				$tccellh> G.Coins </td>
				$tccell1l><input type=\"text\" name=\"gcoins\" maxlength=\"8\" size=\"10\" value=\"". $item['gcoins'] ."\" class=\"right\"> <img src=\"images/coin2.gif\" align=\"absmiddle\"></td>
			</tr>

		</table></form><br>";
	}


	$items	= $sql -> query("SELECT `items`.*, `users`.`id` as uid, `users`.`sex` as usex, `users`.`powerlevel` as upow, `users`.`name` as uname FROM `items` LEFT JOIN `users` ON `users`.`id` = `items`.`user` WHERE `cat` = '$cat'". ($_GET['type'] ? " AND `type` = '". $_GET['type'] ."' " : "") . ($hiddeneditok ? "" : " AND `hidden` = '0'") ." ORDER BY `type` ASC, `coins` ASC, `gcoins` ASC");
	echo "
		$tblstart
			<tr>$tccellc colspan=\"16\">&lt; <a href=\"?cat=$cat&id=-1\">New Item</a> &gt;</td></tr>
			<tr>
				$tccellh>&nbsp;</td>
				$tccellh colspan='2'>Name</td>
				$tccellh>HP</td>
				$tccellh>MP</td>
				$tccellh>Atk</td>
				$tccellh>Def</td>
				$tccellh>Int</td>
				$tccellh>MDf</td>
				$tccellh>Dex</td>
				$tccellh>Lck</td>
				$tccellh>Spd</td>
				$tccellh>Efx</td>
				$tccellh>Coins</td>
				$tccellh>G.Coins</td>
				$tccellh>Pv</td>
			</tr>";

	while ($item = $sql->fetch($items)) {
		$stype	= str_split($item['stype']);

		if ($_GET['id'] == $item['id']) {
			$tc2	= $tccellh;
			$tc2l	= $tccellhl;
			$tc2r	= $tccellhr;
			$tc1	= $tccellh;
		} else {
			$tc2	= $tccell2;
			$tc2l	= $tccell2l;
			$tc2r	= $tccell2r;
			$tc1	= $tccell1;
		}

		if ($item['hidden']) {
			$item['name']	= "<img src='images/dot4.gif' align='absmiddle'> ". $item['name'];
		}

/*
		if ($item['uname']) {
			$item['name']	= "<a href=\"{$GLOBALS['jul_views_path']}/profile.php?id=". $item['uid'] ."\" class=\"fonts\"><font ". getnamecolor($item['usex'], $item['upow']) .">". $item['uname'] ."'s</font></a> ". $item['name'];
		}
*/
		if ($item['uname']) {
			$item['uname']	= "<nobr><a href=\"{$GLOBALS['jul_views_path']}/profile.php?id=". $item['uid'] ."\" class=\"fonts\"><font ". getnamecolor($item['usex'], $item['upow']) .">". $item['uname'] ."</font></a></nobr>";
		} else {
			$item['uname']	= "";
		}

		if ($item['desc']) {
			$item['name']	.= " <span class=\"fonts\" style=\"color: #88f;\">- ". $item['desc'] ."</span>";
		}

		$typerow[$item['type']] .= "<tr>
				$tccell1s><a href=\"?cat=$cat&id=". $item['id'] . ($_GET['type'] ? "&type=". $_GET['type'] : "") ."\">Edit</a></td>
				$tc2>". $item['uname'] ."</td>$tc2l>". $item['name'] ."</td>";

		$val	= 0;
		foreach($stats as $n => $stat) {
			$num	= ($stype[$n] == "m" ? vsprintf('%1.2fx',$item[$stat]/100) : $item[$stat]);
			if ($item[$stat] > 0 && $stype[$n] != "m") $num = "+". $num;
			if ($item[$stat] == 0 && $stype[$n] != "m") $num = "";

			if ($item[$stat] > 0 && $stype[$n] == "a") {
				$num = "<font color=\"#88ff88\">$num</font>";
				$val += floor(pow(($item[$stat] * 1.80), 1.739));

			} elseif ($item[$stat] > 100 && $stype[$n] == "m") {
				$num = "<font color=\"#ccffcc\">$num</font>";
				$val += floor(pow(($item[$stat] - 100) * 100, 1.3));

			} elseif ($item[$stat] < 0 && $stype[$n] == "a") {
				$num = "<font color=\"#ffbbbb\">$num</font>";
				$val -= floor(pow(abs(($item[$stat]) * 2), 1.25));

			} elseif ($item[$stat] < 100 && $stype[$n] == "m") {
				$num = "<font color=\"#ff8888\">$num</font>";
				$val -= floor(pow(abs($item[$stat] - 100) * 2.5, 1.3));
			}

			$typerow[$item['type']] .= "$tc1>". $num ."</td>\n";
		}

		$valt	= $val ."t";
		$val	= round(($val * 2), -1 * (strlen($valt) - 3)) / 2;

		$val	= number_format($val);


		$typerow[$item['type']] .= "
				$tc2>". ($item['effect'] ? $item['effect'] : "&nbsp;") ."</td>
				$tc2r>". number_format($item['coins']) ."</td>
				$tc2r>". number_format($item['gcoins']) ."</td>
				$tc2r>$smallfont<nobr>". $val ." Pv</nobr></td>
			</tr>";
	}

	if ($typerow[0]) {
		$typerow[0]	= "<tr>$tccellc colspan=\"16\"><b>???</b></td></tr>". $typerow[0];
	}

	print implode("", $typerow);
	echo "<tr>
				$tccellh>&nbsp;</td>
				$tccellh colspan='2'>Name</td>
				$tccellh>HP</td>
				$tccellh>MP</td>
				$tccellh>Atk</td>
				$tccellh>Def</td>
				$tccellh>Int</td>
				$tccellh>MDf</td>
				$tccellh>Dex</td>
				$tccellh>Lck</td>
				$tccellh>Spd</td>
				$tccellh>Efx</td>
				$tccellh>Coins</td>
				$tccellh>G.Coins</td>
				$tccellh>Pv</td>
			</tr>
			<tr>$tccellc colspan=\"16\">&lt; <a href=\"?cat=$cat&id=-1\">New Item</a> &gt;</td></tr>
			</table>";


	print $footer;
	printtimedif($startingtime);




	function linkbar($links, $sel = 1, $type = 0, $name = "cat") {

		global $tblstart, $tblend, $tccell1, $tccellh, $tccellc;

		if ($type == 0) {
			$c	= count($links);
			$w	= floor(1 / $c * 100);

			$r	= "$tblstart<tr>$tccellh colspan=$c><b>Item Categories</b></td></tr><tr>";

			foreach($links as $link => $name) {

				$cell	= $tccell1;
				if ($link == $sel) $cell	= $tccellc;
				$r	.= "$cell width=\"$w%\"><a href=\"?cat=$link\">$name</a></td>";
			}

			return $r ."$tblend<br>";
		} else {

			$r	= "<select name=\"$name\">";

			foreach($links as $link => $name) {

				$cell	= $tccell1;
				if ($link == $sel) $cell	= $tccellc;
				$r	.= "<option value=\"$link\"". ($sel == $link ? " selected" : "") .">$name</option>";
			}

			return $r ."</select>";
		}
	}
?>
