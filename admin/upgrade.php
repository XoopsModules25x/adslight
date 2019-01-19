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

use XoopsModules\Adslight;

$moduleDirName = basename(dirname(__DIR__));
$admin_lang    = '_AM_' . mb_strtoupper($moduleDirName);

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
xoops_cp_header();
require_once XOOPS_ROOT_PATH . '/modules/adslight/class/Utility.php';

if (($xoopsUser instanceof \XoopsUser)
    && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;

    if (!Adslight\Utility::checkTableExists($xoopsDB->prefix('adslight_pictures'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('adslight_pictures') . " (
              cod_img INT(11) NOT NULL AUTO_INCREMENT,
              title VARCHAR(255) NOT NULL DEFAULT '',
              date_added INT(10)NOT NULL DEFAULT '0',
              date_modified INT(10) NOT NULL DEFAULT '0',
              lid INT(11) NOT NULL DEFAULT '0',
              uid_owner VARCHAR(50) NOT NULL DEFAULT '',
              url VARCHAR(50) NOT NULL DEFAULT '',
              PRIMARY KEY  (cod_img),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . constant("{$admin_lang}_UPGRADEFAILED") . ' ' . constant("{$admin_lang}_UPGRADEFAILED1");
            ++$errors;
        }
    }

    // 3) Create the adslight_replies table if it does NOT exist
    if (!Adslight\Utility::checkTableExists($xoopsDB->prefix('adslight_replies'))) {
        $sql3 = 'CREATE TABLE ' . $xoopsDB->prefix('adslight_replies') . " (
      r_lid INT(11) NOT NULL AUTO_INCREMENT,
      lid INT(5) UNSIGNED NOT NULL DEFAULT '0',
      title VARCHAR(50) NOT NULL DEFAULT '',
      date INT(10) NOT NULL DEFAULT '0',
      submitter VARCHAR(60) NOT NULL DEFAULT '',
      message TEXT NULL,
      tele VARCHAR(20) NOT NULL DEFAULT '0',
      email VARCHAR(100) NOT NULL DEFAULT '',
      r_usid INT(11) NOT NULL DEFAULT '0',
      PRIMARY KEY  (r_lid)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql3)) {
            echo '<br>' . constant("{$admin_lang}_UPGRADEFAILED") . ' ' . constant("{$admin_lang}_UPGRADEFAILED1");
            ++$errors;
        }
    }

    //  Add the new fields to the categories table
    if (!Adslight\Utility::checkFieldExists('cat_desc', $xoopsDB->prefix('adslight_categories'))) {
        Adslight\Utility::addField("cat_desc text DEFAULT '' AFTER title", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Adslight\Utility::checkFieldExists('cat_keywords', $xoopsDB->prefix('adslight_categories'))) {
        Adslight\Utility::addField("cat_keywords text DEFAULT '' AFTER title", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Adslight\Utility::checkFieldExists('cat_moderate', $xoopsDB->prefix('adslight_categories'))) {
        Adslight\Utility::addField("cat_moderate int(5) DEFAULT '0' AFTER affprice", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Adslight\Utility::checkFieldExists('moderate_subcat', $xoopsDB->prefix('adslight_categories'))) {
        Adslight\Utility::addField("moderate_subcat int(5) DEFAULT '0' AFTER cat_moderate", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the listing table
    if (!Adslight\Utility::checkFieldExists('status', $xoopsDB->prefix('adslight_listing'))) {
        Adslight\Utility::addField("status INT(3) DEFAULT '0' NOT NULL AFTER title", $xoopsDB->prefix('adslight_listing'));
    }
    //  Add the new fields to the listing table
    if (!Adslight\Utility::checkFieldExists('remind', $xoopsDB->prefix('adslight_listing'))) {
        Adslight\Utility::addField("remind INT(11) DEFAULT '0' NOT NULL AFTER comments", $xoopsDB->prefix('adslight_listing'));
    }

    // At the end, if there was errors, show them or redirect user to the module's upgrade page
    if ($errors) {
        echo '<h1>' . constant("{$admin_lang}_UPGRADEFAILED") . '</h1>';
        echo '<br>' . constant("{$admin_lang}_UPGRADEFAILED0");
    } else {
        echo constant("{$admin_lang}_UPDATECOMPLETE") . " - <a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module={$moduleDirName}'>" . constant("{$admin_lang}_UPDATEMODULE") . '</a>';
    }
} else {
    printf("<h2>%s</h2>\n", constant("{$admin_lang}_UPGR_ACCESS_ERROR"));
}
xoops_cp_footer();
