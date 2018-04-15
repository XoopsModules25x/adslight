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
 * @package
 * @author       XOOPS Development Team
 */

use Xmf\Database\Tables;
use Xmf\Database\Migrate;

if ((!defined('XOOPS_ROOT_PATH'))
    || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !$GLOBALS['xoopsUser']->IsAdmin()) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * @param string $tablename
 *
 * @return bool
 */
function tableExists($tablename)
{
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) ? true : false;
}

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_adslight(\XoopsModule $module)
{
    /** @var Adslight\Helper $helper */
    /** @var Adslight\Utility $utility */
    $moduleDirName = basename(dirname(__DIR__));
    $helper       = Adslight\Helper::getInstance();
    $utility      = new Adslight\Utility();

    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);
    return $xoopsSuccess && $phpSuccess;
}

/**
 *
 * Performs tasks required during update of the module
 * @param XoopsModule $module {@link XoopsModule}
 * @param null        $previousVersion
 *
 * @return bool true if update successful, false if not
 */

function xoops_module_update_adslight(\XoopsModule $module, $previousVersion = null)
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $capsDirName   = strtoupper($moduleDirName);

    /** @var Adslight\Helper $helper */
    /** @var Adslight\Utility $utility */
    /** @var Adslight\Configurator $configurator */
    $helper  = Adslight\Helper::getInstance();
    $utility = new Adslight\Utility();
    $configurator = new Adslight\Configurator();

    if ($previousVersion < 230) {

        /*
                //rename column
                $tables     = new Tables();
                $migrate    = new Migrate($moduleDirName);
                $table      = 'adslight_categories';
                $column     = 'ordre';
                $newName    = 'cat_order';
                $attributes = "INT(5) NOT NULL DEFAULT '0'";
                if ($tables->useTable($table)) {
                    $tables->alterColumn($table, $column, $attributes, $newName);
                    if (!$tables->executeQueue()) {
                        echo '<br>' . _AM_ADSLIGHT_UPGRADEFAILED0 . ' ' . $migrate->getLastError();
                    }
                }

        */
        /*
                global $xoopsModule;
                // default Permission Settings ----------------------
                $moduleId = $xoopsModule->getVar('mid');
                //    $module_name = $xoopsModule->getVar('name');
                //    $module_dirname = $xoopsModule->getVar('dirname');
                //    $module_version = $xoopsModule->getVar('version');
                $grouppermHandler = xoops_getHandler('groupperm');
                // access rights ------------------------------------------
                $grouppermHandler->addRight($moduleDirName . '_premium', 1, XOOPS_GROUP_ADMIN, $moduleId);
                $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
                $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
                $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_USERS, $moduleId);
                $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
                $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);
        */

        /*
                $groups1 = [XOOPS_GROUP_ADMIN];
                $groups2 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS];
                $groups3 = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS];

                $permName1 = $moduleDirName . '_premium';
                $permName2 = $moduleDirName . '_submit';
                $permName3 = $moduleDirName . '_view';

                $rowsCount = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ''));

                $result = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('adslight_categories'));

                while (false !== ($myrow = $xoopsDB->fetchArray($result))) {

                    $categoryId = (int)($myrow['cid']);
                    $utility::saveCategoryPermissions($groups1, $categoryId, $permName1);
                    $utility::saveCategoryPermissions($groups2, $categoryId, $permName2);
                    $utility::saveCategoryPermissions($groups3, $categoryId, $permName3);
                }

        */

        //delete old HTML templates
        if (count($configurator->templateFolders) > 0) {
            foreach ($configurator->templateFolders as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), ['..', '.']);
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new \SplFileInfo($templateFolder . $v);
                        if ('html' === $fileInfo->getExtension() && 'index.html' !== $fileInfo->getFilename()) {
                            if (file_exists($templateFolder . $v)) {
                                unlink($templateFolder . $v);
                            }
                        }
                    }
                }
            }
        }

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator->oldFiles) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFiles) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
                if (is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //  ---  DELETE OLD FOLDERS ---------------
        xoops_load('XoopsFile');
        if (count($configurator->oldFolders) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFolders) as $i) {
                $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFolders[$i]);
                /** @var \XoopsObjectHandler $folderHandler */
                $folderHandler = XoopsFile::getHandler('folder', $tempFolder);
                $folderHandler->delete($tempFolder);
            }
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
            $file =  dirname(__DIR__) . '/assets/images/blank.png';
            foreach (array_keys($configurator->copyBlankFiles) as $i) {
                $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
                $utility::copyFile($file, $dest);
            }
        }

        /** @var XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');

        return $grouppermHandler->deleteByModule($module->getVar('mid'), 'item_read');
    }
}
