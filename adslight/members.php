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

include "header.php";
include(XOOPS_ROOT_PATH."/modules/adslight/include/functions.php");
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
global $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');


include_once XOOPS_ROOT_PATH."/modules/adslight/class/classifiedstree.php";
$mytree = new ClassifiedsTree($xoopsDB->prefix("adslight_categories"),"cid","pid");
$xoopsOption['template_main'] = "adslight_members.html";
include XOOPS_ROOT_PATH."/header.php";
include XOOPS_ROOT_PATH.'/include/comment_view.php';
	$lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;
	$usid = isset($_GET['usid']) ? intval($_GET['usid']) : 0;
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

$xoopsTpl->assign('permit', $permit);
if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}
	$xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM." ".$xoopsConfig['sitename']);
	$xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM );
	$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
	$xoopsTpl->assign('mydirname', $mydirname);
	$xoopsTpl->assign('comments_head', _ADSLIGHT_COMMENTS_HEAD);
	$xoopsTpl->assign('lang_user_rating', _ADSLIGHT_USER_RATING);
	$xoopsTpl->assign('lang_ratethisuser', _ADSLIGHT_RATETHISUSER);
	$xoopsTpl->assign('title_head', _ADSLIGHT_TITLE);
	$xoopsTpl->assign('date_head', _ADSLIGHT_ADDED_ON);
	$xoopsTpl->assign('views_head', _ADSLIGHT_VIEW2);
	$xoopsTpl->assign('replies_head', _ADSLIGHT_REPLIES);
	$xoopsTpl->assign('expires_head', _ADSLIGHT_EXPIRES_ON);
	$xoopsTpl->assign('all_user_listings', _ADSLIGHT_ALL_USER_LISTINGS);
	$xoopsTpl->assign('nav_main', '<a href="index.php">'._ADSLIGHT_MAIN.'</a>');
	$xoopsTpl->assign('mydirname', $mydirname);
	
	$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" href="'.XOOPS_URL.'/modules/adslight/style/adslight.css" type="text/css" media="all" />');
	
	$xoopsTpl->assign('adslight_active_menu', $xoopsModuleConfig['adslight_active_menu']);
	$xoopsTpl->assign('adslight_active_rss', $xoopsModuleConfig['adslight_active_rss']);
	$xoTheme -> addMeta ( 'meta', 'robots', 'noindex, nofollow');
	
	$show = 4;
	$min = isset($_GET['min']) ? intval($_GET['min']) : 0;
	if (!isset($max)) {
        $max = $min + $show;
	}
        $orderby = 'date ASC';
if ($xoopsModuleConfig["adslight_rate_user"] == '1') {
		$rate = '1';
	}else{
		$rate = '0';
	}
	$xoopsTpl->assign('rate', $rate);
if ($xoopsUser) {
	$member_usid = $xoopsUser->getVar("uid", "E");
if ($usid == $member_usid) {
	$istheirs = 1;
	
		} else {
	$istheirs = '';
	
			}
		}
		
$cat_perms = "";
$categories = adslight_MygetItemIds("adslight_view");
if(is_array($categories) && count($categories) > 0) {
	$cat_perms .= ' AND cid IN ('.implode(',', $categories).') ';
}


if ($istheirs= 1) {

$countresult=$xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_listing")." where usid=".mysql_real_escape_string($usid)." AND valid='Yes' $cat_perms");
		list($trow) = $xoopsDB->fetchRow($countresult);



$sql="select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, date, email, submitter, usid, town, country, contactby, premium, valid, photo, hits, item_rating, item_votes, user_rating, user_votes, comments FROM ".$xoopsDB->prefix("adslight_listing")." WHERE usid = ".mysql_real_escape_string($usid)." AND valid='Yes' $cat_perms ORDER BY $orderby";
$result=$xoopsDB->query($sql,$show,$min);
    } else {


$countresult=$xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_listing")." where usid=".mysql_real_escape_string($usid)." AND valid='Yes' AND status!='1' $cat_perms");
		list($trow) = $xoopsDB->fetchRow($countresult);


$sql="select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, date, email, submitter, usid, town, country, contactby, premium, valid, photo, hits, item_rating, item_votes, user_rating, user_votes, comments FROM ".$xoopsDB->prefix("adslight_listing")." WHERE usid = ".mysql_real_escape_string($usid)." AND valid='Yes' AND status!='1' $cat_perms ORDER BY $orderby";
$result=$xoopsDB->query($sql,$show,$min);
}


$trows = $trow;
		$pagenav = '';
		if ($trows > "0") {
		$xoopsTpl->assign('min', $min);
	$rank = 1;


	if ($trows > "1") {
	$xoopsTpl->assign('show_nav', true);
        $xoopsTpl->assign('lang_sortby', _ADSLIGHT_SORTBY);
        $xoopsTpl->assign('lang_title', _ADSLIGHT_TITLE);
	$xoopsTpl->assign('lang_titleatoz', _ADSLIGHT_TITLEATOZ);
	$xoopsTpl->assign('lang_titleztoa', _ADSLIGHT_TITLEZTOA);
        $xoopsTpl->assign('lang_date', _ADSLIGHT_DATE);
	$xoopsTpl->assign('lang_dateold', _ADSLIGHT_DATEOLD);
	$xoopsTpl->assign('lang_datenew', _ADSLIGHT_DATENEW);
        $xoopsTpl->assign('lang_popularity', _ADSLIGHT_POPULARITY);
	$xoopsTpl->assign('lang_popularityleast', _ADSLIGHT_POPULARITYLTOM);
	$xoopsTpl->assign('lang_popularitymost', _ADSLIGHT_POPULARITYMTOL);
     
		}
while(list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $date, $email, $submitter, $usid, $town, $country, $contactby, $premium, $valid, $photo, $hits, $item_rating, $item_votes, $user_rating, $user_votes, $comments) = $xoopsDB->fetchRow($result)) {


		$newitem = '';
		$newcount = $xoopsModuleConfig['adslight_countday'];
		$startdate = (time()-(86400 * $newcount));
		if ($startdate < $date) {
		$newitem = '<img src="'.XOOPS_URL.'/modules/adslight/images/newred.gif" alt="New" />';
			}




if ($status == 0) {
$status_is = _ADSLIGHT_ACTIVE;
	}
if ($status == 1) {
$status_is = _ADSLIGHT_INACTIVE;
	}
if ($status == 2) {
$status_is = _ADSLIGHT_SOLD;
	}
	$countresult=$xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_replies")." where lid=".mysql_real_escape_string($lid)."");
		list($rrow) = $xoopsDB->fetchRow($countresult);
		$rrows = $rrow;
	$xoopsTpl->assign('reply_count', $rrows);

	$result2=$xoopsDB->query("select r_lid, lid, date, submitter, message, email, r_usid FROM ".$xoopsDB->prefix("adslight_replies")." where lid =".mysql_real_escape_string($lid)."");
	list($r_lid, $rlid, $rdate, $rsubmitter, $message, $remail, $r_usid )=$xoopsDB->fetchRow($result2);


	if ($isadmin) {
	$adminlink = "<a href='".XOOPS_URL."/modules/adslight/admin/validate_ads.php?op=ModifyAds&amp;lid=".$lid."'><img src='" . $pathIcon16."/edit.png' border=0 alt=\""._ADSLIGHT_MODADMIN."\" /></a>";
	$xoopsTpl->assign('isadmin', $isadmin);
	} else {
		$adminlink = '';
	}
    $modify_link = '';
    if ($xoopsUser) {
        $member_usid = $xoopsUser->getVar("uid", "E");
        if ($usid == $member_usid) {
            $istheirs = true;
            $xoopsTpl->assign('istheirs', $istheirs);
            $modify_link = "<a href='modify.php?op=ModAd&amp;lid=" . $lid . "'><img src='" . $pathIcon16 . "/edit.png'  border=0 alt=\"" . _ADSLIGHT_MODADMIN . "\" /></a>";
        } else {
            $istheirs = false;
            $xoopsTpl->assign('istheirs', '');
        }
    }

		$xoopsTpl->assign('submitter',$submitter);
		$xoopsTpl->assign('usid', $usid);
		$xoopsTpl->assign('read', "$hits "._ADSLIGHT_VIEW2);
		$xoopsTpl->assign('rating', number_format($user_rating, 2));
		$xoopsTpl->assign('status_head', _ADSLIGHT_STATUS);
//  For US currency with 2 numbers after the decimal comment out if you dont want 2 numbers after decimal
		$price = number_format($price, 2, ",", " ");
//  For other countries uncomment the below line and comment out the above line
//		$price = number_format($price);
		$xoopsTpl->assign('price', '<strong>'._ADSLIGHT_PRICE . "</strong>$price".$xoopsModuleConfig["adslight_money"]." - $typeprice");
		$xoopsTpl->assign('price_head', _ADSLIGHT_PRICE );
		$xoopsTpl->assign('money_sign', "".$xoopsModuleConfig["adslight_money"]."");
		$xoopsTpl->assign('price_typeprice', $typeprice);
		$xoopsTpl->assign('local_town', "$town");
		$xoopsTpl->assign('local_country', "$country");
		$xoopsTpl->assign('local_head', _ADSLIGHT_LOCAL2);
        $xoopsTpl->assign('edit_ad', _ADSLIGHT_EDIT);

		$usid = addslashes($usid);
if ($user_votes == 1) {
		$votestring = _ADSLIGHT_ONEVOTE;
		} else {
		$votestring = sprintf(_ADSLIGHT_NUMVOTES,$user_votes);
			}
		$xoopsTpl->assign('user_votes', $votestring);	
		$date2 = $date + ($expire*86400);
		$date = formatTimestamp($date,"s");
		$date2 = formatTimestamp($date2,"s");
	$path = $mytree->getPathFromId($cid, "title");
	$path = substr($path, 1);
	$path = str_replace("/"," - ",$path);
	if ($rrows >= 1) {
		$view_now = "<a href='replies.php?lid=".$lid."'>"._ADSLIGHT_VIEWNOW."</a>";
	}else{
		$view_now = '';
	}
		$sold = "";
		if ($status == 2) {
		$sold = _ADSLIGHT_RESERVEDMEMBER;
		}


			$xoopsTpl->assign('xoops_pagetitle',""._ADSLIGHT_ALL_USER_LISTINGS." ".$submitter."");
$updir = $xoopsModuleConfig['adslight_link_upload'];
	$sql = "select cod_img, lid, uid_owner, url from ".$xoopsDB->prefix("adslight_pictures")." where  uid_owner=".mysql_real_escape_string($usid)." and lid=".mysql_real_escape_string($lid)." order by date_added ASC limit 1";
		$resultp = $xoopsDB->query($sql);
		while(list($cod_img, $pic_lid, $uid_owner, $url)=$xoopsDB->fetchRow($resultp)) {
		if ($photo) {
			$photo = "<a href='viewads.php?lid=".$lid."'><img class=\"thumb\" src=\"$updir/thumbs/thumb_$url\" align=\"left\" width=\"100px\" alt=\"$title\" /></a>";
				}
			}
		$no_photo = "<a href='viewads.php?lid=".$lid."'><img class=\"thumb\" src=\"images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"$title\" /></a>";

	$xoopsTpl->append('items', array('id' => $lid, 'cid' => $cid, 'title' => $myts->htmlSpecialChars($title), 'status' => $myts->htmlSpecialChars($status_is), 'expire' => $myts->htmlSpecialChars($expire), 'type' => $myts->htmlSpecialChars($type), 'desctext' => $myts->displayTarea($desctext), 'tel' => $myts->htmlSpecialChars($tel), 'price' => $myts->htmlSpecialChars($price), 'typeprice' => $myts->htmlSpecialChars($typeprice), 'date' => $myts->htmlSpecialChars($date), 'email' => $myts->htmlSpecialChars($email), 'submitter' => $myts->htmlSpecialChars($submitter), 'usid' => $myts->htmlSpecialChars($usid), 'town' => $myts->htmlSpecialChars($town), 'country' => $myts->htmlSpecialChars($country), 'contactby' => $myts->htmlSpecialChars($contactby), 'premium' => $myts->htmlSpecialChars($premium), 'valid' => $myts->htmlSpecialChars($valid), 'hits' => $hits, 'rlid' => $myts->htmlSpecialChars($rlid), 'rdate' => $myts->htmlSpecialChars($rdate), 'rsubmitter' => $myts->htmlSpecialChars($rsubmitter), 'message' => $myts->htmlSpecialChars($message), 'remail' => $myts->htmlSpecialChars($remail), 'rrows' => $rrows, 'expires' => $myts->htmlSpecialChars($date2), 'view_now' => $view_now, 'modify_link' => $modify_link, 'photo' => $photo, 'no_photo' => $no_photo, 'adminlink' => $adminlink, 'new' => $newitem, 'sold' => $sold));
}
		$usid = intval($_GET['usid']);
		
//Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if ($linkpages!=1 && $linkpages!=0) {
       $prev = $min - $show;
       if ($prev>=0) {
            $pagenav .= "<a href='members.php?usid=$usid&min=$prev&show=$show'><strong><u>&laquo;</u></strong></a> ";
        }
        $counter = 1;
        $currentpage = ($max / $show);
        while ( $counter<=$linkpages ) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<strong>($counter)</strong> ";
            } else {
                $pagenav .= "<a href='members.php?usid=$usid&min=$mintemp&show=$show'>$counter</a> ";
            }
            $counter++;
        }
        if ( $trows>$max ) {
            $pagenav .= "<a href='members.php?usid=$usid&min=$max&show=$show'>";
            $pagenav .= "<strong><u>&raquo;</u></strong></a>";
        		}$xoopsTpl->assign('nav_page', "<strong>"._ADSLIGHT_PAGES."</strong>&nbsp;&nbsp; $pagenav");
 		}
	}

include XOOPS_ROOT_PATH.'/footer.php';