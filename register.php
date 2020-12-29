<?php

	if ($_POST['action'] == "Register" && $_POST['homepage']) {
		// If someone submits the form with the fake homepage field filled,
		// just do nothing and send them off elsewhere to spam
		header("Location: http://127.0.0.1");
		die();
	}

	require 'lib/function.php';
	require 'lib/layout.php';

	print $header;

	if ($adminconfig['registrationdisable']) {
		die("$tblstart<br>$tccell2>Registration is disabled. Please contact an admin if you have any questions.$tblend$footer");
	}


	// Errors for display in the registration form
	$error = false;
	$errors = [
		'name' => "",
		'pass' => "",
		'email' => "",
		];

	// If true, won't show the form again on error
	$fatal = false;
	$registered = false;

	$name = trim($_POST['name'] ?? "");
	$pass = $_POST['pass'] ?? null;
	$email = $_POST['email'] ?? null;

	if ($_POST['action'] == 'Register') {

		if ($name === "") {
			$error = "No username given.";
			$errors['name']	= "Required";
		}

		if ($pass === null) {
			$error = "No password given.";
			$errors['pass']	= "Required";
		}

		// If e-mail address is given, make sure it is an actual e-mail address
		if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid e-mail address.";
			$errors['email']	= "Invalid";
		}


		// Only do any of this if we don't have an issue already
		if (!$error) {

			// Simple check if the person in question is using some trash proxy
			// or other service to get around bans ...
			// Do a simple cURL request to their IP address and see if it responds.
			// If it does, and contains one of the usual words, throw them out the window

			// This used to be a surprisingly good way of catching shitters,
			// and it might even still work to this day

			$ch = curl_init();
			curl_setopt ($ch,CURLOPT_URL, "http://". $_SERVER['REMOTE_ADDR']);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
			$file_contents = curl_exec($ch);
			curl_close($ch);

			if (
				stristr($file_contents, "proxy")
				|| stristr($file_contents, "forbidden")
				|| stristr($file_contents, "it works")
				|| stristr($file_contents, "anonymous")
				|| stristr($file_contents, "filter")
				|| stristr($file_contents, "panel")
				|| stristr($file_contents, "apache")
				|| stristr($file_contents, "nginx")
				) {

				// $sql -> query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Reregistering fuckwit'");
				// @xk_ircsend("1|". xk(7) ."Auto-IP banned proxy-abusing $adjectives[0] with IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." on registration. (Tried to register with username $name)");

				// Rather than IP banning them on principle, though, give them a message
				// about why they're not allowed to register, just in case

				$error = "It appears you're trying to register through some proxy service or other anonymizing tool.
				<br>These have often been abused to get around bans, so we don't allow registering using these.
				<br>Try disabling it and registering again, or contact an administrator for help.";
				$fatal = true;

				// die("$tccell1>Thank you, $name, for registering your account.<br>".redirect('index.php', 'the board',0).$footer);
			}
		}

		// Only do this if we have no other errors already, like the proxy check
		if (!$error) {

			// Check if the username is available
			// FIrst, remove all spaces and other nonsense from it
			// @TODO This is really bad and should be fixed
			$username = substr(trim($name), 0, 25);
			$username2 = str_replace(' ', '', $username);
			$username2 = str_replace(' ', '', $username2);
			$username2 = preg_replace("'&nbsp;?'si", '', $username2);
			$username2 = stripslashes($username2);
			$userid = false;

			// If 1, user will be registered as an admin.
			// This is done so the first user on the board registers as an admin
			$admin = 1;

			$users = $sql->query('SELECT id, name FROM users');
			while ($user = $sql->fetch($users)) {
				// We found a user, so no admin for this user
				$admin = 0;
				$user['name'] = str_replace(' ', '', $user['name']);
				$user['name'] = str_replace(' ', '', $user['name']);
				if (strcasecmp($user['name'], $username2) == 0) {
					$userid = $user['id'];
					break;
				}
			}

			// Does anyone else have this IP address? If so, abort (unless they're an admin)
			$nomultis = $sql->fetchq("SELECT * FROM `users` WHERE `lastip` = '". mysql_real_escape_string($_SERVER['REMOTE_ADDR']) ."'");

			if ($userid === false && $name && $pass && (!$nomultis || $isadmin)) {

				$currenttime = ctime();
				$ipaddr = $_SERVER['REMOTE_ADDR'];

				$ircout['name']		= stripslashes($name);
				$ircout['ip']		= $ipaddr;

				$succ = $sql->query("
					INSERT INTO `users`
					SET
						`name` = '". mysql_real_escape_string($name) ."',
						". ($email !== null ? "`email` = '". mysql_real_escape_string($email) ."'," : "") ."
						`powerlevel` = '". ($admin ? 3 : 0) ."',
						`postsperpage` = '20',
						`threadsperpage` = '50',
						`lastip` = '". mysql_real_escape_string($ipaddr) ."',
						`layout` = '1',
						`scheme` = '0',
						`lastactivity` = '$currenttime',
						`regdate` = '$currenttime'
						");

				$newuserid			= mysql_insert_id();
				$sql->query("UPDATE users SET `password` = '".getpwhash($pass, $newuserid)."' WHERE `id` = '$newuserid'");

				$ircout['id']		= $newuserid;
				xk_ircout("user", $ircout['name'], $ircout);

				$sql->query("INSERT INTO `users_rpg` (`uid`) VALUES ('". $newuserid ."')") or print mysql_error();

				print "<br>$tblstart$tccell1>Your new account, $name, has been registered.<br>".redirect('login.php', 'log in',0);
				$registered = true;

			} else {

				if ($userid !== false) {
					$error = "The username '". htmlspecialchars($name) ."' is already <a href='profile.php?id=$userid'>in use</a>.";
					$errors['name'] = "In use";

				} elseif ($nomultis) {
					$error = "You may have an account already as '<a href=profile.php?id=$nomultis[id]>$nomultis[name]</a>'.<br>If this is incorrect, please contact an administrator.";
					$fatal = true;

				} else {
					$error = "Unknown reason. Please contact an administrator.";
					$fatal = true;
				}

			}

			print $tblend;

		}
	}

	if ($error) {
		print <<<HTML
		<br>
		$tblstart
		<tr>$tccellh>Error registering account</td>
		<tr>$tccell1>$error
		$tblend
HTML;
	}


	// If we didn't register and/or we don't have a fatal error, show the form
	if (!$registered && !$fatal) {
		$descbr="</b>$smallfont<br></center>&nbsp";

		$namev = htmlspecialchars($name);
		$emailv = htmlspecialchars($email);

		print <<<HTML

		<form action="register.php" method="post">
		<br>
		$tblstart

			$tccellh colspan="2">Login information</td>
			<tr>
			$tccell1><b>User name:</b>$descbr The name you want to use on the board.</td>
			$tccell2l width=50%>$inpt=name size="25" maxlength="25" id="name" value="$namev"> {$errors['name']}
			<tr>
			$tccell1><b>Password:</b>$descbr Enter any password up to 32 characters in length. It can later be changed by editing your profile.</td>
			$tccell2l width=50%>$inpp=pass size="25" maxlength="64"> {$errors['pass']}
			<tr>
			$tccell1><b>E-mail address:</b>$descbr Your e-mail address. This will only be used for recovering your account. (optional)</td>
			$tccell2l width=50%>$inpt=email size="50" maxlength="60" value="$emailv"> {$errors['email']}
			<tr>
			$tccellh colspan="2">&nbsp;<tr>
			$tccell1>&nbsp;</td>$tccell2l>
			$inph=action value="Register">
			$inps=submit value="Register account"></td>
			</table>

			<div style='visibility: hidden;'><b>Homepage:</b><small> DO NOT FILL IN THIS FIELD. DOING SO WILL RESULT IN INSTANT IP-BAN.</small> - $inpt=homepage SIZE=25 MAXLENGTH=255></div>

		</form>

		<script>
			document.getElementById("name").focus();
		</script>

HTML;

	}

	print $footer;
	printtimedif($startingtime);
