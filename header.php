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
/////////////////////////////////////
// AdsLight UrlRewrite By Nikita   //
// http://www.aideordi.com         //
/////////////////////////////////////

use Xmf\Module\Admin;

$moduleDirName = basename(__DIR__);
require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/class/utility.php';

if ($GLOBALS['xoopsModuleConfig']['active_rewriteurl'] > 0) {
    require_once __DIR__ . '/seo_url.php';
}

$pathIcon16 = Admin::iconUrl('', 16);

$myts = MyTextSanitizer::getInstance();

xoops_loadLanguage('main', $moduleDirName);
