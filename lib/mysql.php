<?php

	if (!function_exists("mysql_connect")) {
		// probably php 7, load shim for it
		require_once("lib/mysql_compat.php");
	}

	class mysql {
		// a 'backport' of my 'static' class in not-as-static form
		// the statistics remain static so they're global just in case this gets used for >1 connection
		static $queries   = 0;
		static $cachehits = 0;
		static $rowsf     = 0;
		static $rowst     = 0;
		static $time      = 0;

		// Query debugging functions for admins
		static $connection_count = 0;
		static $debug_on   = false;
		static $debug_list = array();

		var $cache = array();
		var $connection = NULL;
		var $id = 0;

		public function connect($host,$user,$pass,$persist=false) {
			$start=microtime(true);
			$this->connection = (($persist) ? mysql_pconnect($host,$user,$pass) : mysql_connect($host,$user,$pass));
			$t = microtime(true)-$start;
			$this->id = ++self::$connection_count;
			$this->set_character_encoding("utf8mb4");

			if (self::$debug_on) {
				$b = self::getbacktrace();
				self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", "<i>".(($persist)?"Persistent c":"C")."onnection established to mySQL server ($host, $user, using password: ".(($pass!=="") ? "YES" : "NO").")</i>", sprintf("%01.6fs",$t));
			}

			self::$time += $t;
			return $this->connection;
		}

		public function selectdb($dbname)	{
			$start=microtime(true);
			$r = mysql_select_db($dbname, $this->connection);
			self::$time += microtime(true)-$start;
			return $r;
		}

		public function query($query, $usecache = false) {
			if ($usecache && array_key_exists($hash = md5($query), $this->cache)) {
				$start=microtime(true);
				++self::$cachehits;
				@mysql_data_seek($this->cache[$hash], 0);
				$t = microtime(true)-$start;
				if (self::$debug_on) {
					$b = self::getbacktrace();
					self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", "<font color=#00dd00>$query</font>", "<font color=#00dd00>".sprintf("%01.6fs",$t)."</font>");
				}
				return $this->cache[$hash];
			}

			$start=microtime(true);
			if($res = mysql_query($query, $this->connection)) {
				++self::$queries;
				if (!is_bool($res))
					self::$rowst += @mysql_num_rows($res);

				if ($usecache) {
					$this->cache[md5($query)] = &$res;
				}
			}
			else {
				ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();
				error_log($trace);
				trigger_error(mysql_error(), E_USER_ERROR);
			}

			$t = microtime(true)-$start;
			self::$time += $t;

			if (self::$debug_on) {
				$b = self::getbacktrace();
				$tx = ((!$err) ? $query : "<span style=\"color:#FF0000;border-bottom:1px dotted red;\" title=\"$err\">$query</span>");
				self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", $tx, sprintf("%01.6fs",$t));
			}

			return $res;
		}

		public function fetch($result, $flag = MYSQL_BOTH){
			$start=microtime(true);

			if($result && $res=mysql_fetch_array($result, $flag))
					++self::$rowsf;

			self::$time += microtime(true)-$start;
			return $res;
		}

		public function result($result,$row=0,$col=0){
			$start=microtime(true);

			if($result) {
				if (mysql_num_rows($result) < $row+1)
					$res = NULL;
				elseif ($res=@mysql_result($result,$row,$col))
					++self::$rowsf;
			}

			self::$time += microtime(true)-$start;
			return $res;
		}

		public function fetchq($query, $flag = MYSQL_BOTH, $cache = false){
			$res = $this->query($query, $cache);
			$res = $this->fetch($res, $flag);
			return $res;
		}

		public function resultq($query,$row=0,$col=0, $cache = false){
			$res = $this->query($query, $cache);
			$res = $this->result($res,$row,$col);
			return $res;
		}

		public function getmultiresults($query, $key, $wanted, $cache = false) {
			$q = $this->query($query, $cache);
			$ret = array();
			$tmp = array();

			while ($res = @$this->fetch($q, MYSQL_ASSOC))
				$tmp[$res[$key]][] = $res[$wanted];
			foreach ($tmp as $keys => $values)
				$ret[$keys] = implode(",", $values);
			return $ret;
		}

		public function getresultsbykey($query, $key, $wanted, $cache = false) {
			$q = $this->query($query, $cache);
			$ret = array();
			while ($res = @$this->fetch($q, MYSQL_ASSOC))
				$ret[$res[$key]] = $res[$wanted];
			return $ret;
		}

		public function getresults($query, $wanted, $cache = false) {
			$q = $this->query($query, $cache);
			$ret = array();
			while ($res = @$this->fetch($q, MYSQL_ASSOC))
				$ret[] = $res[$wanted];
			return $ret;
		}

		public function getarraybykey($query, $key, $cache = false) {
			$q = $this->query($query, $cache);
			$ret = array();
			while ($res = @$this->fetch($q, MYSQL_ASSOC))
				$ret[$res[$key]] = $res;
			return $ret;
		}

		public function getarray($query, $cache = false) {
			$q = $this->query($query, $cache);
			$ret = array();
			while ($res = @$this->fetch($q, MYSQL_ASSOC))
				$ret[] = $res;
			return $ret;
		}

		public function escape($s) {
			return mysql_real_escape_string($s);
		}


		public function set_character_encoding($s) {
			return mysql_set_charset($s, $this->connection);
		}

		//private function __construct() {}

		// Debugging shit for admins
		public static function debugprinter() {
			global $tccellh, $tccellc, $tccell1, $tccell2, $tblstart, $smallfont, $tblend;
			if (!self::$debug_on) return "";
			$out  = "";
			$out .= "<br>$tblstart<tr>$tccellh colspan=5><b>SQL Debug</b></td><tr>
				$tccellh width=20>&nbsp</td>
				$tccellh width=20>ID</td>
				$tccellh width=300>Function</td>
				$tccellh width=*>Query</td>
				$tccellh width=90>Time</td></tr>";
			foreach(self::$debug_list as $i => $d) {
				$altcell = "tccell" . (($i & 1)+1);
				$cell = $$altcell;
				if ($oldid && $oldid != $d[0])
					$out .= "<tr>$tccellc colspan=5><img src='{$GLOBALS['jul_base_dir']}/images/_.gif' height='4' width='1'></td></tr>";
				$oldid = $d[0];
				$out .= "<tr>
					$cell>$i</td>
					$cell>$d[0]</td>
					$cell>$d[1]$smallfont<br>$d[2]</font></td>
					$cell style='white-space: pre-wrap; text-align: left'>$d[3]</td>
					$cell>$d[4]</td></tr>";
			}
			$out .= "$tblend";
			return $out;
		}

		private static function getbacktrace() {
			$backtrace = debug_backtrace();
			for ($i = 1; isset($backtrace[$i]); ++$i) {
				if (substr($backtrace[$i]['file'], -9) !== "mysql.php") {
					if (!($backtrace[$i]['pfunc'] = $backtrace[$i+1]['function']))
						$backtrace[$i]['pfunc'] = "<i>(main)</i>";
					$backtrace[$i]['file'] = str_replace($_SERVER['DOCUMENT_ROOT'], "", $backtrace[$i]['file']);
					return $backtrace[$i];
				}
			}
			return $backtrace[$i-1];
		}
	}
?>
