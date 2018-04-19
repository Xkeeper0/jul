<?php

	require_once '../lib/function.php';
	$windowtitle	= "Admin Cruft -- {$GLOBALS['jul_settings']['board_name']}";
	require_once '../lib/layout.php';

	print "$header<br>";

	if (!$isadmin) {

		print "
			$tblstart
				$tccell1>Uh oh, you are not the admin go away.</td>
			$tblend

		$footer
		";
		printtimedif($startingtime);
		die();
	}


	$misc	= $sql -> fetchq("SELECT * FROM `misc`");

	print adminlinkbar("{$GLOBALS['jul_views_path']}/admin.php") ."
		$tblstart
			<tr>$tccellh><b>Panel de Admin<br></td></tr>
			<tr>$tccell1>&nbsp;
				<br>Under construction for
				<br><b>". timeunits2(time() - mktime(19, 20, 21, 9, 27, 2007)) ."</b>
				<br>...and counting!
				<br>&nbsp;
			</td></tr>
		$tblend

		<br>

		<form action='{$GLOBALS['jul_views_path']}/admin.php' method='post'>
		$tblstart
			<tr>$tccellh colspan=2><b>Setting up the Soft Dip</b></td></tr>
			<tr>$tccellc colspan=2>Board settings</td></tr>

			<tr>$tccell1 width='200'><b>". $statusicons['hot'] ." threshold</b></td>
				$tccell2l>	$inpt='hotcount' value='". $misc['hotcount'] ."' class='right'> replies
							$inph='hotcount_' value='". $misc['hotcount'] ."'>
				</td></tr>

			<tr>$tccell1 width='200'><b>Disable forum?</b></td>
				$tccell2l>	$inpc='disable' value='1'> Disable
				</td></tr>


			<tr>$tccellc colspan=2>Records</td></tr>

			<tr>$tccell1 width='200'><b>View count</b></td>
				$tccell2l>	$inpt='views' value='". $misc['views'] ."' class='right'> views
							$inph='views' value='". $misc['views'] ."'>
				</td></tr>


			<tr>$tccell1 width='200'><b>Max posts/day</b></td>
				$tccell2l>	$inpt='maxpostsday' value='". $misc['maxpostsday'] ."' class='right'> posts, at $inpt='maxpostsdaydate' value='". $misc['maxpostsdaydate'] ."' class='right'>
							$inph='maxpostsday' value='". $misc['maxpostsday'] ."'>$inph='maxpostsdaydate' value='". $misc['maxpostsdaydate'] ."'>
				</td></tr>

			<tr>$tccell1 width='200'><b>Max posts/hour</b></td>
				$tccell2l>	$inpt='maxpostshour' value='". $misc['maxpostshour'] ."' class='right'> posts, at $inpt='maxpostshourdate' value='". $misc['maxpostshourdate'] ."' class='right'>
							$inph='maxpostshour' value='". $misc['maxpostshour'] ."'>$inph='maxpostshourdate' value='". $misc['maxpostshourdate'] ."'>
				</td></tr>

			<tr>$tccell1 width='200'><b>Most users online</b></td>
				$tccell2l>	$inpt='maxusers' value='". $misc['maxusers'] ."' class='right'> users, at $inpt='maxusersdate' value='". $misc['maxusersdate'] ."' class='right'>
							<br>$inpc='maxusersreset' value='1'> Reset user list
							$inph='maxusers' value='". $misc['maxusers'] ."'>$inph='maxusersdate' value='". $misc['maxusersdate'] ."'>
				</td></tr>


			<tr>$tccellc colspan=2><img src=\"images/ihateglennbeckbutistillthinkthisimagefitsquitenicelyundertheadminpanelmoneycounter.jpg\" title=\"longest file name ever\"><br>Monetary settings</td></tr>

			<tr>$tccell1 width='200'><b>Donations</b></td>
				$tccell2l>	$inpt='donations' value='". sprintf("%01.2f", $misc['donations']) ."' class='right'>$
							$inph='donations' value='". sprintf("%01.2f", $misc['donations']) ."'>
				</td></tr>

			<tr>$tccell1 width='200'><b>$$$ Ads $$$</b></td>
				$tccell2l>	$inpt='ads' value='". sprintf("%01.2f", $misc['ads']) ."' class='right'>$
							$inph='ads' value='". sprintf("%01.2f", $misc['ads']) ."'>
				</td></tr>


			<tr>$tccellc colspan=2>&nbsp;</td></tr>

			<tr>$tccell1 width='200'>&nbsp;</td>
				$tccell2l>	$inps='submit' value='Submit changes'>
				<br><s><strong>(Only saves the money settings though.)</strong></s> just kidding, it doesn't work.
				</td></tr>


		$tblend
		</form>

	";



	print "$footer";
	printtimedif($startingtime);


	// returns several field names with hours/date/time all set up and that jazz etc blah blah blah
	function timetofields($fname, $time) {



		return;
	}





?>
