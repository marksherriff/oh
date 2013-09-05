<?php
	class DbUtil{
		public static $lu = "ohqueue";
		public static $lp = "backupdb";
		public static $host = "stardock.cs.virginia.edu";
		public static $schema = "ohqueue";
		
		public static function loginConnection() {
			$db = new mysqli(DbUtil::$host, DbUtil::$lu, DbUtil::$lp, DbUtil::$schema);
			if($db->connect_errno) {
				echo "fail";
				$db->close();
				exit();
			}
			return $db;
		}
	}

/*
	class DbUtil{
		public static $lu = "root";
		public static $lp = "root";
		public static $host = "localhost";
		public static $schema = "oh";

		public static function loginConnection() {
			$db = new mysqli(DbUtil::$host, DbUtil::$lu, DbUtil::$lp, DbUtil::$schema);
			if($db->connect_errno) {
				echo "fail";
				$db->close();
				exit();
			}
			return $db;
		}
	}
*/
?>
