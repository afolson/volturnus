<?php
error_reporting(0);

/* Start the session! */
session_start();

/* Grab the SQL details to auth */
include_once("config.inc.php");

$sql_conn = mysql_connect($sqldetails['server'], $sqldetails['user'], $sqldetails['password']);
mysql_select_db($sqldetails['database'], $sql_conn);

include_once("auth.inc.php");

if (!$page['error']) {
	if (isset($_GET['p'])) {
		$p = strtolower($_GET['p']);
		if ($p == "home") { $incfile = "pages/home.inc.php"; }
		else if ($p == "opers") { $incfile = "pages/opers.inc.php"; }
		else if ($p == "servers") { $incfile = "pages/servers.inc.php"; }
		else if ($p == "channels") { $incfile = "pages/channels.inc.php"; }
		else if ($p == "bans") { $incfile = "pages/bans.inc.php"; }
		else if ($p == "exceptions") { $incfile = "pages/exceptions.inc.php"; }
		else if ($p == "dcc") { $incfile = "pages/dcc.inc.php"; }
		else if ($p == "vhosts") { $incfile = "pages/vhosts.inc.php"; }
		else if ($p == "badwords") { $incfile = "pages/badwords.inc.php"; }
		else if ($p == "spamfilters") { $incfile = "pages/spamfilters.inc.php"; }
		else if ($p == "cgiirc") { $incfile = "pages/cgiirc.inc.php"; }
		else if ($p == "other") { $incfile = "pages/other.inc.php"; }
		// TODO: This needs to be optional depending on which version of the system you are running
		// It does no good to have this here if your IRCd doesn't have remote includes of if you use the cron.
		else if ($p == "rehash") { $incfile = "pages/rehash.inc.php"; }
	} else {
		$incfile = "pages/home.inc.php";
	}
	include($incfile);
}

include("templates/header.tpl");
include("templates/block.tpl");
include("templates/footer.tpl");

mysql_close($sql_conn);

?>