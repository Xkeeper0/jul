<?php
	chdir("../");
	require_once 'lib/function.php';
	require_once 'lib/rpg.php';


	if(!intval($u)) die("Missing ?u=<id> parameter");
	$user=mysql_fetch_array(mysql_query("SELECT name,posts,regdate,users_rpg.* FROM users,users_rpg WHERE id='$u' AND uid=id")) or die("User doesn't exist");
	$p=$user[posts];
	$d=(ctime()-$user[regdate])/86400;
	if(!$it) $it=0;
	if(!$ne) {
		$eqitems=mysql_query("SELECT * FROM items WHERE id=$user[eq1] OR id=$user[eq2] OR id=$user[eq3] OR id=$user[eq4] OR id=$user[eq5] OR id=$user[eq6] OR id=$it") or print mysql_error();
		while($item=mysql_fetch_array($eqitems)) $items[$item[id]]=$item;
	}
	if($ct){
		 $GPdif=floor($items[$user['eq'.$ct]][coins]*0.6)-$items[$it][coins];
		 $user['eq'.$ct]=$it;
	}

	$st=getstats($user,$items,$class);
	$st[GP]+=$GPdif;
	if($st[lvl]>0) $pct=1-calcexpleft($st[exp])/totallvlexp($st[lvl]);



	$st['tonext']	= calcexpleft($st['exp']);
	$st['GP2']		= $user['gcoins'];
	$st['id']		= $u;
	$st['name']		= $user['name'];

	if (isset($_REQUEST['s'])) {
		if ($_REQUEST['s'] == "json") {
			header("Content-type: application/json;");
			print json_encode($st);

		} else {
			header("Content-type: text/plain;");
			print serialize($st);
		}
	} else {
		header("Content-type: text/plain;");
		foreach ($st as $k => $v) {
			print "$k=$v\n";
		}
	}
