<?php
error_reporting(0);

if (!$_SESSION['admin']) {
	header("Location: ./");
	exit;
}

$page['title'] = "Page";

$page['content'] =
'
Content
';
?>