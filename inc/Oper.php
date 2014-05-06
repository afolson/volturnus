<?php
class Oper {

	static public function listOpers() {
		$db = new PDO('mysql:host=localhost;dbname=volturnus;charset=UTF8', 'root', '7@Dp0l3!', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

		$stmt = $db->query('SELECT `id`, `username`, `flags` FROM `opers`');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
}

?>


