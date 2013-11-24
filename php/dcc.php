<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

include('inc/classes/DCC.php');

$pageTitle = "DCC";


$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
$operRole = $_SESSION['admin'];


$dccList = new DCC();
$dccList = $dccList->listDCC();

echo $twig->render('dcc.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID,
										'operRole' => $operRole,
										'dccList' => $dccList
									));
?>