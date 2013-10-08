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

/**
 * Xoops header
 */
include_once("../../mainfile.php");
$xoopsOption['template_main'] = "adslight_view_photos.html";
include_once("../../header.php");

/**
 * Module classes
 */
include("class/pictures.php");
if ( isset($_GET['lid']))
        {
        $lid = $_GET['lid'];
        } else {
    
        header("Location: ".XOOPS_URL."/modules/adslight/index.php");
    }
/**
 * Is a member looking ? 
 */
if (!empty($xoopsUser)){
    /**
     * If no $_GET['uid'] then redirect to own 
     */
    if ( isset($_GET['uid']))
        {
        $uid = $_GET['uid'];
        } else {
    
        header("Location: ".XOOPS_URL."/modules/adslight/index.php");
    }


/**
 * Is the user the owner of the album ? 
 */

$isOwner = ($xoopsUser->getVar('uid')==$_GET['uid'])?true:false;

$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler =& xoops_gethandler('groupperm');

if (isset($_POST['item_id'])) {
    $perm_itemid = intval($_POST['item_id']);
} else {
    $perm_itemid = 0;
}

				//If no access
if (!$gperm_handler->checkRight("adslight_premium", $perm_itemid, $groups, $module_id)) {
$permit = "0";
	} else {
$permit = "1";
}

/**
 * If it is an anonym
 */
} else {
    if ( isset($_GET['uid']))
    {
    $uid = $_GET['uid'];
    } else {
    header("Location: ".XOOPS_URL."/modules/adslight/index.php");
    $isOwner = false;
    }
}

/**
 * Filter for search pictures in database
 */    
$criteria_lid = new criteria('lid',$lid);
$criteria_uid = new criteria('uid',$uid);
/**
 * Creating a factory of pictures
 */  
$album_factory      = new Xoopsjlm_picturesHandler($xoopsDB);


/**
 * Fetch pictures from the factory
 */  
$pictures_object_array = $album_factory->getObjects($criteria_lid,$criteria_uid);

/**
 * How many pictures are on the user album
 */  
$pictures_number = $album_factory->getCount($criteria_lid,$criteria_uid);

/**
 * If there is no pictures in the album
 */  
if ($pictures_number==0){
        $nopicturesyet = _ADSLIGHT_NOTHINGYET;
        $xoopsTpl->assign('lang_nopicyet',$nopicturesyet);
} else {

    /**
     * Lets populate an array with the data from the pictures
     */  
    $i = 0;
    foreach ($pictures_object_array as $picture){
        $pictures_array[$i]['url']      = $picture->getVar("url","s");
        $pictures_array[$i]['desc']     = $picture->getVar("title","s");
        $pictures_array[$i]['cod_img']  = $picture->getVar("cod_img","s");
        $pictures_array[$i]['lid']      = $picture->getVar("lid","s");
        $xoopsTpl->assign('pics_array', $pictures_array);

    $i++;
    }
}

/**
 * Show the form if it is the owner and he can still upload pictures
 */  
if (!empty($xoopsUser)){
        if ($isOwner && $xoopsModuleConfig["adslight_nb_pict"]>$pictures_number){
            $maxfilebytes = $xoopsModuleConfig["adslight_maxfilesize"];
           $album_factory->renderFormSubmit($uid,$lid,$maxfilebytes,$xoopsTpl);
        }
}

/**
 * Let's get the user name of the owner of the album
 */ 
$owner = new XoopsUser();
$identifier = $owner->getUnameFromId($uid);

/**
 * Adding to the module js and css of the lightbox and new ones
 */ 

if ($xoopsModuleConfig["adslight_lightbox"] == 1) {

$header_lightbox = '<script type="text/javascript" src="lightbox/js/prototype.js"></script>
<script type="text/javascript" src="lightbox/js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="include/adslight.css" type="text/css" media="screen" />
<link rel="stylesheet" href="lightbox/css/lightbox.css" type="text/css" media="screen" />';

} else {

$header_lightbox = '<link rel="stylesheet" href="style/galery.css" type="text/css" media="screen" />';
}

/**
 * Assigning smarty variables
 */  


$sql = "SELECT title FROM ".$xoopsDB->prefix("adslight_listing")." where lid=".$lid." and valid='Yes'";
$result=$xoopsDB->query($sql);
while(list($title) = $xoopsDB->fetchRow($result)) {
		$xoopsTpl->assign('lang_gtitle',"<a href='viewads.php?lid=".$lid."'>".$title."</a>");
		$xoopsTpl->assign('lang_showcase',_ADSLIGHT_SHOWCASE);
	}



$xoopsTpl->assign('lang_not_premium', sprintf(_ADSLIGHT_BMCANHAVE,$xoopsModuleConfig["adslight_not_premium"]));

$xoopsTpl->assign('lang_no_prem_nb', sprintf(_ADSLIGHT_PREMYOUHAVE,$pictures_number));

$upgrade = "<a href=\"premium.php\"><strong> "._ADSLIGHT_UPGRADE_NOW."</strong></a>";
$xoopsTpl->assign('lang_upgrade_now',$upgrade);



$xoopsTpl->assign('lang_max_nb_pict', sprintf(_ADSLIGHT_YOUCANHAVE,$xoopsModuleConfig["adslight_nb_pict"]));
$xoopsTpl->assign('lang_nb_pict', sprintf(_ADSLIGHT_YOUHAVE,$pictures_number));

$xoopsTpl->assign('lang_albumtitle',sprintf(_ADSLIGHT_ALBUMTITLE,"<a href=".XOOPS_URL."/userinfo.php?uid=".addslashes($uid).">".$identifier."</a>"));

$xoopsTpl->assign('path_uploads',$xoopsModuleConfig['adslight_link_upload']);

$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name'). " - ".$identifier."'s album");

$xoopsTpl->assign('nome_modulo', $xoopsModule->getVar('name'));

$xoopsTpl->assign('lang_delete', _ADSLIGHT_DELETE);
$xoopsTpl->assign('lang_editdesc', _ADSLIGHT_EDITDESC);
$xoopsTpl->assign('isOwner',$isOwner);
$xoopsTpl->assign('permit',$permit);
$xoopsTpl->assign('xoops_module_header', $header_lightbox);

/**
 * Check if using Xoops or XoopsCube (by jlm69)
 */

$xCube=false;
if(preg_match("/^XOOPS Cube/",XOOPS_VERSION)) // XOOPS Cube 2.1x
{
$xCube=true;
}

/**
 * Verify Ticket (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.
 * If your site is Xoops it uses xoopsSecurity for the token.
 */

if ($xCube) {
$xoopsTpl->assign('token',$GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ ));
$xoopsTpl->assign('xcube', '1');
} else {
$xoopsTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());
$xoopsTpl->assign('xcube', '');
}


/**
 * Adding the comment system
 */ 
include XOOPS_ROOT_PATH.'/include/comment_view.php';

/**
 * Closing the page
 */ 
include("../../footer.php");