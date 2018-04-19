<?php
  require_once '../lib/function.php';
  require_once '../lib/layout.php';
  require_once '../lib/rpg.php';
  print "$header<br>";
  if(!$log){
    print "
	$tblstart$tccell1>You must be logged in to access the Item Shop.<br>".
	redirect("{$GLOBALS['jul_base_dir']}/index.php",'return to the main page',0).
	$tblend;
  }else{
    $user=mysql_fetch_array(mysql_query("SELECT posts,regdate,users_rpg.* FROM users,users_rpg WHERE id=$loguserid AND uid=id"));
    $p=$user[posts];
    $d=(ctime()-$user[regdate])/86400;
    $st=getstats($user);
    $GP=$st[GP];
    switch($action){
	case '':
	  $shops=mysql_query('SELECT * FROM itemcateg ORDER BY corder');
	  $eq=mysql_fetch_array(mysql_query("SELECT * FROM users_rpg WHERE uid=$loguserid"));
	  $eqitems=mysql_query("SELECT * FROM items WHERE id=$eq[eq1] OR id=$eq[eq2] OR id=$eq[eq3] OR id=$eq[eq4] OR id=$eq[eq5] OR id=$eq[eq6] OR id=$eq[eq7]");
	  while($item=mysql_fetch_array($eqitems)) $items[$item[id]]=$item;
	  while($shop=mysql_fetch_array($shops))
	    $shoplist.="
		<tr>
		$tccell1><a href={$GLOBALS['jul_views_path']}/shoph.php?action=items&cat=$shop[id]#status>$shop[name]</a></td>
		$tccell2s>$shop[description]
		$tccell1s>".$items[$eq['eq'.$shop[id]]][name]."
	    ";
	  print "
		<table width=100%><td valign=top width=120>
		 <img src={$GLOBALS['jul_views_path']}/status.php?u=$loguserid>
		</td><td valign=top>
		$tblstart
		 $tccellh><b>???</b><tr>
		 $tccell1>This place hasn't been touched in ages...</td>
		$tblend
		<br>
		$tblstart
		 $tccellh colspan=3>Hidden Shop<tr>
		 $tccellc>Shop</td>$tccellc>Description</td>$tccellc>Item equipped</td>
		 $shoplist
		$tblend
		</table>
	  ";
	break;
	case 'items':
	  $eq=mysql_fetch_array(mysql_query("SELECT eq$cat AS e FROM users_rpg WHERE uid=$loguserid"));
	  $eqitem=mysql_fetch_array(mysql_query("SELECT * FROM items WHERE id=$eq[e]"));
        print "
		<script>
		  function preview(user,item,cat,name){
		    document.getElementById('prev').src='{$GLOBALS['jul_views_path']}/status.php?u='+user+'&it='+item+'&ct='+cat+'&'+Math.random();
		    document.getElementById('pr').innerHTML='Equipped with<br>'+name+'<br>---------->';
		  }
		</script>
		<style>
			.disabled	{color:#888888}
			.higher	{color:#abaffe}
			.equal	{color:#ffea60}
			.lower	{color:#ca8765}
		</style>
		$tblstart
		  $tccell1><a href={$GLOBALS['jul_views_path']}/shoph.php>Return to shop list</a>
		$tblend
		<a name=status>
		<table><td width=256>
		 <img src={$GLOBALS['jul_views_path']}/status.php?u=$loguserid>
		</td><td width=150>
		 <center><font class=fonts>
		  <div id=pr></div>
		 </font></center>
		</td><td>
		 <img src=images/_.gif id=prev>
		</table>
		<br>
	  ";
	  $atrlist='';
	  for($i=0;$i<9;$i++) $atrlist.="$tccellh width=50>$stat[$i]</td>";
	  $items=mysql_query("SELECT * FROM items WHERE (cat=$cat OR cat=0) AND `hidden` = 1 ORDER BY type,coins");
	  print "
		$tblstart
		$tccellh width=110 colspan=2>Commands</td>$tccellct width=1 rowspan=10000>&nbsp;</td>
		$tccellh colspan=1>Item</td>
		$atrlist
		$tccellh width=6%><img src=images/coin.gif></td>
		$tccellh width=5%><img src=images/coin2.gif></td>
	  ";
	  while($item=mysql_fetch_array($items)){
	    $preview="<a href=#status onclick='preview($loguserid,$item[id],$cat,\"$item[name]\")'>Preview</a>";
	    if($item[id]==$eq[e] && $item[id]){
		$comm="width=80 colspan=2><a href={$GLOBALS['jul_views_path']}/shoph.php?action=sell&cat=$cat>Sell</a>";
	    }elseif($item[id] && $item[coins]<=$GP && !$item[gcoins]){
		$comm="width=30><a href={$GLOBALS['jul_views_path']}/shoph.php?action=buy&id=$item[id]>Buy</a></td>$tccell1 width=50>$preview";
	    }elseif(!$eq[e] && !$item[id]){
		$comm="width=80 colspan=2>-";
	    }else{
		$comm="width=80 colspan=2>$preview";
	    }
	    if($item[id]==$eqitem[id]) $color=' class=equal';
	    elseif($item[coins]>$GP) $color=' class=disabled';
	    else $color='';
	    $atrlist='';
	    for($i=0;$i<9;$i++){
		$st=$item["s$stat[$i]"];
		if(substr($item[stype],$i,1)=='m'){
		  $st=vsprintf('x%1.2f',$st/100);
		  if($st==100) $st='&nbsp;';
		}else{
		  if($st>0) $st="+$st";
		  if(!$st) $st='&nbsp;';
		}
		$itst=$item["s$stat[$i]"];
		$eqst=$eqitem["s$stat[$i]"];
		if(!$color && substr($item[stype],$i,1)==substr($eqitem[stype],$i,1)){
		  if($itst> $eqst) $st="<font class=higher>$st</font>";
		  if($itst==$eqst) $st="<font class=equal>$st</font>";
		  if($itst< $eqst) $st="<font class=lower>$st</font>";
		}
		$atrlist.="
		  $tccell1>$st</td>";
	    }

		if ($item['desc']) {
			$item['name']	.= " <span class=\"fonts\" style=\"color: #88f;\">- ". $item['desc'] ."</span>";
		}

	    print "
		<tr$color>
		$tccell1 $comm</td>
		$tccell2l>$item[name]</td>
		$atrlist
		$tccell2r>". ($item[coins] < 8388607 ? $item[coins] : "tons") ."</td>
		$tccell2r>". ($item[gcoins] < 8388607 ? $item[gcoins] : "tons") ."</td>
	    ";
	  }
	  print $tblend;
	break;
	case 'buy':
	  $item=mysql_fetch_array(mysql_query("SELECT * FROM items WHERE id=$id"));
	  if($item[coins]<=$GP){
	    $pitem=mysql_fetch_array(mysql_query("SELECT coins FROM items WHERE id=".$user['eq'.$item[cat]]));
	    $whatever = $pitem[coins]*0.6-$item[coins];
	    mysql_query("UPDATE users_rpg SET `eq". $item[cat] ."`='". $id ."',`spent`=spent-". $whatever ." WHERE uid=$loguserid") or print mysql_error();
	    print "
		$tblstart
		  $tccell1>The $item[name] has been bought and equipped.<br>
		  ".redirect("{$GLOBALS['jul_views_path']}/shoph.php",'return to the shop',0)."
		$tblend
	    ";
	  }
	break;
	case 'sell':
	  $pitem=mysql_fetch_array(mysql_query("SELECT coins FROM items WHERE id=".$user['eq'.$cat]));
	  mysql_query("UPDATE users_rpg SET eq$cat=0,spent=spent-$pitem[coins]*0.6 WHERE uid=$loguserid") or print mysql_error();
	  print "
	    $tblstart
		$tccell1>The $item[name] has been unequipped and sold.<br>
		".redirect("{$GLOBALS['jul_views_path']}/shoph.php",'return to the shop',0)."
	    $tblend
	  ";
	break;
	default:
    }
  }
  print $footer;
  printtimedif($startingtime);
?>
