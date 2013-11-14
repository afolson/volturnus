<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

//$twig->addGlobal("session", $_SESSION);

$pageTitle = "Home";
$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
echo $twig->render('home.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID
									));
?>

