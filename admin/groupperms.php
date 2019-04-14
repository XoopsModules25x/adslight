<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops

        Redesigned and ameliorate By Luc Bizet user at www.frxoops.org
        Started with the Classifieds module and made MANY changes
        Website : http://www.luc-bizet.fr
        Contact : adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller
 Author Website : pascal.e-xoops@perso-search.com
 Licence Type   : GPL
-------------------------------------------------------------------------
*/

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/admin_header.php';
//require_once XOOPS_ROOT_PATH."/modules/adslight/class/classifiedstree.php";

$op = Request::getString('op', 'liste');
xoops_cp_header();
//loadModuleAdminMenu(3, '');
$adminObject->displayNavigation(basename(__FILE__));
echo '<br><br>';
global $xoopsDB;
$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_categories'));
list($cat_row) = $xoopsDB->fetchRow($countresult);
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
    $cattree  = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $allcats  = $cattree->getCategoryList();
    foreach ($allcats as $cid => $category) {
        $permform->addItem($cid, $category['title'], $category['pid']);
    }
    echo $permform->render();
    echo "<br><br><br><br>\n";
    unset($permform);
}
xoops_cp_footer();
