<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		vhosts_add();
	} else if ($_GET['action'] == "edit") {
		vhosts_edit();
	} else if ($_GET['action'] == "delete") {
		vhosts_delete();
	} else {
		vhosts_list();
	}
} else {
	vhosts_list();
}

function vhosts_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add vHost";
	$doform = false;
	$vhost['username'] = "";
	$vhost['password'] = "";
	$vhost['passtype'] = "";
	$vhost['hosts'] = "*@*";
	$vhost['vhost'] = "";
	$vhost['swhois'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['username']) and isset($_POST['vhost'])) {
		$sql = "SELECT * FROM vhosts WHERE username = '".mysql_real_escape_string($_POST['username'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A vHost with the username ".htmlspecialchars($_POST['username'])." already exists!</font>\n";
			$vhost['username'] = htmlspecialchars($_POST['username']);
			$vhost['password'] = htmlspecialchars($_POST['password']);
			$vhost['passtype'] = $_POST['passtype'];
			$vhost['hosts'] = htmlspecialchars($_POST['hosts']);
			$vhost['vhost'] = htmlspecialchars($_POST['vhost']);
			$vhost['swhois'] = htmlspecialchars($_POST['swhois']);
			$doform = true;
		} else {
			$sql = "INSERT INTO vhosts (username, password, passtype, hosts, vhost, swhois) VALUES ('".mysql_real_escape_string($_POST['username'])."', '".mysql_real_escape_string($_POST['password'])."', '".mysql_real_escape_string($_POST['passtype'])."', '".mysql_real_escape_string(serialize(explode("\r\n", $_POST['hosts'])))."', '".mysql_real_escape_string($_POST['vhost'])."', '".mysql_real_escape_string($_POST['swhois'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=vhosts");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=vhosts&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Username:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".$vhost['username']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".$vhost['password']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password Type:</td><td>";
		$page['content'] .= "<select name=\"passtype\" style=\"WIDTH: 300px\">\n";
		if ($vhost['passtype'] == "") {
			$page['content'] .= "<option value=\"\" selected>none/plain</option>\n";
		} else {
			$page['content'] .= "<option value=\"\">none/plain</option>\n";
		}
		if ($vhost['passtype'] == "crypt") {
			$page['content'] .= "<option value=\"crypt\" selected>crypt</option>\n";
		} else {
			$page['content'] .= "<option value=\"crypt\">crypt</option>\n";
		}
		if ($vhost['passtype'] == "md5") {
			$page['content'] .= "<option value=\"md5\" selected>md5</option>\n";
		} else {
			$page['content'] .= "<option value=\"md5\">md5</option>\n";
		}
		if ($vhost['passtype'] == "sha1") {
			$page['content'] .= "<option value=\"sha1\" selected>sha1</option>\n";
		} else {
			$page['content'] .= "<option value=\"sha1\">sha1</option>\n";
		}
		if ($vhost['passtype'] == "ripemd160") {
			$page['content'] .= "<option value=\"ripemd160\" selected>ripemd160</option>\n";
		} else {
			$page['content'] .= "<option value=\"ripemd160\">ripemd160</option>\n";
		}
		if ($vhost['passtype'] == "sslclientcert") {
			$page['content'] .= "<option value=\"sslclientcert\" selected>sslclientcert</option>\n";
		} else {
			$page['content'] .= "<option value=\"sslclientcert\">sslclientcert</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Hosts:</td><td>\n";
		$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"5\" name=\"hosts\">".$vhost['hosts']."</textarea>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>vHost:</td><td><input type=\"text\" name=\"vhost\" style=\"WIDTH: 300px\" value=\"".$vhost['vhost']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>SWHOIS:</td><td><input type=\"text\" name=\"swhois\" style=\"WIDTH: 300px\" value=\"".$vhost['swhois']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add vHost\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function vhosts_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM vhosts WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM vhosts WHERE username = '".mysql_real_escape_string($_POST['username'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A vHost with the username ".htmlspecialchars($_POST['username'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE vhosts SET username = '".mysql_real_escape_string($_POST['username'])."', password = '".mysql_real_escape_string($_POST['password'])."', passtype = '".mysql_real_escape_string($_POST['passtype'])."', hosts = '".mysql_real_escape_string(serialize(explode("\r\n", $_POST['hosts'])))."', vhost = '".mysql_real_escape_string($_POST['vhost'])."', swhois = '".mysql_real_escape_string($_POST['swhois'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit vHost - ".htmlspecialchars($row['username']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=vhosts&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Username:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['username'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['password'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Password Type:</td><td>";
			$page['content'] .= "<select name=\"passtype\" style=\"WIDTH: 300px\">\n";
			if ($row['passtype'] == "") {
				$page['content'] .= "<option value=\"\" selected>none/plain</option>\n";
			} else {
				$page['content'] .= "<option value=\"\">none/plain</option>\n";
			}
			if ($row['passtype'] == "crypt") {
				$page['content'] .= "<option value=\"crypt\" selected>crypt</option>\n";
			} else {
				$page['content'] .= "<option value=\"crypt\">crypt</option>\n";
			}
			if ($row['passtype'] == "md5") {
				$page['content'] .= "<option value=\"md5\" selected>md5</option>\n";
			} else {
				$page['content'] .= "<option value=\"md5\">md5</option>\n";
			}
			if ($row['passtype'] == "sha1") {
				$page['content'] .= "<option value=\"sha1\" selected>sha1</option>\n";
			} else {
				$page['content'] .= "<option value=\"sha1\">sha1</option>\n";
			}
			if ($row['passtype'] == "ripemd160") {
				$page['content'] .= "<option value=\"ripemd160\" selected>ripemd160</option>\n";
			} else {
				$page['content'] .= "<option value=\"ripemd160\">ripemd160</option>\n";
			}
			if ($row['passtype'] == "sslclientcert") {
				$page['content'] .= "<option value=\"sslclientcert\" selected>sslclientcert</option>\n";
			} else {
				$page['content'] .= "<option value=\"sslclientcert\">sslclientcert</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Hosts:</td><td>\n";
			$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"5\" name=\"hosts\">".htmlspecialchars(implode("\r\n", unserialize($row['hosts'])))."</textarea>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>vHost:</td><td><input type=\"text\" name=\"vhost\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['vhost'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>SWHOIS:</td><td><input type=\"text\" name=\"swhois\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['swhois'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update vHost\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=vhosts");
			exit;
		}
	} else {
		header("Location: ./?p=vhosts");
		exit;
	}
}

function vhosts_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM vhosts WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM vhosts WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=vhosts");
			exit;
		}
		
		$page['title'] = "Delete vHost - ".htmlspecialchars($row['username']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=vhosts&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['username'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=vhosts");
		exit;
	}
}

function vhosts_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM vhosts";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "vHosts";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>User</th><th>vHost</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=vhosts&action=add\">New vHost</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=vhosts&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/monitor_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/monitor.png\" alt=\"".htmlspecialchars($row['username'])."\" title=\"".htmlspecialchars($row['username'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=vhosts&action=edit&id=".$row['id']."\">".htmlspecialchars($row['username'])."</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['vhost'])."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=vhosts&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/monitor_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=vhosts&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/monitor_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}
?>