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

$moduleDirName = basename(dirname(__DIR__));
$main_lang     = '_' . mb_strtoupper($moduleDirName);

/**
 * Xoops Header
 */
require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/criteria.php';

/**
 * Module classes
 */
require_once __DIR__ . '/class/pictures.php';

if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, _ADSLIGHT_TOKENEXPIRED);
}

/**
 * Receiving info from input parameters
 */
$cod_img = Request::getString('cod_img', '', 'POST');

/**
 * Creating the factory  and the criteria to delete the picture
 * The user must be the owner
 */
$album_factory = new PicturesHandler($xoopsDB);
$criteria_img  = new \Criteria('cod_img', $cod_img);
$uid           = $GLOBALS['xoopsUser']->getVar('uid');
$criteria_uid  = new \Criteria('uid_owner', $uid);
//$criteria_lid = new \Criteria ('lid',$lid);
$criteria = new \CriteriaCompo($criteria_img);
$criteria->add($criteria_uid);

$objects_array = $album_factory->getObjects($criteria);
$image_name    = $objects_array[0]->getVar('url');
/**
 * Try to delete
 */
if ($album_factory->deleteAll($criteria)) {
    $path_upload = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'];
    unlink("{$path_upload}/{$image_name}");
    unlink("{$path_upload}/thumbs/thumb_{$image_name}");
    unlink("{$path_upload}/midsize/resized_{$image_name}");

    $lid = Request::getInt('lid', 0, 'POST');

    $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET photo=photo-1 WHERE lid='{$lid}'");

    redirect_header("view_photos.php?lid={$lid}&uid={$uid}", 10, _ADSLIGHT_DELETED);
} else {
    redirect_header("view_photos.php?lid={$lid}&uid={$uid}", 10, _ADSLIGHT_NOCACHACA);
}

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
