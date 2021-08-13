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

require_once __DIR__ . '/header.php';

/**
 * Xoops Header
 */
require_once XOOPS_ROOT_PATH . '/header.php';

/**
 * Include modules classes
 */

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, _ADSLIGHT_TOKENEXPIRED);
}

/**
 * Receiving info from get parameters
 */
$cod_img = Request::getInt('cod_img', 0, 'POST');
$marker  = Request::getInt('marker', 0, 'POST');

if (1 == $marker) {
    /**
     * Creating the factory  loading the picture changing its caption
     */
    $title = Request::getString('caption', '', 'POST');

    $picture_factory = new Adslight\PicturesHandler($xoopsDB);
    $picture         = $picture_factory->create(false);
    $picture->load($cod_img);
    $picture->setVar('title', $title);

    /**
     * Verifying who's the owner to allow changes
     */
    $uid = $GLOBALS['xoopsUser']->getVar('uid');
    $lid = $picture->getVar('lid');
    if ($uid == $picture->getVar('uid_owner')) {
        if ($picture_factory->insert($picture)) {
            redirect_header("view_photos.php?lid={$lid}&uid={$uid}", 2, _ADSLIGHT_DESC_EDITED);
        } else {
            redirect_header("view_photos.php?lid={$lid}&uid={$uid}", 2, _ADSLIGHT_NOCACHACA);
        }
    }
}

/**
 * Creating the factory  and the criteria to edit the desc of the picture
 * The user must be the owner
 */
$album_factory = new Adslight\PicturesHandler($xoopsDB);
$criteria_img  = new \Criteria('cod_img', $cod_img);
$uid           = $GLOBALS['xoopsUser']->getVar('uid');
$criteria_uid  = new \Criteria('uid_owner', $uid);
$criteria      = new \CriteriaCompo($criteria_img);
$criteria->add($criteria_uid);

/**
 * Lets fetch the info of the pictures to be able to render the form
 * The user must be the owner
 */
if ($array_pict = $album_factory->getObjects($criteria)) {
    $caption = $array_pict[0]->getVar('title');
    $url     = $array_pict[0]->getVar('url');
}
$url = "{$GLOBALS['xoopsModuleConfig']['adslight_link_upload']}/thumbs/thumb_{$url}";
$album_factory->renderFormEdit($caption, $cod_img, $url);

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
