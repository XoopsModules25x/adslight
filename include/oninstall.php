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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

use XoopsModules\Adslight;

include dirname(__DIR__) . '/preloads/autoloader.php';

/**
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_adslight(\XoopsModule $module)
{
    require_once __DIR__ . '/common.php';
    /** @var \XoopsModules\Adslight\Utility $utility */
    $utility = new \XoopsModules\Adslight\Utility();
    //check for minimum XOOPS version
    $xoopsSuccess = $utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $moduleTables = &$module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

/**
 * Performs tasks required during installation of the module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_adslight(\XoopsModule $module)
{
    require_once dirname(__DIR__, 3) . '/mainfile.php';

    global $xoopsDB;
    //    $moduleDirName = $module->getVar('dirname');
    $moduleDirName = basename(dirname(__DIR__));
    xoops_loadLanguage('admin', $moduleDirName);
    xoops_loadLanguage('modinfo', $moduleDirName);

    //    $configurator = require_once __DIR__   . '/config.php';
    $configurator = new Adslight\Common\Configurator();
    /** @var \XoopsModules\Adslight\Utility $utility */
    $utility = new \XoopsModules\Adslight\Utility();

    /*

    // default Permission Settings ----------------------
    $moduleId = $module->getVar('mid');
    //    $module_name = $module->getVar('name');
    //    $module_dirname = $module->getVar('dirname');
    //    $module_version = $module->getVar('version');
    $grouppermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $grouppermHandler->addRight($moduleDirName . '_premium', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_USERS, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    $result8 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_categories'));
    $rowsCount = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ''));



    $utility::saveCategoryPermissions($groups, $categoryId, $permName);
*/

    $groups1 = [XOOPS_GROUP_ADMIN];
    $groups2 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS];
    $groups3 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS];

    $permName1 = $moduleDirName . '_premium';
    $permName2 = $moduleDirName . '_submit';
    $permName3 = $moduleDirName . '_view';

    $rowsCount = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ' '));

    $result = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('adslight_categories'));

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $categoryId = (int)$myrow['cid'];
        $utility::saveCategoryPermissions($groups1, $categoryId, $permName1);
        $utility::saveCategoryPermissions($groups2, $categoryId, $permName2);
        $utility::saveCategoryPermissions($groups3, $categoryId, $permName3);
    }

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }

    return true;
}
