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
include_once __DIR__ . '/admin_header.php';

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'liste';
}

$mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

global $mytree, $xoopsDB, $xoopsModuleConfig, $moduleDirName;
include_once __DIR__ . '/header.php';
xoops_cp_header();
//loadModuleAdminMenu(1, "");

echo "<fieldset style='padding: 20px;'><legend style='font-weight: bold; color: #FF7300;'>" . _AM_ADSLIGHT_GESTCAT . ' </legend>';
echo "<p align=\"left\"><button name=\"buttonName\" type=\"button\" onclick=\"document.location.href='category.php?op=AdsNewCat&amp;cid=0';\">" . _AM_ADSLIGHT_ADDCATPRINC . '</button></p>';
$mytree->makeAdSelBox('title', '' . $xoopsModuleConfig['adslight_csortorder'] . '');
echo '<br>';
echo '<br></fieldset><br>';

xoops_cp_footer();
