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

include("header.php");

if ( isset($_GET['cod_img']) ) {
	$cod_img = intval($_GET['cod_img']);
} else {
	redirect_header("index.php",1, _ADSLIGHT_VALIDATE_FAILED);
}
xoops_header();

global $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsDB, $xoops_footer, $xoopsLogger;
$currenttheme = getTheme();


$result=$xoopsDB->query("select url FROM ".$xoopsDB->prefix("adslight_picture")." WHERE cod_img = ".mysql_real_escape_string($cod_img)."");
$recordexist = $xoopsDB->getRowsNum($result);


if ($recordexist)
{     
	list($url)=$xoopsDB->fetchRow($result);
	echo "<br /><br /><center><img class=\"thumb\" src=\"photo/$url\" border=0></center>";
}	


echo "<table><tr><td><center><a href=#  onClick='window.close()'>"._ADSLIGHT_CLOSEF."</a></center></td></tr></table>";


xoops_footer();