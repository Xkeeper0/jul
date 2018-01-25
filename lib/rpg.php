<?php
	$stat=array('HP','MP','Atk','Def','Int','MDf','Dex','Lck','Spd');
	function basestat($p,$d,$stat){
		$p+=0;
		$e=calcexp($p,$d);
		$l=calclvl($e);
		if($l=='NAN') return 1;
		switch($stat){
			case 0: return (pow($p,0.26) * pow($d,0.08) * pow($l,1.41) * 0.95) + 20; //HP
			case 1: return (pow($p,0.22) * pow($d,0.12) * pow($l,1.41) * 0.32) + 10; //MP
			case 2: return (pow($p,0.18) * pow($d,0.04) * pow($l,1.37) * 0.29) +  2; //Str
			case 3: return (pow($p,0.16) * pow($d,0.07) * pow($l,1.37) * 0.28) +  2; //Atk
			case 4: return (pow($p,0.15) * pow($d,0.09) * pow($l,1.37) * 0.29) +  2; //Def
			case 5: return (pow($p,0.14) * pow($d,0.10) * pow($l,1.37) * 0.29) +  1; //Shl
			case 6: return (pow($p,0.17) * pow($d,0.05) * pow($l,1.37) * 0.29) +  2; //Lck
			case 7: return (pow($p,0.19) * pow($d,0.03) * pow($l,1.37) * 0.29) +  1; //Int
			case 8: return (pow($p,0.21) * pow($d,0.02) * pow($l,1.37) * 0.25) +  1; //Spd
		}
	}
	function getstats($u, $items=0, $class = 0){

		$stat=array('HP','MP','Atk','Def','Int','MDf','Dex','Lck','Spd');
		$p=$u['posts'];
		$d=(ctime()-$u['regdate'])/86400;
		for($i=0;$i<9;$i++) {
		$m[$i]=1;
		}
		for($i=1;$i<7;$i++){
			$item=$items[$u['eq'.$i]];
			for($k=0;$k<9;$k++){
				$is=$item['s'.$stat[$k]];
				if(substr($item['stype'],$k,1)=='m') $m[$k]*=$is/100;
				else $a[$k]+=$is;
			}
		}
		for($i=0;$i<9;$i++){
			$stats[$stat[$i]]=max(1,floor(basestat($p,$d,$i)*$m[$i])+$a[$i]);
		}
		// after calculating stats with items
		for($k=0;$k<9;$k++){
			if (isset($class[$stat[$k]])) {
				//$stats[$stat[$k]]	= ceil($stats[$stat[$k]] * ($class[$stat[$k]] != 0 ? $class[$stat[$k]] : -1));		// 0 can be 0, anything else will result in 1 because of max(1)
				$stats[$stat[$k]] = ceil($stats[$stat[$k]] * $class[$stat[$k]]);
			}
		}

		$stats['GP']=coins($p,$d)-$u['spent'];
		$stats['exp']=calcexp($p,$d);
		$stats['lvl']=calclvl($stats['exp']);
		return $stats;
	}
	function coins($p,$d){
		$p+=0;
		if($p<0 or $d<0) return 0;
		return floor(pow($p,1.3) * pow($d,0.4) + $p*10);
	}

/*
	case 0: return (pow($p,0.21) * pow($d,0.15) * pow($l,1.11) * 1.00) + 20; //HP
	case 1: return (pow($p,0.10) * pow($d,0.26) * pow($l,1.11) * 0.32) + 10; //MP
	case 2: return (pow($p,0.16) * pow($d,0.09) * pow($l,1.09) * 0.29) +  2; //Str
	case 3: return (pow($p,0.15) * pow($d,0.11) * pow($l,1.09) * 0.28) +  2; //Atk
	case 4: return (pow($p,0.10) * pow($d,0.17) * pow($l,1.09) * 0.29) +  2; //Def
	case 5: return (pow($p,0.09) * pow($d,0.18) * pow($l,1.09) * 0.29) +  1; //Shl
	case 6: return (pow($p,0.13) * pow($d,0.13) * pow($l,1.09) * 0.29) +  2; //Lck
	case 7: return (pow($p,0.07) * pow($d,0.20) * pow($l,1.09) * 0.29) +  1; //Int
	case 8: return (pow($p,0.19) * pow($d,0.07) * pow($l,1.09) * 0.25) +  1; //Spd
*/
