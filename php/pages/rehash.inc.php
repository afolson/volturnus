<?php
error_reporting(0);
/* $Id:$ */

$irc['nick'] = "RehashBot";
$irc['user'] = "RehashBot";
$irc['name'] = "RehashBot";

$irc['operuser'] = "rehashbot";
$irc['operpass'] = "rehashbot";

$irc['server'] = "127.0.0.1";
$irc['port'] = "6667";
$irc['pass'] = "";

$irc['identify'] = "IDENTIFY SuperSecurePasswordInPlainText";

$page['title'] = "Rehashing Servers";


$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, $irc['server'], $irc['port']);

$irc['mynick'] = $irc['nick'];
$connected = false;
$donerehash = false;

if (!$socket) {
    $page['content'] = "$errstr ($errno)\n";
} else {
	while (1)
	{
		$line = socket_read($socket, 100000);
		if (strlen($line) == 0)
		{
			break;
		}
		$line = str_replace("\r", "\n", $line);
		$line = str_replace("\n\n", "\n", $line);
		$lines = explode("\n", $line);
		foreach ($lines as $line)
		{

			preg_match("/(?:[:@]([^\s]+) )?([^\s]+)(?: (?:([^:\s][^\s]*) ?)*)(?::(.*))?/i", str_replace("\r\n", "", $line), $matches);
			$msg = $matches;
		
			switch (strtolower($msg[2])) {
				case "ping":
					socket_write($socket, "PONG :".$msg[4]."\r\n");
					break;
				case "001":
					socket_write($socket, $irc['identify']."\r\n");
					socket_write($socket, "OPER ".$irc['operuser']." ".$irc['operpass']."\r\n");
					break;
				case "005":
					do_rehash($socket);
					break;
				case "433":
					$irc['mynick'] = $irc['nick'].rand(0,9).rand(0,9).rand(0,9);
					socket_write($socket, "NICK ".$irc['mynick']."\r\n");
					break;
			}
		
			if ( !$connected ) {
				if ($irc['pass'] <> "") {
					socket_write($socket, "PASS ".$irc['pass']."\r\n");
				}
				socket_write($socket, "NICK ".$irc['mynick']."\r\n");
				socket_write($socket, "USER ".$irc['user']." 0 * :".$irc['name']."\r\n");
				$connected = true;
			}
		}
	}
}

function do_rehash($socket) {
	global $page;
	global $sql_conn;
	global $donerehash;
	
	if (!$donerehash) {
		$donerehash = true;
		$sql = "SELECT * FROM servers";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		
		if (mysql_num_rows($result)) {
			$page['content'] .= "Rehashing:<br />\n";
			/* Rehash local server */
			socket_write($socket, "REHASH\r\n");
			while ($row = mysql_fetch_array($result)) {
				$page['content'] .= $row['name'] . "<br />\n";
				/* Rehash any others remotely */
				socket_write($socket, "REHASH ".$row['name']."\r\n");
			}
		} else {
			$page['content'] .= "No servers listed to rehash";
		}
		socket_write($socket, "QUIT :Finished!\r\n");
	}
}
?>
