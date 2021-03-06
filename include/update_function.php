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

/**
 * @param XoopsObject $xoopsModule
 *
 * @return bool
 */
function xoops_module_update_adslight(\XoopsObject $xoopsModule)
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
    foreach ($template_list as $k => $v) {
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
