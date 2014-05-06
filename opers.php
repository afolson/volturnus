<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

include('inc/classes/Oper.php');

$pageTitle = "Opers";


$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
$operRole = $_SESSION['admin'];


$flagList = new Oper();
$flagList = $flagList->listFlags();



echo $twig->render('opers-edit.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID,
										'operRole' => $operRole,
										'flagList' => $flagList
									));
?>