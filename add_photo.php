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

$moduleDirName = basename(dirname(__DIR__));
$main_lang     = '_' . mb_strtoupper($moduleDirName);

/**
 * Xoops header ...
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'adslight_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/**
 * Modules class includes
 */

/**
 * Factory of pictures created
 */
$album_factory = new Adslight\PicturesHandler($xoopsDB);

/**
 * Getting the title
 */
$title = Request::getString('caption', '', 'POST');
$lid   = Request::getInt('lid', 0, 'POST');
/**
 * Getting parameters defined in admin side
 */
$path_upload   = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'];
$pictwidth     = $GLOBALS['xoopsModuleConfig']['adslight_resized_width'];
$pictheight    = $GLOBALS['xoopsModuleConfig']['adslight_resized_height'];
$thumbwidth    = $GLOBALS['xoopsModuleConfig']['adslight_thumb_width'];
$thumbheight   = $GLOBALS['xoopsModuleConfig']['adslight_thumb_height'];
$maxfilebytes  = $GLOBALS['xoopsModuleConfig']['adslight_maxfilesize'];
$maxfileheight = $GLOBALS['xoopsModuleConfig']['adslight_max_orig_height'];
$maxfilewidth  = $GLOBALS['xoopsModuleConfig']['adslight_max_orig_width'];

/**
 * If we are receiving a file
 */
if ('sel_photo' === Request::getArray('xoops_upload_file', '', 'POST')[0]) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, _ADSLIGHT_TOKENEXPIRED);
    }

    /**
     * Try to upload picture resize it insert in database and then redirect to index
     */
    if ($album_factory->receivePicture($title, $path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes, $maxfilewidth, $maxfileheight)) {
        header('Location: ' . XOOPS_URL . "/modules/adslight/view_photos.php?lid={$lid}&uid=" . $GLOBALS['xoopsUser']->getVar('uid'));

        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET photo=photo+1 WHERE lid={$lid}");
    } else {
        redirect_header(XOOPS_URL . '/modules/adslight/view_photos.php?uid=' . $xoopsUser->getVar('uid'), 15, _ADSLIGHT_NOCACHACA);
    }
}

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
