<?php
// Check to make sure there isn't any data in the database
	// If there is, abort
	// if there isn't, continue
	
include_once("config.inc.php");





function siteUri($scriptname, $filter) {
    $dir = str_replace($filter, "", $scriptname);
    return "http://$_SERVER[HTTP_HOST]$dir";
}
function parse_safe($data) {
   return(htmlentities(stripslashes($data), ENT_QUOTES));
}
$sitepath = siteUri($_SERVER['PHP_SELF'], "arloria_install.php");

if(file_exists("./installer-locking")) {
  define("INSTALLER_LOCK", 1);
}
$date = gmdate("U");
$defaultrules = "Rules and regulations upon signup:

Privacy policy:

This site records only data that is necessary to ensure that the site can run effectively.

Most of the data collected is willingly entered by the user, such as a username and e-mail address, however, to defend against malicious users, IP addresses are also recorded.

E-mail addresses entered by the user are not shared under any circumstances, and we are a firm believer in anti-spam. Users have an option to disable their e-mail address being publicly viewed, and those which are displayed use a method which helps prevent e-mail harvesting.

General site usage:

You may use the facilities available on this site for free as a member, however, to continue using the site you must follow a few core rules as listed below:

No spam: We do not wish to see any 'spam' (pointless or advert-orientated material) on the site, it is simple. Spam will result in a warning followed by a ban if repeated.

Treat staff with respect, they bring the site to you and keep it in order, 'flaming' (attacking verbally) of any person is not tolerated.

The rules are subject to change, as with the entire site and any part of it. New rules will almost always be announced, but it is your job to make sure you are up to date with them.

Thanks, team.";
if(empty($_REQUEST['step'])) {
    $_REQUEST['step'] = "";
}

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
   \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
<head>
   <style type='text/css'>   
.float {
  float: left;
  width: 48%;
}
body {   
  background-color: #808090;
  font-family: Arial;  
  font-size: 10pt;    
  padding: 0px;
  margin: 0px;
}
hr {
 background-color: #B0B0D0; 
}
input, textarea {
  background-color: #CCCCCC;
  border: 1px solid #606060;
  color: #404040;  
}
.continue {
  background-color: #CCCCCC;
  border: 1px solid #606060;
  color: #404040;  
  padding: 2px;
}
.continue a {
   text-decoration: none;
   color: #404040;
}
.insthead {
  background: url('./skins/5/box_head.jpg') repeat-x;  
  color: #EFEFEF;   
  font-family: Verdana;   
  font-size: 11pt;
  font-weight: bold;
}
.insttbl {   
  float: center;
  background-color: #EFEFEF;
  border: 1px solid #606060;
  margin-left: 100px;
  text-align: center;
  width: 80%;
}
.iterror {
  background-color: #DF8F8F;
  border-top: 1px solid #606060;
  border-bottom: 1px solid #606060;
  color: #202020;
}
textarea {
  height: 200px;
  width: 98%;
}
</style>
<title>Arloria Content Management System</title>
</head>";
echo "<body>
<div class='insttbl'><div class='insthead'>Arloria CMS Installer</div><br />
<div class='float'>&nbsp; &nbsp; &nbsp;Created by Sheepeep</div>
<div class='float'><a href='http://www.sheepeep.com'>Sheepeep.com</a></div><br /></div><br />
<div class='insttbl'>";
if(defined("INSTALLER_LOCK")) {
  exit("<p class='iterror'> Error, cannot continue<br />Installer is locked.</p>");
}
echo "<div class='insthead'>";
switch($_REQUEST['step']) {
    case "":
echo "Welcome to the Arloria Content Management System installer</div><br />Posted by: <a href='http://www.sheepeep.com/' target= '_blank'>
Sheepeep</a> (" . gmdate("l, dS F Y") . ")<br /><br />
This will install <b>Arloria</b> onto your site<br />
Please check that you have uploaded <b>All</b> files before continuing
<p>After checking that you have uploaded all files, please click the button below to continue.</p>
<br /><br /><br /><textarea name='bugs' rows='60' cols='6'>";
echo "</textarea><br /><br />";
$conf_files = array("./conf_cms.php", "./conf_sql.php", "./conf_shared.php");
foreach($conf_files as $file) {
  if(!file_exists($file)) {
    exit("<div align='center' class='iterror'> Error, cannot find $file<br />Please ensure you have uploaded this file.</div></div></body></html>");
  }
  if(!is_writable($file)) {
    exit("<div align='center' class='iterror'> Error, cannot write to $file
<br />Please check this file is writable
<br />If this is a *NIX-based server, check CHMOD settings</div></div></body></html>");
  }
}
echo "<b class='continue'><a href='./arloria_install.php?step=details'>Continue</a></b><br /><br /></div>";
break;
   case "details":
      echo "Set up site</div>";
      $show = 1;
      $message = "";
      $sql_host = empty($_POST['sql_host']) ? "localhost" : parse_safe($_POST['sql_host']);
      $sql_user = empty($_POST['sql_user']) ? "root" : parse_safe($_POST['sql_user']);
      $sql_pass = empty($_POST['sql_pass']) ? "" : parse_safe($_POST['sql_pass']);
      $sql_db = empty($_POST['sql_db']) ? "" : parse_safe($_POST['sql_db']);
      $sql_port = empty($_POST['sql_port']) || !is_numeric($_POST['sql_port']) ? "3306" : $_POST['sql_port'];
      $sql_dbcreate = empty($_POST['sql_dbcreate']) ? 0 : 1;
      $sql_type = empty($_POST['sql_type']) ? "mysql" : $_POST['sql_type'];
      $site_username = empty($_POST['site_username']) ? "" : parse_safe($_POST['site_username']);
      $site_pass = empty($_POST['site_pass']) ? "" : parse_safe($_POST['site_pass']);
      $site_pass2 = empty($_POST['site_pass2']) ? "" : parse_safe($_POST['site_pass2']);
      $site_name = empty($_POST['site_name']) ? "" : parse_safe($_POST['site_name']);
      $site_online = empty($_POST['site_online']) ? 0 : 1;
      $site_rules = empty($_POST['rules']) ? $defaultrules : parse_safe($_POST['rules']);               
      if(!empty($_POST)) {
         if(!file_exists("./corefunc_{$sql_type}.php")) {
            $message = "You have selected $sql_type, but corefunc_{$sql_type}.php does not exist<br />Please ensure this file has been uploaded and retry";
         }
         if(empty($sql_db)) {
            $message = "You did not specify a database to connect to<br />Set this under 'database name' to continue.";
         }
         elseif(empty($site_username) || empty($site_pass)) {
            $message = "You are rquired to make an administrator account with a username and password in order to install Arloria Content Management System";
         }
         elseif(empty($site_pass2) && $site_pass != $site_pass2) {
            $message = "The passwords must match in order to continue<br />Please make sure 'Site Password' and 'Confirm Password' are the same";
         }
         elseif(empty($site_name)) {
            $message = "No site name entered.<br />You must enter a site name in order to set up Arloria Content Management System";
         }
         else {
            include("./corefunc_{$sql_type}.php");
            $db = array("host" => $sql_host, "user" => $sql_user, "pass" => $sql_pass, "port" => $sql_port);
            $sql = new database($db);
            if(!$sql->connect()) {
               $message = "Unable to connect to database<br />Please ensure that you have entered the correct SQL details";
            }
            if($open = fopen("./conf_sql.php", "w") && !empty($message)) {
               fwrite($open, "<?php
\$db['SQL_TYPE'] = \"mysql\";
\$db['host'] = \"$sql_host\";
\$db['user'] = \"$sql_user\";
\$db['pass'] = \"$sql_pass\";
\$db['port'] = $sql_port;
\$db['database'] = \"$sql_db\";
?" . ">");
            fclose($open);                        
            }
            else
            {
               $message = "Unable to write to conf_sql.php<br />
               please check the permissions of this file and try again.";               
            }            
            if($open = fopen("./conf_cms.php", "w") && !empty($message)) {
               fwrite($open, "<?php
\$setting['SITE_NAME'] = \"$site_name\";
\$setting['SITE_URL'] = \"$sitepath\";
\$setting['SITE_PATH'] = \"$setting[DOCUMENT_ROOT]\";
\$setting['meta']['description'] = \"Arloria Content Management System is a single-manned attempt to create a fully-usable, fully-customisable suite of web applications for use by anyone. Still in private-beta, the flagship product is expected to be released by the end of this year.\";
\$setting['meta']['keywords'] = \"web, portal, php, forum, content, management, system, mysql, mysqli, sheepeep, fireblast, arloria\";
\$setting['prime_page'] = \"forum\";
\$setting['forum']['announcecat'] = 1;
\$setting['forum']['announcename'] = \"News, Information and Announcements\";
\$setting['forum']['description'] = \"All announcements are posted here\";
?" . ">");
               fclose($open);
            }
            else
            {
               $message = "Unable to write to conf_cms.php<br />
               please check the permissions of this file and try again.";               
            }
            if($open = fopen("./conf_shared.php", "w") && !empty($message)) {
               fwrite($open, "<?php
\$setting['activation'] = 0;
\$setting['default_skin'] = 5;
\$setting['default_act_usergroup'] = 2;
\$setting['default_usergroup'] = 3;
\$setting['email_host'] = \"\";
\$setting['email_addr'] = \"\";     
\$setting['forum_enabled'] = 1;          
\$setting['lang'] = \"en\";
\$setting['NAMED_AVATARS'] = 1;
\$setting['online_minutes_cutoff'] = 30;
\$setting['rules'] = \"$siterules\";
\$setting['SITE_ONLINE'] = 1;
\$setting['SITE_PATH'] = \"$sitepath\";
\$setting['SITE_ROOT'] = \"$scriptname\";
\$setting['started'] = \"$date\";
\$setting['version_specifics'] = 1;
?" . ">");
               fclose($open);            
            }
            else
            {
               $message = "Unable to write to conf_shared.php<br />
               please check the permissions of this file and try again.";               
            }
         }
      }
if($show == 1) {   
   echo "<form method='post' action='?step=details'><br />
<table width='100%' style='border-collapse: collapse;'>";
   if(!empty($message)) {
      echo "<tr>
      <th colspan='2' class='iterror'>$message</th>
   </tr>";
   }
   echo "<tr>
      <td><br /></td>
   </tr>
   <tr>
      <td>SQL host</td>
	  <td><input type='text' value='$sql_host' name='sql_host' /><b>:</b><input type='text' size='2' maxlength='5' value='$sql_port' name='sql_port' /></td>
   </tr>
   <tr>
      <td>SQL username</td>
	  <td><input type='text' value='$sql_user' name='sql_user' /></td>
   </tr>
   <tr>
      <td>SQL password</td>
	  <td><input type='password' name='sql_pass' /></td>
   </tr>   
   <tr>
      <td>SQL database</td>
	  <td>Create if permissions allow?<input type='checkbox' name='sql_dbcreate' /><br /><input type='text' value='$sql_db' name='sql_db' /></td>
   </tr>
   <tr>
      <td>SQL username</td>
	  <td><select name='sql_type' />";
	  if(function_exists('mysqli_connect')) {
         echo "<option value='mysqli'>MySQLi</option>";
     }	  
	  if(function_exists('mysql_connect')) {
	     echo "<option value='mysql'>MySQL</option>";
	  }
     echo "</select></td>
   </tr>   
   <tr>
      <td colspan='2'><hr /></td>
   </tr>
   <tr>
      <td>Site Username</td>
	  <td><input type='text' value='$site_username' name='site_username' /></td>
   </tr>
   <tr>
      <td>Site Password</td>
	  <td><input type='password' name='site_pass' /></td>
   </tr>
   <tr>
      <td>Confirm Password</td>
	  <td><input type='password' name='site_pass2' /></td>
   </tr>
   <tr>
      <td colspan='2'><hr /></td>
   </tr>
   <tr>
      <td>Site Name</td>
	  <td><input type='text' value='$site_name' name='site_name' /></td>
   </tr>
   <tr>
      <td>Site online</td>
	  <td><input type='checkbox' name='site_online' checked='checked' /></td>
   </tr>
   <tr>
      <td colspan='2'><br />Edit Rules and privacy policy<br />
      <textarea name='rules' cols='55' rows='12'>$site_rules</textarea></td>
   </tr>
   <tr>
      <td colspan='2'><input type='submit' name='continue' value='Set up board' /><br /></td>
   </tr>
</table>
</form><br /><br /></div><br /><br /><br /><br /></div>";
}
break;
case "final":
echo "Set up site</div>";
$sql_test = @mysql_connect($sql_host .":" . $sql_port, $sql_user, $sql_pass) or die("<br /><div align='center' ><div class='iterror'>Error</div><div style='width: 80%;border: 1px solid #606060; border-top: 0px;'>Unable to connect to MySQL server, please check entered details and try again.</div></div>$detailsformrender");
if($sql_dbcreate == "0") {
$sql_db_test = mysql_select_db("$sql_db");
}
else
{
    $dbcreate = mysql_query("CREATE DATABASE $sql_db");
    $sql_db_test = mysql_select_db($sql_db);
}
$open = fopen("./conf_sql.php", "w");

$table['0'] = "`links` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `name` char(50) NOT NULL,
  `link` char(150) NOT NULL,
  `admin` int(1) NOT NULL default '0',
  `onpublic` int(1) NOT NULL default '0',
  `addedby` INT UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
)";

$table['1'] = "`bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `address` char(15) NOT NULL,
  `whitelist` int(1) NOT NULL,
  `expiry` int(11) NOT NULL,
  `reason` char(50) NOT NULL,
  PRIMARY KEY  (`id`)
)";

$table['2'] = "`blog_comments` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `posterid` int(11) NOT NULL default '0',
  `title` char(64) NOT NULL default '',
  `content` text NOT NULL,
  `source` int(11) UNSIGNED NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `is_topic` int(1) NOT NULL default '0',
  `closed` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";
  
$table['3'] = "`emoticons` (
  `id` int(10) UNSIGNED NOT NULL auto_increment,
  `ecode` char(12) NOT NULL,
  `description` char(64) default NULL,
  `filename` char(64) NOT NULL,
  `locked` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";
  
$table['4'] = "`groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(64) NOT NULL,
  `pre` char(64) default NULL,
  `sfx` char(64) default NULL,
  `position` int(3) NOT NULL default '0',
  `see_offline` tinyint(1) NOT NULL default '0',
  `may_message` tinyint(1) NOT NULL default '0',
  `may_blog` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";

$table['5'] = "`nationalities` (
  `id` int(5) NOT NULL auto_increment,
  `name` char(64) NOT NULL,
  `image` char(64) NOT NULL,
  `code` char(5) NOT NULL,
  PRIMARY KEY  (`id`)
)";

$table['6'] = "`news_comments` (
  `id` int(11) NOT NULL auto_increment,
  `posterid` int(11) NOT NULL default '0',
  `title` char(64) NOT NULL default '',
  `content` text NOT NULL,
  `invisible` int(1) NOT NULL default '0',
  `source` int(11) NOT NULL default '0',
  `signature` int(1) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";

$table['7'] = "`pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `comments` int(11) NOT NULL default '0',
  `category` int(11) NOT NULL default '0',
  `date` char(10) NOT NULL default '',
  `last_edit` char(10) NOT NULL default '',
  `status` int(1) NOT NULL default '0',
  `author` int(11) NOT NULL default '0',
  `permissions` char(100) NOT NULL default '',
  `rating` char(5) NOT NULL default '0',
  `description` char(255) NOT NULL default '',
  `views` int(11) NOT NULL default '0',
  `voters` text NOT NULL,
  PRIMARY KEY  (`id`)
)";

$table['8'] = "`poll` (
  `id` int(10) NOT NULL auto_increment,
  `source` char(10) NOT NULL default '',
  `question` char(100) NOT NULL default '',
  `answer` text NOT NULL,
  `voters` text NOT NULL,
  `score` char(10) NOT NULL default '',
  `author` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `closed` int(1) NOT NULL,
  `close_date` int(10) NOT NULL,
   PRIMARY KEY (`id`)
)";
   
$table['9'] = "`private_messages` (
  `id` int(11) NOT NULL auto_increment,
  `senderid` int(11) NOT NULL default '0',
  `recid` int(11) NOT NULL default '0',
  `title` char(100) NOT NULL default '0',
  `content` text NOT NULL,
  `deleted` int(1) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `is_read` int(1) NOT NULL,  
  PRIMARY KEY  (`id`)
)";

$table['10'] = "`skins` (
  `id` int(11) NOT NULL auto_increment,
  `version` char(10) NOT NULL,
  `name` char(30) NOT NULL,
  `description` text NOT NULL,
  `author` char(50) NOT NULL,
  PRIMARY KEY  (`id`)
)";

$table['11'] = "`subjects` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) NOT NULL,
  `valid_ids` char(100) NOT NULL,
  `id_type` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";

$table['12'] = "`topics` (
  `id` INT UNSIGNED NOT NULL auto_increment,
  `title` char(64) NOT NULL default '',
  `posterid` char(11) NOT NULL default '',
  `content` text NOT NULL,
  `date` int(10) default NULL,
  `comments` int(1) NOT NULL default '1',
  `invisible` int(1) NOT NULL default '0',
  `is_html` INT(1) NOT NULL default '0',
  `subject` int(3) NOT NULL default '0',
  `project` int(3) NOT NULL default '0',
  `source` INT UNSIGNED NOT NULL ;  
  PRIMARY KEY  (`id`)
)";

$table['13'] = "`userblogs` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(64) NOT NULL default '',
  `description` varchar(255) default NULL,
  `private` int(1) NOT NULL default '0',
  `valid_id` varchar(255) default NULL,
  `mod_ids` varchar(64) default NULL,
  `owner` int(11) NOT NULL default '0',
  `enabled` int(1) NOT NULL default '0',
  `skin_css` text,
  `blocked_id` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";
  
$table['14'] = "`users` (
  `id` int(10) NOT NULL auto_increment,
  `name` char(50) NOT NULL default '',
  `location` char(25) NOT NULL default '',
  `email_address` char(100) NOT NULL default '',
  `username` char(100) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `info` char(100) NOT NULL default '',
  `user_level` int(5) NOT NULL default '0',
  `emailpublic` char(1) NOT NULL default '1',
  `avatar` char(5) default NULL,
  `skin` int(5) default '0',
  `posts` int(11) NOT NULL default '0',
  `signature` char(500),
  `signup` int(11) NOT NULL default '0',
  `last_visit` int(11) NOT NULL default '0',
  `ip_addr` char(15) NOT NULL default '',
  `may_blog` int(1) NOT NULL default '0',
  `rank` int(11) NOT NULL default '0',
  `money` int(64) NOT NULL default '0',
  `mod_comment` text NOT NULL,
  `age` int(3) NOT NULL default '0',
  `nationality` int(11) NOT NULL default '0',
  `string` char(10) NOT NULL,
  `snippets` text NOT NULL,
  `online_location` char(100) NOT NULL,
  `timezone` tinyint(2) UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
)";
#foreach($table as $arrayvalue) {
#    $query = mysql_query("CREATE TABLE $arrayvalue");
#    if(!$query) {
#                exit("Error: " . mysql_error());
#    }
#}
$encpass = md5($site_pass);
$row['0'] = "`admin_links` VALUES (1, 'Sheepeep.com', 'Sheepeep.com', '1', '1', '1')";
$row['1'] = "`groups` VALUES (1, 'Banned', '0', '<strike><font color=\'#CC33CC\'>', '</font></strike>', 0, 0, 0)";
$row['2'] = "`groups` VALUES (2, 'unvalidated', '1', NULL, NULL, 0, 1, 0)";
$row['3'] = "`groups` VALUES (3, 'Member', '2', NULL, NULL, 0, 1, 1)";
$row['4'] = "`groups` VALUES (4, 'Moderator', '3', '<font color=\'#2f6f40\'>', '</font>', 0, 1, 1)";
$row['5'] = "`groups` VALUES (5, 'Admin', '4', '<font color=\'#2f40ff\'>', '</font>', 1, 1, 1)";
$row['6'] = "`groups` VALUES (6, 'Superadmin', '5', '<font color=\'#ff402f\'>', '</font>', 1, 1, 1)";
$row['7'] = "`skins` VALUES (1, 'Fireblast', 'A skin which uses shades of red in a way which gives a simple yet refined look.')";
$row['8'] = "`skins` VALUES (2, 'Midnight', 'An in progress theme which is still very much under construction.')";
$row['9'] = "`skins` VALUES (5, 'Standard', 'A light, professional theme')";
$row['10'] = "`topics` VALUES (1, 'Welcome to your new web portal!', '1', 'Welcome to your new web portal<br /><br /> please bear in mind that this is still beta software and is only an example of the full functionality of the final version.<br />Please report all bugs not mentioned in the bugs list to <a href=\'http://www.sheepeep.com\'>Sheep</a>.<br />Thankyou for using this software,<br />Sheep', $date, 0, 0, 1, 0, 1)";
$row['11'] = "`users` (`name`, `email_address`, `username`, `password`, `user_level`, `emailpublic`, `posts`, `signup`) VALUES ('Site Admin', 'admin@domain.com', '$site_username', '$encpass', 6, 1, 1, $date)";
#foreach($row as $arrayvalue) {
#    $query = mysql_query("INSERT INTO $arrayvalue");
#    if(!$query) {
#        echo "Error." . mysql_error();
#        exit();
#}
#}
echo "<font size='5'>Congratulations!</font><p>Arloria Content Management System should now be installed.</p><br />Thank you for using our software.<br /></div>";
#$test = fopen("./installer-locking", 'w');
if(!$test) {
  echo "<p class='iterror'>Warning:<br /><br />Installer file was unable to be locked, as a security measure - Please delete arloria_install.php from your web server";
}
else
{
  echo "Installer lock written.";
}
echo "<br /></div>";
break;
}
echo "</body></html>";
?>
