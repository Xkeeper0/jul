
<?php
	global $TEMPLATE_VARS;
?>

<table><tr><td>id</td><td>title</td></tr>
<?php
	while ($res = mysql_fetch_assoc($TEMPLATE_VARS["lastreplied"])) {
		?>
			<hr>
			<tr><td><? print $res["id"] ?></td><td><? print $res["title"] ?></td></tr>
		<?php
			
	} ?>
	</table>
	<br>
		Favorited threads:
	<br>
	<?php
	
	while ($res = mysql_fetch_assoc($TEMPLATE_VARS["favorited"])) {
		var_dump($res); ?> <br> <?php
	} ?>
