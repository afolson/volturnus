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

	public function addChannel($mask, $type, $reason="", $warn="0", $redirect="") {
		$valid = validateChannel($mask, $type);
		if ($valid == true) {
			$stmt = $this->db->prepare("INSERT INTO `channels` (mask ,type ,reason, warn, redirect) VALUES (?, ?, ?, ?, ?);");
			$res = $stmt->execute(array($mask, $type, $reason, $warn, $redirect));
		}
		else {
			// TODO: Channel already exists
		}
	}

	private function validateChannel($mask, $type) {
		$stmt = $this->db->prepare('SELECT * FROM `channels` WHERE `mask` =? AND type =?');
		$res = $stmt->execute(array($mask, $type));
		// The mask already exists
		$result = ($res->fetchColumn() > 0) ? false : true;
		return $result;
	}
}

?>