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

include_once XOOPS_ROOT_PATH.'/modules/adslight/include/functions.php';
function xoops_module_update_adslight(&$xoopsModule) {

		$db =& XoopsDatabaseFactory::getDatabaseConnection();
		
		$sql = "ALTER TABLE `".$db->prefix('adslight_listing')."` MODIFY `price` decimal(20,2) NOT NULL default '0.00' AFTER `tel` ;";
		$db->query($sql);
	
		$sql = "ALTER TABLE `".$db->prefix('adslight_listing')."` MODIFY `photo` varchar(100) NOT NULL default '0';";
		$db->query($sql);
	
	
    return true;
}