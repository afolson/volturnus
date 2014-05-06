<?php
/* Start the session! */
session_start();

require_once('inc/classes/Database.php');

$db = new Database();




// Check to see if the user is logged in
// If they are not logged in, prompt them to log in.
	// Do logginning stuff here
// If they're logged in redirect them



// Array
// (
//     [username] => config
//     [loggedin] => 1
//     [id] => 1
//     [admin] => 1
// )


//include_once("auth.inc.php");

if (!$page['error']) {
	if (isset($_GET['p'])) {
		$p = strtolower($_GET['p']);
		if ($p == "home") { $incfile = "home.php"; }
		else if ($p == "opers") { $incfile = "opers.php"; }
		else if ($p == "servers") { $incfile = "servers.php"; }
		else if ($p == "channels") { $incfile = "channels.php"; }
		else if ($p == "bans") { $incfile = "bans.php"; }
		else if ($p == "exceptions") { $incfile = "exceptions.php"; }
		else if ($p == "dcc") { $incfile = "dcc.php"; }
		else if ($p == "vhosts") { $incfile = "vhosts.php"; }
		else if ($p == "badwords") { $incfile = "badwords.php"; }
		else if ($p == "spamfilters") { $incfile = "spamfilters.php"; }
		else if ($p == "cgiirc") { $incfile = "cgiirc.php"; }
		else if ($p == "other") { $incfile = "other.php"; }
		// TODO: This needs to be optional depending on which version of the system you are running
		// It does no good to have this here if your IRCd doesn't have remote includes of if you use the cron.
		else if ($p == "rehash") { $incfile = "rehash.php"; }
	} else {
		$incfile = "home.php";
	}
	include($incfile);
}

// include("templates/header.tpl");
// include("templates/block.tpl");
// include("templates/footer.tpl");

// mysql_close($sql_conn);

?>


