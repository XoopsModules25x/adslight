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

/**
 * Prepares system prior to attempting to install module
 * @param XoopsModule $xoopsModule {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_adslight(XoopsModule $xoopsModule)
{
    $moduleDirName = basename(dirname(__DIR__));
    $className     = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($className)) {
        xoops_load('utility', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$className::checkXoopsVer($xoopsModule)) {
        return false;
    }

    // check for minimum PHP version
    if (!$className::checkPhpVer($xoopsModule)) {
        return false;
    }

    $mod_tables = $xoopsModule->getInfo('tables');
    foreach ($mod_tables as $table) {
        $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
    }

    return true;
}

/**
 *
 * Performs tasks required during installation of the module
 * @param XoopsModule $xoopsModule {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_adslight(XoopsModule $xoopsModule)
{
    require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

    global $xoopsDB;
    //    $moduleDirName = $xoopsModule->getVar('dirname');
    $moduleDirName = basename(dirname(__DIR__));
    xoops_loadLanguage('admin', $moduleDirName);
    xoops_loadLanguage('modinfo', $moduleDirName);

    $configurator = include __DIR__ . '/config.php';
    $classUtility = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($classUtility)) {
        xoops_load('utility', $moduleDirName);
    }

    /*

    // default Permission Settings ----------------------
    $moduleId = $xoopsModule->getVar('mid');
    //    $module_name = $xoopsModule->getVar('name');
    //    $module_dirname = $xoopsModule->getVar('dirname');
    //    $module_version = $xoopsModule->getVar('version');
    $gpermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $gpermHandler->addRight($moduleDirName . '_premium', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_USERS, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    $result8 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_categories'));
    $rowsCount = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ''));



    $classUtility::saveCategoryPermissions($groups, $categoryId, $permName);
*/

    $groups1 = [XOOPS_GROUP_ADMIN];
    $groups2 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS];
    $groups3 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS];

    $permName1 = $moduleDirName . '_premium';
    $permName2 = $moduleDirName . '_submit';
    $permName3 = $moduleDirName . '_view';

    $rowsCount = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ''));

    $result = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('adslight_categories'));

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $categoryId = (int)$myrow['cid'];
        $classUtility::saveCategoryPermissions($groups1, $categoryId, $permName1);
        $classUtility::saveCategoryPermissions($groups2, $categoryId, $permName2);
        $classUtility::saveCategoryPermissions($groups3, $categoryId, $permName3);
    }

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator['uploadFolders']) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator['uploadFolders']) as $i) {
            $classUtility::createFolder($configurator['uploadFolders'][$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator['copyFiles']) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator['copyFiles']) as $i) {
            $dest = $configurator['copyFiles'][$i] . '/blank.png';
            $classUtility::copyFile($file, $dest);
        }
    }

    return true;
}
