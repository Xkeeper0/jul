<?php
	class mysql{
		var $queries=0;
		var $rowsf=0;
		var $rowst=0;
		var $time=0;
		function connect($host,$user,$pass) {return mysql_connect($host,$user,$pass);}
		function selectdb($dbname)          {return mysql_select_db($dbname);}

		function query($query){
			$start=microtime(true);
			if($res=mysql_query($query)){
				$this->queries++;
				$this->rowst+=@mysql_num_rows($res);
			} else {
				trigger_error("MySQL error: ". mysql_error(), E_USER_WARNING);
			}

			$this->time+=microtime(true)-$start;
			return $res;
		}

		function fetch($result){
			$start=microtime(true);

			if($result && $res=mysql_fetch_array($result, MYSQL_ASSOC))
				$this->rowsf++;

			$this->time+=microtime(true)-$start;
			return $res;
		}

		function result($result,$row=0,$col=0){
			$start=microtime(true);

			if($result && $res=@mysql_result($result,$row,$col))
			  $this->rowsf++;

			$this->time+=microtime(true)-$start;
			return $res;
		}

		function fetchq($query,$row=0,$col=0){
			$res=$this->query($query);
			$res=$this->fetch($res);
			return $res;
		}

		function resultq($query,$row=0,$col=0){
			$res=$this->query($query);
			$res=$this->result($res,$row,$col);
			return $res;
		}
	}
?>