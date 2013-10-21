<?php
error_reporting(0);

$flagsnew2old = Array();
initflagslist();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		opers_add();
	} else if ($_GET['action'] == "edit") {
		opers_edit();
	} else if ($_GET['action'] == "delete") {
		opers_delete();
	} else {
		opers_list();
	}
} else {
	opers_list();
}

function opers_add() {
	global $page;
	global $sql_conn;
	global $flagsnew2old;
	global $flaggroups;
	
	if (!$_SESSION['admin']) {
		header("Location: ./");
		exit;
	}
	
	$page['title'] = "Add Oper";
	$doform = false;
	$oper['username'] = "";
	$oper['password'] = "";
	$oper['passtype'] = "";
	$oper['hosts'] = "*@*";
	$oper['class'] = "clients";
	$oper['maxlogins'] = "0";
	$oper['swhois'] = "";
	$oper['modes'] = "";
	$oper['snomask'] = "";
	$oper['flags'] = array("local");
	
	if (isset($_POST['submit']) and isset($_POST['username'])) {
		$sql = "SELECT * FROM opers WHERE username = '".mysql_real_escape_string($_POST['username'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> An oper with the name ".htmlspecialchars($_POST['username'])." already exists!</font>\n";
			$oper['username'] = htmlspecialchars($_POST['username']);
			$oper['password'] = htmlspecialchars($_POST['password']);
			$oper['passtype'] = $_POST['passtype'];
			$oper['hosts'] = htmlspecialchars($_POST['hosts']);
			$oper['class'] = htmlspecialchars($_POST['class']);
			$oper['maxlogins'] = htmlspecialchars($_POST['maxlogins']);
			$oper['swhois'] = htmlspecialchars($_POST['swhois']);
			$oper['modes'] = htmlspecialchars($_POST['modes']);
			$oper['snomask'] = htmlspecialchars($_POST['snomask']);
			if (is_array($_POST['flags'])) {
				$oper['flags'] = $_POST['flags'];
			} else {
				$oper['flags'] = array();
			}
			$doform = true;
		} else if (!is_array($_POST['flags'])) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select one or more flags!</font>\n";
			$oper['username'] = htmlspecialchars($_POST['username']);
			$oper['password'] = htmlspecialchars($_POST['password']);
			$oper['passtype'] = $_POST['passtype'];
			$oper['hosts'] = htmlspecialchars($_POST['hosts']);
			$oper['class'] = htmlspecialchars($_POST['class']);
			$oper['maxlogins'] = htmlspecialchars($_POST['maxlogins']);
			$oper['swhois'] = htmlspecialchars($_POST['swhois']);
			$oper['modes'] = htmlspecialchars($_POST['modes']);
			$oper['snomask'] = htmlspecialchars($_POST['snomask']);
			$oper['flags'] = array();
			$doform = true;
		} else {
			$oper['username'] = $_POST['username'];
			$oper['password'] = $_POST['password'];
			$oper['passtype'] = $_POST['passtype'];
			$oper['hosts'] = serialize(explode("\r\n", $_POST['hosts']));
			$oper['class'] = $_POST['class'];
			$oper['maxlogins'] = $_POST['maxlogins'];
			$oper['swhois'] = $_POST['swhois'];
			$oper['modes'] = $_POST['modes'];
			$oper['snomask'] = $_POST['snomask'];
			$oper['flags'] = $_POST['flags'];
			foreach ($oper['flags'] as $mflag) {
				if (isset($flaggroups[$mflag])) {
					foreach ($flaggroups[$mflag] as $flag) {
						if (!in_array($flag, $oper['flags'])) {
							$oper['flags'][] = $flag;
						}
					}
				}
			}
			$oper['flags'] = serialize($oper['flags']);
			
			$sql = "INSERT INTO opers (username, password, passtype, hosts, class, flags, swhois, modes, snomask, maxlogins) VALUES ('".mysql_real_escape_string($oper['username'])."', '".mysql_real_escape_string($oper['password'])."', '".mysql_real_escape_string($oper['passtype'])."', '".mysql_real_escape_string($oper['hosts'])."', '".mysql_real_escape_string($oper['class'])."', '".mysql_real_escape_string($oper['flags'])."', '".mysql_real_escape_string($oper['swhois'])."', '".mysql_real_escape_string($oper['modes'])."', '".mysql_real_escape_string($oper['snomask'])."', '".mysql_real_escape_string($oper['maxlogins'])."')";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=opers");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=opers&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".$oper['username']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".$oper['password']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password Type:</td><td>";
		$page['content'] .= "<select name=\"passtype\" style=\"WIDTH: 300px\">\n";
		if ($oper['passtype'] == "") {
			$page['content'] .= "<option value=\"\" selected>none/plain</option>\n";
		} else {
			$page['content'] .= "<option value=\"\">none/plain</option>\n";
		}
		if ($oper['passtype'] == "crypt") {
			$page['content'] .= "<option value=\"crypt\" selected>crypt</option>\n";
		} else {
			$page['content'] .= "<option value=\"crypt\">crypt</option>\n";
		}
		if ($oper['passtype'] == "md5") {
			$page['content'] .= "<option value=\"md5\" selected>md5</option>\n";
		} else {
			$page['content'] .= "<option value=\"md5\">md5</option>\n";
		}
		if ($oper['passtype'] == "sha1") {
			$page['content'] .= "<option value=\"sha1\" selected>sha1</option>\n";
		} else {
			$page['content'] .= "<option value=\"sha1\">sha1</option>\n";
		}
		if ($oper['passtype'] == "ripemd160") {
			$page['content'] .= "<option value=\"ripemd160\" selected>ripemd160</option>\n";
		} else {
			$page['content'] .= "<option value=\"ripemd160\">ripemd160</option>\n";
		}
		if ($oper['passtype'] == "sslclientcert") {
			$page['content'] .= "<option value=\"sslclientcert\" selected>sslclientcert</option>\n";
		} else {
			$page['content'] .= "<option value=\"sslclientcert\">sslclientcert</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Hosts:</td><td>\n";
		$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"5\" name=\"hosts\">".$oper['hosts']."</textarea>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Class:</td><td><input type=\"text\" name=\"class\" style=\"WIDTH: 300px\" value=\"".$oper['class']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Max Logins:</td><td><input type=\"text\" name=\"maxlogins\" style=\"WIDTH: 300px\" value=\"".$oper['maxlogins']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>SWHOIS:</td><td><input type=\"text\" name=\"swhois\" style=\"WIDTH: 300px\" value=\"".$oper['swhois']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Modes:</td><td><input type=\"text\" name=\"modes\" style=\"WIDTH: 300px\" value=\"".$oper['modes']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Server Notice Mask:</td><td><input type=\"text\" name=\"snomask\" style=\"WIDTH: 300px\" value=\"".$oper['snomask']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Flags:</td><td>";
		$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";
		
		$col = 0;
		foreach (array_keys($flagsnew2old) as $flag) {
			$col++;
			$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"flags[]\" value=\"".$flag."\"" . (in_array($flag,$oper['flags'])?"checked":"") . " />".$flag."</td>";
			if ($col == 3) {
				$page['content'] .= "</tr><tr>";
				$col = 0;
			}
		}
		if (($col < 3) and ($col != 0)) {
			for ($i=0;$i<(3-$col);$i++) {
				$page['content'] .= "<td>&nbsp;</td>";
			}
		}

		$page['content'] .= "</table>";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Oper\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function opers_edit() {
	global $page;
	global $sql_conn;
	global $flagsnew2old;
	global $flaggroups;
	
	if (!$_SESSION['admin'] and isset($_GET['id'])) {
		if ($_SESSION['id'] != $_GET['id']) {
			header("Location: ./");
			exit;
		}
	}
	
	$doform = false;
	
	$sql = "SELECT * FROM opers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM opers WHERE username = '".mysql_real_escape_string($_POST['username'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> An oper with the name ".htmlspecialchars($_POST['username'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				if ($_SESSION['admin']) { $oper['username'] = $_POST['username']; }
				$oper['password'] = $_POST['password'];
				$oper['passtype'] = $_POST['passtype'];
				$oper['hosts'] = serialize(explode("\r\n", $_POST['hosts']));
				if ($_SESSION['admin']) { $oper['class'] = $_POST['class']; }
				if ($_SESSION['admin']) { $oper['maxlogins'] = $_POST['maxlogins']; }
				$oper['swhois'] = $_POST['swhois'];
				$oper['modes'] = $_POST['modes'];
				$oper['snomask'] = $_POST['snomask'];
				if ($_SESSION['admin']) {
					$oper['flags'] = $_POST['flags'];
					foreach ($oper['flags'] as $mflag) {
						if (isset($flaggroups[$mflag])) {
							foreach ($flaggroups[$mflag] as $flag) {
								if (!in_array($flag, $oper['flags'])) {
									$oper['flags'][] = $flag;
								}
							}
						}
					}
					$oper['flags'] = serialize($oper['flags']);
				}
				if ($_SESSION['admin']) { $oper['admin'] = ($_POST['admin']?"1":"0"); }
				if (($_SESSION['admin']) and ($_SESSION['id'] == $row['id'])) { $oper['admin'] = 1; };
				if ($_SESSION['admin']) {
					$sql = "UPDATE opers SET username = '".mysql_real_escape_string($oper['username'])."', password = '".mysql_real_escape_string($oper['password'])."', passtype = '".mysql_real_escape_string($oper['passtype'])."', hosts = '".mysql_real_escape_string($oper['hosts'])."', class = '".mysql_real_escape_string($oper['class'])."', maxlogins = '".mysql_real_escape_string($oper['maxlogins'])."', swhois = '".mysql_real_escape_string($oper['swhois'])."', modes = '".mysql_real_escape_string($oper['modes'])."', snomask = '".mysql_real_escape_string($oper['snomask'])."', flags = '".mysql_real_escape_string($oper['flags'])."', admin = '".mysql_real_escape_string($oper['admin'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				} else {
					$sql = "UPDATE opers SET password = '".mysql_real_escape_string($oper['password'])."', passtype = '".mysql_real_escape_string($oper['passtype'])."', hosts = '".mysql_real_escape_string($oper['hosts'])."', swhois = '".mysql_real_escape_string($oper['swhois'])."', modes = '".mysql_real_escape_string($oper['modes'])."', snomask = '".mysql_real_escape_string($oper['snomask'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				}
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$oper['username'] = $row['username'];
			$oper['password'] = $row['password'];
			$oper['passtype'] = $row['passtype'];
			$oper['hosts'] = implode("\r\n", unserialize($row['hosts']));
			$oper['class'] = $row['class'];
			$oper['maxlogins'] = $row['maxlogins'];
			$oper['swhois'] = $row['swhois'];
			$oper['modes'] = $row['modes'];
			$oper['snomask'] = $row['snomask'];
			$oper['flags'] = unserialize($row['flags']);
			foreach ($flaggroups as $flag) {
				if (!in_array($flag, $oper['flags'])) {
					$oper['flags'][] = $flag;
				}
			}
			$oper['flags'] = $oper['flags'];
			$oper['admin'] = $row['admin'];
			
			$page['title'] = "Edit Oper - ".htmlspecialchars($row['username']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=opers&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			if ($_SESSION['admin']) { 
				$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['username'])."\" /></td></tr>\n";
			} else {
				$page['content'] .= "<tr><td>Name:</td><td>".$oper['username']."</td></tr>\n";
			}
			$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['password'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Password Type:</td><td>";
			$page['content'] .= "<select name=\"passtype\" style=\"WIDTH: 300px\">\n";
			if ($oper['passtype'] == "") {
				$page['content'] .= "<option value=\"\" selected>none/plain</option>\n";
			} else {
				$page['content'] .= "<option value=\"\">none/plain</option>\n";
			}
			if ($oper['passtype'] == "crypt") {
				$page['content'] .= "<option value=\"crypt\" selected>crypt</option>\n";
			} else {
				$page['content'] .= "<option value=\"crypt\">crypt</option>\n";
			}
			if ($oper['passtype'] == "md5") {
				$page['content'] .= "<option value=\"md5\" selected>md5</option>\n";
			} else {
				$page['content'] .= "<option value=\"md5\">md5</option>\n";
			}
			if ($oper['passtype'] == "sha1") {
				$page['content'] .= "<option value=\"sha1\" selected>sha1</option>\n";
			} else {
				$page['content'] .= "<option value=\"sha1\">sha1</option>\n";
			}
			if ($oper['passtype'] == "ripemd160") {
				$page['content'] .= "<option value=\"ripemd160\" selected>ripemd160</option>\n";
			} else {
				$page['content'] .= "<option value=\"ripemd160\">ripemd160</option>\n";
			}
			if ($oper['passtype'] == "sslclientcert") {
				$page['content'] .= "<option value=\"sslclientcert\" selected>sslclientcert</option>\n";
			} else {
				$page['content'] .= "<option value=\"sslclientcert\">sslclientcert</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Hosts:</td><td>\n";
			$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"5\" name=\"hosts\">".htmlspecialchars($oper['hosts'])."</textarea>\n";
			$page['content'] .= "</td></tr>\n";
			if ($_SESSION['admin']) {
				$page['content'] .= "<tr><td>Class:</td><td><input type=\"text\" name=\"class\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['class'])."\" /></td></tr>\n";
				$page['content'] .= "<tr><td>Max Logins:</td><td><input type=\"text\" name=\"maxlogins\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['maxlogins'])."\" /></td></tr>\n";
			} else {
				$page['content'] .= "<tr><td>Class:</td><td>".$oper['class']."</td></tr>\n";
				$page['content'] .= "<tr><td>Max Logins:</td><td>".$oper['maxlogins']."</td></tr>\n";
			}
			$page['content'] .= "<tr><td>SWHOIS:</td><td><input type=\"text\" name=\"swhois\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['swhois'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Modes:</td><td><input type=\"text\" name=\"modes\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['modes'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Server Notice Mask:</td><td><input type=\"text\" name=\"snomask\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($oper['snomask'])."\" /></td></tr>\n";
			if ($_SESSION['admin']) {
				$page['content'] .= "<tr><td valign=\"top\">Flags:</td><td>";
				$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";
				
				$col = 0;
				foreach (array_keys($flagsnew2old) as $flag) {
					$col++;
					$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"flags[]\" value=\"".$flag."\"" . (in_array($flag,$oper['flags'])?"checked":"") . " />".$flag."</td>";
					if ($col == 3) {
						$page['content'] .= "</tr><tr>";
						$col = 0;
					}
				}
				if (($col < 3) and ($col != 0)) {
					for ($i=0;$i<(3-$col);$i++) {
						$page['content'] .= "<td>&nbsp;</td>";
					}
				}
		
				$page['content'] .= "</table>";
				if ($_SESSION['id'] != $_GET['id']) {
					$page['content'] .= "<tr><td>Config Admin:</td><td><input type=\"checkbox\" name=\"admin\" value=\"1\"".($oper['admin']?" checked":"")." /></td></tr>\n";
				}
			} else {
				$page['content'] .= "<tr><td>Flags:</td><td>".flagsnew2oldtitle($oper['flags'])."</td></tr>\n";
			}
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Oper\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=opers");
			exit;
		}
	} else {
		header("Location: ./?p=opers");
		exit;
	}
}

function opers_delete() {
	global $page;
	global $sql_conn;
	
	if ((!$_SESSION['admin']) or ($_SESSION['id'] == $_GET['id'])) {
		header("Location: ./");
		exit;
	}
	
	$sql = "SELECT * FROM opers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM opers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=opers");
			exit;
		}
		
		$page['title'] = "Delete Oper - ".htmlspecialchars($row['username']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=opers&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['username'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=opers");
		exit;
	}
}

function opers_list() {
	global $page;
	global $sql_conn;
	
	if (!$_SESSION['admin']) {
		header("Location: ./");
		exit;
	}
	
	$sql = "SELECT * FROM opers";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Opers";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Username</th><th>Flags</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=opers&action=add\">New Oper</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=opers&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/user_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			if ($row['admin']) {
				$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/user_gray.png\" alt=\"".htmlspecialchars($row['username'])."\" title=\"".htmlspecialchars($row['username'])."\" /></td>";
			} else {
				$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/user.png\" alt=\"".htmlspecialchars($row['username'])."\" title=\"".htmlspecialchars($row['username'])."\" /></td>";
			}
			$page['content'] .= "<td><a href=\"./?p=opers&action=edit&id=".$row['id']."\">".htmlspecialchars($row['username'])."</a></td>";
			$page['content'] .= "<td>".flagsnew2oldtitle(unserialize($row['flags']))."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=opers&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/user_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			if ($_SESSION['id'] != $row['id']) {
				$page['content'] .= "<a href=\"./?p=opers&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/user_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			}
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}

function flagsnew2oldtitle($flags) {
	global $flagsnew2old;
	$flagsout = "";
	
	if (is_array($flags)) {
		foreach (array_keys($flagsnew2old) as $flag) {
			if (in_array($flag, $flags)) {
				$flagsout .= "<span title=\"$flag\">" . $flagsnew2old[$flag] . "</span>";
			}
		}
	}
	
	return $flagsout;
}

function flagsnew2old($flags) {
	global $flagsnew2old;
	$flagsout = "";
	
	foreach (array_keys($flagsnew2old) as $flag) {
		if (in_array($flag, $flags)) {
			$flagsout .= $flagsnew2old[$flag];
		}
	}
	
	return $flagsout;
}

function flagsold2new($flags) {
	global $flagsold2new;
	$flagsout = array();
	
	foreach (array_keys($flagsold2new) as $flag) {
		if(strpos($flags, $flag) !== false) {
			$flagsout[] = $flagsold2new[$flag];
		}
	}
	
	return $flagsout;
}

function initflagslist() {
	global $flagsnew2old;
	global $flagsold2new;
	global $flaggroups;
	
	$flagsnew2old['local'] = "o";
	$flagsnew2old['global'] = "O";
	$flagsnew2old['coadmin'] = "C";
	$flagsnew2old['admin'] = "A";
	$flagsnew2old['services-admin'] = "a";
	$flagsnew2old['netadmin'] = "N";
	$flagsnew2old['helpop'] = "h";
	$flagsnew2old['can_rehash'] = "r";
	$flagsnew2old['can_die'] = "D";
	$flagsnew2old['can_restart'] = "R";
	$flagsnew2old['can_wallops'] = "w";
	$flagsnew2old['can_globops'] = "g";
	$flagsnew2old['can_localroute'] = "c";
	$flagsnew2old['can_globalroute'] = "L";
	$flagsnew2old['can_localkill'] = "k";
	$flagsnew2old['can_globalkill'] = "K";
	$flagsnew2old['can_kline'] = "b";
	$flagsnew2old['can_unkline'] = "B";
	$flagsnew2old['can_localnotice'] = "n";
	$flagsnew2old['can_globalnotice'] = "G";
	$flagsnew2old['can_zline'] = "z";
	$flagsnew2old['can_gkline'] = "t";
	$flagsnew2old['can_gzline'] = "Z";
	$flagsnew2old['get_umodew'] = "W";
	$flagsnew2old['get_host'] = "H";
	$flagsnew2old['can_override'] = "v";
	$flagsnew2old['can_setq'] = "q";
	$flagsnew2old['can_addline'] = "X";
	$flagsnew2old['can_dccdeny'] = "d";
	
	foreach (array_keys($flagsnew2old) as $flag) {
		$flagsold2new[$flagsnew2old[$flag]] = $flag;
	}
	
	$flaggroups['local'] = flagsold2new("rhgwckbBn");
	$flaggroups['global'] =  flagsold2new("rhgwckbBnLKG");
	$flaggroups['coadmin'] =  flagsold2new("rhgwckbBnLKGOd");
	$flaggroups['admin'] =  flagsold2new("rhgwckbBnLKGOd");
	$flaggroups['services-admin'] =  flagsold2new("rhgwckbBnLKGOdq");
	$flaggroups['netadmin'] =  flagsold2new("rhgwckbBnLKGOdqAa");
}
?>
