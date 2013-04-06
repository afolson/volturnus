#!/usr/bin/perl -w
use Digest::MD5;

my (%config);
my ($md5temp, $temphash);
my ($md5final, $finalhash);

################################################################################
# Edit the following fields with the required information:					   #
#																			   #
# configurl = This is the URL you need to grab the config! use the example as  #
# a template. The URL is built with the following information:				   #
#																			   #
# servername: This should be a server specified in the config admin.		   #
# bindip: This is the IP of the IRCd. It should match the one in the config    #
# admin.																	   #
# temppath: This is a temporary file. You will need to create it yourself 	   #
# using touch																   #
# finalpath: This should be the name of the config. Leave this alone unless    # 
# you know what it does!													   #
# rehashcmd: This will vary by IRCd. You will need to know where your IRCd PID #
# file is. See examples below.												   #
################################################################################

$config{'configurl'} = "http://server1:password\@www.mysite.net/volturnus/remote.php";
$config{'bindip'} = "127.0.0.1";
$config{'temppath'} = "/home/username/Unreal3.2/config.conf.temp";
$config{'finalpath'} = "/home/username/Unreal3.2/config.conf";
$config{'rehashcmd'} = "kill -HUP \`cat /home/username/Unreal3.2/ircd.pid\`";

################################################################################
#																			   #
#       DO NOT EDIT BELOW THIS BOX UNLESS YOU KNOW WHAT YOU'RE DOING!!!		   #
#																			   #
################################################################################

# Create objects
$md5temp = Digest::MD5->new();
$md5final = Digest::MD5->new();

# Download remote config
system("wget " . $config{'configurl'} . " --bind-address=" . $config{'bindip'} . " --output-document=" . $config{'temppath'} .
">/dev/null 2>&1");

# Get info on temp config file
my($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$size,$atime,$mtime,$ctime,$blksize,$blocks) = stat($config{'temppath'});

# Get hash for temp config file
open(TEMPFILE, $config{'temppath'}) || die "Cannot open file for reading: " . $config{'temppath'};
$md5temp->addfile(*TEMPFILE);
$temphash = $md5temp->hexdigest();
close(TEMPFILE);

# Get hash for actual config file
open(FINALFILE, $config{'finalpath'}) || die "Cannot open file for reading: " . $config{'finalpath'};
$md5final->addfile(*FINALFILE);
$finalhash = $md5final->hexdigest();
close(FINALFILE);

if ($size > 0) {
  if ($temphash ne $finalhash) {
    # If downloaded config isnt 0 in size, and is different from actual config
    # replace actual with temp
    unlink $config{'finalpath'};
    rename $config{'temppath'}, $config{'finalpath'};
    # Rehash IRCd
    system($config{'rehashcmd'});
  }
}


