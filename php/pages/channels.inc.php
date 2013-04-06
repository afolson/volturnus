<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		channels_add();
	} else if ($_GET['action'] == "edit") {
		channels_edit();
	} else if ($_GET['action'] == "delete") {
		channels_delete();
	} else {
		channels_list();
	}
} else {
	channels_list();
}

function channels_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add Channel";
	$doform = false;
	$channel['mask'] = "";
	$channel['type'] = "";
	$channel['reason'] = "";
	$channel['warn'] = "";
	$channel['redirect'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['mask'])) {
		$sql = "SELECT * FROM channels WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A channel with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
			$channel['mask'] = htmlspecialchars($_POST['mask']);
			$channel['type'] = $_POST['type'];
			$channel['reason'] = htmlspecialchars($_POST['reason']);
			$channel['warn'] = $_POST['warn'];
			$channel['redirect'] = htmlspecialchars($_POST['redirect']);
			$doform = true;
		} else {
			if ($_POST['type'] = "deny") {
				$sql = "INSERT INTO channels (mask ,type ,reason, warn, redirect) VALUES ('".mysql_real_escape_string($_POST['mask'])."', '".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['reason'])."', '".mysql_real_escape_string($_POST['warn'])."', '".mysql_real_escape_string($_POST['redirect'])."');";
			} else {
				$sql = "INSERT INTO channels (mask ,type) VALUES ('".mysql_real_escape_string($_POST['mask'])."', '".mysql_real_escape_string($_POST['type'])."');";
			}
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=channels");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=channels&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".$channel['mask']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Type:</td><td>";
		$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
		if ($channel['type'] == "allow") {
			$page['content'] .= "<option value=\"allow\" selected>Allow</option>\n";
		} else {
			$page['content'] .= "<option value=\"allow\">Allow</option>\n";
		}
		if ($channel['type'] == "deny") {
			$page['content'] .= "<option value=\"deny\" selected>Deny</option>\n";
		} else {
			$page['content'] .= "<option value=\"deny\">Deny</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".$channel['reason']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Warn:</td><td><input type=\"checkbox\" name=\"warn\" value=\"1\"".($oper['warn']?" checked":"")." /></td></tr>\n";
		$page['content'] .= "<tr><td>Redirect:</td><td><input type=\"text\" name=\"redirect\" style=\"WIDTH: 300px\" value=\"".$channel['redirect']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Channel\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function channels_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM channels WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM channels WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A channel with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				$sql = "UPDATE channels SET mask = '".mysql_real_escape_string($_POST['mask'])."', type = '".mysql_real_escape_string($_POST['type'])."', reason = '".mysql_real_escape_string($_POST['reason'])."', warn = '".mysql_real_escape_string($_POST['warn'])."', redirect = '".mysql_real_escape_string($_POST['redirect'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Channel - ".htmlspecialchars($row['mask']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=channels&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['mask'])."\" /></td></tr>\n";
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
			$page['content'] .= "<tr><td>Warn:</td><td><input type=\"checkbox\" name=\"warn\" value=\"1\"".($row['warn']?" checked":"")." /></td></tr>\n";
			$page['content'] .= "<tr><td>Redirect:</td><td><input type=\"text\" name=\"redirect\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['redirect'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Channel\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=channels");
			exit;
		}
	} else {
		header("Location: ./?p=channels");
		exit;
	}
}

function channels_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM channels WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM channels WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=channels");
			exit;
		}
		
		$page['title'] = "Delete Channel - ".htmlspecialchars($row['mask']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=channels&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['mask'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=channels");
		exit;
	}
}

function channels_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM channels ORDER BY type DESC, mask ASC";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Channels";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Mask</th><th>Type</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=channels&action=add\">New Channel</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=channels&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/comment_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/comment.png\" alt=\"".htmlspecialchars($row['mask'])."\" title=\"".htmlspecialchars($row['mask'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=channels&action=edit&id=".$row['id']."\">".htmlspecialchars($row['mask'])."</a></td>";
			$page['content'] .= "<td>";
			if ($row['type'] == "allow") {
				$page['content'] .= "<img border=\"0\" src=\"templates/images/famfamfam/tick.png\" alt=\"allow\" title=\"allow\" />";
			} else {
				$page['content'] .= "<img border=\"0\" src=\"templates/images/famfamfam/cross.png\" alt=\"deny\" title=\"deny\" />";
			}
			$page['content'] .= "</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=channels&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/comment_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=channels&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/comment_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}
?>