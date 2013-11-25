<?php
require_once('Database.php');

class Vhost {
	public function __construct() {
		$this->db = new Database();
	}

	public function listVhosts() {
		$stmt = $this->db->query('SELECT `id`, `username`, `vhost` FROM `vhosts`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>