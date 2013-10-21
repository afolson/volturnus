<?php
error_reporting(0);

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		dcc_add();
	} else if ($_GET['action'] == "edit") {
		dcc_edit();
	} else if ($_GET['action'] == "delete") {
		dcc_delete();
	} else {
		dcc_list();
	}
} else {
	dcc_list();
}

function dcc_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add DCC";
	$doform = false;
	$dcc['type'] = "";
	$dcc['filename'] = "";
	$dcc['reason'] = "";
	$dcc['soft'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['filename'])) {
		$sql = "SELECT * FROM dcc WHERE filename = '".mysql_real_escape_string($_POST['filename'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A dcc ".htmlspecialchars($_POST['type'])." with the file name ".htmlspecialchars($_POST['filename'])." already exists!</font>\n";
			$dcc['type'] = htmlspecialchars($_POST['type']);
			$dcc['filename'] = htmlspecialchars($_POST['filename']);
			$dcc['reason'] = htmlspecialchars($_POST['reason']);
			$dcc['soft'] = htmlspecialchars($_POST['soft']);
			$doform = true;
		} else {
			$sql = "INSERT INTO dcc (type, filename, reason, soft) VALUES ('".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['filename'])."', '".mysql_real_escape_string($_POST['reason'])."', '".mysql_real_escape_string($_POST['soft'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=dcc");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=dcc&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Filename:</td><td><input type=\"text\" name=\"filename\" style=\"WIDTH: 300px\" value=\"".$dcc['filename']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Type:</td><td>";
		$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
		if ($dcc['type'] == "allow") {
			$page['content'] .= "<option value=\"allow\" selected>Allow</option>\n";
		} else {
			$page['content'] .= "<option value=\"allow\">Allow</option>\n";
		}
		if ($dcc['type'] == "deny") {
			$page['content'] .= "<option value=\"deny\" selected>Deny</option>\n";
		} else {
			$page['content'] .= "<option value=\"deny\">Deny</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".$dcc['reason']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Soft:</td><td><input type=\"checkbox\" name=\"soft\" value=\"1\"".($dcc['soft']?" checked":"")." /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add DCC\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function dcc_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM dcc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM dcc WHERE filename = '".mysql_real_escape_string($_POST['filename'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A dcc ".htmlspecialchars($_POST['type'])." with the file name ".htmlspecialchars($_POST['filename'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE dcc SET type = '".mysql_real_escape_string($_POST['type'])."', filename = '".mysql_real_escape_string($_POST['filename'])."', reason = '".mysql_real_escape_string($_POST['reason'])."', soft = '".mysql_real_escape_string($_POST['soft'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit DCC - ".htmlspecialchars($row['filename']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=dcc&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Filename:</td><td><input type=\"text\" name=\"filename\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['filename'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Type:</td><td>";
			$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
			if ($row['type'] == "allow") {
				$page['content'] .= "<option value=\"allow\" selected>Allow</option>\n";
			} else {
				$page['content'] .= "<option value=\"allow\">Allow</option>\n";
			}
			if ($row['type'] == "deny") {
				$page['content'] .= "<option value=\"deny\" selected>Deny</option>\n";
			} else {
				$page['content'] .= "<option value=\"deny\">Deny</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['reason'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Soft:</td><td><input type=\"checkbox\" name=\"soft\" value=\"1\"".($row['soft']?" checked":"")." /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update DCC\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=dcc");
			exit;
		}
	} else {
		header("Location: ./?p=dcc");
		exit;
	}
}

function dcc_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM dcc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM dcc WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=dcc");
			exit;
		}
		
		$page['title'] = "Delete DCC - ".htmlspecialchars($row['filename']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=dcc&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['filename'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=dcc");
		exit;
	}
}

function dcc_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM dcc ORDER BY type DESC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "DCC";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Filename</th><th>Type</th><th>Soft</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=dcc&action=add\">New DCC Action</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=dcc&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/page_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/page.png\" alt=\"".htmlspecialchars($row['filename'])."\" title=\"".htmlspecialchars($row['filename'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=dcc&action=edit&id=".$row['id']."\">".htmlspecialchars($row['filename'])."</a></td>";
			$page['content'] .= "<td>";
			if ($row['type'] == "allow") {
				$page['content'] .= "<img border=\"0\" src=\"templates/images/famfamfam/tick.png\" alt=\"allow\" title=\"allow\" />";
			} else {
				$page['content'] .= "<img border=\"0\" src=\"templates/images/famfamfam/cross.png\" alt=\"deny\" title=\"deny\" />";
			}
			$page['content'] .= "</td>";
			$page['content'] .= "<td>";
			if ($row['soft']) {
				$page['content'] .= "<img border=\"0\" src=\"templates/images/famfamfam/tick.png\" alt=\"yes\" title=\"yes\" />";
			} else {
				$page['content'] .= "&nbsp;";
			}
			$page['content'] .= "</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=dcc&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/page_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=dcc&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/page_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}

?>
