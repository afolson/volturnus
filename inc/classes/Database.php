<?php
require_once("config.inc.php");
/* Look how cute! :D */
class Database extends PDO {
	public function __construct() {		
		try {
			parent::__construct(sprintf('mysql:host=%s;dbname=%s;charset=UTF8', DB_HOST, 
				DB_NAME), DB_USER, DB_PASSWORD, 
			array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (PDOException $e) {
			print "Hrm, this isn't good: " . $e->getMessage();
		}
	}
}
?>