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
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if( $ref == '' || strpos( $ref , XOOPS_URL.'/modules/system/admin.php' ) === 0 ) {
	/* module specific part */



	/* General part */

	// Keep the values of block's options when module is updated (by nobunobu)
	include dirname( __FILE__ ) . "/updateblock.inc.php" ;

}