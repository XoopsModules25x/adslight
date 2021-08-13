<?php

declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Adslight\{
    Tree,
    Helper,
    PicturesHandler,
    Utility
};

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';
xoops_load('XoopsLocal');

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid      = Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
if ($grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
    $prem_perm = '1';
} else {
    $prem_perm = '0';
}

$mytree = new Tree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

#  function viewads
#####################################################
/**
 * @param int $lid
 */
function viewAds($lid = 0)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsTpl, $myts, $meta, $prem_perm, $xoopsUser;

    $helper = Helper::getInstance();

    $moduleDirName = \basename(__DIR__);

    $pathIcon16     = Admin::iconUrl('', 16);
    $contact_pm     = $contact = '';
    $pictures_array = [];
    $cid            = 0;

    $tempXoopsLocal                          = new \XoopsLocal();
    $GLOBALS['xoopsOption']['template_main'] = 'adslight_item.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    //    require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
    $lid  = ((int)$lid > 0) ? (int)$lid : 0;
    $rate = ('1' == $GLOBALS['xoopsModuleConfig']['adslight_rate_item']) ? '1' : '0';
    $GLOBALS['xoopsTpl']->assign('rate', $rate);
    $GLOBALS['xoopsTpl']->assign('xmid', $xoopsModule->getVar('mid'));
    $GLOBALS['xoopsTpl']->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);

    // Hack redirection erreur 404  si lid=null
    if ('' == $lid) {
        header('Status: 301 Moved Permanently', false, 301);
        //        header('Location: '.XOOPS_URL.'/modules/adslight/404.php');
        //        exit();
        $helper->redirect('404.php', 1);
    }

    $GLOBALS['xoopsTpl']->assign('adslight_active_bookmark', $GLOBALS['xoopsModuleConfig']['adslight_active_bookmark']);
    $GLOBALS['xoopsTpl']->assign('adslight_style_bookmark', $GLOBALS['xoopsModuleConfig']['adslight_style_bookmark']);
//    $GLOBALS['xoopsTpl']->assign('adslight_active_xpayement', $GLOBALS['xoopsModuleConfig']['adslight_active_xpayment']);

    // adslight 2
    $GLOBALS['xoopsTpl']->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $GLOBALS['xoopsTpl']->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $GLOBALS['xoopsTpl']->assign('istheirs', true);

            if ('' !== $GLOBALS['xoopsUser']->getVar('name')) {
                $GLOBALS['xoopsTpl']->assign('user_name', $GLOBALS['xoopsUser']->getVar('name') . ' (' . $GLOBALS['xoopsUser']->getVar('uname') . ')');
            } else {
                $GLOBALS['xoopsTpl']->assign('user_name', $GLOBALS['xoopsUser']->getVar('uname'));
            }

            $GLOBALS['xoopsTpl']->assign('user_email', $GLOBALS['xoopsUser']->getVar('email'));

            [$show_user] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid=$member_usid"));

            $GLOBALS['xoopsTpl']->assign('show_user', $show_user);
            $GLOBALS['xoopsTpl']->assign('show_user_link', 'members.php?uid=' . $member_usid);
        }
    }

    if ($GLOBALS['xoopsUser']) {
        $currentid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
    }

    $cat_perms  = '';
    $categories = Utility::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result      = $xoopsDB->query(
        'SELECT l.lid, l.cid, l.title, l.status, l.expire, l.type, l.desctext, l.tel, l.price, l.typeprice, l.typecondition, l.date_created, l.email, l.submitter, l.usid, l.town, l.country, l.contactby, l.premium, l.valid, l.photo, l.hits, l.item_rating, l.item_votes, l.user_rating, l.user_votes, l.comments, p.cod_img, p.lid, p.uid_owner, p.url FROM '
        . $xoopsDB->prefix('adslight_listing')
        . ' l LEFT JOIN '
        . $xoopsDB->prefix('adslight_pictures')
        . " p ON l.lid=p.lid  WHERE l.valid='Yes' AND l.lid = "
        . $xoopsDB->escape($lid)
        . " and l.status!='1' $cat_perms"
    );
    $recordexist = $xoopsDB->getRowsNum($result);

    // Hack redirection erreur 404  si recordexist=null
    if ('' == $recordexist) {
        header('Status: 301 Moved Permanently', false, 301);
        //        header('Location: '.XOOPS_URL.'/modules/adslight/404.php');
        //        exit();
        $helper->redirect('404.php', 1);
    }

    if ($recordexist) {
        [
            $lid,
            $cid,
            $title,
            $status,
            $expire,
            $type,
            $desctext,
            $tel,
            $price,
            $typeprice,
            $typecondition,
            $date_created,
            $email,
            $submitter,
            $usid,
            $town,
            $country,
            $contactby,
            $premium,
            $valid,
            $photo,
            $hits,
            $item_rating,
            $item_votes,
            $user_rating,
            $user_votes,
            $comments,
            $cod_img,
            $pic_lid,
            $uid_owner,
            $url,
        ] = $xoopsDB->fetchRow($result);

        $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
        $startdate = (time() - (86400 * $newcount));
        if ($startdate < $date_created) {
            $newitem = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" alt="new" >';
            $GLOBALS['xoopsTpl']->assign('new', $newitem);
        }

        $updir = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
        $GLOBALS['xoopsTpl']->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
        $GLOBALS['xoopsTpl']->assign('add_from_title', _ADSLIGHT_ADDFROM);
        $GLOBALS['xoopsTpl']->assign('add_from_sitename', $xoopsConfig['sitename']);
        $GLOBALS['xoopsTpl']->assign('ad_exists', $recordexist);
        $GLOBALS['xoopsTpl']->assign('mydirname', $moduleDirName);

        $count = 0;
        $x     = 0;
        $i     = 0;

        $result3 = $xoopsDB->query('SELECT cid, pid, title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE  cid=' . $xoopsDB->escape($cid));
        [$ccid, $pid, $ctitle] = $xoopsDB->fetchRow($result3);

        $GLOBALS['xoopsTpl']->assign('category_title', $ctitle);

        $module_id = $xoopsModule->getVar('mid');
        if (is_object($GLOBALS['xoopsUser'])) {
            $groups = $GLOBALS['xoopsUser']->getGroups();
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
        }
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        $GLOBALS['xoopsTpl']->assign('purchasable', $grouppermHandler->checkRight('adslight_purchase', $cid, $groups, $module_id));

        $ctitle     = \htmlspecialchars($ctitle, ENT_QUOTES | ENT_HTML5);
        $varid[$x]  = $ccid;
        $varnom[$x] = $ctitle;

        [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE valid="Yes" AND cid=' . $xoopsDB->escape($cid) . ' AND status!="1"'));

        if (0 != $pid) {
            $x = 1;
            while (0 != $pid) {
                $result4 = $xoopsDB->query('SELECT cid, pid, title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . $xoopsDB->escape($pid));
                [$ccid, $pid, $ctitle] = $xoopsDB->fetchRow($result4);

                $ctitle     = \htmlspecialchars($ctitle, ENT_QUOTES | ENT_HTML5);
                $varid[$x]  = $ccid;
                $varnom[$x] = $ctitle;
                ++$x;
            }
            --$x;
        }
        $subcats = '';
        $arrow   = '&nbsp;<img src="' . XOOPS_URL . '/modules/adslight/assets/images/arrow.gif" alt="&raquo;" >';
        while (-1 != $x) {
            $subcats .= ' ' . $arrow . ' <a href="viewcats.php?cid=' . $varid[$x] . '">' . $varnom[$x] . '</a>';
            --$x;
        }
        $GLOBALS['xoopsTpl']->assign('nav_main', '<a href="index.php">' . _ADSLIGHT_MAIN . '</a>');
        $GLOBALS['xoopsTpl']->assign('nav_sub', $subcats);
        $GLOBALS['xoopsTpl']->assign('nav_subcount', $nbe);
        $viewcount_judge = true;
        $useroffset      = '';
        if ($GLOBALS['xoopsUser']) {
            $timezone = $GLOBALS['xoopsUser']->timezone();
            if (isset($timezone)) {
                $useroffset = $GLOBALS['xoopsUser']->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }
            if ($GLOBALS['xoopsUser']->isAdmin()) {
                $adslight_admin = true;
            } else {
                $adslight_admin = false;
            }

            if (($adslight_admin = true)
                || ($GLOBALS['xoopsUser']->getVar('uid') == $usid)) {
                $viewcount_judge = false;
            }

            $contact_pm = '<a href="' . XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . addslashes($usid) . '">&nbsp;' . _ADSLIGHT_CONTACT_BY_PM . '</a>';
        }
        if ($viewcount_judge) {
            $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . ' SET hits=hits+1 WHERE lid = ' . $xoopsDB->escape($lid));
        }
        if (1 == $item_votes) {
            $votestring = _ADSLIGHT_ONEVOTE;
        } else {
            $votestring = sprintf(_ADSLIGHT_NUMVOTES, $item_votes);
        }
        $date_created     = ($useroffset * 3600) + $date_created;
        $date2    = $date_created + ($expire * 86400);
        $date_created     = formatTimestamp($date_created, 's');
        $date2    = formatTimestamp($date2, 's');
        $title    = \htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
        $status   = \htmlspecialchars($status, ENT_QUOTES | ENT_HTML5);
        $expire   = \htmlspecialchars($expire, ENT_QUOTES | ENT_HTML5);
        $type     = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);
        $desctext = $myts->displayTarea($desctext, 1, 1, 1);
        $tel      = \htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);
        //        $price = XoopsLocal::number_format($price, 2, ',', ' ');
        $typeprice = \htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);
        $typecondition = \htmlspecialchars($typecondition, ENT_QUOTES | ENT_HTML5);
        $submitter = \htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);
        $usid      = \htmlspecialchars($usid, ENT_QUOTES | ENT_HTML5);
        $town      = \htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);
        $country   = \htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);
        $contactby = \htmlspecialchars($contactby, ENT_QUOTES | ENT_HTML5);
        $premium   = \htmlspecialchars($premium, ENT_QUOTES | ENT_HTML5);

        if (2 == $status) {
            $sold = _ADSLIGHT_RESERVED;
        } else {
            $sold = '';
        }

        $GLOBALS['xoopsTpl']->assign('printA', '<a href="print.php?op=PrintAd&amp;lid=' . $lid . '" ><img src="assets/images/print.gif" border=0 alt="' . _ADSLIGHT_PRINT . '" ></a>&nbsp;');

        if ($usid > 0) {
            $GLOBALS['xoopsTpl']->assign('submitter', '<img src="assets/images/lesannonces.png" border="0" alt="' . _ADSLIGHT_VIEW_MY_ADS . '" >&nbsp;&nbsp;<a href="members.php?usid=' . addslashes($usid) . '" >' . _ADSLIGHT_VIEW_MY_ADS . ' ' . $submitter . '</a>');
        } else {
            $GLOBALS['xoopsTpl']->assign('submitter', _ADSLIGHT_VIEW_MY_ADS . ' $submitter');
        }
        $GLOBALS['xoopsTpl']->assign('lid', $lid);
        $GLOBALS['xoopsTpl']->assign('read', "$hits " . _ADSLIGHT_VIEW2);
        $GLOBALS['xoopsTpl']->assign('rating', $tempXoopsLocal->number_format($item_rating, 2));
        $GLOBALS['xoopsTpl']->assign('votes', $votestring);
        $GLOBALS['xoopsTpl']->assign('lang_rating', _ADSLIGHT_RATINGC);
        $GLOBALS['xoopsTpl']->assign('lang_ratethisitem', _ADSLIGHT_RATETHISITEM);
        $GLOBALS['xoopsTpl']->assign('xoop_user', false);
        $isOwner = '';
        if ($GLOBALS['xoopsUser']) {
            $GLOBALS['xoopsTpl']->assign('xoop_user', true);
            $currentid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
            if ($usid == $currentid) {
                $GLOBALS['xoopsTpl']->assign('modifyads', '<img src=' . $pathIcon16 . '/edit.png border="0" alt="' . _ADSLIGHT_MODIFANN . '" >&nbsp;&nbsp;<a href="modify.php?op=ModAd&amp;lid=' . $lid . '">' . _ADSLIGHT_MODIFANN . '</a>');
                $GLOBALS['xoopsTpl']->assign('deleteads', '<img src=' . $pathIcon16 . '/delete.png  border="0" alt="' . _ADSLIGHT_SUPPRANN . '" >&nbsp;&nbsp;<a href="modify.php?op=ListingDel&amp;lid=' . $lid . '">' . _ADSLIGHT_SUPPRANN . '</a>');
                $GLOBALS['xoopsTpl']->assign('add_photos', '<img src="assets/images/shape_square_add.png" border="0" alt="' . _ADSLIGHT_SUPPRANN . '" >&nbsp;&nbsp;<a href="view_photos.php?lid=' . $lid . '&uid=' . $usid . '">' . _ADSLIGHT_ADD_PHOTOS . '</a>');

                $isOwner = true;
                $GLOBALS['xoopsTpl']->assign('isOwner', $isOwner);
            }
            if ($GLOBALS['xoopsUser']->isAdmin()) {
                $GLOBALS['xoopsTpl']->assign('admin', '<a href="' . XOOPS_URL . '/modules/adslight/admin/modify_ads.php?op=modifyAds&amp;lid=' . $lid . '"><img src=' . $pathIcon16 . '/edit.png  border=0 alt="' . _ADSLIGHT_MODADMIN . '" ></a>');
            }
        }

        $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='" . $xoopsDB->escape($type) . "'");
        [$nom_type] = $xoopsDB->fetchRow($result7);

        $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price='" . $xoopsDB->escape($typeprice) . "'");
        [$nom_price] = $xoopsDB->fetchRow($result8);

        $result9 = $xoopsDB->query('SELECT nom_condition FROM ' . $xoopsDB->prefix('adslight_condition') . " WHERE id_condition='" . $xoopsDB->escape($typecondition) . "'");
        [$nom_condition] = $xoopsDB->fetchRow($result9);

        $GLOBALS['xoopsTpl']->assign('type', htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5));
        $GLOBALS['xoopsTpl']->assign('title', $title);
        $GLOBALS['xoopsTpl']->assign('status', $status);
        $GLOBALS['xoopsTpl']->assign('desctext', $desctext);
        $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $title . ' - ' . $town . ': ' . $country . ' - ' . $ctitle);

        // meta description tags for ads
        $desctextclean = strip_tags($desctext, '<span><img><strong><i><u>');
        $GLOBALS['xoTheme']->addMeta('meta', 'description', "$title - " . mb_substr($desctextclean, 0, 150));

        $currencyCode                 = $helper->getConfig('adslight_currency_code');
        $currencySymbol               = $helper->getConfig('adslight_currency_symbol');
        $currencyPosition             = $helper->getConfig('currency_position');
        $formattedCurrencyUtilityTemp = Utility::formatCurrencyTemp($price, $currencyCode, $currencySymbol, $currencyPosition);

        if ($price > 0) {
            $GLOBALS['xoopsTpl']->assign('price_head', _ADSLIGHT_PRICE2);
            $GLOBALS['xoopsTpl']->assign('price_price', $formattedCurrencyUtilityTemp);

            $priceTypeprice = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
            $GLOBALS['xoopsTpl']->assign('price_typeprice', $priceTypeprice);
            $priceCurrency = $GLOBALS['xoopsModuleConfig']['adslight_currency_code'];
            $GLOBALS['xoopsTpl']->assign('price_currency', $priceCurrency);

            //            $priceHtml = '<strong>' . _ADSLIGHT_PRICE2 . '</strong>' . $price . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . ' - ' . $typeprice;

            $priceHtml = '<strong>' . _ADSLIGHT_PRICE2 . '</strong>' . $formattedCurrencyUtilityTemp . ' - ' . $priceTypeprice;

            $GLOBALS['xoopsTpl']->assign('price', $priceHtml);

            $GLOBALS['xoopsTpl']->assign('price_amount', $price);
        }

        $GLOBALS['xoopsTpl']->assign('condition_typecondition', $nom_condition);
        $GLOBALS['xoopsTpl']->assign('premium', $premium);

        // $GLOBALS['xoopsTpl']->assign('mustlogin', _ADSLIGHT_MUSTLOGIN);
        $GLOBALS['xoopsTpl']->assign('redirect', '' . '?xoops_redirect=/modules/adslight/index.php');

        if ($town) {
            $GLOBALS['xoopsTpl']->assign('local_town', $town);
        }
        if (1 == $GLOBALS['xoopsModuleConfig']['adslight_use_country']) {
            if ($country) {
                $GLOBALS['xoopsTpl']->assign('local_country', $country);
                $GLOBALS['xoopsTpl']->assign('country_head', '<img src="assets/images/world_go.png" border="0" alt="country" >&nbsp;&nbsp;' . _ADSLIGHT_COUNTRY);
            }
        }

        $tphon = '';
        if ($tel) {
            $tphon = '<br>' . _ADSLIGHT_ORBY . '&nbsp;<strong>' . _ADSLIGHT_TEL . '</strong> ' . $tel;
        }

        if (1 == $contactby) {
            $contact = '<a rel="nofollow" href="contact.php?lid=' . $lid . '">' . _ADSLIGHT_BYMAIL2 . '</a>' . $tphon . '';
        }
        if (2 == $contactby) {
            $contact = $contact_pm . '' . $tphon;
        }
        if (3 == $contactby) {
            $contact = '<a rel="nofollow" href="contact.php?lid=' . $lid . '">' . _ADSLIGHT_BYMAIL2 . '</a>' . $tphon . '<br>' . _ADSLIGHT_ORBY . '' . $contact_pm;
        }
        if (4 == $contactby) {
            $contact = '<br><strong>' . _ADSLIGHT_TEL . '</strong> ' . $tel;
        }
        // $GLOBALS['xoopsTpl']->assign('contact', $contact);
        $GLOBALS['xoopsTpl']->assign('local_head', '<img src="assets/images/house.png" border="0" alt="local_head" >&nbsp;&nbsp;' . _ADSLIGHT_LOCAL);

        if ($lid) {
            if ($sold) {
                $GLOBALS['xoopsTpl']->assign('bullinfotext', $sold);
            } elseif ($GLOBALS['xoopsUser']) {
                $GLOBALS['xoopsTpl']->assign('bullinfotext', _ADSLIGHT_CONTACT_SUBMITTER . ' ' . $submitter . ' ' . _ADSLIGHT_CONTACTBY2 . ' ' . $contact);
            } else {
                $GLOBALS['xoopsTpl']->assign('bullinfotext', '<span style="color: #de090e;"><b>' . _ADSLIGHT_MUSTLOGIN . '</b></span>');
            }
        }

        $user_profile = \XoopsUser::getUnameFromId($usid);
        $GLOBALS['xoopsTpl']->assign('user_profile', '<img src="assets/images/profil.png" border="0" alt="' . _ADSLIGHT_PROFILE . '" >&nbsp;&nbsp;<a rel="nofollow" href="' . XOOPS_URL . '/user.php?usid=' . addslashes($usid) . '">' . _ADSLIGHT_PROFILE . ' ' . $user_profile . '</a>');

        if ('' != $photo) {
            $criteria_lid          = new \Criteria('lid', $lid);
            $criteria_uid          = new \Criteria('uid', $usid);
            $album_factory         = new PicturesHandler($xoopsDB);
            $pictures_object_array = $album_factory->getObjects($criteria_lid, $criteria_uid);
            $pictures_number       = $album_factory->getCount($criteria_lid, $criteria_uid);
            if (0 == $pictures_number) {
                $nopicturesyet = _ADSLIGHT_NOTHINGYET;
                $GLOBALS['xoopsTpl']->assign('lang_nopicyet', $nopicturesyet);
            } else {
                /**
                 * Lets populate an array with the data from the pictures
                 */
                $i = 0;
                foreach ($pictures_object_array as $picture) {
                    $pictures_array[$i]['url']     = $picture->getVar('url', 's');
                    $pictures_array[$i]['desc']    = $picture->getVar('title', 's');
                    $pictures_array[$i]['cod_img'] = $picture->getVar('cod_img', 's');
                    $pictures_array[$i]['lid']     = $picture->getVar('lid', 's');
                    $GLOBALS['xoopsTpl']->assign('pics_array', $pictures_array);

                    ++$i;
                }
            }
            $owner      = new \XoopsUser();
            $identifier = $owner::getUnameFromId($usid);

            Utility::load_lib_js(); // JJDai
            /*
                        if (1 == $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {

                            $header_lightbox = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >
            <script type="text/javascript" src="assets/lightbox/js/jquery-1.7.2.min.js"></script>
            <script type="text/javascript" src="assets/lightbox/js/jquery-ui-1.8.18.custom.min"></script>
            <script type="text/javascript" src="assets/lightbox/js/jquery.smooth-scroll.min.js"></script>
            <script type="text/javascript" src="assets/lightbox/js/lightbox.js"></script>
            <link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >
            <link rel="stylesheet" type="text/css" media="screen" href="assets/lightbox/css/lightbox.css"></link>';
                        } else {
                            $header_lightbox = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >
            <link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >';
                        }
            */

            $GLOBALS['xoopsTpl']->assign('path_uploads', $GLOBALS['xoopsModuleConfig']['adslight_link_upload']);

            $GLOBALS['xoopsTpl']->assign('permit', $prem_perm);
            if ($GLOBALS['xoopsModuleConfig']['active_rewriteurl'] > 0) {
                /*  ici le meta Canonicale pour le Rewrite */
                //$GLOBALS['xoopsTpl']->assign('xoops_module_header', $header_lightbox);
            } else {
                //$GLOBALS['xoopsTpl']->assign('xoops_module_header', $header_lightbox);
            }

            $GLOBALS['xoopsTpl']->assign('photo', $photo);
            $GLOBALS['xoopsTpl']->assign('pic_lid', $pic_lid);
            $GLOBALS['xoopsTpl']->assign('pic_owner', $uid_owner);
        } else {
            $GLOBALS['xoopsTpl']->assign('photo', '');
        }
        $GLOBALS['xoopsTpl']->assign(
            'date_created',
            '<img alt="date" border="0" src="assets/images/date.png" >&nbsp;&nbsp;<strong>' . _ADSLIGHT_DATE2 . ':</strong> ' . $date_created . '<br><img alt="date_error" border="0" src="assets/images/date_error.png" >&nbsp;&nbsp;<strong>' . _ADSLIGHT_DISPO . ':</strong> ' . $date2
        );
    } else {
        $GLOBALS['xoopsTpl']->assign('no_ad', _ADSLIGHT_NOCLAS);
    }
    $result8 = $xoopsDB->query('SELECT title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . $xoopsDB->escape($cid));

    [$ctitle] = $xoopsDB->fetchRow($result8);
    $GLOBALS['xoopsTpl']->assign('friend', '<img src="assets/images/friend.gif" border="0" alt="' . _ADSLIGHT_SENDFRIENDS . '" >&nbsp;&nbsp;<a rel="nofollow" href="sendfriend.php?op=SendFriend&amp;lid=' . $lid . '">' . _ADSLIGHT_SENDFRIENDS . '</a>');

    $GLOBALS['xoopsTpl']->assign('alerteabus', '<img src="assets/images/error.png" border="0" alt="' . _ADSLIGHT_ALERTEABUS . '" >&nbsp;&nbsp;<a rel="nofollow" href="report-abuse.php?op=ReportAbuse&amp;lid=' . $lid . '">' . _ADSLIGHT_ALERTEABUS . '</a>');

    $GLOBALS['xoopsTpl']->assign('link_main', '<a href="../adslight/">' . _ADSLIGHT_MAIN . '</a>');
    $GLOBALS['xoopsTpl']->assign('link_cat', '<a href="viewcats.php?cid=' . addslashes($cid) . '">' . _ADSLIGHT_GORUB . ' ' . $ctitle . '</a>');

    $GLOBALS['xoopsTpl']->assign('printA', '<img src="assets/images/print.gif" border="0" alt="' . _ADSLIGHT_PRINT . '" >&nbsp;&nbsp;<a rel="nofollow" href="print.php?op=PrintAd&amp;lid=' . $lid . '">' . _ADSLIGHT_PRINT . '</a>');
}

#  function categorynewgraphic
#####################################################
/**
 * @param $cid
 *
 * @return string
 */
function categorynewgraphic($cid): string
{
    global $xoopsDB;

    $cat_perms  = '';
    $categories = Utility::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $newresult = $xoopsDB->query('SELECT date_created FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . $xoopsDB->escape($cid) . ' AND valid = "Yes" ' . $cat_perms . ' ORDER BY date_created DESC LIMIT 1');
    [$date_created] = $xoopsDB->fetchRow($newresult);

    $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
    $startdate = (time() - (86400 * $newcount));
    if ($startdate < $date_created) {
        return '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" alt="new" >';
    }
}

######################################################

$pa      = Request::getInt('pa', null, 'GET');
$lid     = Request::getInt('lid', null, 'GET');
$cid     = Request::getInt('cid', null, 'GET');
$usid    = Request::getString('usid', '', 'GET');
$min     = Request::getInt('min', null, 'GET');
$show    = Request::getInt('show', null, 'GET');
$orderby = Request::getInt('orderby', null, 'GET');

switch ($pa) {
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_item.tpl';

        viewAds($lid);
        require XOOPS_ROOT_PATH . '/include/comment_view.php';
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
