<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Volturnus - <?php echo $page['title']; ?></title>
    <meta name="author" content="zomg (Amanda F) based on work by Jobe" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel='icon' href='favicon.ico' type='image/x-icon' />
    <link rel='shortcut icon' href='favicon.ico' type='image/xicon' />
    <link rel="stylesheet" type="text/css" href="templates/site.css" />
</head>
<body>
<center>
    <table cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td class="top" colspan="2">
            	<table cellpadding="0" cellspacing="0" width="100%">
            		<tr>
            			<td rowspan="3"></td>
						<td class="date"><?php echo gmdate('l, d F Y, H:i'); ?></td>
            		</tr>
            		<tr>
            			<td class="date">Logged in as: <?php echo $_SESSION['username']; ?></td>
            		</tr>
            		<tr>
            			<td style="height: 76px;">&nbsp;</td>
            		</tr>
            	</table>
            </td>
        </tr>
        <tr>
<?php include("pages/toc.inc.php"); ?>
            <td class="content">
                <table class="main" cellpadding="0" cellspacing="0">

<!-- == END header.tpl == -->
