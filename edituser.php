<?php

	require 'lib/function.php';
	require 'lib/layout.php';


	print $header;

	if (!$isadmin) {
		// TODO: Better error message formatting. Or not.
		print "No.";
		print $footer;
		die();
	}
 

	$user		= $sql -> fetchq("SELECT * FROM `users` WHERE `id`='$id'");



	if (!$_POST['action']) {

		// Generate layout + options
		$check1[$user['powerlevel']]		= "selected='selected'";
		$check2[$user['sex']]				= "checked='1'";
		$check3[$user['useranks']]			= "checked='1'";
		$check4[$user['profile_locked']]	= "checked='1'";
		$check5[$user['editing_locked']]	= "checked='1'";
		$checked2[$user['viewsig']]			= "checked='1'";
		$checked3[$user['posttool']]		= "checked='1'";
		
		// Why are these in reverse order, anyway
		$plocking	= "
			<label for='plock1' style='margin-right: 2em;'>$radio=profile_locked id='plock1' value='1' $check4[1]>Locked</label>
			<label for='plock0' style='margin-right: 2em;'>$radio=profile_locked id='plock0' value='0' $check4[0]>Unlocked</label>
			";
			
		$elocking	= "
			<label for='elock1' style='margin-right: 2em;'>$radio=editing_locked id='elock1' value='1' $check5[1]>Locked</label>
			<label for='elock0' style='margin-right: 2em;'>$radio=editing_locked id='elock0' value='0' $check5[0]>Unlocked</label>
			";
			
		$levellist	= "
			<select name=powerlevel>
				<option value='-1' {$check1['-1']}>Banned</option>
				<option value='0' $check1[0]>Normal</option>
				<option value='1' $check1[1]>Normal +</option>
				<option value='2' $check1[2]>Moderator</option>
				<option value='3' $check1[3]>Administrator</option>
				<option value='4' $check1[4]>Administrator (invisible)</option>
			</select>
			";
			
		$sexlist	= "
			<label for='sex0' style='margin-right: 2em;'>$radio='sex' id='sex0' value='0' $check2[0]>Male</label>
			<label for='sex1' style='margin-right: 2em;'>$radio='sex' id='sex1' value='1' $check2[1]>Female</label>
			<label for='sex2' style='margin-right: 2em;'>$radio='sex' id='sex2' value='2' $check2[2]>N/A</label>
			<label for='sexr'>$radio='sex' id='sexr' value='-378'>Raw value</label>: $inpt='sexn' value=$user[sex]>
			";

		$vsig		= "
			<label for='viewsig0' style='margin-right: 2em;'>$radio='viewsig' id='viewsig0' value=0 $checked2[0]>Disabled</label>
			<label for='viewsig1' style='margin-right: 2em;'>$radio='viewsig' id='viewsig1' value=1 $checked2[1]>Enabled</label>
			<label for='viewsig2' style='margin-right: 2em;'>$radio='viewsig' id='viewsig2' value=2 $checked2[2]>Auto-updating</label>
			";
			
		$vtool		= "
			<label for='posttool0' style='margin-right: 2em;'>$radio='posttool' id='posttool1' value='0' $checked3[0]>Disabled&nbsp &nbsp
			<label for='posttool1' style='margin-right: 2em;'>$radio='posttool' id='posttool1' value='1' $checked3[1]>Enabled
			";
			
		if ($user['birthday']) {
			$birthday	= getdate($user['birthday']);
			$month		= $birthday['mon'];
			$day		= $birthday['mday'];
			$year		= $birthday['year'];
		} else {
			$month		= "";
			$day		= "";
			$year		= "";
		}
		
		
		# TODO: Combine this whole thing into one query with the magic of GROUP BY
		$schemes		= $sql -> query("SELECT `id`, `name` FROM `schemes` ORDER BY `ord`");
		while ($sch = $sql -> fetch($schemes)) {
			$sel		= ($sch['id'] == $user['scheme']) ? "selected='selected'" : "";
			$used		= $sql -> resultq("SELECT COUNT(`id`) cnt FROM `users` WHERE `scheme` = '$sch[id]'");
			$schlist	.= "
				<option value='$sch[id]' $sel>$sch[name] ($used)</option>
				";
		}
		$schlist		= "
			<select name=sscheme>
				$schlist
			</select>
			";

			
		
		$tlayouts		= $sql -> query("SELECT `id`, `name` FROM `tlayouts` ORDER BY `ord`");
		while ($lay = $sql -> fetch($tlayouts)) {
			$sel		= ($lay['id'] == $user['layout']) ? "selected='selected'" : "";
			$used		= $sql -> resultq("SELECT COUNT(`id`) FROM `users` WHERE `layout` = '$lay[id]'");
			$laylist	.= "
				<option value='$lay[id]' $sel>$lay[name] ($used)</option>
				";
		}
		$laylist		= "
			<select name='tlayout'>
				$laylist
			</select>
			";


		$rsets			= $sql -> query("SELECT `id`, `name` FROM `ranksets` ORDER BY `id`");
		while ($set = $sql -> fetch($rsets)) {
			$sel		= ($set['id'] == $user['useranks']) ? "selected='selected'" : "";
			$used		= $sql -> resultq("SELECT COUNT(*) FROM `users` WHERE `useranks` = '$set[id]'");
			$rsetlist	.= "
				<option value='$set[id]' $sel>$set[name] ($used)</option>
				";
		}
		$rsetlist		= "
			<select name='useranks'>
				$rsetlist
			</select>
			";


		$lft	= "<tr>$tccell1><b>";
		$rgt	= ":</td>$tccell2l>";
		$hlft	= "<tr>$tccellh>";
		$hrgt	= "</td>$tccellh>&nbsp;</td>";
		squot(0,$user['name']);
		squot(0,$user['title']);
		squot(0,$user['realname']);
		squot(0,$user['location']);
		squot(0,$user['homepagename']);
		sbr(1, $user['bio']);
		sbr(1, $user['signature']);
		sbr(1, $user['postheader']);
		print "
			<br>
			<form action='edituser.php' method='post'>
				$tblstart
					$hlft Login information         $hrgt
					$lft User name                  $rgt $inpt=username value=\"$user[name]\" size=25 maxlength=25>
					$lft Password                   $rgt $inpp=password value=\"\" size=13 maxlength=32>

					$hlft Administrative bells and whistles $hrgt
					$lft Power level                $rgt $levellist    
					$lft Custom title               $rgt $inpt=usertitle value=\"$user[title]\" size=60 maxlength=255>
					$lft Rank set                   $rgt $rsetlist
					$lft Number of posts            $rgt $inpt=numposts size=5 maxlength=10 value=$user[posts]>
					$lft Registration time:</b>$smallfont<br>(seconds since ".date($dateformat,$tzoff).")</td>$tccell2l>$inpt=regtime size=10 maxlength=15 value=$user[regdate]><tr>
					$lft Lock Profile               $rgt $plocking
					$lft Restrict Editing           $rgt $elocking

					$hlft Appearance                $hrgt            
					$lft Mini picture               $rgt $inpt=minipic value=\"$user[minipic]\" size=60 maxlength=100>
					$lft User picture               $rgt $inpt=picture value=\"$user[picture]\" size=60 maxlength=100>
					$lft Mood avatar                $rgt $inpt=moodurl value=\"$user[moodurl]\" size=60 maxlength=100>
					$lft Post background            $rgt $inpt=postbg value=\"$user[postbg]\" size=60 maxlength=100>
					$lft Post header                $rgt $txta=postheader rows=5 cols=60>". htmlspecialchars($user[postheader]) ."</textarea>
					$lft Signature                  $rgt $txta=signature rows=5 cols=60>". htmlspecialchars($user[signature]) ."</textarea>

					$hlft Personal information      $hrgt                
					$lft Sex                        $rgt $sexlist
					$lft Real name                  $rgt $inpt=realname value=\"$user[realname]\" size=40 maxlength=60>
					$lft Location                   $rgt $inpt=location value=\"$user[location]\" size=40 maxlength=60>
					$lft Birthday                   $rgt Month: $inpt=bmonth size=2 maxlength=2 value=$month> 
					&nbsp;                               Day:   $inpt=bday size=2 maxlength=2 value=$day> 
					&nbsp;                               Year:  $inpt=byear size=4 maxlength=4 value=$year>
					$lft Bio                        $rgt $txta=bio rows=5 cols=60>". htmlspecialchars($user[bio]) ."</textarea>    

					$hlft Online services           $hrgt    
					$lft Email address              $rgt $inpt=email value=\"$user[email]\" size=60 maxlength=60>
					$lft AIM screen name            $rgt $inpt=aim value=\"$user[aim]\" size=30 maxlength=30>
					$lft ICQ number                 $rgt $inpt=icq size=10 maxlength=10 value=$user[icq]>
					$lft Homepage title             $rgt $inpt=pagename value=\"$user[homepagename]\" size=60 maxlength=80>
					$lft Homepage URL               $rgt $inpt=homepage value=\"$user[homepageurl]\" size=60 maxlength=80>

					$hlft Options                   $hrgt
					$lft Timezone offset            $rgt $inpt=timezone size=5 maxlength=5 value=$user[timezone]>
					$lft Date format                $rgt $inpt=dateformat value=\"". $user['dateformat'] ."\" size=16 maxlength=32>
					$lft Short date format          $rgt $inpt=dateshort value=\"". $user['dateshort'] ."\" size=8 maxlength=32>
					$lft Posts per page             $rgt $inpt=postsperpage size=5 maxlength=5 value=$user[postsperpage]>
					$lft Threads per page           $rgt $inpt=threadsperpage size=4 maxlength=4 value=$user[threadsperpage]>
					$lft Use post toolbar           $rgt $vtool
					$lft View post layouts          $rgt $vsig
					$lft Thread layout              $rgt $laylist
					$lft Color scheme               $rgt $schlist

					$lft &nbsp;</td>                $tccell2l>
                    $inph=action value='saveprofile'>
                    $inph=userid value='$id'>
                    $inps=submit value='Edit profile'></td>

				$tblend
			</form>
		"; 
        
        

	} elseif ($_POST['action'] == "saveprofile") {

		sbr(0, $signature);
		sbr(0, $bio);
		sbr(0, $postheader);
		$minipic		= htmlspecialchars($minipic);
		$avatar			= htmlspecialchars($avatar);
		
		if (!$bmonth || !$bday || !$byear) {
			$birthday	= 0;
		} else {
			$birthday	= mktime(0, 0, 0, $bmonth, $bday, $byear);
		}
		
		if ($password) {
			$passedit="`password` = '".md5($password)."', ";
		}
		
		if ($sex == -378) {
			$sex = $sexn;
		}
	
		$sql -> query("
		UPDATE	`users` 
		SET		`posts`				= '$numposts',
				`regdate`			= '$regtime',
				`name`				= '$username',
				$passedit
				`picture`			= '$picture', 
				`signature`			= '$signature', 
				`bio`				= '$bio', 
				`powerlevel`		= '$powerlevel', 
				`title`				= '$usertitle', 
				`email`				= '$email', 
				`icq`				= '$icq', 
				`aim`				= '$aim', 
				`sex`				= '$sex',  
				`homepageurl`		= '$homepage', 
				`timezone`			= '$timezone', 
				`dateformat`		= '$dateformat', 
				`dateshort`			= '$dateshort', 
				`postsperpage`		= '$postsperpage', 
				`realname`			= '$realname', 
				`location`			= '$location', 
				`postbg`			= '$postbg', 
				`postheader`		= '$postheader', 
				`useranks`			= '$useranks', 
				`birthday`			= '$birthday', 
				`minipic`			= '$minipic', 
				`homepagename`		= '$pagename', 
				`scheme`			= '$sscheme', 
				`threadsperpage`	= '$threadsperpage', 
				`viewsig`			= '$viewsig', 
				`layout`			= '$tlayout', 
				`posttool`			= '$posttool', 
				`moodurl`			= '$moodurl', 
				`profile_locked`	= '$profile_locked', 
				`editing_locked`	= '$editing_locked' 
				
		WHERE	`id`				= '$userid'	
			") or print mysql_error();
			
		print "
			$tblstart
			 $tccell1>Thank you, $loguser[name], for editing this user.<br>
						". redirect("index.php","return to the board", 0) ."
			$tblend"; 
	}
	
	print $footer;
	printtimedif($startingtime);

	