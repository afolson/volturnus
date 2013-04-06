<?php
error_reporting(0);
/* $Id:$ */

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

inittypeslist();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		badwords_add();
	} else if ($_GET['action'] == "edit") {
		badwords_edit();
	} else if ($_GET['action'] == "delete") {
		badwords_delete();
	} else {
		badwords_list();
	}
} else {
	badwords_list();
}

function badwords_add() {
	global $page;
	global $sql_conn;
	global $typesword2letter;
	
	$page['title'] = "Add Badword";
	$doform = false;
	$badword['word'] = "";
	$badword['types'] = Array();
	$badword['replace'] = "";
	$badword['action'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['word'])) {
		$sql = "SELECT * FROM badwords WHERE word = '".mysql_real_escape_string($_POST['word'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> The badword ".htmlspecialchars($_POST['word'])." already exists!</font>\n";
			$badword['word'] = htmlspecialchars($_POST['word']);
			if (!isset($_POST['types'])) {
				$badword['types'] = Array();
			} else {
				$badword['types'] = $_POST['types'];
			}
			$badword['replace'] = htmlspecialchars($_POST['replace']);
			$badword['action'] = htmlspecialchars($_POST['action']);
			$doform = true;
		} else if (!isset($_POST['types'])) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select at least one type!</font>\n";
			$badword['word'] = htmlspecialchars($_POST['word']);
			$badword['types'] = Array();
			$badword['replace'] = htmlspecialchars($_POST['replace']);
			$badword['action'] = htmlspecialchars($_POST['action']);
			$doform = true;
		} else {
			$badword['word'] = htmlspecialchars($_POST['word']);
			$badword['types'] = $_POST['types'];
			$badword['replace'] = htmlspecialchars($_POST['replace']);
			$badword['action'] = htmlspecialchars($_POST['action']);
			
			if (in_array("channel", $badword['types']) and in_array("message", $badword['types']) and in_array("quit", $badword['types'])) {
				$badword['types'] = Array("all");
			}
			
			$sql = "INSERT INTO badwords (word, types, `replace`, action) VALUES ('".mysql_real_escape_string($badword['word'])."', '".mysql_real_escape_string(serialize($badword['types']))."', '".mysql_real_escape_string($badword['replace'])."', '".mysql_real_escape_string($badword['action'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=badwords");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=badwords&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Word:</td><td><input type=\"text\" name=\"word\" style=\"WIDTH: 300px\" value=\"".$badword['word']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Types:</td><td>";
		$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";

		$col = 0;
		foreach (array_keys($typesword2letter) as $type) {
			if ($type != "all") {
				$col++;
				$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"types[]\" value=\"".$type."\"" . (in_array($type,$badword['types'])?"checked":"") . " />".$type."</td>";
				if ($col == 3) {
					$page['content'] .= "</tr><tr>";
					$col = 0;
				}
			}
		}
		if (($col < 3) and ($col != 0)) {
			for ($i=0;$i<(3-$col);$i++) {
				$page['content'] .= "<td>&nbsp;</td>";
			}
		}
		
		$page['content'] .= "</table>";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Replace:</td><td><input type=\"text\" name=\"replace\" style=\"WIDTH: 300px\" value=\"".$badword['replace']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Action:</td><td>";
		$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
		if ($badword['action'] == "replace") {
			$page['content'] .= "<option selected>replace</option>\n";
		} else {
			$page['content'] .= "<option>replace</option>\n";
		}
		if ($badword['action'] == "block") {
			$page['content'] .= "<option selected>block</option>\n";
		} else {
			$page['content'] .= "<option>block</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Badword\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function badwords_edit() {
	global $page;
	global $sql_conn;
	global $typesword2letter;
	
	$doform = false;
	
	$sql = "SELECT * FROM badwords WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM badwords WHERE word = '".mysql_real_escape_string($_POST['word'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> The badword ".htmlspecialchars($_POST['word'])." already exists!</font>\n";
					$doform = true;
				}
			}
			if (!isset($_POST['types'])) {
				$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select at least one type!</font>\n";
				$doform = true;
			}
			if (!$doform) {
				$badword['word'] = htmlspecialchars($_POST['word']);
				$badword['types'] = $_POST['types'];
				$badword['replace'] = htmlspecialchars($_POST['replace']);
				$badword['action'] = htmlspecialchars($_POST['action']);
				
				if (in_array("channel", $badword['types']) and in_array("message", $badword['types']) and in_array("quit", $badword['types'])) {
					$badword['types'] = Array("all");
				}
				$sql = "UPDATE badwords SET word = '".mysql_real_escape_string($badword['word'])."', types = '".mysql_real_escape_string(serialize($badword['types']))."', `replace` = '".mysql_real_escape_string($badword['replace'])."', action = '".mysql_real_escape_string($badword['action'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			$page['title'] = "Edit Badword - ".htmlspecialchars($row['word']);
			$page['content'] .= "<form method=\"post\" action=\"./?p=badwords&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Word:</td><td><input type=\"text\" name=\"word\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['word'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Types:</td><td>";
			$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";
	
			$col = 0;
			foreach (array_keys($typesword2letter) as $type) {
				if ($type != "all") {
					$col++;
					$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"types[]\" value=\"".$type."\"" . ((in_array($type,unserialize($row['types'])) or in_array("all",unserialize($row['types'])))?"checked":"") . " />".$type."</td>";
					if ($col == 3) {
						$page['content'] .= "</tr><tr>";
						$col = 0;
					}
				}
			}
			if (($col < 3) and ($col != 0)) {
				for ($i=0;$i<(3-$col);$i++) {
					$page['content'] .= "<td>&nbsp;</td>";
				}
			}
			
			$page['content'] .= "</table>";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Replace:</td><td><input type=\"text\" name=\"replace\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['replace'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Action:</td><td>";
			$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
			if ($row['action'] == "replace") {
				$page['content'] .= "<option selected>replace</option>\n";
			} else {
				$page['content'] .= "<option>replace</option>\n";
			}
			if ($row['action'] == "block") {
				$page['content'] .= "<option selected>block</option>\n";
			} else {
				$page['content'] .= "<option>block</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Badword\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=badwords");
			exit;
		}
	} else {
		header("Location: ./?p=badwords");
		exit;
	}
}

function badwords_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM badwords WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM badwords WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=badwords");
			exit;
		}
		
		$page['title'] = "Delete Badword - ".htmlspecialchars($row['word']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=badwords&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['word'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=badwords");
		exit;
	}
}

function badwords_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM badwords ORDER BY types, `replace`, word";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Badwords";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Word</th><th>Types</th><th>Replacement</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=badwords&action=add\">New Server</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=badwords&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/tag_blue_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/tag_blue.png\" alt=\"".htmlspecialchars($row['word'])."\" title=\"".htmlspecialchars($row['word'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=badwords&action=edit&id=".$row['id']."\">".htmlspecialchars($row['word'])."</a></td>";
			$page['content'] .= "<td>".typesword2letter(unserialize($row['types']))."</td>";
			$page['content'] .= "<td>".($row['replace']?htmlspecialchars($row['replace']):"&lt;default&gt;")."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=badwords&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/tag_blue_edit.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=badwords&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/tag_blue_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
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
	
	$typesword2letter['channel'] = "c";
	$typesword2letter['message'] = "m";
	$typesword2letter['quit'] = "q";
	$typesword2letter['all'] = "a";
	
	foreach (array_keys($typesword2letter) as $type) {
		$typesletter2word[$typesword2letter[$type]] = $type;
	}
}
?>