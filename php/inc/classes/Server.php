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

	public function getServerByName($servername) {
		$stmt = $this->db->prepare('SELECT * FROM `servers` WHERE `name` = :servername');
		$stmt->execute(array('servername' => $servername));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}
?>