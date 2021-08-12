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

if (file_exists(__DIR__ . '/mainfile.php')) {
    require_once __DIR__ . '/mainfile.php';
} elseif (file_exists(__DIR__ . '/../mainfile.php')) {
    require_once dirname(__DIR__) . '/mainfile.php';
} else {
    require_once dirname(__DIR__, 2) . '/mainfile.php';
}
require_once XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsTpl']->assign('xoops_showrblock', 1); // 1 = Avec blocs de droite - 0 = Sans blocs de droite
$GLOBALS['xoopsTpl']->assign('xoops_showlblock', 1); // 1 = Avec blocs de gauche - 0 = Sans blocs de gauche
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', _MN_ADSLIGHT_ERROR404);
$GLOBALS['xoTheme']->addMeta('meta', 'robots', 'noindex, nofollow');

echo _MN_ADSLIGHT_ERROR404_TEXT;

require_once XOOPS_ROOT_PATH . '/footer.php';
