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

require_once dirname(__DIR__, 2) . '/mainfile.php';

$moduleDirName = basename(__DIR__);

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
$com_itemid = $com_itemid;
if ($com_itemid > 0) {
    // Get link title
    $sql            = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid={$com_itemid}";
    $result         = $xoopsDB->query($sql);
    $row            = $xoopsDB->fetchArray($result);
    $com_replytitle = $row['title'];
    require_once XOOPS_ROOT_PATH . '/include/comment_new.php';
}
