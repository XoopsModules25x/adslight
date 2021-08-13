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

use Xmf\Request;
use XoopsModules\Adslight;

/**
 * Xoops header ...
 */
require_once \dirname(__DIR__, 2) . '/mainfile.php';
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
        $helper->redirect('view_photos.php?uid=' . $xoopsUser->getVar('uid'), 15, _ADSLIGHT_NOCACHACA);
    }
}

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
