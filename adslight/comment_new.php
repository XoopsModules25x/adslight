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

include '../../mainfile.php';

$mydirname = basename( dirname( __FILE__ ) ) ;

$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
	// Get link title
	$sql = "SELECT title FROM " . $xoopsDB->prefix("adslight_listing") . " WHERE usid=" . $com_itemid . "";
	$result = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($result);
    $com_replytitle = $row['title'];
    include XOOPS_ROOT_PATH.'/include/comment_new.php';
}