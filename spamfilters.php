<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

include('inc/classes/Spamfilter.php');

$pageTitle = "Spamfilters";


$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
$operRole = $_SESSION['admin'];


$spamfilterList = new Spamfilter();
$spamfilterList = $spamfilterList->listSpamfilters();



echo $twig->render('spamfilters.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID,
										'operRole' => $operRole,
										'spamfilterList' => $spamfilterList
									));
?>
