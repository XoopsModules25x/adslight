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
// comment callback functions
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

$moduleDirName = basename(dirname(__DIR__));
if (isset($usid)) {
    /**
     * @param $usid
     * @param $total_num
     */
    function adslight_com_update($usid, $total_num)
    {
        $db  = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql = 'UPDATE ' . $db->prefix('adslight_listing') . " SET comments = {$total_num} WHERE usid = {$usid}";
        $db->query($sql);
    }

    /**
     * @param $comment
     */
    function adslight_com_approve(&$comment)
    {
        // notification mail here
    }
}
