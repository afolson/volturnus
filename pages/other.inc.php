<?php
error_reporting(0);

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		other_add();
	} else if ($_GET['action'] == "edit") {
		other_edit();
	} else if ($_GET['action'] == "delete") {
		other_delete();
	} else {
		other_list();
	}
} else {
	other_list();
}

function other_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add Other Config";
	$doform = false;
	$other['name'] = "";
	$other['config'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['name'])) {
		$sql = "SELECT * FROM other WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A config item with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
			$other['name'] = htmlspecialchars($_POST['name']);
			$other['config'] = htmlspecialchars($_POST['config']);
			$doform = true;
		} else {
			$sql = "INSERT INTO other (name ,config) VALUES ('".mysql_real_escape_string($_POST['name'])."', '".mysql_real_escape_string($_POST['config'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=other");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=other&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".$other['name']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Config:</td><td>\n";
		$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"8\" name=\"config\">".$other['config']."</textarea>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Other Config\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function other_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM other WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM other WHERE name = '".mysql_real_escape_string($_POST['name'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A config item with the name ".htmlspecialchars($_POST['name'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE other SET name = '".mysql_real_escape_string($_POST['name'])."', config = '".mysql_real_escape_string($_POST['config'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Other Config - ".htmlspecialchars($row['name']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=other&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['name'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Config:</td><td>\n";
			$page['content'] .= "<textarea style=\"WIDTH: 300px\" rows=\"8\" name=\"config\">".htmlspecialchars($row['config'])."</textarea>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Other Config\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=other");
			exit;
		}
	} else {
		header("Location: ./?p=other");
		exit;
	}
}

function other_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM other WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM other WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=other");
			exit;
		}
		
		$page['title'] = "Delete Other Config - ".htmlspecialchars($row['name']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=other&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['name'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=other");
		exit;
	}
}

function other_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM other";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Other Config";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Name</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=other&action=add\">New Item</a></td>";
	$page['content'] .= "<td><a href=\"./?p=other&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/pencil_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/pencil.png\" alt=\"".htmlspecialchars($row['name'])."\" title=\"".htmlspecialchars($row['name'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=other&action=edit&id=".$row['id']."\">".htmlspecialchars($row['name'])."</a></td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=other&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/pencil_go.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=other&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/pencil_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}
?>
