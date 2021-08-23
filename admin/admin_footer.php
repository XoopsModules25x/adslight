<?php

declare(strict_types=1);
/*
* You may not change or alter any portion of this comment or credits
* of supporting developers from this source code or any supporting source code
* which is considered copyrighted (c) material of the original comment or credit authors.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;

$pathIcon32 = Admin::iconUrl('', 32);

echo "<div class='adminfooter'>\n" . "  <div style='text-align: center;'>\n" . "    <a href='https://xoops.org' rel='external'><img src='{$pathIcon32}/xoopsmicrobutton.gif' alt='XOOPS' title='XOOPS'></a>\n" . "  </div>\n" . '  ' . _AM_MODULEADMIN_ADMIN_FOOTER . "\n" . '</div>';


//if (isset($GLOBALS['xoTheme'])) {
//    $GLOBALS['xoTheme']->addScript("browse.php?Frameworks/jquery/jquery.js");
//    $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.tablesorter.js');
//} else {
//    echo '<script type="text/javascript" src="' . XOOPS_URL . '/include/spectrum.js"></script>';
//    echo '<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/include/spectrum.css">';
//}


$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.tablesorter.js');
$xoTheme->addScript($helper->url( 'assets/js/tablesorter/jquery.tablesorter.js'));
$xoTheme->addScript($helper->url( 'assets/js/tablesorter/jquery.tablesorter.widgets.js'));
$xoTheme->addScript($helper->url( 'assets/js/functions.js'));

xoops_cp_footer();
