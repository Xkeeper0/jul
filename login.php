<?php
	require 'lib/function.php';
	require 'lib/layout.php';

	// Bots don't need to be on this page
	$meta['noindex'] = true;

	$username	= $_POST['username'] ?? null;
	$password	= $_POST['userpass'] ?? null;
	$verifyid	= $_POST['verify'] ?? null;
	$action		= $_POST['action'] ?? null;
	$show_form	= true;

	$txt		= "$header<br>";
	$msg		= null;

	if ($action=='login') {
		if (!$username) {
			$msg = "Couldn't login.  You didn't input a username.";
		} else {
			$username = trim($username);

			$useridn = checkusername(stripslashes($username));
			$userid = checkuser($username,$password);

			if ($useridn === -1) {
				$msg = "No user with that username exists.<br><br>If you aren't sure if you have an account, check the <a href='memberlist.php'>memberlist</a> or <a href='register.php'>register a new account</a>.";

			} elseif ($userid !== -1) {
				$pwhash = $sql->resultq("SELECT `password` FROM `users` WHERE `id` = '$userid'");
				$verify = create_verification_hash($verifyid, $pwhash);

				setcookie('loguserid',$userid,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);
				setcookie('logverify',$verify,2147483647, "/", $_SERVER['SERVER_NAME'], false, true);

				$msg		= "You are now logged in as <b>$username</b>.<br><br>".redirect('index.php','the board',2);
				$show_form	= false;

			} else {
				$sql->query("INSERT INTO `failedlogins` SET `time` = '". ctime() ."', `username` = '". $username ."', `password` = '". $password ."', `ip` = '". $_SERVER['REMOTE_ADDR'] ."'");
				$fails = $sql->resultq("SELECT COUNT(`id`) FROM `failedlogins` WHERE `ip` = '". $_SERVER['REMOTE_ADDR'] ."' AND `time` > '". (ctime() - 1800) ."'");

				// Keep in mind, it's now not possible to trigger this if you're IP banned
				// when you could previously, making extra checks to stop botspam not matter
				// @xk_ircsend("102|". xk(14) ."Failed attempt". xk(8) ." #$fails ". xk(14) ."to log in as ". xk(8) . $username . xk(14) ." by IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(14) .".");
				// report("mod", "Failed attempt **#$fails** to log in as **$username** by IP " . $_SERVER['REMOTE_ADDR'] . ".");

				if ($fails >= 10) {
					$sql->query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Too many failed login attempts. Send e-mail for password recovery'");
					@xk_ircsend("102|". xk(7) ."Auto-IP banned ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." for this.");
					report("mod", "Auto-IP banned " . $_SERVER['REMOTE_ADDR'] . "for this.");
					@xk_ircsend("1|". xk(7) ."Auto-IP banned ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." for repeated failed logins.");
					report("super", "Auto-IP banned " . $_SERVER['REMOTE_ADDR'] . "for repeated failed logins.");
				}

				$msg = "Couldn't login. The password you entered doesn't match.
				<br><br>If you've forgotten your password, <a href='thread.php?id=17948'>join Discord</a> (sorry) or email me at <tt>xkeeper@gmail.com</tt> / Discord <tt>@xkeeper</tt>.";

				if ($fails >= 5) {
					$msg .= "<br><b>Warning: Continued failed attempts will result in a ban.</b>";
				}

			}
		}
		// $txt.="$tccell1>$msg<br>".redirect('index.php','the board',0);

	} elseif ($action == 'logout') {
		setcookie('loguserid','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);
		setcookie('logverify','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);

		// May as well unset this as well
		setcookie('logpassword','', time()-3600, "/", $_SERVER['SERVER_NAME'], false, true);
		$show_form = false;
		$txt.="$tccell1> You are now logged out.<br>".redirect('index.php','the board',0);

	} elseif ($action) { // Just what do you think you're doing
		die("error");
		// $sql->query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Generic internet exploit searcher'");
		// if (!mysql_error())
		// 	xk_ircsend("1|". xk(7) ."Auto-banned asshole trying to be clever with the login form (action: ".xk(8).$action.xk(7).") with IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) .".");
	}

	if ($msg) {
		$txt .= <<<MSG
	$tblstart
	<tr>
		$tccellh><b>Message</b></td>
	</tr><tr>
		$tccell1>$msg</td>
	</tr></table><br>
MSG;

}

	if ($show_form) {
		$ipaddr = explode('.', $_SERVER['REMOTE_ADDR']);
		for ($i = 4; $i > 0; --$i) {
			$verifyoptext[$i] = "(".implode('.', $ipaddr).")";
			$ipaddr[$i-1]       = 'xxx';
		}
		$txt .= "<form action='login.php' name='REPLIER' method='post'>
		$tblstart
	<tr>
		$tccellh width=150>&nbsp;</td>
		$tccellh width=40%>&nbsp;</td>
		$tccellh width=150>&nbsp;</td>
		$tccellh width=40%>&nbsp;</td>
	</tr><tr>
		$tccell1><b>User name:</b></td>
		$tccell2l>$inpt=username maxlength=25 style='width:280px;' ". (!$username ? "autofocus='1' " : "") ."tabindex='1' value=\"". htmlspecialchars($username) ."\"></td>
		$tccell1 rowspan=2><b>IP Verification:</b></td> $tccell2l rowspan=2>
			<select name='verify' tabindex=4>
				<option selected value=0>Don't use</option>
				<option value=1> /8 $verifyoptext[1]</option>
				<option value=2>/16 $verifyoptext[2]</option>
				<option value=3>/24 $verifyoptext[3]</option>
				<option value=4>/32 $verifyoptext[4]</option>
			</select><br><small>You can require your IP address to match your current IP, to an extent, to remain logged in.</small>
	</tr><tr>
		$tccell1><b>Password:</b></td>
		$tccell2l>$inpp=userpass maxlength=64 style='width:180px;' tabindex='2'". ($username ? " autofocus='1' " : "") ."></td>
	</tr><tr>
		$tccell1>&nbsp;</td>$tccell2l colspan=3>
		$inph=action value='login'>
		$inps=submit value='Login' tabindex='3'></td></tr>
	</table>
	</form>";

	}

	print $txt.$tblend.$footer;
	printtimedif($startingtime);
