<?php
error_reporting(0);
/* $Id:$ */

if ($_SESSION['loggedin']) { ?>
<!-- == BEGIN toc.tpl == -->
            <td class="toc">
                <div class="menu-top"><img src="templates/images/menutop.png" alt="Menu Top" /></div>
                <div class="menu-item-top" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./">Home</a></div>
                <?php if ($_SESSION['admin']) { ?>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=opers">Opers</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=servers">Servers</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=channels">Channels</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=bans">Bans</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=exceptions">Exceptions</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=dcc">DCC (Allow/Deny)</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=vhosts">vHosts</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=badwords">Bad Words</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=spamfilters">Spamfilters</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=cgiirc">CGIIRC</a></div>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=other">Other</a></div>
				<div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=rehash">Rehash</a></div>
                <?php } else { ?>
                <div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=opers&action=edit&id=<?php echo $_SESSION['id']; ?>">Oper Block</a></div>
				<div class="menu-item" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';"><a href="./?p=rehash">Rehash</a></div>
                <?php }?>
                <div class="menu-bottom"><img src="templates/images/menubottom.png" alt="Menu Bottom" /></div>
            </td>
<!-- == END toc.tpl == -->
<?php } else { ?>
<!-- == BEGIN errortoc.tpl == -->
            <td class="toc">
                <div class="menu-top"><img src="templates/images/menutop.png" alt="Menu Top" /></div>
                <div class="menu-item-top" onmouseover="this.style.backgroundColor='white';" onmouseout="this.style.backgroundColor='#EDEDED';">Error</div>
                <div class="menu-bottom"><img src="templates/images/menubottom.png" alt="Menu Bottom" /></div>
            </td>
<!-- == END errortoc.tpl == -->
<?php } ?>