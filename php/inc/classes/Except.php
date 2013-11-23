<?php

require_once('Database.php');

class Except {
	public function __construct() {
		$this->db = new Database();
	}

	public function listExceptions() {
		$stmt = $this->db->query('SELECT * FROM `exceptions` ORDER BY `type`, `mask`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>