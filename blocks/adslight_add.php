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
//require_once dirname(__DIR__) . '/class/classifiedstree.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
/**
 * @return array
 */
function b_adslight_add()
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    xoops_loadLanguage('main', $moduleDirName);
    $xoopsTree   = new \XoopsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $jump = XOOPS_URL . '/modules/adslight/addlisting.php?cid=';
    ob_start();
    $xoopsTree->makeMySelBox('title', 'title', 0, 1, 'pid', "location=\"{$jump}\"+this.options[this.selectedIndex].value");
    $block['selectbox'] = ob_get_clean();

    return $block;
}
