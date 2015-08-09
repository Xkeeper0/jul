<?php

	if (!$_POST['go']) {

		?>
<html>
	<title> uh oh </title>
	<body style="background: #000 url('images/bombbg.png'); color: #f00;">
		<font style="font-family: Verdana, sans-serif;">
		<center>
		<br><font style="color: #f88; size: 175%;"><big><b>This site has been blocked for <i>your protection</i>.</b></big></font>
		<br>
		<br><font style="color: #f55;">http://insectduel.proboards82.com/ - Reason: <b>fucking stupid</b></font>
		<br>
		<br>If you are sure you want to visit this site (e.g., for humor), please click the button.
		<br>
		<br><form style="margin: 0; padding: 0;" action="idiotredir.php" method="post"><input type="submit" name="go" value="I'm sure" style="border: 1px solid #c99; background: #833; color: #fdd; font-weight: bold; font-family: Verdana, sans-serif; padding: 5px;"><input type="hidden" value="<?php print $_SERVER['QUERY_STRING']; ?>" name="url"></form>
	</body>
</html>
	<?php

	} else {

		header("Location: http://insectduel.proboards82.com". $_POST['url']);

	}

?>