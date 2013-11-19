<?php

require_once('Database.php');

class Oper {
	public function __construct() {
		$this->db = new Database();
	}

	public function listOpers() {
		$stmt = $this->db->query('SELECT `id`, `username`, `flags` FROM `opers`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>


