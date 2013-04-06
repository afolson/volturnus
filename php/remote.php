<?php
error_reporting(0);
/* $Id:$ */

session_start();

header("Content-Type: text/plain;");

/* set messages*/
$auth_error_text['user'] = "Invalid User";
$auth_error_text['password'] = "Invalid Password";
$auth_error_text['access'] = "Access Denied From";
$auth_error_text['success'] = "Successfully Authenticated!";

/* set the name of the table that the user data is stored in. */
$auth_board['usertable'] = "servers";

include_once("config.inc.php");

$sql_conn = mysql_connect($sqldetails['server'], $sqldetails['user'], $sqldetails['password']);
mysql_select_db($sqldetails['database'], $sql_conn);

/* set the auth realm */
$auth_realm = "Server Access Only!";

/* set SQL statement */
$auth_sql = "SELECT * FROM ".$auth_board['usertable']." WHERE name = '".mysql_real_escape_string(stripslashes($_SERVER["PHP_AUTH_USER"]))."'";

/* execute SQL statement */
$auth_result = mysql_query($auth_sql, $sql_conn) or die(mysql_error());

$_SESSION['name'] = "";
$_SESSION['loggedin'] = false;

/* check for valid password */
if (mysql_num_rows($auth_result)) {
	$auth_urow = mysql_fetch_array($auth_result);
	if ( $_SERVER["PHP_AUTH_PW"] != $auth_urow['password'] ) {
		$auth_error = "2";
	} else {
		$auth_error = "3";
		if ( $_SERVER['REMOTE_ADDR'] == $auth_urow['ip'] ) {
			$auth_error = "0";
			$_SESSION['name'] = $auth_urow['name'];
			$_SESSION['id'] = $auth_urow['id'];
			$_SESSION['ip'] = $auth_urow['ip'];
			$_SESSION['loggedin'] = true;
		}
	}
} else {
	$auth_error = "1";
}

/* check if correct user and password supplied if not request it */
if  ( $auth_error != "0" )  {
	Header( "HTTP/1.0 401 Authorization Required");
	Header( "WWW-Authenticate: Basic realm=\"".$auth_realm."\"");
	print "/*\n\n";
	if ( $auth_error == "2" ) {
		print $auth_error_text['password'] . "\n";
	} else if ( $auth_error == "3" ) {
		print $auth_error_text['access'] . "\n";
	} else {
		print $auth_error_text['user'] . "\n";
	}
	print $_SERVER["PHP_AUTH_USER"] . "\n";
	print $_SERVER["PHP_AUTH_PW"] . "\n";
	$page['error'] = true;
	print "\n*/\n\n";
} else {
	print "/*\n\n";
	print "  " . $auth_error_text['success'] . "\n\n";
	print "  Server Name: " . $_SESSION['name'] . "\n";
	print "  Server IP:   " . $_SESSION['ip'] . "\n";
	print "\n*/\n\n";

	generate_config();
	
	print $page['content'];
}

mysql_close($sql_conn);

/* This generates the config, obviously */
function generate_config() {
	generate_opers();
	generate_channels();
	generate_bans();
	generate_exceptions();
	generate_dcc();
	generate_vhosts();
	generate_badwords();
	generate_spamfilters();
	generate_cgiirc();
	generate_other();
}

function generate_opers() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM opers";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* OPER Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "oper " . $row['username'] . " { ";
			if ($row['passtype']) {
				$page['content'] .= "password \"" . $row['password'] . "\" { " . $row['passtype'] . "; }; ";
			} else {
				$page['content'] .= "password \"" . $row['password'] . "\"; ";
			}
			$page['content'] .= "class \"" . $row['class'] . "\"; ";
			$page['content'] .= "from { ";
			foreach (unserialize($row['hosts']) as $host) {
				$page['content'] .= "userhost " . $host . "; ";
			}
			$page['content'] .= "}; ";
			$page['content'] .= "flags { ";
			foreach (unserialize($row['flags']) as $flag) {
				$page['content'] .= $flag . "; ";
			}
			$page['content'] .= "}; ";
			if ($row['maxlogins']) {
				$page['content'] .= "maxlogins " . $row['maxlogins'] . "; ";
			}
			if ($row['modes']) {
				$page['content'] .= "modes \"" . $row['modes'] . "\"; ";
			}
			if ($row['snomask']) {
				$page['content'] .= "snomask \"" . $row['snomask'] . "\"; ";
			}
			if ($row['swhois']) {
				$page['content'] .= "swhois \"" . $row['swhois'] . "\"; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_channels() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM channels ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* CHANNEL Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			if ($row['type'] == "deny") {
				$page['content'] .= "deny channel { ";
				$page['content'] .= "channel \"" . $row['mask'] . "\"; ";
				$page['content'] .= "reason \"" . $row['reason'] . "\"; ";
				$page['content'] .= "warn \"" . ($row['warn']?"on":"off") . "\"; ";
				if ($row['redirect']) {
					$page['content'] .= "redirect \"" . $row['redirect'] . "\"; ";
				}
				$page['content'] .= "};\n";
			} else {
				$page['content'] .= "allow channel { ";
				$page['content'] .= "channel \"".$row['mask']."\"; ";
				$page['content'] .= "};\n";
			}
		}
		$page['content'] .= "\n";
	}
}

function generate_bans() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM bans ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* BAN Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "ban " . $row['type'] . " { ";
			$page['content'] .= "mask \"" . $row['mask'] . "\"; ";
			$page['content'] .= "reason \"" . $row['reason'] . "\"; ";
			if (($row['type'] == "version") and ($row['action'])) {
				$page['content'] .= "reason " . $row['action'] . "; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_exceptions() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM exceptions ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* EXCEPT Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "except " . $row['type'] . " { ";
			$page['content'] .= "mask \"" . $row['mask'] . "\"; ";
			if ($row['type'] == "tkl") {
				$page['content'] .= "type { ";
				foreach (unserialize($row['types']) as $type) {
					$page['content'] .= $type . "; ";
				}
				$page['content'] .= "}; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_dcc() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM dcc ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* DCC Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= $row['type'] . " dcc { ";
			$page['content'] .= "filename \"" . $row['filename'] . "\"; ";
			if ($row['type'] == "deny") {
				$page['content'] .= "reason \"" . $row['reason'] . "\"; ";
			}
			$page['content'] .= "soft " . ($row['soft']?"yes":"no") . "; ";
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_vhosts() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM vhosts";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* VHOST Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "vhost { ";
			$page['content'] .= "login \"" . $row['username'] . "\"; ";
			if ($row['passtype']) {
				$page['content'] .= "password \"" . $row['password'] . "\" { " . $row['passtype'] . "; }; ";
			} else {
				$page['content'] .= "password \"" . $row['password'] . "\"; ";
			}
			$page['content'] .= "from { ";
			foreach (unserialize($row['hosts']) as $host) {
				$page['content'] .= "userhost " . $host . "; ";
			}
			$page['content'] .= "}; ";
			$page['content'] .= "vhost \"" . $row['vhost'] . "\"; ";
			if ($row['swhois']) {
				$page['content'] .= "swhois \"" . $row['swhois'] . "\"; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_badwords() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM badwords ORDER BY word";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* BADWORD Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			foreach (unserialize($row['types']) as $type) {
				$page['content'] .= "badword " . $type . " { ";
				$page['content'] .= "word \"" . $row['word'] . "\"; ";
				if ($row['action']) {
					$page['content'] .= "action \"" . $row['action'] . "\"; ";
					if (($row['action'] == "replace") and $row['replace']) {
						$page['content'] .= "replace \"" . $row['replace'] . "\"; ";
					}
				}
				$page['content'] .= "};\n";
			}
		}
		$page['content'] .= "\n";
	}
}

function generate_spamfilters() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM spamfilters";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* SPAMFILTER Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "spamfilter { ";
			$page['content'] .= "regex \"" . $row['regex'] . "\"; ";
			$page['content'] .= "target { ";
			foreach (unserialize($row['targets']) as $target) {
				$page['content'] .= $target . "; ";
			}
			$page['content'] .= "}; ";
			$page['content'] .= "action \"" . $row['action'] . "\"; ";
			$page['content'] .= "reason \"" . $row['reason'] . "\"; ";
			if ($row['time']) {
				$page['content'] .= "ban-time \"" . $row['time'] . "\"; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_cgiirc() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM cgiirc ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* CGIIRC Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "cgiirc { ";
			$page['content'] .= "type \"" . $row['type'] . "\"; ";
			$page['content'] .= "username \"" . $row['username'] . "\"; ";
			$page['content'] .= "hostname \"" . $row['hostname'] . "\"; ";
			if ($row['type'] == "webirc") {
				$page['content'] .= "password \"" . $row['password'] . "\"; ";
			}
			$page['content'] .= "};\n";
		}
		$page['content'] .= "\n";
	}
}

function generate_other() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM other";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$page['content'] .= "/* OTHER Blocks: */\n";
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= $row['config'] . "\n";
		}
		$page['content'] .= "\n";
	}
}

?>