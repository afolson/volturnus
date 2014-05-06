<?php
error_reporting(0);

// IRC settings.
$irc = Array(
   // IRC nickname, username and real name.
   "nick"=> "RehashBot",
   "user"=> "RehashBot",
   "name"=> "RehashBot",

   // No need for multiple vars, use the full oper command.
   // Example: "oper AwesomeOperName ohlookapassword"
   "oper"=> "oper rehashbot rehashbot",

   // Support SSL. tcp://irc.server.tld:6667 or ssl://irc.server.tld:6697 or tcp://[::1]:6667
   "server" => "tcp://127.0.0.1:6667",

   // A password required to connect to IRC. (usually optional)
   "pass" => false,

   // NickServ auth. (optional)
   // Example: "IDENTIFY SuperSecurePasswordInPlainText"
   "identify" => false,

   // An IPv4 or IPv6 address to bind to. (optional)
   "bindip" => false,

   // Placeholder for stream_context_create.
   "context" => Array(),
);


/*
 --- SHOULD HAVE NO NEED TO EDIT BELOW THIS LINE UNLESS YOU'RE AN ADVANCED USER ---
*/
$donerehash = false; // An "afolson" oddness.
$page['title'] = "Rehashing Servers"; // Another "afolson"...

// Create an array for bindip, if required.
if ((isset($irc["bindip"]) && !empty($irc["bindip"]))) {
   // Support for IPv4 and IPv6 addresses.
   $irc["context"] = Array('socket'=>array('bindto'=>(strpos($irc["bindip"],':')?'['.$irc["bindip"].']':$irc["bindip"]).':0'));
};

// Create the context from the opts array.
$ctxt = stream_context_create($irc["context"]);
// Connect to IRC!
if ($con = stream_socket_client($irc["server"],$errno,$errstr,5,STREAM_CLIENT_CONNECT,$ctxt)) {
   // Send PASS if set.
   if ((isset($irc["pass"]) && !empty($irc["pass"]))) {
      fwrite($con, "PASS ".$irc["pass"]."\r\n");
   };

   // Send all the required info for connection.
   fwrite($con,"NICK ".$irc["nick"]."\r\n");
   fwrite($con,"USER ".$irc["nick"]." 0 * :".$irc["nick"]."\r\n");

   // Keep looping until FEOF is hit.
   while (!feof($con)) {
         // Read from IRC, at 512 bytes.
         if ($raw = trim(fgets($con,512))) {
            // Prevent the "need" for regex.
            $line = explode(" ",$raw);

            // Reply to the initial PING from some servers.
            if ($line[0] == "PING") {
               fwrite($con,"PONG ".$line[1]."\r\n");
               continue;
            };

            // Only take action for some server responses.
            switch (strtolower($line[1])) {
                case "001":
                     if ((isset($irc["identify"]) && !empty($irc["identify"]))) {
                        fwrite($con, $irc["identify"]."\r\n");
                     };
                     fwrite($con, $irc["oper"]."\r\n");
                     break;
                case "005":
                     do_rehash($con);
                     break;
                case "433":
                     $irc['nick'] = $irc['nick'].rand(0,9).rand(0,9).rand(0,9);
                     fwrite($con, "NICK ".$irc['nick']."\r\n");
                     break;

            };
         };
   };
 } else {
    $page['content'] = "$errstr ($errno)\n"; // I don't know what this is for.. ask "afolson"...
};


// Blame "afolson" for this "do_rehash" mess.
function do_rehash ($con) {
	global $page;
	global $sql_conn;
	global $donerehash;
	if (!$donerehash) {
		$donerehash = true;
		$sql = "SELECT * FROM servers";
		$result = mysql_query($sql, $sql_conn) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$page['content'] .= "Rehashing:<br />\n";
			/* Rehash local server */
			fwrite($con, "REHASH\r\n");
			while ($row = mysql_fetch_array($result)) {
				$page['content'] .= $row['name'] . "<br />\n";
				/* Rehash any others remotely */
				fwrite($con, "REHASH ".$row['name']."\r\n");
			}
		} else {
			$page['content'] .= "No servers listed to rehash";
		}
		fwrite($con, "QUIT :Finished!\r\n");
	}
};
?>
