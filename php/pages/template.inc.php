<?php
error_reporting(0);
/* $Id:$ */

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