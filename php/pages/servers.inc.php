<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		servers_add();
	} else if ($_GET['action'] == "edit") {
		servers_edit();
	} else if ($_GET['action'] == "delete") {
		servers_delete();
	} else {
		servers_list();
	}
} else {
	servers_list();
}

function servers_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add Server";
	$doform = false;
	$server['name'] = "";
	$server['ip'] = "";
	$server['password'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['name'])) {
		$sql = "SELECT * FROM servers WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A server with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
			$server['name'] = htmlspecialchars($_POST['name']);
			$server['ip'] = htmlspecialchars($_POST['ip']);
			$server['password'] = htmlspecialchars($_POST['password']);
			$doform = true;
		} else {
			$sql = "INSERT INTO servers (name ,ip ,password) VALUES ('".mysql_real_escape_string($_POST['name'])."', '".mysql_real_escape_string($_POST['ip'])."', '".mysql_real_escape_string($_POST['password'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=servers");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=servers&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".$server['name']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>IP:</td><td><input type=\"text\" name=\"ip\" style=\"WIDTH: 300px\" value=\"".$server['ip']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".$server['password']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Server\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function servers_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM servers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM servers WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A server with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE servers SET name = '".mysql_real_escape_string($_POST['name'])."', ip = '".mysql_real_escape_string($_POST['ip'])."', password = '".mysql_real_escape_string($_POST['password'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Server - ".htmlspecialchars($row['name']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=servers&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['name'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>IP:</td><td><input type=\"text\" name=\"ip\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['ip'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Password:</td><td><input type=\"text\" name=\"password\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['password'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Server\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=servers");
			exit;
		}
	} else {
		header("Location: ./?p=servers");
		exit;
	}
}

function servers_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM servers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM servers WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=servers");
			exit;
		}
		
		$page['title'] = "Delete Server - ".htmlspecialchars($row['name']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=servers&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['name'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=servers");
		exit;
	}
}

function servers_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM servers";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Servers";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Server</th><th>IP</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=servers&action=add\">New Server</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=servers&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/server_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/server.png\" alt=\"".htmlspecialchars($row['name'])."\" title=\"".htmlspecialchars($row['name'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=servers&action=edit&id=".$row['id']."\">".htmlspecialchars($row['name'])."</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['ip'])."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=servers&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/server_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=servers&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/server_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}
?>