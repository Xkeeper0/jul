<?php

	include "lib/function.php";
	include "lib/layout.php";

	print "$header
		<br>
		$tblstart
		$tccell1>&nbsp;<br>If you can't restore your original post, <i>delete it</i>. Any posts not fixed will be deleted in a few days.<br>&nbsp;</td>
		$tblend
		<br>

		$tblstart";

	$z	= "SELECT `p`.`pid`, `u`.`id`, `u`.`name`, `u`.`powerlevel`, `u`.`sex` FROM `posts_text` p LEFT JOIN `posts` p2 ON `p`.`pid` = `p2`.`id` LEFT JOIN `users` u ON `u`.`id` = `p2`.`user` WHERE `p`.`headtext` = 'COCKS'";
	$sql	= mysql_query($z);

	print "<tr>$tccellc colspan=2>". mysql_num_rows($sql) ." posts remaining</td></tr>
		<tr>$tccellh>PostID</td>$tccellh>Username</td></tr>";

	while($post = mysql_fetch_array($sql, MYSQL_ASSOC)) {

		if ($loguser['id'] == $post['id']) {
			$tablecell	= $tccellc;
		} else {
			$tablecell	= $tccell1;
		}

		print "<tr>$tablecell><a href=\"thread.php?pid=". $post['pid'] ."&r=1#". $post['pid'] ."\">". $post['pid'] ."</a></td>$tablecell><a href=\"profile.php?id=\"". $post['id'] ."\"><font ". getnamecolor($post['sex'], $post['powerlevel']) .">". $post['name'] ."</font></a></td></tr>";

	}


	print "$tblend $footer";

?>