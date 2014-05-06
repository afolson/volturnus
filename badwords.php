<?php
session_start();

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('html');
$twig = new Twig_Environment($loader, array(
//    'cache' => 'cache',
));

include('inc/classes/Badword.php');

$pageTitle = "Bad Words";


$userName = $_SESSION['username'];
$operID = $_SESSION['id'];
$operRole = $_SESSION['admin'];


$wordList = new Badword();
$wordList = $wordList->listWords();



echo $twig->render('badwords.html', array('pageTitle' => $pageTitle,
										'userName' => $userName,
										'operID' => $operID,
										'operRole' => $operRole,
										'wordList' => $wordList
									));
?>