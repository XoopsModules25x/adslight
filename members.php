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

require_once __DIR__ . '/header.php';
//include XOOPS_ROOT_PATH . '/modules/adslight/class/utility.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
global $xoopsModule;
$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);
xoops_load('XoopsLocal');

require_once XOOPS_ROOT_PATH . '/modules/adslight/class/classifiedstree.php';
$mytree                                  = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
$GLOBALS['xoopsOption']['template_main'] = 'adslight_members.tpl';
include XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/include/comment_view.php';

$lid       = Request::getInt('lid', 0, 'GET');
$usid      = Request::getInt('usid', 0, 'GET');
$module_id = $xoopsModule->getVar('mid');
if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var XoopsGroupPermHandler $gpermHandler */
$gpermHandler = xoops_getHandler('groupperm');
$perm_itemid  = Request::getInt('item_id', 0, 'POST');

//If no access
$permit = (!$gpermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) ? '0' : '1';

$xoopsTpl->assign('permit', $permit);
$isadmin = (($GLOBALS['xoopsUser'] instanceof XoopsUser)
            && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) ? true : false;

$xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('mydirname', $moduleDirName);
$xoopsTpl->assign('comments_head', _ADSLIGHT_COMMENTS_HEAD);
$xoopsTpl->assign('lang_user_rating', _ADSLIGHT_USER_RATING);
$xoopsTpl->assign('lang_ratethisuser', _ADSLIGHT_RATETHISUSER);
$xoopsTpl->assign('title_head', _ADSLIGHT_TITLE);
$xoopsTpl->assign('date_head', _ADSLIGHT_ADDED_ON);
$xoopsTpl->assign('views_head', _ADSLIGHT_VIEW2);
$xoopsTpl->assign('replies_head', _ADSLIGHT_REPLIES);
$xoopsTpl->assign('expires_head', _ADSLIGHT_EXPIRES_ON);
$xoopsTpl->assign('all_user_listings', _ADSLIGHT_ALL_USER_LISTINGS);
$xoopsTpl->assign('nav_main', '<a href="index.php">' . _ADSLIGHT_MAIN . '</a>');
$xoopsTpl->assign('mydirname', $moduleDirName);
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >');

$xoopsTpl->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
$xoopsTpl->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);
$GLOBALS['xoTheme']->addMeta('meta', 'robots', 'noindex, nofollow');

$show = 4;
$min  = Request::getInt('min', 0, 'GET');
if (!isset($max)) {
    $max = $min + $show;
}
$orderby = 'date ASC';
$rate    = ('1' == $GLOBALS['xoopsModuleConfig']['adslight_rate_user']) ? '1' : '0';
$xoopsTpl->assign('rate', $rate);

if ($GLOBALS['xoopsUser']) {
    $member_usid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
    $istheirs    = ($usid == $member_usid) ? 1 : '';
}

$cat_perms  = '';
$categories = AdslightUtility::getMyItemIds('adslight_view');
if (is_array($categories) && count($categories) > 0) {
    $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
}

if (1 == $istheirs) {
    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid) . " AND valid='Yes' $cat_perms");
    list($trow) = $xoopsDB->fetchRow($countresult);

    $sql    = 'SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, date, email, submitter, usid, town, country, contactby, premium, valid, photo, hits, item_rating, item_votes, user_rating, user_votes, comments FROM '
              . $xoopsDB->prefix('adslight_listing')
              . ' WHERE usid = '
              . $xoopsDB->escape($usid)
              . " AND valid='Yes' $cat_perms ORDER BY $orderby";
    $result = $xoopsDB->query($sql, $show, $min);
} else {
    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid) . " AND valid='Yes' AND status!='1' $cat_perms");
    list($trow) = $xoopsDB->fetchRow($countresult);

    $sql    = 'SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, date, email, submitter, usid, town, country, contactby, premium, valid, photo, hits, item_rating, item_votes, user_rating, user_votes, comments FROM '
              . $xoopsDB->prefix('adslight_listing')
              . ' WHERE usid = '
              . $xoopsDB->escape($usid)
              . " AND valid='Yes' AND status!='1' $cat_perms ORDER BY $orderby";
    $result = $xoopsDB->query($sql, $show, $min);
}

$trows   = $trow;
$pagenav = '';
if ($trows > '0') {
    $xoopsTpl->assign('min', $min);
    $rank = 1;

    if ($trows > '1') {
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
    while (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $date, $email, $submitter, $usid, $town, $country, $contactby, $premium, $valid, $photo, $hits, $item_rating, $item_votes, $user_rating, $user_votes, $comments) = $xoopsDB->fetchRow($result)) {
        $newitem   = '';
        $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
        $startdate = (time() - (86400 * $newcount));
        if ($startdate < $date) {
            //@todo move "New" alt text to language file
            $newitem = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" alt="New" >';
        }

        if (0 == $status) {
            $status_is = _ADSLIGHT_ACTIVE;
        }
        if (1 == $status) {
            $status_is = _ADSLIGHT_INACTIVE;
        }
        if (2 == $status) {
            $status_is = _ADSLIGHT_SOLD;
        }
        $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_replies') . " WHERE lid='" . $xoopsDB->escape($lid) . "'");
        list($rrow) = $xoopsDB->fetchRow($countresult);
        $rrows = $rrow;
        $xoopsTpl->assign('reply_count', $rrows);

        $result2 = $xoopsDB->query('SELECT r_lid, lid, date, submitter, message, email, r_usid FROM ' . $xoopsDB->prefix('adslight_replies') . ' WHERE lid =' . $xoopsDB->escape($lid));
        list($r_lid, $rlid, $rdate, $rsubmitter, $message, $remail, $r_usid) = $xoopsDB->fetchRow($result2);

        if ($isadmin) {
            $adminlink = "<a href='" . XOOPS_URL . '/modules/adslight/admin/validate_ads.php?op=ModifyAds&amp;lid=' . $lid . "'><img src='" . $pathIcon16 . "/edit.png' border=0 alt=\"" . _ADSLIGHT_MODADMIN . '" ></a>';
            $xoopsTpl->assign('isadmin', $isadmin);
        } else {
            $adminlink = '';
        }
        $modify_link = '';
        if ($GLOBALS['xoopsUser'] instanceof XoopsUser) {
            $member_usid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
            if ($usid == $member_usid) {
                $istheirs = true;
                $xoopsTpl->assign('istheirs', $istheirs);
                $modify_link = "<a href='modify.php?op=ModAd&amp;lid=" . $lid . "'><img src='" . $pathIcon16 . "/edit.png'  border=0 alt=\"" . _ADSLIGHT_MODADMIN . '" ></a>';
            } else {
                $istheirs = false;
                $xoopsTpl->assign('istheirs', '');
            }
        }

        $xoopsTpl->assign('submitter', $submitter);
        $xoopsTpl->assign('usid', $usid);
        $xoopsTpl->assign('read', "$hits " . _ADSLIGHT_VIEW2);
        $xoopsTpl->assign('rating', number_format($user_rating, 2));
        $xoopsTpl->assign('status_head', _ADSLIGHT_STATUS);
        $tempXoopsLocal = new XoopsLocal;
        //  For US currency with 2 numbers after the decimal comment out if you dont want 2 numbers after decimal
        $price = $tempXoopsLocal->number_format($price, 2, ',', ' ');
        //  For other countries uncomment the below line and comment out the above line
        //      $price = $tempXoopsLocal->number_format($price);
        $xoopsTpl->assign('price', '<strong>' . _ADSLIGHT_PRICE . "</strong>$price" . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . " - $typeprice");
        $xoopsTpl->assign('price_head', _ADSLIGHT_PRICE);
        $xoopsTpl->assign('money_sign', '' . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol']);
        $xoopsTpl->assign('price_typeprice', $typeprice);
        $xoopsTpl->assign('local_town', "$town");
        $xoopsTpl->assign('local_country', "$country");
        $xoopsTpl->assign('local_head', _ADSLIGHT_LOCAL2);
        $xoopsTpl->assign('edit_ad', _ADSLIGHT_EDIT);

        $usid       = addslashes($usid);
        $votestring = (1 == $user_votes) ? _ADSLIGHT_ONEVOTE : sprintf(_ADSLIGHT_NUMVOTES, $user_votes);

        $xoopsTpl->assign('user_votes', $votestring);
        $date2 = $date + ($expire * 86400);
        $date  = formatTimestamp($date, 's');
        $date2 = formatTimestamp($date2, 's');
        $path  = $mytree->getPathFromId($cid, 'title');
        $path  = substr($path, 1);
        $path  = str_replace('/', ' - ', $path);
        if ($rrows >= 1) {
            $view_now = "<a href='replies.php?lid=" . $lid . "'>" . _ADSLIGHT_VIEWNOW . '</a>';
        } else {
            $view_now = '';
        }
        $sold = '';
        if (2 == $status) {
            $sold = _ADSLIGHT_RESERVEDMEMBER;
        }

        $xoopsTpl->assign('xoops_pagetitle', '' . _ADSLIGHT_ALL_USER_LISTINGS . ' ' . $submitter);
        $updir   = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
        $sql     = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE  uid_owner=' . $xoopsDB->escape($usid) . ' AND lid=' . $xoopsDB->escape($lid) . ' ORDER BY date_added ASC LIMIT 1';
        $resultp = $xoopsDB->query($sql);
        while (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp)) {
            if ($photo) {
                $photo = "<a href='viewads.php?lid=" . $lid . "'><img class=\"thumb\" src=\"$updir/thumbs/thumb_$url\" align=\"left\" width=\"100px\" alt=\"$title\" ></a>";
            }
        }
        $no_photo = "<a href='viewads.php?lid=" . $lid . "'><img class=\"thumb\" src=\"assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"$title\" ></a>";

        $xoopsTpl->append('items', [
            'id'          => $lid,
            'cid'         => $cid,
            'title'       => $myts->htmlSpecialChars($title),
            'status'      => $myts->htmlSpecialChars($status_is),
            'expire'      => $myts->htmlSpecialChars($expire),
            'type'        => $myts->htmlSpecialChars($type),
            'desctext'    => $myts->displayTarea($desctext),
            'tel'         => $myts->htmlSpecialChars($tel),
            'price'       => $myts->htmlSpecialChars($price),
            'typeprice'   => $myts->htmlSpecialChars($typeprice),
            'date'        => $myts->htmlSpecialChars($date),
            'email'       => $myts->htmlSpecialChars($email),
            'submitter'   => $myts->htmlSpecialChars($submitter),
            'usid'        => $myts->htmlSpecialChars($usid),
            'town'        => $myts->htmlSpecialChars($town),
            'country'     => $myts->htmlSpecialChars($country),
            'contactby'   => $myts->htmlSpecialChars($contactby),
            'premium'     => $myts->htmlSpecialChars($premium),
            'valid'       => $myts->htmlSpecialChars($valid),
            'hits'        => $hits,
            'rlid'        => $myts->htmlSpecialChars($rlid),
            'rdate'       => $myts->htmlSpecialChars($rdate),
            'rsubmitter'  => $myts->htmlSpecialChars($rsubmitter),
            'message'     => $myts->htmlSpecialChars($message),
            'remail'      => $myts->htmlSpecialChars($remail),
            'rrows'       => $rrows,
            'expires'     => $myts->htmlSpecialChars($date2),
            'view_now'    => $view_now,
            'modify_link' => $modify_link,
            'photo'       => $photo,
            'no_photo'    => $no_photo,
            'adminlink'   => $adminlink,
            'new'         => $newitem,
            'sold'        => $sold
        ]);
    }
    $usid = Request::getInt('usid', 0, 'GET');

    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if (1 != $linkpages && 0 != $linkpages) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='members.php?usid=$usid&min=$prev&show=$show'><strong><u>&laquo;</u></strong></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<strong>($counter)</strong> ";
            } else {
                $pagenav .= "<a href='members.php?usid=$usid&min=$mintemp&show=$show'>$counter</a> ";
            }
            ++$counter;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='members.php?usid=$usid&min=$max&show=$show'>";
            $pagenav .= '<strong><u>&raquo;</u></strong></a>';
        }
        $xoopsTpl->assign('nav_page', '<strong>' . _ADSLIGHT_PAGES . "</strong>&nbsp;&nbsp; $pagenav");
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
