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

	public function listOperFlags($id) {
		
	}
	
	public function addOper($username, $password, $passtype, $hosts, $class, $maxlogins, $swhois, $modes, $snomask, $flags) {
		validateOper($username);
		// Check to see that flags are valid

	}

	public function editOper($id, $username, $password, $passtype, $hosts, $class, $maxlogins, $swhois, $modes, $snomask, $flags) {
		validateOper($username);
		// Check to see that flags are valid
	}

	private function validateOper($name) {
		// Check to see if the oper's name already exists in the DB
		$stmt = $this->db->prepare('SELECT `username` FROM `opers` WHERE `username` = :name');
		$stmt->execute(array('name' => $name));
		$count = $stmt->fetchColumn();
		if ($count >= 1) {
			// The oper already exists. ERROR.
			return false;
		}
		else {
			// The oper doesn't exist.
			return true;
		}



		// $res = $DB->prepare('SELECT COUNT(*) FROM table');
		// $res->execute();
		// $num_rows = $res->fetchColumn();



		// $stmt = $this->db->prepare('SELECT * FROM `servers` WHERE `name` = :servername');
		// $stmt->execute(array('servername' => $servername));
		// $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// return $result;

		// SELECT * FROM opers WHERE username = '".mysql_real_escape_string($_POST['username'])."'
	}

}

?>
