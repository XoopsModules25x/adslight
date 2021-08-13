<?php
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
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'list');

$mytree = new  Adslight\Tree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

global $mytree, $xoopsDB;
xoops_cp_header();
//loadModuleAdminMenu(1, "");
$adminObject->displayNavigation(basename(__FILE__));

echo "<fieldset style='padding: 20px;'><legend style='font-weight: bold; color: #FF7300;'>" . _AM_ADSLIGHT_GESTCAT . ' </legend>';
echo "<p class=\"left\"><button name=\"buttonName\" type=\"button\" onclick=\"document.location.href='category.php?op=AdsNewCat&amp;cid=0';\">" . _AM_ADSLIGHT_ADDCATPRINC . '</button></p>';
$mytree->makeAdSelBox('title', $GLOBALS['xoopsModuleConfig']['adslight_csortorder']);
echo '<br>';
echo '<br></fieldset><br>';

xoops_cp_footer();
