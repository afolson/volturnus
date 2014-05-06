<?php
require_once('Database.php');

class DCC {
	public function __construct() {
		$this->db = new Database();
	}

	public function listDCC() {
		$stmt = $this->db->query('SELECT * FROM `dcc` ORDER BY `type` DESC');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// print_r($results);
		return $results;
	}
}

?>
