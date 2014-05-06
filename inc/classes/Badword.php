<?php
require_once('Database.php');

class Badword {
	public function __construct() {
		$this->db = new Database();
	}

	public function listWords() {
		$stmt = $this->db->query('SELECT * FROM `badwords` ORDER BY `types`, `replace`, `word`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// print_r($results);
		return $results;
	}
}

?>