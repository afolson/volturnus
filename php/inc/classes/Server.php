<?php

require_once('Database.php');

class Server {
	public function __construct() {
		$this->db = new Database();
	}

	public function listServers() {
		$stmt = $this->db->query('SELECT `id`, `name`, `ip` FROM `servers`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>