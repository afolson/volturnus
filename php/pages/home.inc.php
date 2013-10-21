<?php
error_reporting(0);

$page['title'] = "Home";

$page['content'] .=
'<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="4">Select the aspect of the configuration you wish to modify below.</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
';
if ($_SESSION['admin']) {
	$page['content'] .=
'	<tr>
		<td width="20%" align="center"><a href="./?p=opers"><img src="templates/images/icons/opers.png" border="0" alt="Opers" title="Opers" /><br /><br />Opers</a></td>
		<td width="20%" align="center"><a href="./?p=servers"><img src="templates/images/icons/servers.png" border="0" alt="Servers" title="Servers" /><br /><br />Servers</a></td>
		<td width="20%" align="center"><a href="./?p=channels"><img src="templates/images/icons/channels.png" border="0" alt="Channels" title="Channels" /><br /><br />Channels</a></td>
		<td width="20%" align="center"><a href="./?p=bans"><img src="templates/images/icons/bans.png" border="0" alt="Bans" title="Bans" /><br /><br />Bans</a></td>
		<td width="20%" align="center"><a href="./?p=exceptions"><img src="templates/images/icons/exceptions.png" border="0" alt="Exceptions" title="Exceptions" /><br /><br />Exceptions</a></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td width="20%" align="center"><a href="./?p=dcc"><img src="templates/images/icons/dcc.png" border="0" alt="DCC" title="DCC" /><br /><br />DCC</a></td>
		<td width="20%" align="center"><a href="./?p=vhosts"><img src="templates/images/icons/vhosts.png" border="0" alt="VHosts" title="VHosts" /><br /><br />vHosts</a></td>
		<td width="20%" align="center"><a href="./?p=badwords"><img src="templates/images/icons/badwords.png" border="0" alt="Bad Words" title="Bad Words" /><br /><br />Bad Words</a></td>
		<td width="20%" align="center"><a href="./?p=spamfilters"><img src="templates/images/icons/spamfilters.png" border="0" alt="Spamfilters" title="Spamfilters" /><br /><br />Spamfilters</a></td>
		<td width="20%" align="center"><a href="./?p=cgiirc"><img src="templates/images/icons/cgiirc.png" border="0" alt="CGIIRC" title="CGIIRC" /><br /><br />CGIIRC</a></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td width="20%" align="center"><a href="./?p=other"><img src="templates/images/icons/other.png" border="0" alt="Other" title="Other" /><br /><br />Other</a></td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
	</tr>
';
} else {
	$page['content'] .=
'	<tr>
		<td width="20%" align="center"><a href="./?p=opers&action=edit&id='.$_SESSION['id'].'"><img src="templates/images/icons/opers.png" border="0" alt="Oper Block" title="Oper Block" /><br /><br />Oper Block</a></td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
		<td width="20%" align="center">&nbsp;</td>
	</tr>
';
}
$page['content'] .=
'	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
</table>
';
?>
