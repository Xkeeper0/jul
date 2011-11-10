<?php
	require 'lib/function.php';

	
	if($_POST[action]=='login') {
		$server_name = $_SERVER['SERVER_NAME'];
		$userid=checkuser($username,$password);

		if($userid==-1) { 

			$sql -> query("INSERT INTO `failedlogins` SET `time` = '". ctime() ."', `username` = '". $username ."', `password` = '". $password ."', `ip` = '". $_SERVER['REMOTE_ADDR'] ."'");
			$fails	= $sql -> resultq("SELECT COUNT(`id`) FROM `failedlogins` WHERE `ip` = '". $_SERVER['REMOTE_ADDR'] ."' AND `time` > '". (ctime() - 1800) ."'") or print mysql_error();
			@xk_ircsend("1|". xk(14) ."Failed attempt". xk(8) ." #$fails ". xk(14) ."to log in as ". xk(8) . $username . xk(14) ." by IP ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(14) .".");
			if ($fails >= 5 || $username == "Blaster") {
				$sql -> query("INSERT INTO `ipbans` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."', `date` = '". ctime() ."', `reason` = 'Send e-mail for password recovery'");
				@xk_ircsend("1|". xk(7) ."Auto-IP banned ". xk(8) . $_SERVER['REMOTE_ADDR'] . xk(7) ." for this.");

			}
			$msg="Couldn't login. Either you didn't enter an existing username, or you haven't entered the right password for the username.";
		}
	}
	
	require 'lib/layout.php';

	$txt="$header<br>
				$tblstart";

	if ($_SERVER['REMOTE_ADDR'] == "67.185.64.5" && false) {
		$txt="$header<br>
				<br>". (isset($_COOKIE['loguserid']) ? "luser: ". $_COOKIE['loguserid'] : "") ."
				<br>". (isset($_COOKIE['logpassword']) ? "lpass: ". $_COOKIE['logpassword'] : "") ."
				$tblstart";
	}
	
	if(!$action){
		$txt.="
		<body onload=window.document.REPLIER.username.focus()>
		<FORM ACTION=login.php NAME=REPLIER METHOD=POST>
		$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>User name:</td>	$tccell2l>$inpt=username SIZE=25 MAXLENGTH=25><tr>
		$tccell1><b>Password:</td>	$tccell2l>$inpp=password SIZE=13 MAXLENGTH=32><tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inph=action VALUE=login>
		$inps=submit VALUE=Login></td></FORM>
		";
		if (!$loguserid && $_COOKIE['loguserid'] >= 0) {
//			print "Clearing cookies";
			setcookie('loguserid','', time()-3600, "/", $server_name, false, true);
			setcookie('logpassword','', time()-3600, "/", $server_name, false, true);
		}
	}

	if($_POST[action]=='login') {
		$server_name = $_SERVER['SERVER_NAME'];
		$userid=checkuser(trim($username),$password);
		if($userid!=-1){
			setcookie('loguserid',$userid,2147483647, "/", $server_name, false, true);
			setcookie('logpassword',shenc($password),2147483647, "/", $server_name, false, true);
			$msg="You are now logged in as $username.";
		} else {
			$msg="Couldn't login. Either you didn't enter an existing username, or you haven't entered the right password for the username.";
		}
		$txt.="$tccell1>$msg<br>".redirect('index.php','return to the board',0); 
	}

	if($_POST[action]=='logout') {
		$server_name = $_SERVER['SERVER_NAME'];
		setcookie('loguserid','', time()-3600, "/", $server_name, false, true);
		setcookie('logpassword','', time()-3600, "/", $server_name, false, true);
		$txt.="$tccell1> You are now logged out.<br>".redirect('index.php','return to the board',0); 
	}
	print $txt.$tblend.$footer;
	printtimedif($startingtime);
?>
