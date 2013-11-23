<?php

require_once('Database.php');

class Channel {
	public function __construct() {
		$this->db = new Database();
	}

	public function listChannels() {
		$stmt = $this->db->query('SELECT `id`, `type`, `mask` FROM `channels`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>