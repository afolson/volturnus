<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

include('inc/classes/Channel.php');

$pageTitle = "Channels";


$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
$operRole = $_SESSION['admin'];


$channelList = new Channel();
$channelList = $channelList->listChannels();



echo $twig->render('channels.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID,
										'operRole' => $operRole,
										'channelList' => $channelList
									));
?>