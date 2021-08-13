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
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'list');
xoops_cp_header();
//loadModuleAdminMenu(3, '');
$adminObject->displayNavigation(basename(__FILE__));
echo '<br><br>';
global $xoopsDB;
$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_categories'));
[$cat_row] = $xoopsDB->fetchRow($countresult);
$cat_rows = $cat_row;

if ('0' == $cat_rows) {
    echo _MI_ADSLIGHT_MUST_ADD_CAT;
} else {
    //$permtoset= isset($_POST['permtoset']) ? (int)$_POST['permtoset'] : 1;
    $permtoset                = Request::getInt('permtoset', 1, 'POST');
    $selected                 = [
        '',
        '',
        '',
    ];
    $selected[$permtoset - 1] = ' selected';
    echo "<form method='post' name='jselperm' action='groupperms.php'><table border=0><tr><td><select name='permtoset' onChange='document.jselperm.submit()'><option value='1'"
         . $selected[0]
         . '>'
         . _MI_ADSLIGHT_VIEWFORM
         . "</option><option value='2'"
         . $selected[1]
         . '>'
         . _MI_ADSLIGHT_SUBMITFORM
         . "</option><option value='3'"
         . $selected[2]
         . '>'
         . _MI_ADSLIGHT_PREMIUM
         . '</option></select></td><td></tr></table></form>';
    $module_id = $xoopsModule->getVar('mid');

    switch ($permtoset) {
        case 1:
            $title_of_form = _MI_ADSLIGHT_VIEWFORM;
            $perm_name     = 'adslight_view';
            $perm_desc     = _MI_ADSLIGHT_VIEWFORM_DESC;
            break;
        case 2:
            $title_of_form = _MI_ADSLIGHT_SUBMITFORM;
            $perm_name     = 'adslight_submit';
            $perm_desc     = _MI_ADSLIGHT_SUBMITFORM_DESC;
            break;
        case 3:
            $title_of_form = _MI_ADSLIGHT_PREMIUM;
            $perm_name     = 'adslight_premium';
            $perm_desc     = _MI_ADSLIGHT_PREMIUM_DESC;
            break;
    }

    $permform = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php');
    $cattree  = new Adslight\Tree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $allcats  = $cattree->getCategoryList();
    foreach ($allcats as $cid => $category) {
        $permform->addItem($cid, $category['title'], $category['pid']);
    }
    echo $permform->render();
    echo "<br><br><br><br>\n";
    unset($permform);
}
xoops_cp_footer();
