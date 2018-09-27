<?php

	
	if ($_GET['action'] == "buy" && $_GET['id'] == 286) {
		return header("Location: shop.php?h"); // Merged
	}
	
	if (isset($_GET['h'])) {
		$hidden = 1;
		$h_q = "?h&";
	} else {
		$hidden = 0;
		$h_q = "?";
	}

  require 'lib/function.php';
  require 'lib/layout.php';
  require 'lib/rpg.php';
  print "$header<br>";
  if(!$log){
    print "
	$tblstart$tccell1>You must be logged in to access the Item Shop.<br>".
	redirect('index.php','return to the main page',0).
	$tblend;
  }else{
    $user=$sql->fetchp("SELECT posts,regdate,users_rpg.* FROM users,users_rpg WHERE id='{$loguserid}' AND uid=id");
    $p=$user['posts'];
    $d=(ctime()-$user['regdate'])/86400;
    $st=getstats($user);
    $GP=$st['GP'];
    switch($action){
	case '':
	  $shops=$sql->query('SELECT * FROM itemcateg ORDER BY corder');
	  $eq=$sql->fetchq("SELECT * FROM users_rpg WHERE uid='{$loguserid}'");
	  
	  $itemlist = array($user['eq1'], $user['eq2'], $user['eq3'], $user['eq4'], $user['eq5'], $user['eq6'], $user['eq7']);
	  $eqitems=$sql->query("SELECT * FROM items WHERE id IN (".implode(',', $itemlist).")");
	  while($item=$sql->fetch($eqitems)) $items[$item['id']]=$item;
	  while($shop=$sql->fetch($shops))
	    $shoplist.="
		<tr>
		$tccell1><a href=shop.php{$h_q}action=items&cat=$shop[id]#status>$shop[name]</a></td>
		$tccell2s>$shop[description]
		$tccell1s>".$items[$eq['eq'.$shop['id']]]['name']."
	    ";
	  print "
		<table width=100%><td valign=top width=120>
		 <img src=status.php?u=$loguserid>
		</td><td valign=top>
		".($hidden ? "
		<table class='table' cellspacing=0>
			<tr><td class='tbl tdbgh font center'><b>???</b></td></tr>
			<tr><td class='tbl tdbg1 font center'>This place hasn't been touched in ages...</td></tr>
		</table><br>" : "")."
		$tblstart
		 $tccellh colspan=3>".($hidden ? "Hidden Shop" : "Shop list")."<tr>
		 $tccellc>Shop</td>$tccellc>Description</td>$tccellc>Item equipped</td>
		 $shoplist
		$tblend
		</table>
	  ";
	break;
	case 'items':
	  $eq=$sql->fetchq("SELECT eq$cat AS e FROM users_rpg WHERE uid='{$loguserid}'");
	  $eqitem=$sql->fetchq("SELECT * FROM items WHERE id='{$eq['e']}'");
        print "
		<script>
		  function preview(user,item,cat,name){
		    document.getElementById('prev').src='status.php?u='+user+'&it='+item+'&ct='+cat+'&'+Math.random();
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
		  $tccell1><a href='shop.php$h_q'>Return to shop list</a>
		$tblend
		<a name=status>
		<table><td width=256>
		 <img src=status.php?u=$loguserid>
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
	  $items=$sql->queryp("SELECT * FROM items WHERE (cat=? OR cat=0) AND `hidden` = ? ORDER BY type,coins", array($cat, $hidden));
	  print "
		$tblstart
		$tccellh width=110 colspan=2>Commands</td>$tccellct width=1 rowspan=10000>&nbsp;</td>
		$tccellh colspan=1>Item</td>
		$atrlist
		$tccellh width=6%><img src=images/coin.gif></td>
		$tccellh width=5%><img src=images/coin2.gif></td>
	  ";
	  while($item=$sql->fetch($items)){
	    $preview="<a href=#status onclick='preview($loguserid,$item[id],$cat,\"". htmlentities($item['name'], ENT_QUOTES) ."\")'>Preview</a>";
	    if($item['id']==$eq['e'] && $item['id']){
		$comm="width=80 colspan=2><a href=shop.php{$h_q}action=sell&cat=$cat>Sell</a>";
	    }elseif($item['id'] && $item['coins']<=$GP && $item['gcoins'] <= $user['gcoins']){
		$comm="width=30><a href=shop.php{$h_q}action=buy&id=$item[id]>Buy</a></td>$tccell1 width=50>$preview";
	    }elseif(!$eq['e'] && !$item['id']){
		$comm="width=80 colspan=2>-";
	    }else{
		$comm="width=80 colspan=2>$preview";
	    }
	    if($item['id']==$eqitem['id']) $color=' class=equal';
	    elseif($item['coins']>$GP || $item['gcoins'] > $user['gcoins']) $color=' class=disabled';
	    else $color='';
	    $atrlist='';
	    for($i=0;$i<9;$i++){
		$st=$item["s$stat[$i]"];
		if(substr($item['stype'],$i,1)=='m'){
		  $st=vsprintf('x%1.2f',$st/100);
		  if($st==100) $st='&nbsp;';
		}else{
		  if($st>0) $st="+$st";
		  if(!$st) $st='&nbsp;';
		}
		$itst=$item["s$stat[$i]"];
		$eqst=$eqitem["s$stat[$i]"];
		if(!$color && substr($item['stype'],$i,1)==substr($eqitem['stype'],$i,1)){
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
		$tccell2r>". ($item['coins'] < 8388607 ? $item['coins'] : "tons") ."</td>
		$tccell2r>". ($item['gcoins'] < 8388607 ? $item['gcoins'] : "tons") ."</td>
	    ";
	  }
	  print $tblend;
	break;
	case 'buy':
	  $item=$sql->fetchp("SELECT * FROM items WHERE id=? AND hidden = ?", array($id, $hidden));
	  if($item && $item['coins']<=$GP && $item['gcoins'] <= $user['gcoins']){
	    $where = array('id' => $user['eq'.$item['cat']]);
	    $pitem=$sql->fetchp("SELECT coins FROM items WHERE id=:id", $where);
	    $whatever = $item['coins'] - $pitem['coins']*0.6;
		print "Debug output: Cost: ". $item['coins'] ." - Current item's sell value: ". ($pitem['coins'] * 0.6) ." - Amount to subtract: ". $whatever .". /debug";
		$values = array(
			'item'        => $id,
			'olditemcost' => $whatever,
			'gcoins'      => $item['gcoins'],
			'user'        => $loguserid,
		);
	    $sql->queryp("UPDATE users_rpg SET `eq{$item['cat']}` = :item, `spent` = spent + :olditemcost, `gcoins` = `gcoins` - :gcoins WHERE uid = :user", $values) or print $sql->error();
	    print "
		$tblstart
		  $tccell1>The $item[name] has been bought and equipped.<br>
		  ".redirect("shop.php$h_q",'return to the shop',0)."
		$tblend
	    ";
	  }
	break;
	case 'sell':
		$pitem=$sql->fetchp("SELECT coins FROM items WHERE id=? AND hidden = ?", array($user['eq'.$cat], $hidden));
		$values = array(
			'equipped'    => 0,
			'olditemcost' => $pitem['coins'],
			'user'        => $loguserid,
		);
	  $sql->queryp("UPDATE users_rpg SET eq$cat=:equipped,spent=spent-:olditemcost*0.6 WHERE uid=:user", $values) or print $sql->error();
	  print "
	    $tblstart
		$tccell1>The $item[name] has been unequipped and sold.<br>
		".redirect("shop.php$h_q",'return to the shop',0)."
	    $tblend
	  ";
	break;
	default:
    }
  }
  print $footer;
  printtimedif($startingtime);
?>