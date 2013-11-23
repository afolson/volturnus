<?php

require_once('Database.php');

class Ban {
	public function __construct() {
		$this->db = new Database();
	}

	public function listBans() {
		$stmt = $this->db->query('SELECT `id`, `type`, `mask` FROM `bans`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>