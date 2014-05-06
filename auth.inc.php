<?php

require_once('inc/classes/Database.php');

$db = new Database();

/* set messages*/
$auth_error_text['title'] = "Error";
$auth_error_text['user'] = "Invalid User";
$auth_error_text['password'] = "Invalid Password";
$auth_error_text['access'] = "Access Denied";
$auth_error_text['banned'] = "Banned";

/* set the name of the table that the user data is stored in. */
$auth_board['usertable'] = "opers";

/* set the auth realm */
$auth_realm = "Staff Access Only!";

function verify_pass($plain, $check, $type) {
	$valid = false;
	
	if (strtolower($type) == "crypt") {
		if (crypt($plain, $check) == $check) {
			$valid = true;
		}
	} else if ($type == "") {
		if ($plain == $check) {
			$valid = true;
		}
	} else {
		$salt = "";
		$result = "";
		preg_match("/^\\$([^\\$]+)\\$(.+)$/", $check, $matches);
		$salt = base64_decode($matches[1]);
		if (strtolower($type) == "md5") {
			$h1 = pack("H*", md5($plain));
			$h2 = pack("H*", md5($h1 . $salt));
		} else if (strtolower($type) == "sha1") {
			$h1 = pack("H*", sha1($plain));
			$h2 = pack("H*", sha1($h1 . $salt));
		} else if (strtolower($type) == "ripemd160") {
			$h1 = pack("H*", hash("ripemd160", $plain));
			$h2 = pack("H*", hash("ripemd160", $h1 . $salt));
		}
		$result = "$" . base64_encode($salt) . "$" . base64_encode($h2);
		if ($check == $result) {
			$valid = true;
		}
	}
	
	return $valid;
}

/* set SQL statement */
//$auth_sql = "SELECT * FROM ".$auth_board['usertable']." WHERE username = '".mysql_real_escape_string(stripslashes($_SERVER["PHP_AUTH_USER"]))."'";

/* execute SQL statement */
//$auth_result = mysql_query($auth_sql, $sql_conn) or die(mysql_error());

$username = stripslashes($_SERVER["PHP_AUTH_USER"]);
$stmt = $db->prepare("SELECT * FROM `opers` WHERE `username`=?");
$stmt->execute(array($username));
$auth_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$_SESSION['username'] = "";
$_SESSION['loggedin'] = false;

/* check for valid password */
if ( mysql_num_rows($auth_result) != 0 ) {
	$auth_urow = mysql_fetch_array($auth_result);
	if ( !verify_pass($_SERVER["PHP_AUTH_PW"], $auth_urow['password'], $auth_urow['passtype']) ) {
		$auth_error = "2";
	} else {
		$auth_error = "3";
		$auth_error = "0";
		$_SESSION['username'] = $auth_urow['username'];
		$_SESSION['id'] = $auth_urow['id'];
		$_SESSION['loggedin'] = true;
		if ($auth_urow['admin']) {
			$_SESSION['admin'] = true;
		} else {
			$_SESSION['admin'] = false;
		}
	}
} else {
	$auth_error = "1";
}

/* check if correct user and password supplied if not request it */
if  ( $auth_error != "0" )  {
	Header( "HTTP/1.0 401 Authorization Required");
	Header( "WWW-Authenticate: Basic realm=\"".$auth_realm."\"");
	if ( $auth_error == "2" ) {
		$page['title'] = $auth_error_text['title'];
		$page['content'] = $auth_error_text['password'];
	} else if ( $auth_error == "3" ) {
		$page['title'] = $auth_error_text['title'];
		$page['content'] = $auth_error_text['access'];
	} else if ( $auth_error == "4" ) {
		$page['title'] = $auth_error_text['title'];
		$page['content'] = $auth_error_text['banned'];
	} else {
		$page['title'] = $auth_error_text['title'];
		$page['content'] = $auth_error_text['user'];
	}
	$page['error'] = true;
}
?>
