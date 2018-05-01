<?php
	
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

		public $cache = array();
		public $connection = NULL;
		public $id = 0;
		
		public function connect($host,$user,$pass,$dbname,$persist=false) {
			$start=microtime(true);
			$dsn = "mysql:dbname=$dbname;host=$host;charset=utf8mb4";
			$opt = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
				PDO::ATTR_EMULATE_PREPARES   => false,
				PDO::ATTR_PERSISTENT         => $persist
			);
			try {
				$this->connection = new PDO($dsn, $user, $pass, $opt);
			}
			catch (PDOException $x) {
				return NULL;
			}
			$t = microtime(true)-$start;
			$this->id = ++self::$connection_count;

			if (self::$debug_on) {
				$b = self::getbacktrace();
				self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", "<i>".(($persist)?"Persistent c":"C")."onnection established to mySQL server ($host, $user, using password: ".(($pass!=="") ? "YES" : "NO").")</i>", sprintf("%01.6fs",$t));
			}

			self::$time += $t;
			return $this->connection;
		}
		
		public function query($query, $hash = false) {
			if ($hash && isset($this->cache[$hash])) {
				++self::$cachehits;
				$t = 0;
				if (self::$debug_on) {
					$b = self::getbacktrace();
					self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", "<font color=#00dd00>$query</font>", "<font color=#00dd00>".sprintf("%01.6fs",$t)."</font>");
				}
				return NULL;
			}

			$start=microtime(true);
			try {
				$res = $this->connection->query($query);
				++self::$queries;
				
				if (strtoupper(substr(ltrim($query), 0, 6)) == "SELECT")
					self::$rowst += $res->rowCount();
			}
			catch (PDOException $e) {
				// the huge SQL warning text sucks
				$err = str_replace("You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use", "SQL syntax error", $this->error());
				trigger_error("MySQL error: $err", E_USER_ERROR);
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
		
		public function prepare($query, $hash = NULL) {
			if ($hash && isset($this->cache[$hash])) {
				++self::$cachehits;
				$t = 0;
				if (self::$debug_on) {
					$b = self::getbacktrace();
					self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", "<font color=#00dd00>[P] $query</font>", "<font color=#00dd00>".sprintf("%01.6fs",$t)."</font>");
				}
				return NULL;
			}
			
			$start = microtime(true);
			$res = NULL;
			try {
				$res = $this->connection->prepare($query);
			}
			catch (PDOException $e) {
				// the huge SQL warning text sucks
				$err = str_replace("You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use", "SQL syntax error", $this->error());
				trigger_error("MySQL (prepare) error: $err", E_USER_ERROR);
			}
			
			$t = microtime(true) - $start;
			self::$time += $t;
			
			if (self::$debug_on) {
				$b = self::getbacktrace();
				$tx = ((!$err) ? $query : "<span style=\"color:#FF0000;border-bottom:1px dotted red;\" title=\"$err\">[P] $query</span>");
				self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", $tx, sprintf("%01.6fs",$t));
			}
			return $res;
		}
		
		public function execute($result, $vals = array()){
			if (!$result) {
				trigger_error("MySQL (execute) error: Called execute() on null \$result", E_USER_ERROR);
				return NULL;
			}
			$query = $result->queryString;
			$start = microtime(true);
			try {
				$res = $result->execute($vals);
				if (!is_numeric($result->errorInfo()[0])) // It's possible for this to fail silently (seriously)
					throw new PDOException($result->errorInfo()[0]); //trigger_error("MySQL (execute) error: ".$sql->error(), E_USER_ERROR);
				++self::$queries;
				if (strtoupper(substr(trim($query), 0, 6)) == "SELECT")
					self::$rowst += $result->rowCount();
			}
			catch (PDOException $e){
				trigger_error("MySQL (execute) error: ".$this->error(), E_USER_ERROR);
			}
			
			$t = microtime(true) - $start;
			self::$time += $t;
			
			if (self::$debug_on) {
				$b = self::getbacktrace();
				$tx = ((!$err) ? $query : "<span style=\"color:#FF0000;border-bottom:1px dotted red;\" title=\"$err\">[P] $query</span>");
				self::$debug_list[] = array($this->id, $b['pfunc'], "$b[file]:$b[line]", $tx, sprintf("%01.6fs",$t));
			}
			return $res;
		}

		public function fetch($result, $flag = PDO::FETCH_BOTH, $hash = NULL){
			$start=microtime(true);
			
			if ($hash && isset($this->cache[$hash])) {
				$res = $this->cache[$hash];
			} else if ($result != false && $res = $result->fetch($flag)) {
				++self::$rowsf;
				if ($hash) $this->cache[$hash] = $res;
			}

			self::$time += microtime(true)-$start;
			return $res;
		}

		public function fetchAll($result, $flag = PDO::FETCH_BOTH, $hash = NULL){
			$start = microtime(true);
			$res   = NULL;
			
			if ($hash && isset($this->cache[$hash])) {
				$res = $this->cache[$hash];
			} else if ($result != false && $res = $result->fetchAll($flag)) {
				++self::$rowsf;
				if ($hash) $this->cache[$hash] = $res;
			}
			
			self::$time += microtime(true) - $start;
			return $res;
		}
		
		public function result($result, $row = 0, $col = 0, $hash = NULL){
			$start=microtime(true);
			
			if ($row) {
				trigger_error("Deprecated: passed \$row > 0", E_USER_NOTICE);
			}
			
			if ($hash && isset($this->cache[$hash])) {
				$res = $this->cache[$hash];
			} else if ($result != false && $result->rowCount() > $row) {
				$res = $result->fetchColumn($col);
				++self::$rowsf;
				if ($hash) $this->cache[$hash] = $res;
			} else {
				$res = NULL;
			}
			
			self::$time += microtime(true)-$start;
			return $res;
		}
		
		public function queryp($query, $values = array()) {
			$q = $this->prepare($query);
			$result = $this->execute($q, $values);
			return $q;
		}

		public function fetchq($query, $flag = PDO::FETCH_BOTH, $cache = false){
			$hash = $cache ? self::get_query_hash($query) : NULL;
			$res = $this->query($query, $hash);
			$res = $this->fetch($res, $flag, $hash);
			return $res;
		}

		public function fetchp($query, $values = array(), $flag = PDO::FETCH_BOTH, $cache = false) {
			$hash = $cache ? self::get_query_hash($query) : NULL;
			$res = $this->prepare($query, array(), $hash);
			if ($hash === NULL && !$this->execute($res, $values)) 
				return false;
			return $this->fetch($res, $flag, $hash);
		}

		public function resultq($query,$row=0,$col=0, $cache = false){
			$hash = $cache ? self::get_query_hash($query) : NULL;
			$res = $this->query($query, $hash);
			$res = $this->result($res,$row,$col,$hash);
			return $res;
		}
		
		public function resultp($query, $values = array(), $row=0, $col=0, $cache = false){
			$hash = $cache ? self::get_query_hash($query) : NULL;
			$res = $this->prepare($query, $hash);
			if ($hash === NULL && !$this->execute($res, $values)) 
				return false;
			return $this->result($res, $row, $col, $hash);
		}
		
		public function getmultiresults($query, $key, $wanted, $cache = false) {
			$hash = $cache ? 'gmr'.self::get_query_hash($query) : NULL;
			
			$q = $this->query($query, $hash);
			if ($hash && isset($this->cache[$hash]))
				return $this->cache[$hash];
			
			$ret = array();
			$tmp = $this->fetchAll($q, PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
			foreach ($tmp as $keys => $values)
				$ret[$keys] = implode(",", $values);
			return $ret;
		}

		public function getresultsbykey($query, $key = '', $wanted = '', $cache = false) {
			$hash = $cache ? 'grbk'.self::get_query_hash($query) : NULL;
			$q = $this->query($query, $hash);
			return $this->fetchAll($q, PDO::FETCH_KEY_PAIR, $hash);
		}

		public function getresults($query, $wanted = '', $cache = false) {
			$hash = $cache ? 'gr'.self::get_query_hash($query) : NULL;
			$q = $this->query($query, $hash);
			return $this->fetchAll($q, PDO::FETCH_COLUMN, $hash);
		}

		public function getarraybykey($query, $key = '', $cache = false) {
			$hash = $cache ? 'gabk'.self::get_query_hash($query) : NULL;
			$q = $this->query($query, $hash);
			$ret = $this->fetchAll($q, PDO::FETCH_UNIQUE, $hash);
			// hack around this fetch mode not inserting the $key with the array values
			// (can be removed once the code stops relying on the non-index $key)
			foreach ($ret as $id => $val)
				$ret[$id][$key] = $val[$key];
			if ($hash) $this->cache[$hash] = $ret;
			return $ret;
		}

		public function getarray($query, $cache = false) {
			$hash = $cache ? 'ga'.self::get_query_hash($query) : NULL;
			$q = $this->query($query, $hash);
			return $this->fetchAll($q, PDO::FETCH_ASSOC, $hash);
		}

		public function escape($s) {
			return $this->connection->quote($s);
		}
		
		public function num_rows($res) {
			if ($res === NULL || is_bool($res)) return 0;
			return $res->rowCount();
		}
		
		public function insert_id() {
			return $this->connection->lastInsertId();
		}
		
		public function error() {
			$err = $this->connection->errorInfo();
			return ($err && $err[1]) ? "SQLSTATE[{$err[0]}]: {$err[2]}" : "";
		}
		
		public static function phs($arraySet) {
			$out 	= "";
			$fields = array_keys($arraySet);
			$i 		= 0;
			foreach ($fields as $field)
				$out .= ($i++ ? "," : "")."`$field`=:".str_replace("`","",$field);
			return $out;
		}
		private static function get_query_hash($query) {
			return md5($query);
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
					$out .= "<tr>$tccellc colspan=5><img src='images/_.gif' height='4' width='1'></td></tr>";
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
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			
			// Loop until we have found the real location of the query
			for ($i = 1; strpos($backtrace[$i]['file'], "mysql.php"); ++$i);
			
			// Check in what function it comes from
			$backtrace[$i]['pfunc'] = (isset($backtrace[$i+1]) ? $backtrace[$i+1]['function'] : "<i>(main)</i>");
			$backtrace[$i]['file']  = str_replace($_SERVER['DOCUMENT_ROOT'], "", $backtrace[$i]['file']);
			
			return $backtrace[$i];
		}
	}
?>
