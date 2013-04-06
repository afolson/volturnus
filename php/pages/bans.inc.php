<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		bans_add();
	} else if ($_GET['action'] == "edit") {
		bans_edit();
	} else if ($_GET['action'] == "delete") {
		bans_delete();
	} else {
		bans_list();
	}
} else {
	bans_list();
}

function bans_add() {
	global $page;
	global $sql_conn;
	
	$page['title'] = "Add Ban";
	$doform = false;
	$ban['mask'] = "";
	$ban['type'] = "";
	$ban['reason'] = "";
	$ban['action'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['mask'])) {
		$sql = "SELECT * FROM bans WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A ".htmlspecialchars($_POST['type'])." ban with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
			$ban['mask'] = htmlspecialchars($_POST['mask']);
			$ban['type'] = $_POST['type'];
			$ban['reason'] = htmlspecialchars($_POST['reason']);
			$ban['action'] = $_POST['action'];
			$doform = true;
		} else {
			$sql = "INSERT INTO bans (type ,mask ,reason, action) VALUES ('".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['mask'])."', '".mysql_real_escape_string($_POST['reason'])."', '".mysql_real_escape_string($_POST['action'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=bans");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=bans&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Type:</td><td>";
		$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
		if ($ban['type'] == "nick") {
			$page['content'] .= "<option value=\"nick\" selected>nick</option>\n";
		} else {
			$page['content'] .= "<option value=\"nick\">nick</option>\n";
		}
		if ($ban['type'] == "user") {
			$page['content'] .= "<option value=\"user\" selected>user</option>\n";
		} else {
			$page['content'] .= "<option value=\"user\">user</option>\n";
		}
		if ($ban['type'] == "ip") {
			$page['content'] .= "<option value=\"ip\" selected>ip</option>\n";
		} else {
			$page['content'] .= "<option value=\"ip\">ip</option>\n";
		}
		if ($ban['type'] == "realname") {
			$page['content'] .= "<option value=\"realname\" selected>realname</option>\n";
		} else {
			$page['content'] .= "<option value=\"realname\">realname</option>\n";
		}
		if ($ban['type'] == "version") {
			$page['content'] .= "<option value=\"version\" selected>version</option>\n";
		} else {
			$page['content'] .= "<option value=\"version\">version</option>\n";
		}
		if ($ban['type'] == "server") {
			$page['content'] .= "<option value=\"server\" selected>server</option>\n";
		} else {
			$page['content'] .= "<option value=\"server\">server</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".$ban['mask']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".$ban['reason']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Action:</td><td>";
		$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
		if ($ban['type'] == "kill") {
			$page['content'] .= "<option value=\"kill\" selected>kill</option>\n";
		} else {
			$page['content'] .= "<option value=\"kill\">kill</option>\n";
		}
		if ($ban['type'] == "tempshun") {
			$page['content'] .= "<option value=\"tempshun\" selected>tempshun</option>\n";
		} else {
			$page['content'] .= "<option value=\"tempshun\">tempshun</option>\n";
		}
		if ($ban['type'] == "shun") {
			$page['content'] .= "<option value=\"shun\" selected>shun</option>\n";
		} else {
			$page['content'] .= "<option value=\"shun\">shun</option>\n";
		}
		if ($ban['type'] == "kline") {
			$page['content'] .= "<option value=\"kline\" selected>kline</option>\n";
		} else {
			$page['content'] .= "<option value=\"kline\">kline</option>\n";
		}
		if ($ban['type'] == "zline") {
			$page['content'] .= "<option value=\"zline\" selected>zline</option>\n";
		} else {
			$page['content'] .= "<option value=\"zline\">zline</option>\n";
		}
		if ($ban['type'] == "gline") {
			$page['content'] .= "<option value=\"gline\" selected>gline</option>\n";
		} else {
			$page['content'] .= "<option value=\"gline\">gline</option>\n";
		}
		if ($ban['type'] == "gzline") {
			$page['content'] .= "<option value=\"gzline\" selected>gzline</option>\n";
		} else {
			$page['content'] .= "<option value=\"gzline\">gzline</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Ban\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function bans_edit() {
	global $page;
	global $sql_conn;
	
	$doform = false;
	
	$sql = "SELECT * FROM bans WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM bans WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A ".htmlspecialchars($_POST['type'])." ban with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!$doform) {
				if ($_POST['type'] == "version") {
					$sql = "UPDATE bans SET type = '".mysql_real_escape_string($_POST['type'])."', mask = '".mysql_real_escape_string($_POST['mask'])."', reason = '".mysql_real_escape_string($_POST['reason'])."', action = '".mysql_real_escape_string($_POST['action'])."'  WHERE id = ".mysql_real_escape_string($_GET['id']);
				} else {
					$sql = "UPDATE bans SET type = '".mysql_real_escape_string($_POST['type'])."', mask = '".mysql_real_escape_string($_POST['mask'])."', reason = '".mysql_real_escape_string($_POST['reason'])."'  WHERE id = ".mysql_real_escape_string($_GET['id']);
				}
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Ban - ".htmlspecialchars($row['mask']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=bans&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Type:</td><td>";
			$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
			if ($row['type'] == "nick") {
				$page['content'] .= "<option value=\"nick\" selected>nick</option>\n";
			} else {
				$page['content'] .= "<option value=\"nick\">nick</option>\n";
			}
			if ($row['type'] == "user") {
				$page['content'] .= "<option value=\"user\" selected>user</option>\n";
			} else {
				$page['content'] .= "<option value=\"user\">user</option>\n";
			}
			if ($row['type'] == "ip") {
				$page['content'] .= "<option value=\"ip\" selected>ip</option>\n";
			} else {
				$page['content'] .= "<option value=\"ip\">ip</option>\n";
			}
			if ($row['type'] == "realname") {
				$page['content'] .= "<option value=\"realname\" selected>realname</option>\n";
			} else {
				$page['content'] .= "<option value=\"realname\">realname</option>\n";
			}
			if ($row['type'] == "version") {
				$page['content'] .= "<option value=\"version\" selected>version</option>\n";
			} else {
				$page['content'] .= "<option value=\"version\">version</option>\n";
			}
			if ($row['type'] == "server") {
				$page['content'] .= "<option value=\"server\" selected>server</option>\n";
			} else {
				$page['content'] .= "<option value=\"server\">server</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['mask'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['reason'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Action:</td><td>";
			$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
			if ($row['action'] == "kill") {
				$page['content'] .= "<option value=\"kill\" selected>kill</option>\n";
			} else {
				$page['content'] .= "<option value=\"kill\">kill</option>\n";
			}
			if ($row['action'] == "tempshun") {
				$page['content'] .= "<option value=\"tempshun\" selected>tempshun</option>\n";
			} else {
				$page['content'] .= "<option value=\"tempshun\">tempshun</option>\n";
			}
			if ($row['action'] == "shun") {
				$page['content'] .= "<option value=\"shun\" selected>shun</option>\n";
			} else {
				$page['content'] .= "<option value=\"shun\">shun</option>\n";
			}
			if ($row['action'] == "kline") {
				$page['content'] .= "<option value=\"kline\" selected>kline</option>\n";
			} else {
				$page['content'] .= "<option value=\"kline\">kline</option>\n";
			}
			if ($row['action'] == "zline") {
				$page['content'] .= "<option value=\"zline\" selected>zline</option>\n";
			} else {
				$page['content'] .= "<option value=\"zline\">zline</option>\n";
			}
			if ($row['action'] == "gline") {
				$page['content'] .= "<option value=\"gline\" selected>gline</option>\n";
			} else {
				$page['content'] .= "<option value=\"gline\">gline</option>\n";
			}
			if ($row['action'] == "gzline") {
				$page['content'] .= "<option value=\"gzline\" selected>gzline</option>\n";
			} else {
				$page['content'] .= "<option value=\"gzline\">gzline</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Ban\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=bans");
			exit;
		}
	} else {
		header("Location: ./?p=bans");
		exit;
	}
}

function bans_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM bans WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM bans WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=bans");
			exit;
		}
		
		$page['title'] = "Delete Ban - ".htmlspecialchars($row['mask']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=bans&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete the ".htmlspecialchars($row['type'])." ban '".htmlspecialchars($row['mask'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=bans");
		exit;
	}
}

function bans_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM bans ORDER BY type";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Bans";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Mask</th><th>Type</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=bans&action=add\">New Ban</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=bans&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/lock_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/lock.png\" alt=\"".htmlspecialchars($row['mask'])."\" title=\"".htmlspecialchars($row['mask'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=bans&action=edit&id=".$row['id']."\">".htmlspecialchars($row['mask'])."</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['type'])."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=bans&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/lock_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=bans&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/lock_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}

?>