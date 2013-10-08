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

if (file_exists('mainfile.php')) {
    include('mainfile.php');
} elseif (file_exists('../mainfile.php')) {
    include('../mainfile.php');
} else {
    include('../../mainfile.php');
}
include(XOOPS_ROOT_PATH . '/header.php');

$xoopsTpl->assign('xoops_showrblock', 1); // 1 = Avec blocs de droite - 0 = Sans blocs de droite
$xoopsTpl->assign('xoops_showlblock', 1); // 1 = Avec blocs de gauche - 0 = Sans blocs de gauche
$xoopsTpl->assign('xoops_pagetitle', _MN_ADSLIGHT_ERROR404);
$xoTheme->addMeta('meta', 'robots', 'noindex, nofollow');

echo _MN_ADSLIGHT_ERROR404_TEXT;

include(XOOPS_ROOT_PATH . "/footer.php");