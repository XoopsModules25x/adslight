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

include_once '../../../include/cp_header.php';

include_once $GLOBALS['xoops']->path( "/modules/adslight/include/gtickets.php");
include_once $GLOBALS['xoops']->path( "/modules/adslight/include/functions.php");
include_once $GLOBALS['xoops']->path( "/class/xoopsformloader.php" );
include_once $GLOBALS['xoops']->path( "/modules/adslight/class/classifiedstree.php");
include_once $GLOBALS['xoops']->path( "/modules/adslight/class/grouppermform.php");

if ( $xoopsUser ) {
	$xoopsModule = XoopsModule::getByDirname("adslight");
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) { 
		redirect_header(XOOPS_URL."/",3,_NOPERM);
		exit();
	}
} else {
	redirect_header(XOOPS_URL."/",3,_NOPERM);
	exit();
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname', 'e'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname', 'e'));
$myts =& MyTextSanitizer::getInstance();
