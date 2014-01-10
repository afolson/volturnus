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

	public function listFlags() {
		$stmt = $this->db->query('SELECT * FROM  `flags` ORDER BY `id` ASC');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function getOperByName($oper) {
		$stmt = $this->db->prepare('SELECT * FROM  `opers` WHERE username = ?');
		$result = $stmt->execute(array($oper));
	}

}

?>
