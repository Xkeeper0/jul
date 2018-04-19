<?php
	function threadpost($post,$bg,$pthread='') {
		global $loguser,$quote,$edit,$ip,$smallfont,$tzoff,$sep,$dateformat,$dateshort,$tlayout,${"tablebg$bg"};
		$post = setlayout($post);
		$p = $post['id'];
		$u = $post['uid'];
		$set['bg']    = ${"tablebg$bg"};
		$set['tdbg']  = "<td class='tbl font tdbg$bg' valign=top";

    $linkclass = "url".$post['uid'];
    $userlink = getuserlink($post, array('id'=>'uid'), $linkclass);
    unset($postuser);

		$set['userrank'] = getrank($post['useranks'],str_replace("<div", "<<z>idiot", $post['title']),$post['posts'],$post['powerlevel']);
		$set['userlink'] = "<a name={$p}></a>{$userlink}";
		$set['date']  = date($dateformat,$post['date']+$tzoff);

		if($post['location']) { $set['location']="From: ". htmlspecialchars($post['location']); }
		if($post['pronouns']) { $set['pronouns']="Pronouns: ". htmlspecialchars($post['pronouns']); }

		if($post['picture'] || ($post['moodid'] && $post['moodurl'])){
			$post['picture']  = str_replace('>','%3E',$post['picture']);
			$set['userpic']   = "<img class='avatar' src=\"{$post['picture']}\">";
			$set['picture']   = $post['picture'];

			if ($post['moodid'] && $post['moodurl']) {
				$set['userpic'] = "<img class='avatar' src=\"". str_replace(array('$', '>', '"'), array($post['moodid'], '%3E', '%22'), $post['moodurl']) ."\">";
				$set['picture'] = str_replace(array('$', '>', '"'), array($post['moodid'], '%3E', '%22'), $post['moodurl']);
			}
		}

		if($post['signtext']) {
			$post['signtext']=$sep[$loguser['signsep']].$post['signtext'];
		}

		if($pthread) {
			$set['threadlink'] = "<a href={$GLOBALS['jul_views_path']}/thread.php?id={$pthread['id']}>{$pthread['title']}</a>";
		}

		$post['text']=doreplace2($post['text'], $post['options']);

		if (filter_int($post['editdate'])) {
			$post['edited'] = " (last edited by {$post['edited']} at ".date($dateformat,$post['editdate']+$tzoff).")";
		}

		$return=dofilters(postcode($post,$set));
		return $return;
	}

	function preplayouts($posts) {
		global $sql, $postl;

		$ids = array();

		// PHP pre-buffers all queries' results by default
		// Just fetch everything now, seek back to beginning for the original function,
		// and fire off one query to get all post layout data in one query
		// instead of hitting the DB for each new header/signature encountered
		while ($ps = $sql->fetch($posts)) {
			if ($ps['headid']) $ids[] = $ps['headid'];
			if ($ps['signid']) $ids[] = $ps['signid'];
		}
		@mysql_data_seek($posts, 0);

    if (!count($ids)) return;
		$postl = $sql->getresultsbykey("SELECT id, text FROM postlayouts WHERE id IN (".implode(",", array_unique($ids, SORT_NUMERIC)).")", 'id', 'text');
	}

	function setlayout($post) {
		global $sql,$loguser,$postl;

		if($loguser['viewsig']!=1) {
			$post['headid']=$post['signid']=0;
		}

		if(!$loguser['viewsig']){
			$post['headtext']=$post['signtext']='';
			return $post;
		}

		if($loguser['viewsig']!=2){
			if($headid=filter_int($post['headid'])) {
				// just in case
				if($postl[$headid] === NULL) $postl[$headid]=$sql->resultq("SELECT text FROM postlayouts WHERE id=$headid");
				$post['headtext']=$postl[$headid];
			}
			if($signid=filter_int($post['signid'])) {
				// just in case
				if($postl[$signid] === NULL) $postl[$signid]=$sql->resultq("SELECT text FROM postlayouts WHERE id=$signid");
				$post['signtext']=$postl[$signid];
			}
		}

		$post['headtext']=settags($post['headtext'],filter_string($post['tagval']));
		$post['signtext']=settags($post['signtext'],filter_string($post['tagval']));

		if($loguser['viewsig']==2){
			$post['headtext']=doreplace($post['headtext'],$post['num'],($post['date']-$post['regdate'])/86400,$post['name']);
			$post['signtext']=doreplace($post['signtext'],$post['num'],($post['date']-$post['regdate'])/86400,$post['name']);
		}
		$post['headtext']=doreplace2($post['headtext']);
		$post['signtext']=doreplace2($post['signtext']);
		//	$post['text']=doreplace2($post['text'], $post['options']);
		return $post;
	}

function syndrome($num, $double=false, $bar=true){
	$bar	= false;
	$a		= '>Affected by';
	$syn	= "";
	if($num>=75)  {  $syn="83F3A3$a 'Reinfors Syndrome'";			$last=  75; $next=  25;	}
	if($num>=100) {  $syn="FFE323$a 'Reinfors Syndrome' +";		$last= 100; $next=  50;	}
	if($num>=150) {  $syn="FF5353$a 'Reinfors Syndrome' ++";		$last= 150; $next=  50;	}
	if($num>=200) {  $syn="CE53CE$a 'Reinfors Syndrome' +++";		$last= 200; $next=  50;	}
	if($num>=250) {  $syn="8E83EE$a 'Reinfors Syndrome' ++++";	$last= 250; $next=  50;	}
	if($num>=300) {  $syn="BBAAFF$a 'Wooster Syndrome'!!";		$last= 300; $next=  50;	}
	if($num>=350) {  $syn="FFB0FF$a 'Wooster Syndrome' +!!";		$last= 350; $next=  50;	}
	if($num>=400) {  $syn="FFB070$a 'Wooster Syndrome' ++!!";		$last= 400; $next=  50;	}
	if($num>=450) {  $syn="C8C0B8$a 'Wooster Syndrome' +++!!";	$last= 450; $next=  50;	}
	if($num>=500) {  $syn="A0A0A0$a 'Wooster Syndrome' ++++!!";	$last= 500; $next= 100;	}
	if($num>=600) {  $syn="C762F2$a 'Anya Syndrome' +++++!!!";	$last= 600; $next= 200;	}
	if($num>=800) {  $syn="62C7F2$a 'Xkeeper Syndrome' +++++!!";/*	$last= 600; $next= 200;		}
	if($num>=1000) {  $syn="FFFFFF$a 'Something higher than Xkeeper Syndrome' +++++!!";*/		}

	if($syn) {
		if ($next && $bar) {
			$barw1	= min(round(($num - $last) / $next * 150), 150);
			$barw2	= 150 - $barw1;
			$barimg	= "red.png";

			if ($double == true) {
				$hi = 16;
				$barw1 *= 2;
				$barw2 *= 2;
			} else {
				$hi	= 8;
			}

			if ($next	>= 100) $barimg	= "special.gif";
			$bar	= "<br><nobr>". generatenumbergfx($num, 3, $double) ."<img src={$GLOBALS['jul_base_dir']}/images/num1/barleft.png height=$hi><img src={$GLOBALS['jul_base_dir']}/images/num1/bar-on$barimg width=$barw1 height=$hi><img src={$GLOBALS['jul_base_dir']}/images/num1/bar-off.png width=$barw2 height=$hi><img src={$GLOBALS['jul_base_dir']}/images/num1/barright.png height=$hi>". generatenumbergfx($next - ($num - $last), 3, $double) ."</nobr>";
		}
		$syn="<br><i><font color=$syn</font></i>$bar<br>";
	}

	return $syn;
}
