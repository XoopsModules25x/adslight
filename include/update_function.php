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

/**
 * @param XoopsObject $xoopsModule
 */
function xoops_module_update_adslight(\XoopsObject $xoopsModule): bool
{
    global $xoopsDB;

    $sql = 'ALTER TABLE `' . $xoopsDB->prefix('adslight_listing') . "` MODIFY `price` DECIMAL(20,2) NOT NULL DEFAULT '0.00' AFTER `tel` ;";
    $xoopsDB->query($sql);

    $sql = 'ALTER TABLE `' . $xoopsDB->prefix('adslight_listing') . "` MODIFY `photo` VARCHAR(100) NOT NULL DEFAULT '0';";
    $xoopsDB->query($sql);

    // remove old html template files
    $template_directory = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/templates/';
    $template_list      = array_diff(scandir($template_directory, SCANDIR_SORT_NONE), [
        '..',
        '.',
    ]);
    foreach ($template_list as $v) {
        $fileinfo = new \SplFileInfo($template_directory . $v);
        if ('html' === $fileinfo->getExtension()
            && 'index.html' !== $fileinfo->getFilename()) {
            @unlink($template_directory . $v);
        }
    }

    xoops_load('xoopsfile');

    //remove /images directory
    $imagesDirectory = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/images/';
    $folderHandler   = XoopsFile::getHandler('folder', $imagesDirectory);
    $folderHandler->delete($imagesDirectory);

    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return true;
}
