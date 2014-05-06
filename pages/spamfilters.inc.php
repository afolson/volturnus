<?php
error_reporting(0);

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

inittargetslist();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		spamfilters_add();
	} else if ($_GET['action'] == "edit") {
		spamfilters_edit();
	} else if ($_GET['action'] == "delete") {
		spamfilters_delete();
	} else {
		spamfilters_list();
	}
} else {
	spamfilters_list();
}

function spamfilters_add() {
	global $page;
	global $sql_conn;
	global $targetsword2letter;
	
	$page['title'] = "Add Spamfilter";
	$doform = false;
	$spamfilter['regex'] = "";
	$spamfilter['targets'] = array();
	$spamfilter['action'] = "";
	$spamfilter['reason'] = "";
	$spamfilter['time'] = "";
	
	if (isset($_POST['submit']) and isset($_POST['regex'])) {
		$sql = "SELECT * FROM spamfilters WHERE regex = '".mysql_real_escape_string($_POST['regex'])."'";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> A spamfilter with the regex \"".htmlspecialchars($_POST['regex'])."\" already exists!</font>\n";
			$spamfilter['regex'] = htmlspecialchars($_POST['regex']);
			$spamfilter['targets'] = $_POST['targets'];
			$spamfilter['action'] = $_POST['action'];
			$spamfilter['reason'] = htmlspecialchars($_POST['reason']);
			$spamfilter['time'] = htmlspecialchars($_POST['time']);
			if (!is_array($spamfilter['targets'])) {
				$spamfilter['targets'] = array();
			} else {
				$spamfilter['targets'] = $spamfilter['targets'];
			}
			$doform = true;
		} else if (!isset($_POST['targets'])) {
			$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select one or more targets!</font>\n";
			$spamfilter['regex'] = htmlspecialchars($_POST['regex']);
			$spamfilter['targets'] = $_POST['targets'];
			$spamfilter['action'] = $_POST['action'];
			$spamfilter['reason'] = htmlspecialchars($_POST['reason']);
			$spamfilter['time'] = htmlspecialchars($_POST['time']);
			if (!is_array($spamfilter['targets'])) {
				$spamfilter['targets'] = array();
			} else {
				$spamfilter['targets'] = $spamfilter['targets'];
			}
			$doform = true;
		} else {
			$sql = "INSERT INTO spamfilters (regex, targets, action, reason, time) VALUES ('".mysql_real_escape_string($_POST['regex'])."', '".mysql_real_escape_string(serialize($_POST['targets']))."', '".mysql_real_escape_string($_POST['action'])."', '".mysql_real_escape_string($_POST['reason'])."', '".mysql_real_escape_string($_POST['time'])."');";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			header("Location: ./?p=spamfilters");
			exit;
		}
	} else {
		$doform = true;
	}
	
	if ($doform) {
		$page['content'] .= "<form method=\"post\" action=\"./?p=spamfilters&action=add\">\n";
		$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
		
		$page['content'] .= "<tr><td>Regular Expression:</td><td><input type=\"text\" name=\"regex\" style=\"WIDTH: 300px\" value=\"".$spamfilter['regex']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td valign=\"top\">Targets:</td><td>";
		$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>";

		$col = 0;
		foreach (array_keys($targetsword2letter) as $target) {
			$col++;
			$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"flags[]\" value=\"".$target."\"" . (in_array($target,$spamfilter['targets'])?"checked":"") . " />".$target."</td>";
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
		$page['content'] .= "<tr><td>Action:</td><td>";
		$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
		if ($spamfilter['action'] == "kill") {
			$page['content'] .= "<option selected>kill</option>\n";
		} else {
			$page['content'] .= "<option>kill</option>\n";
		}
		if ($spamfilter['action'] == "tempshun") {
			$page['content'] .= "<option selected>tempshun</option>\n";
		} else {
			$page['content'] .= "<option>tempshun</option>\n";
		}
		if ($spamfilter['action'] == "shun") {
			$page['content'] .= "<option selected>shun</option>\n";
		} else {
			$page['content'] .= "<option>shun</option>\n";
		}
		if ($spamfilter['action'] == "kline") {
			$page['content'] .= "<option selected>kline</option>\n";
		} else {
			$page['content'] .= "<option>kline</option>\n";
		}
		if ($spamfilter['action'] == "gline") {
			$page['content'] .= "<option selected>gline</option>\n";
		} else {
			$page['content'] .= "<option>gline</option>\n";
		}
		if ($spamfilter['action'] == "zline") {
			$page['content'] .= "<option selected>zline</option>\n";
		} else {
			$page['content'] .= "<option>zline</option>\n";
		}
		if ($spamfilter['action'] == "gzline") {
			$page['content'] .= "<option selected>gzline</option>\n";
		} else {
			$page['content'] .= "<option>gzline</option>\n";
		}
		if ($spamfilter['action'] == "block") {
			$page['content'] .= "<option selected>block</option>\n";
		} else {
			$page['content'] .= "<option>block</option>\n";
		}
		if ($spamfilter['action'] == "dccblock") {
			$page['content'] .= "<option selected>dccblock</option>\n";
		} else {
			$page['content'] .= "<option>dccblock</option>\n";
		}
		if ($spamfilter['action'] == "viruschan") {
			$page['content'] .= "<option selected>viruschan</option>\n";
		} else {
			$page['content'] .= "<option>viruschan</option>\n";
		}
		if ($spamfilter['action'] == "warn") {
			$page['content'] .= "<option selected>warn</option>\n";
		} else {
			$page['content'] .= "<option>warn</option>\n";
		}
		$page['content'] .= "</select>\n";
		$page['content'] .= "</td></tr>\n";
		$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".$spamfilter['reason']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td>Duration:</td><td><input type=\"text\" name=\"time\" style=\"WIDTH: 300px\" value=\"".$spamfilter['time']."\" /></td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Add Spamfilter\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
		$page['content'] .= "</td></tr>\n";
		
		$page['content'] .= "</table>\n";
		$page['content'] .= "</form>\n";
	}
}

function spamfilters_edit() {
	global $page;
	global $sql_conn;
	global $targetsword2letter;
	
	$doform = false;
	
	$sql = "SELECT * FROM spamfilters WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			$sql = "SELECT * FROM spamfilters WHERE regex = '".mysql_real_escape_string($_POST['regex'])."'";
			$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			if (mysql_num_rows($result)) {
				$row = mysql_fetch_array($result);
				if ($row['id'] != $_GET['id']) {
					$page['content'] .= "<font color=\"red\"><b>Error:</b> A spamfilter with the regex \"".htmlspecialchars($_POST['regex'])."\" already exists!</font>\n";
					$doform = true;
				}
			}
			if (!isset($_POST['targets'])) {
				$page['content'] .= "<font color=\"red\"><b>Error:</b> You must select one or more targets!</font>\n";
				$doform = true;
			}
			if (!$doform) {
				$sql = "UPDATE spamfilters SET regex = '".mysql_real_escape_string($_POST['regex'])."', targets = '".mysql_real_escape_string(serialize($_POST['targets']))."', action = '".mysql_real_escape_string($_POST['action'])."', reason = '".mysql_real_escape_string($_POST['reason'])."', time = '".mysql_real_escape_string($_POST['time'])."' WHERE id = ".mysql_real_escape_string($_GET['id']);
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
		} else {
			$doform = true;
		}
		
		if ($doform) {
			if (strlen($row['regex']) > 20) {
				$page['title'] = "Edit Spamfilter - ".htmlspecialchars(substr($row['regex'], 0, 20)) . "...";
			} else {
				$page['title'] = "Edit Spamfilter - ".htmlspecialchars($row['regex']);
			}
			$page['content'] .= "<br />\n";
			$page['content'] .= "<form method=\"post\" action=\"./?p=spamfilters&action=edit&id=".$_GET['id']."\">\n";
			$page['content'] .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">\n";
			
			$page['content'] .= "<tr><td>Regular Expression:</td><td><input type=\"text\" name=\"regex\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['regex'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td valign=\"top\">Targets:</td><td>";
			$page['content'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";

			$col = 0;
			foreach (array_keys($targetsword2letter) as $target) {
				$col++;
				$page['content'] .= "<td width=\"33%\"><input type=\"checkbox\" name=\"flags[]\" value=\"".$target."\"" . (in_array($target,unserialize($row['targets']))?"checked":"") . " />".$target."</td>";
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
			$page['content'] .= "<tr><td>Action:</td><td>";
			$page['content'] .= "<select name=\"action\" style=\"WIDTH: 300px\">\n";
			if ($row['action'] == "kill") {
				$page['content'] .= "<option selected>kill</option>\n";
			} else {
				$page['content'] .= "<option>kill</option>\n";
			}
			if ($row['action'] == "tempshun") {
				$page['content'] .= "<option selected>tempshun</option>\n";
			} else {
				$page['content'] .= "<option>tempshun</option>\n";
			}
			if ($row['action'] == "shun") {
				$page['content'] .= "<option selected>shun</option>\n";
			} else {
				$page['content'] .= "<option>shun</option>\n";
			}
			if ($row['action'] == "kline") {
				$page['content'] .= "<option selected>kline</option>\n";
			} else {
				$page['content'] .= "<option>kline</option>\n";
			}
			if ($row['action'] == "gline") {
				$page['content'] .= "<option selected>gline</option>\n";
			} else {
				$page['content'] .= "<option>gline</option>\n";
			}
			if ($row['action'] == "zline") {
				$page['content'] .= "<option selected>zline</option>\n";
			} else {
				$page['content'] .= "<option>zline</option>\n";
			}
			if ($row['action'] == "gzline") {
				$page['content'] .= "<option selected>gzline</option>\n";
			} else {
				$page['content'] .= "<option>gzline</option>\n";
			}
			if ($row['action'] == "block") {
				$page['content'] .= "<option selected>block</option>\n";
			} else {
				$page['content'] .= "<option>block</option>\n";
			}
			if ($row['action'] == "dccblock") {
				$page['content'] .= "<option selected>dccblock</option>\n";
			} else {
				$page['content'] .= "<option>dccblock</option>\n";
			}
			if ($row['action'] == "viruschan") {
				$page['content'] .= "<option selected>viruschan</option>\n";
			} else {
				$page['content'] .= "<option>viruschan</option>\n";
			}
			if ($row['action'] == "warn") {
				$page['content'] .= "<option selected>warn</option>\n";
			} else {
				$page['content'] .= "<option>warn</option>\n";
			}
			$page['content'] .= "</select>\n";
			$page['content'] .= "</td></tr>\n";
			$page['content'] .= "<tr><td>Reason:</td><td><input type=\"text\" name=\"reason\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['reason'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td>Duration:</td><td><input type=\"text\" name=\"time\" style=\"WIDTH: 300px\" value=\"".htmlspecialchars($row['time'])."\" /></td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$page['content'] .= "<tr><td colspan=\"2\" align=\"center\">";
			$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Update Spamfilter\" />&nbsp;&nbsp;";
			$page['content'] .= "<input type=\"reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;";
			$page['content'] .= "</td></tr>\n";
			
			$page['content'] .= "</table>\n";
			$page['content'] .= "</form>\n";
		} else {
			header("Location: ./?p=spamfilters");
			exit;
		}
	} else {
		header("Location: ./?p=spamfilters");
		exit;
	}
}

function spamfilters_delete() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM spamfilters WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());

	if (mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		if (isset($_POST['submit'])) {
			if ($_POST['submit'] == "Yes") {
				$sql = "DELETE FROM spamfilters WHERE id = '".mysql_real_escape_string($_GET['id'])."'";
				$result = mysql_query($sql, $sql_conn) or die(mysql_error());
			}
			header("Location: ./?p=spamfilters");
			exit;
		}
		
		$page['title'] = "Delete Spamfilter - ".htmlspecialchars($row['regex']);
		$page['content'] .= "<form method=\"post\" action=\"./?p=spamfilters&action=delete&id=".$_GET['id']."\">\n";
		$page['content'] .= "Are you sure you want to delete '".htmlspecialchars($row['regex'])."'?<br /><br />\n";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"Yes\" />&nbsp;&nbsp;";
		$page['content'] .= "<input type=\"submit\" name=\"submit\" value=\"No\" />";
		$page['content'] .= "</form>\n";
	} else {
		header("Location: ./?p=spamfilters");
		exit;
	}
}

function spamfilters_list() {
	global $page;
	global $sql_conn;
	
	$sql = "SELECT * FROM spamfilters ORDER BY targets, action";
	$result = mysql_query($sql, $sql_conn) or die(mysql_error());
	
	$page['title'] = "Spamfilters";
	$page['content'] = "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">\n";
	$page['content'] .= "<tr><th width=\"18\">&nbsp;</th><th>Regex</th><th>Action</th><th>Targets</th><th>Options</th></tr>\n";
	$page['content'] .= "<tr>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=spamfilters&action=add\">New Spamfilter</a></td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td>&nbsp;</td>";
	$page['content'] .= "<td><a href=\"./?p=spamfilters&action=add\"><img border=\"0\" src=\"templates/images/famfamfam/shield_add.png\" alt=\"Add\" title=\"Add\" /></a></td></tr>\n";
	
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$page['content'] .= "<tr>";
			$page['content'] .= "<td><img border=\"0\" src=\"templates/images/famfamfam/shield.png\" alt=\"".htmlspecialchars($row['action'])."\" title=\"".htmlspecialchars($row['action'])."\" /></td>";
			$page['content'] .= "<td><a href=\"./?p=spamfilters&action=edit&id=".$row['id']."\">";
			if (strlen($row['regex']) > 20) {
				$page['content'] .= htmlspecialchars(substr($row['regex'], 0, 20)) . "...";
			} else {
				$page['content'] .= htmlspecialchars($row['regex']);
			}
			$page['content'] .= "</a></td>";
			$page['content'] .= "<td>".htmlspecialchars($row['action'])."</td>";
			$page['content'] .= "<td>".targetsword2letter(unserialize($row['targets']))."</td>";
			$page['content'] .= "<td>";
			$page['content'] .= "<a href=\"./?p=spamfilters&action=edit&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/shield_go.png\" alt=\"Edit\" title=\"Edit\" /></a>&nbsp;";
			$page['content'] .= "<a href=\"./?p=spamfilters&action=delete&id=".$row['id']."\"><img border=\"0\" src=\"templates/images/famfamfam/shield_delete.png\" alt=\"Delete\" title=\"Delete\" /></a>";
			$page['content'] .= "</td>";
			$page['content'] .= "</tr>\n";
		}
	}
	
	$page['content'] .= "</table>\n";
}

function targetsword2letter($targets) {
	global $targetsword2letter;
	$targetsout = "";
	
	if (is_array($targets)) {
		foreach (array_keys($targetsword2letter) as $target) {
			if (in_array($target, $targets)) {
				$targetsout .= $targetsword2letter[$target];
			}
		}
	}
		
	return $targetsout;
}

function targetsletter2word($targets) {
	global $targetsletter2word;
	$targetsout = array();
	
	foreach (array_keys($targetsletter2word) as $target) {
		if(strpos($targets, $target) !== false) {
			$targetsout[] = $targetsletter2word[$target];
		}
	}
	
	return $targetsout;
}

function inittargetslist() {
	global $targetsword2letter;
	global $targetsletter2word;
	
	$targetsword2letter['channel'] = "c";
	$targetsword2letter['private'] = "p";
	$targetsword2letter['private-notice'] = "n";
	$targetsword2letter['channel-notice'] = "N";
	$targetsword2letter['part'] = "P";
	$targetsword2letter['quit'] = "q";
	$targetsword2letter['dcc'] = "d";
	$targetsword2letter['away'] = "a";
	$targetsword2letter['topic'] = "t";
	$targetsword2letter['user'] = "u";
	
	foreach (array_keys($targetsword2letter) as $target) {
		$targetsletter2word[$targetsword2letter[$target]] = $target;
	}
}

?>
