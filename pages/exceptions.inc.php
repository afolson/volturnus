<?php
error_reporting(0);

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

inittypeslist();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		exceptions_add();
	} else if ($_GET['action'] == "edit") {
		exceptions_edit();
	} else if ($_GET['action'] == "delete") {
		exceptions_delete();
	} else {
		exceptions_list();
	}
} else {
	exceptions_list();
}

function exceptions_add() {
	global $page;
	global $sql_conn;
	global $typesword2letter;
	
	$page['title'] = "Add Exception";
	$doform = false;
	$exception['type'] = "";
	$exception['mask'] = "";
	$exception['types'] = Array();
	
	if (isset($_POST['submit']) and isset($_POST['mask'])) {
		$sql = "SELECT * FROM exceptions WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A ".htmlspecialchars($_POST['type'])." exception with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
			$exception['type'] = $_POST['type'];
			$exception['mask'] = htmlspecialchars($_POST['mask']);
			if (isset($_POST['types'])) {
				$exception['types'] = $_POST['types'];
			} else {
				$exception['types'] = Array();
			}
			$doform = true;
		} else if (!isset($_POST['types']) and ($_POST['type'] == "tkl")) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select at least one 'affects' for a tkl exception!</font>\n";
			$exception['type'] = $_POST['type'];
			$exception['mask'] = htmlspecialchars($_POST['mask']);
			$exception['types'] = Array();
			$doform = true;
		} else {
			if ($_POST['type'] == "tkl") {
				$sql = "INSERT INTO exceptions (type, mask, types) VALUES ('".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['mask'])."', '".mysql_real_escape_string(serialize($_POST['types']))."');";
			} else {
				$sql = "INSERT INTO exceptions (type, mask) VALUES ('".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['mask'])."');";
			}
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=exceptions");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=exceptions&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Type:</td><td>";
		$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
		if ($exception['type'] == "ban") {
			$page['content'] .= "<option selected>ban</option>\n";
		} else {
			$page['content'] .= "<option>ban</option>\n";
		}
		if ($exception['type'] == "tkl") {
			$page['content'] .= "<option selected>tkl</option>\n";
		} else {
			$page['content'] .= "<option>tkl</option>\n";
		}
		if ($exception['type'] == "throttle") {
			$page['content'] .= "<option selected>throttle</option>\n";
		} else {
			$page['content'] .= "<option>throttle</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".$exception['mask']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Affects:</td><td>";
		$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";
		
		$col = 0;
		foreach (array_keys($typesword2letter) as $type) {
			$col++;
			$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"types[]\" value=\"".$type."\"" . (in_array($type,$exception['types'])?"checked":"") . " />".$type."</td>";
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
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Exception\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function exceptions_edit() {
	global $page;
	global $sql_conn;
	global $typesword2letter;
	
	$doform = false;
	
	$sql = "SELECT * FROM exceptions WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM exceptions WHERE mask = '".mysql_real_escape_string($_POST['mask'])."' AND type = '".mysql_real_escape_string($_POST['type'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A ".htmlspecialchars($_POST['type'])." exception with the mask ".htmlspecialchars($_POST['mask'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!isset($_POST['types']) and ($_POST['type'] == "tkl")) {
				$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select at least one 'affects' for a tkl exception!</font>\n";
				$doform = true;
			}
			if (!$doform) {
				if ($_POST['type'] == "tkl") {
					$sql = "UPDATE exceptions SET type = '".mysql_real_escape_string($_POST['type'])."', mask = '".mysql_real_escape_string($_POST['mask'])."', types = '".mysql_real_escape_string(serialize($_POST['types']))."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				} else {
					$sql = "UPDATE exceptions SET type = '".mysql_real_escape_string($_POST['type'])."', mask = '".mysql_real_escape_string($_POST['mask'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				}
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Exception - ".htmlspecialchars($row['mask']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=exceptions&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Type:</td><td>";
			$page['content'] .= "<select name=\"type\" style=\"WIDTH: 300px\">\n";
			if ($row['type'] == "ban") {
				$page['content'] .= "<option selected>ban</option>\n";
			} else {
				$page['content'] .= "<option>ban</option>\n";
			}
			if ($row['type'] == "tkl") {
				$page['content'] .= "<option selected>tkl</option>\n";
			} else {
				$page['content'] .= "<option>tkl</option>\n";
			}
			if ($row['type'] == "throttle") {
				$page['content'] .= "<option selected>throttle</option>\n";
			} else {
				$page['content'] .= "<option>throttle</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Mask:</td><td><input type=\"text\" name=\"mask\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['mask'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Affects:</td><td>";
			$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";
			
			$col = 0;
			foreach (array_keys($typesword2letter) as $type) {
				$col++;
				$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"types[]\" value=\"".$type."\"" . (in_array($type,($row['type']=="tkl"?unserialize($row['types']):Array()))?"checked":"") . " />".$type."</td>";
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
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Exception\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=exceptions");
			exit;
		}
	} else {
		header("Location: ./?p=exceptions");
		exit;
	}
}

function exceptions_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM exceptions WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM exceptions WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=exceptions");
			exit;
		}
		
		$page['title'] = "Delete Exception - ".htmlspecialchars($row['mask']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=exceptions&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete the ".htmlspecialchars($row['type'])." exception '".htmlspecialchars($row['mask'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=exceptions");
		exit;
	}
}

function exceptions_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM exceptions ORDER BY type, mask";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Exceptions";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Mask</th><th>Type</th><th>Affects</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=exceptions&action=add\">New Exception</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=exceptions&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/computer_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/computer.png\" alt=\"".htmlspecialchars($row['mask'])."\" title=\"".htmlspecialchars($row['mask'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=exceptions&action=edit&id=".$row['id']."\">".htmlspecialchars($row['mask'])."</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['type'])."</td>";
			if ($row['type'] == "tkl") {
				$page['content'] .= "<td>".typesword2letter(unserialize($row['types']))."</td>";
			} else {
				$page['content'] .= "<td>&nbsp;</td>";
			}
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=exceptions&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/computer_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=exceptions&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/computer_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}

function typesword2letter($types) {
	global $typesword2letter;
	$typesout = "";
	
	if (is_array($types)) {
		foreach (array_keys($typesword2letter) as $type) {
			if (in_array($type, $types)) {
				$typesout .= $typesword2letter[$type];
			}
		}
	}
		
	return $typesout;
}

function typesletter2word($types) {
	global $typesletter2word;
	$typesout = array();
	
	foreach (array_keys($typesletter2word) as $type) {
		if(strpos($types, $type) !== false) {
			$typesout[] = $typesletter2word[$type];
		}
	}
	
	return $typesout;
}

function inittypeslist() {
	global $typesword2letter;
	global $typesletter2word;
	
	$typesword2letter['gline'] = "G";
	$typesword2letter['gzline'] = "Z";
	$typesword2letter['qline'] = "q";
	$typesword2letter['gqline'] = "Q";
	$typesword2letter['shun'] = "S";
	
	foreach (array_keys($typesword2letter) as $type) {
		$typesletter2word[$typesword2letter[$type]] = $type;
	}
}
?>
