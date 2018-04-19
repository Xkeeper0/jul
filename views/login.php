<?php
	require_once '../lib/function.php';
	require_once '../lib/layout.php';

	// Bots don't need to be on this page
	$meta['noindex'] = true;

	$username = $_POST['username'];
	$password = $_POST['userpass'];
	$verifyid = $_POST['verify'];

	$txt="$header<br>$tblstart";

	if($_POST['action']=='login') {
		if (!$username)
			$msg = "Couldn't login.  You didn't input a username.";
		else {
			$username = trim($username);
			$userid = checkuser($username,$password);

			if($userid!=-1) {
				$pwhash = $sql->resultq("SELECT `password` FROM `users` WHERE `id` = '$userid'");
				$verify = create_verification_hash($verifyid, $pwhash);

				setcookie('loguserid',$userid,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);
				setcookie('logverify',$verify,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);

				$msg = "You are now logged in as $username.";
			}
			else {
				$sql->query("INSERT INTO `failedlogins` SET `time` = '". ctime() ."', `username` = '". $username ."', `password` = '". $password ."', `ip` = '". $_SERVER['REMOTE_ADDR'] ."'");
				$fails = $sql->resultq("SELECT COUNT(`id`) FROM `failedlogins` WHERE `ip` = '". $_SERVER['REMOTE_ADDR'] ."' AND `time` > '". (ctime() - 1800) ."'");

				// Keep in mind, it's now not possible to trigger this if you're IP banned
				// when you could previously, making extra checks to stop botspam not matter

				//if ($fails > 1)
				@xk_ircsend("102|". xk(14) ."Failed attempt". xk(8) ." #$fails ". xk(14) ."to log in as ". xk(8) . $username . xk(14) ." by IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(14) .".");

				if ($fails >= 5) {
					$sql->query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Send e-mail for password recovery'");
					@xk_ircsend("102|". xk(7) ."Auto-IP banned ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." for this.");
					@xk_ircsend("1|". xk(7) ."Auto-IP banned ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." for repeated failed logins.");
				}

				$msg = "Couldn't login.  Either you didn't enter an existing username, or you haven't entered the right password for the username.";
			}
		}
		$txt.="$tccell1>$msg<br>".redirect("{$GLOBALS['jul_base_dir']}/index.php",'the board',0);
	}
	elseif ($_POST['action']=='logout') {
		setcookie('loguserid','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);
		setcookie('logverify','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);

		// May as well unset this as well
		setcookie('logpassword','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);
		$txt.="$tccell1> You are now logged out.<br>".redirect("{$GLOBALS['jul_base_dir']}/index.php",'the board',0);
	}
	elseif (!$_POST['action']) {
		$ipaddr = explode('.', $_SERVER['REMOTE_ADDR']);
		for ($i = 4; $i > 0; --$i) {
			$verifyoptext[$i] = "(".implode('.', $ipaddr).")";
			$ipaddr[$i-1]       = 'xxx';
		}
		$txt .= "<body onload=window.document.replier.username.focus()>
		<FORM ACTION='{$GLOBALS['jul_views_path']}/login.php' NAME=REPLIER METHOD=POST><tr>
		$tccellh width=150>&nbsp;</td>$tccellh width=40%>&nbsp</td>$tccellh width=150>&nbsp;</td>$tccellh width=40%>&nbsp;</td></tr><tr>
		$tccell1><b>User name:</b></td>       $tccell2l>$inpt=username MAXLENGTH=25 style='width:280px;'></td>
		$tccell1 rowspan=2><b>IP Verification:</b></td> $tccell2l rowspan=2>
			<select name=verify>
				<option selected value=0>Don't use</option>
				<option value=1> /8 $verifyoptext[1]</option>
				<option value=2>/16 $verifyoptext[2]</option>
				<option value=3>/24 $verifyoptext[3]</option>
				<option value=4>/32 $verifyoptext[4]</option>
			</select><br><small>You can require your IP address to match your current IP, to an extent, to remain logged in.</small>
		</tr><tr>
		$tccell1><b>Password:</b></td>        $tccell2l>$inpp=userpass MAXLENGTH=64 style='width:180px;'></td>
		</tr><tr>
		$tccell1>&nbsp;</td>$tccell2l colspan=3>
		$inph=action VALUE=login>
		$inps=submit VALUE=Login></td></tr>
		</FORM>";
	}
	else { // Just what do you think you're doing
		$sql->query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Generic internet exploit searcher'");
		if (!mysql_error())
			xk_ircsend("1|". xk(7) ."Auto-banned asshole trying to be clever with the login form (action: ".xk(8).$_POST['action'].xk(7).") with IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) .".");
	}

	print $txt.$tblend.$footer;
	printtimedif($startingtime);
?>
