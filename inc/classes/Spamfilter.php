<?php
require_once('Database.php');

class Spamfilter {
	public function __construct() {
		$this->db = new Database();
	}

	public function listSpamfilters() {
		$stmt = $this->db->query('SELECT `id`, `regex`, `targets`, `action` FROM `spamfilters` ORDER BY `targets`, `action`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>
