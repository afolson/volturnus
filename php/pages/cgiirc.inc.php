<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		cgiirc_add();
	} else if ($_GET['action'] == "edit") {
		cgiirc_edit();
	} else if ($_GET['action'] == "delete") {
		cgiirc_delete();
	} else {
		cgiirc_list();
	}
} else {
	cgiirc_list();
}

function cgiirc_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add CGIIRC";
	$doform = false;
	$cgiirc['name'] = "";
	$cgiirc['hostname'] = "";
	$cgiirc['username'] = "";
	$cgiirc['type'] = "";
	$cgiirc['password'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['name'])) {
		$sql = "SELECT * FROM cgiirc WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A server with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
			$cgiirc['name'] = htmlspecialchars($_POST['name']);
			$cgiirc['hostname'] = htmlspecialchars($_POST['hostname']);
			$cgiirc['username'] = htmlspecialchars($_POST['username']);
			$cgiirc['type'] = $_POST['type'];
			$cgiirc['password'] = htmlspecialchars($_POST['password']);
			$doform = true;
		} else {
			$sql = "INSERT INTO cgiirc (name, type ,hostname, username ,password) VALUES ('".mysql_real_escape_string($_POST['name'])."', '".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['hostname'])."', '".mysql_real_escape_string($_POST['username'])."', '".mysql_real_escape_string($_POST['password'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=cgiirc");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=cgiirc&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".$cgiirc['name']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Type:</td><td>";
		$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
		if ($cgiirc['type'] == "webirc") {
			$page['content'] .= "<option value=\"webirc\" selected>webirc</option>\n";
		} else {
			$page['content'] .= "<option value=\"webirc\">webirc</option>\n";
		}
		if ($cgiirc['type'] == "old") {
			$page['content'] .= "<option value=\"old\" selected>old</option>\n";
		} else {
			$page['content'] .= "<option value=\"old\">old</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Hostname:</td><td><input type=\"text\" name=\"hostname\" style=\"WIDTH: 300px\" value=\"".$cgiirc['hostname']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Username:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".$cgiirc['username']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".$cgiirc['password']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add CGIIRC\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function cgiirc_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM cgiirc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM cgiirc WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A server with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE cgiirc SET name = '".mysql_real_escape_string($_POST['name'])."', type = '".mysql_real_escape_string($_POST['type'])."', hostname = '".mysql_real_escape_string($_POST['hostname'])."', username = '".mysql_real_escape_string($_POST['username'])."', password = '".mysql_real_escape_string($_POST['password'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit CGIIRC - ".htmlspecialchars($row['name']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=cgiirc&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['name'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Type:</td><td>";
			$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
			if ($row['type'] == "webirc") {
				$page['content'] .= "<option value=\"webirc\" selected>webirc</option>\n";
			} else {
				$page['content'] .= "<option value=\"webirc\">webirc</option>\n";
			}
			if ($row['type'] == "old") {
				$page['content'] .= "<option value=\"old\" selected>old</option>\n";
			} else {
				$page['content'] .= "<option value=\"old\">old</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Hostname:</td><td><input type=\"text\" name=\"hostname\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['hostname'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Username:</td><td><input type=\"text\" name=\"username\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['username'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['password'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update CGIIRC\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=cgiirc");
			exit;
		}
	} else {
		header("Location: ./?p=cgiirc");
		exit;
	}
}

function cgiirc_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM cgiirc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM cgiirc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=cgiirc");
			exit;
		}
		
		$page['title'] = "Delete CGIIRC - ".htmlspecialchars($row['name']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=cgiirc&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['name'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=cgiirc");
		exit;
	}
}

function cgiirc_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM cgiirc";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "CGIIRC";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Name</th><th>Hostname</th><th>Type</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=cgiirc&action=add\">New CGIIRC</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=cgiirc&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/world_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/world.png\" alt=\"".htmlspecialchars($row['name'])."\" title=\"".htmlspecialchars($row['name'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=cgiirc&action=edit&id=".$row['id']."\">".htmlspecialchars($row['name'])."</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['hostname'])."</td>";
			$page['content'] .= "<td>".htmlspecialchars($row['type'])."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=cgiirc&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/world_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=cgiirc&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/world_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}
?>