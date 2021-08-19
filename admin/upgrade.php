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

use XoopsModules\Adslight\{
    Utility
};

$moduleDirName = \basename(\dirname(__DIR__));
$admin_lang    = '_AM_' . mb_strtoupper($moduleDirName);

require \dirname(__DIR__, 3) . '/include/cp_header.php';
xoops_cp_header();

if (($xoopsUser instanceof \XoopsUser)
    && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;
    if (!Utility::checkTableExists($xoopsDB->prefix('adslight_pictures'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('adslight_pictures') . " (
              cod_img INT(11) NOT NULL AUTO_INCREMENT,
              title VARCHAR(255) NOT NULL DEFAULT '',
              date_created INT(10)NOT NULL DEFAULT '0',
              date_updated INT(10) NOT NULL DEFAULT '0',
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
    if (!Utility::checkTableExists($xoopsDB->prefix('adslight_replies'))) {
        $sql3 = 'CREATE TABLE ' . $xoopsDB->prefix('adslight_replies') . " (
      r_lid INT(11) NOT NULL AUTO_INCREMENT,
      lid INT(5) UNSIGNED NOT NULL DEFAULT '0',
      title VARCHAR(50) NOT NULL DEFAULT '',
      date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
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
    if (!Utility::checkFieldExists('cat_desc', $xoopsDB->prefix('adslight_categories'))) {
        Utility::addField("cat_desc text DEFAULT '' AFTER title", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Utility::checkFieldExists('cat_keywords', $xoopsDB->prefix('adslight_categories'))) {
        Utility::addField("cat_keywords text DEFAULT '' AFTER title", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Utility::checkFieldExists('cat_moderate', $xoopsDB->prefix('adslight_categories'))) {
        Utility::addField("cat_moderate int(5) DEFAULT '0' AFTER affprice", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the categories table
    if (!Utility::checkFieldExists('moderate_subcat', $xoopsDB->prefix('adslight_categories'))) {
        Utility::addField("moderate_subcat int(5) DEFAULT '0' AFTER cat_moderate", $xoopsDB->prefix('adslight_categories'));
    }

    //  Add the new fields to the listing table
    if (!Utility::checkFieldExists('status', $xoopsDB->prefix('adslight_listing'))) {
        Utility::addField("status INT(3) DEFAULT '0' NOT NULL AFTER title", $xoopsDB->prefix('adslight_listing'));
    }
    //  Add the new fields to the listing table
    if (!Utility::checkFieldExists('remind', $xoopsDB->prefix('adslight_listing'))) {
        Utility::addField("remind INT(11) DEFAULT '0' NOT NULL AFTER comments", $xoopsDB->prefix('adslight_listing'));
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
