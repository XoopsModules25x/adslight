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
    Utility
};

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';
xoops_load('XoopsLocal');
$tempXoopsLocal = new \XoopsLocal();
$myts           = \MyTextSanitizer::getInstance();
$module_id      = $xoopsModule->getVar('mid');

if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

$perm_itemid = Request::getInt('item_id', 0, 'POST');

if (!$grouppermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
if ($grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
    $prem_perm = '1';
} else {
    $prem_perm = '0';
}

$mytree = new Tree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

#  function view (categories)
#####################################################
/**
 * @param int $cid
 * @param int $min
 * @param     $orderby
 * @param int $show
 */
function adsView($cid, $min, $orderby, $show = 0)
{
    global $xoopsDB, $xoopsTpl, $xoopsConfig, $myts, $mytree, $imagecat, $meta, $mid, $prem_perm, $xoopsModule;

    $helper     = Helper::getInstance();
    $pathIcon16 = Admin::iconUrl('', 16);

    $GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';

    $GLOBALS['xoopsTpl']->assign('xmid', $xoopsModule->getVar('mid'));
    $GLOBALS['xoopsTpl']->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $GLOBALS['xoopsTpl']->assign('add_from_sitename', $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('only_pix', _ADSLIGHT_ONLYPIX);
    $GLOBALS['xoopsTpl']->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);
    $GLOBALS['xoopsTpl']->assign('permit', $prem_perm);

    $GLOBALS['xoopsTpl']->assign('xoops_module_header', '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >');

    // $adslight_use_catscode = $GLOBALS['xoopsModuleConfig']['adslight_use_catscode'];
    // $adslight_cats_code = $GLOBALS['xoopsModuleConfig']['adslight_cats_code'];

    $GLOBALS['xoopsTpl']->assign('adslight_use_catscode', $GLOBALS['xoopsModuleConfig']['adslight_use_catscode']);
    $GLOBALS['xoopsTpl']->assign('adslight_cats_code', $GLOBALS['xoopsModuleConfig']['adslight_cats_code']);

    $banner = xoops_getbanner();
    $GLOBALS['xoopsTpl']->assign('banner', $banner);
    // $index_code_place = $GLOBALS['xoopsModuleConfig']['adslight_index_code_place'];
    // $use_extra_code = $GLOBALS['xoopsModuleConfig']['adslight_use_index_code'];
    // $adslight_use_banner = $GLOBALS['xoopsModuleConfig']['adslight_use_banner'];
    // $index_extra_code = $GLOBALS['xoopsModuleConfig']['adslight_index_code'];

    $GLOBALS['xoopsTpl']->assign('use_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_use_index_code']);
    $GLOBALS['xoopsTpl']->assign('adslight_use_banner', $GLOBALS['xoopsModuleConfig']['adslight_use_banner']);
    $GLOBALS['xoopsTpl']->assign('index_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_index_code']);
    $GLOBALS['xoopsTpl']->assign('index_code_place', $GLOBALS['xoopsModuleConfig']['adslight_index_code_place']);

    // adslight 2
    $GLOBALS['xoopsTpl']->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $GLOBALS['xoopsTpl']->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    /// No Adds in this Cat ///
    $submit_perms = Utility::getMyItemIds('adslight_submit');

    if (is_array($submit_perms) && $GLOBALS['xoopsUser']
        && count($submit_perms) > 0) {
        $GLOBALS['xoopsTpl']->assign('not_adds_in_this_cat', '' . _ADSLIGHT_ADD_LISTING_NOTADDSINTHISCAT . '<a href="addlisting.php?cid=' . addslashes((string)$cid) . '">' . _ADSLIGHT_ADD_LISTING_NOTADDSSUBMIT . '</a>');
    } else {
        $GLOBALS['xoopsTpl']->assign('not_adds_in_this_cat', '' . _ADSLIGHT_ADD_LISTING_NOTADDSINTHISCAT . '<br>' . _ADSLIGHT_ADD_LISTING_BULL . '<a href="' . XOOPS_URL . '/register.php">' . _ADSLIGHT_ADD_LISTING_SUB . '</a>.');
    }

    $GLOBALS['xoopsTpl']->assign('Feed_RSS_cat', '&nbsp;&nbsp;&nbsp;<a href="rss.php?cid=' . addslashes((string)$cid) . '"><img border="0" alt="Feed RSS" src="assets/images/rssfeed_buttons.png" ></a>');

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $GLOBALS['xoopsTpl']->assign('istheirs', true);

            [$show_user] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $member_usid . ' '));

            $GLOBALS['xoopsTpl']->assign('show_user', $show_user);
            $GLOBALS['xoopsTpl']->assign('show_user_link', 'members.php?usid=' . $member_usid);
        }
    }

    $default_sort = $GLOBALS['xoopsModuleConfig']['adslight_lsort_order'];

    $cid     = ((int)$cid > 0) ? (int)$cid : 0;
    $min     = ((int)$min > 0) ? (int)$min : 0;
    $show    = ((int)$show > 0) ? (int)$show : $GLOBALS['xoopsModuleConfig']['adslight_perpage'];
    $max     = $min + $show;
    $orderby = isset($orderby) ? Utility::convertOrderByIn($orderby) : $default_sort;

    $updir = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
    $GLOBALS['xoopsTpl']->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $GLOBALS['xoopsTpl']->assign('add_from_sitename', $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('subcat_title2', _ADSLIGHT_ANNONCES);

    $categories = Utility::getMyItemIds('adslight_view');

    //TO DO - check on permissions here
    //    if ($categories && is_array($categories)) {
    //        if (!in_array($cid, $categories)) {
    //            $helper->redirect('index.php', 3, _NOPERM);
    //        }
    //    } else {    // User can't see any category
    //        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    //    }

    $arrow = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/arrow.gif" alt="&raquo;" >';

    $pathstring = '<a href="index.php">' . _ADSLIGHT_MAIN . '</a>';
    $pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcats.php?');
    $GLOBALS['xoopsTpl']->assign('module_name', $xoopsModule->getVar('name'));
    $GLOBALS['xoopsTpl']->assign('category_path', $pathstring);
    $GLOBALS['xoopsTpl']->assign('category_id', $cid);

    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE  cid=' . $xoopsDB->escape($cid) . ' AND valid="Yes" AND status!="1"');
    [$trow] = $xoopsDB->fetchRow($countresult);
    $trows = $trow;

    $cat_perms = '';
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query('SELECT cid, pid, title, cat_desc, cat_keywords FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . $xoopsDB->escape($cid) . ' ' . $cat_perms);
    [$cid, $pid, $title, $cat_desc, $cat_keywords] = $xoopsDB->fetchRow($result);

    $GLOBALS['xoopsTpl']->assign('cat_desc', $cat_desc);
    $GLOBALS['xoopsTpl']->assign('cat_title', _ADSLIGHT_ANNONCES . ' ' . $title);
    $GLOBALS['xoopsTpl']->assign('cat_keywords', $cat_keywords);
    $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $title);

    if ($cat_desc > '0') {
        // meta description & keywords tags for categories
        $cat_desc_clean     = strip_tags($cat_desc, '<span><img><strong><i><u>');
        $cat_keywords_clean = strip_tags($cat_keywords, '<span><img><strong><i><u><br><li>');

        $GLOBALS['xoTheme']->addMeta('meta', 'description', '' . mb_substr($cat_desc_clean, 0, 200));
        $GLOBALS['xoTheme']->addMeta('meta', 'keywords', '' . mb_substr($cat_keywords_clean, 0, 1000));
    }

    $submit_perms = Utility::getMyItemIds('adslight_submit');
    if (is_array($submit_perms) && $GLOBALS['xoopsUser']
        && count($submit_perms) > 0) {
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULLCATS . '<a href="addlisting.php?cid=' . addslashes($cid) . '">' . _ADSLIGHT_ADD_LISTING_SUBOK . '</a>';
    } else {    // User can't see any category
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULLCATSOK . '<a href="' . XOOPS_URL . '/register.php">' . _ADSLIGHT_ADD_LISTING_SUB . '</a>.';
    }

    if (0 != $pid || 1 == $GLOBALS['xoopsModuleConfig']['adslight_main_cat']) {
        $GLOBALS['xoopsTpl']->assign('bullinfotext', $add_listing);
    }

    $arr = [];
    $arr = $mytree->getFirstChild($cid, 'title');

    if (count($arr) > 0) {
        $scount = 1;
        foreach ($arr as $ele) {
            if (in_array($ele['cid'], $categories)) {
                $sub_arr         = [];
                $sub_arr         = $mytree->getFirstChild($ele['cid'], 'title');
                $space           = 0;
                $chcount         = 0;
                $infercategories = '';
                $totallisting    = Utility::getTotalItems($ele['cid'], 1);
                foreach ($sub_arr as $sub_ele) {
                    if (in_array($sub_ele['cid'], $categories)) {
                        $chtitle = \htmlspecialchars($sub_ele['title'], ENT_QUOTES | ENT_HTML5);

                        if ($chcount > 5) {
                            $infercategories .= '...';
                            break;
                        }
                        if ($space > 0) {
                            $infercategories .= ', ';
                        }
                        $infercategories .= '<a href="' . XOOPS_URL . '/modules/adslight/viewcats.php?cid=' . $sub_ele['cid'] . '">' . $chtitle . '</a>';

                        $infercategories .= '&nbsp;(' . Utility::getTotalItems($sub_ele['cid']) . ')';
                        $infercategories .= '&nbsp;' . categorynewgraphic($sub_ele['cid']) . '';
                        ++$space;
                        ++$chcount;
                    }
                }

                $GLOBALS['xoopsTpl']->append('subcategories', [
                    'title'           => htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5),
                    'id'              => $ele['cid'],
                    'infercategories' => $infercategories,
                    'totallisting'    => $totallisting,
                    '',
                ]);

                ++$scount;
                $GLOBALS['xoopsTpl']->assign('lang_subcat', _ADSLIGHT_AVAILAB);
            }
        }
    }

    $pagenav = '';
    if ($trows > '0') {
        $GLOBALS['xoopsTpl']->assign('last_head', _ADSLIGHT_THE . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_newcount'] . ' ' . _ADSLIGHT_LASTADD);
        $GLOBALS['xoopsTpl']->assign('last_head_title', _ADSLIGHT_TITLE);
        $GLOBALS['xoopsTpl']->assign('last_head_price', _ADSLIGHT_PRICE);
        $GLOBALS['xoopsTpl']->assign('last_head_date', _ADSLIGHT_DATE);
        $GLOBALS['xoopsTpl']->assign('last_head_local', _ADSLIGHT_LOCAL2);
        $GLOBALS['xoopsTpl']->assign('last_head_hits', _ADSLIGHT_VIEW);
        $GLOBALS['xoopsTpl']->assign('last_head_photo', _ADSLIGHT_PHOTO);
        $GLOBALS['xoopsTpl']->assign('cat', $cid);
        $GLOBALS['xoopsTpl']->assign('min', $min);
        $rank = 1;

        $cat_perms = '';
        if (is_array($categories) && count($categories) > 0) {
            $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        $sql     = 'SELECT lid, title, status, type, price, typeprice, date_created, town, country, contactby, usid, premium, valid, photo, hits FROM '
                   . $xoopsDB->prefix('adslight_listing')
                   . ' WHERE valid="Yes" AND cid='
                   . $xoopsDB->escape($cid)
                   . ' AND status!="1" '
                   . $cat_perms
                   . ' ORDER BY '
                   . $orderby
                   . '';
        $result1 = $xoopsDB->query($sql, $show, $min);
        if ($trows > '1') {
            $GLOBALS['xoopsTpl']->assign('show_nav', true);
            $orderbyTrans = Utility::convertOrderByTrans($orderby);
            $GLOBALS['xoopsTpl']->assign('lang_sortby', _ADSLIGHT_SORTBY);
            $GLOBALS['xoopsTpl']->assign('lang_title', _ADSLIGHT_TITLE);
            $GLOBALS['xoopsTpl']->assign('lang_titleatoz', _ADSLIGHT_TITLEATOZ);
            $GLOBALS['xoopsTpl']->assign('lang_titleztoa', _ADSLIGHT_TITLEZTOA);
            $GLOBALS['xoopsTpl']->assign('lang_date', _ADSLIGHT_DATE);
            $GLOBALS['xoopsTpl']->assign('lang_dateold', _ADSLIGHT_DATEOLD);
            $GLOBALS['xoopsTpl']->assign('lang_datenew', _ADSLIGHT_DATENEW);
            $GLOBALS['xoopsTpl']->assign('lang_price', _ADSLIGHT_PRICE);
            $GLOBALS['xoopsTpl']->assign('lang_priceltoh', _ADSLIGHT_PRICELTOH);
            $GLOBALS['xoopsTpl']->assign('lang_pricehtol', _ADSLIGHT_PRICEHTOL);
            $GLOBALS['xoopsTpl']->assign('lang_popularity', _ADSLIGHT_POPULARITY);
            $GLOBALS['xoopsTpl']->assign('lang_popularityleast', _ADSLIGHT_POPULARITYLTOM);
            $GLOBALS['xoopsTpl']->assign('lang_popularitymost', _ADSLIGHT_POPULARITYMTOL);
            $GLOBALS['xoopsTpl']->assign('lang_cursortedby', sprintf(_ADSLIGHT_CURSORTEDBY, Utility::convertOrderByTrans($orderby)));
        }

        while (false !== (list($lid, $title, $status, $type, $price, $typeprice, $date_created, $town, $country, $contactby, $usid, $premium, $valid, $photo, $hits) = $xoopsDB->fetchRow($result1))) {
            $a_item = [];
            $title  = \htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
            $type   = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);
            //      $price = number_format($price, 2, ',', ' ');
            $town       = \htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);
            $country    = \htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);
            $contactby  = \htmlspecialchars($contactby, ENT_QUOTES | ENT_HTML5);
            $useroffset = '';

            $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
            $startdate = (time() - (86400 * $newcount));
            if ($startdate < $date_created) {
                $newitem       = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" >';
                $a_item['new'] = $newitem;
            }
            if ($GLOBALS['xoopsUser']) {
                $timezone = $GLOBALS['xoopsUser']->timezone();
                if (isset($timezone)) {
                    $useroffset = $GLOBALS['xoopsUser']->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }
            $date_created = ($useroffset * 3600) + $date_created;
            $date_created = formatTimestamp($date_created, 's');
            if ($GLOBALS['xoopsUser']) {
                if ($GLOBALS['xoopsUser']->isAdmin()) {
                    $a_item['admin'] = '<a href="' . XOOPS_URL . '/modules/adslight/admin/validate_ads.php?op=modifyAds&amp;lid=' . $lid . '"><img src="' . $pathIcon16 . '/edit.png' . '" border=0 alt="' . _ADSLIGHT_MODADMIN . '" title="' . _ADSLIGHT_MODADMIN . '"></a>';
                }
            }

            $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='" . $xoopsDB->escape($type) . "'");
            [$nom_type] = $xoopsDB->fetchRow($result7);

            $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price='" . $xoopsDB->escape($typeprice) . "'");
            [$nom_price] = $xoopsDB->fetchRow($result8);

            $a_item['type']   = \htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);
            $a_item['title']  = '<a href="viewads.php?lid=' . $lid . '"><strong>' . $title . '</strong></a>';
            $a_item['status'] = $status;
            if ($price > 0) {
                $currencyCode                 = $helper->getConfig('adslight_currency_code');
                $currencySymbol               = $helper->getConfig('adslight_currency_symbol');
                $currencyPosition             = $helper->getConfig('currency_position');
                $formattedCurrencyUtilityTemp = Utility::formatCurrencyTemp($price, $currencyCode, $currencySymbol, $currencyPosition);
                $priceHtml                    = '<strong>' . _ADSLIGHT_PRICE2 . '</strong>' . $formattedCurrencyUtilityTemp . ' - ' . $nom_price;

                $a_item['price'] = $priceHtml;

                $a_item['price_typeprice'] = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
            }
            $a_item['date_created']  = $date_created;
            $a_item['local'] = '';
            if ($town) {
                $a_item['local'] .= $town;
            }
            $a_item['country'] = '';
            if ($country) {
                $a_item['country'] = $country;
            }

            $cat = addslashes($cid);
            if (2 == $status) {
                $a_item['sold'] = _ADSLIGHT_RESERVEDMEMBER;
            }

            if ($GLOBALS['xoopsModuleConfig']['active_thumbscats'] > 0) {
                $a_item['no_photo'] = '<a href="' . XOOPS_URL . '/modules/adslight/viewads.php?lid=' . $lid . '"><img class="thumb" src="' . XOOPS_URL . '/modules/adslight/assets/images/nophoto.jpg" align="left" width="100px" alt="' . $title . '" ></a>';

                $updir   = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                $sql     = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE  uid_owner=' . $xoopsDB->escape($usid) . ' AND lid=' . $xoopsDB->escape($lid) . ' ORDER BY date_created ASC LIMIT 1';
                $resultp = $xoopsDB->query($sql);

                while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                    if ($photo) {
                        $a_item['photo'] = '<a href="' . XOOPS_URL . '/modules/adslight/viewads.php?lid=' . $lid . '"><img class="thumb" src="' . $updir . '/thumbs/thumb_' . $url . '" align="left" width="100px" alt="' . $title . '" ></a>';
                    }
                }
            } else {
                $a_item['no_photo'] = '<p><img src="' . XOOPS_URL . '/modules/adslight/assets/images/camera_nophoto.png" align="left" width="24" alt="' . $title . '" ></p>';
                $updir              = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                $sql                = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE  uid_owner=' . $xoopsDB->escape($usid) . ' AND lid=' . $xoopsDB->escape($lid) . ' ORDER BY date_created ASC LIMIT 1';
                $resultp            = $xoopsDB->query($sql);
                while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                    if ($photo) {
                        $a_item['photo'] = '<p><img src="' . XOOPS_URL . '/modules/adslight/assets/images/camera_photo.png" align="left" width="24" alt="' . $title . '" ></p>';
                    }
                }
            }

            $a_item['hits'] = $hits;
            ++$rank;
            $GLOBALS['xoopsTpl']->append('items', $a_item);
        }

        $cid = ((int)$cid > 0) ? (int)$cid : 0;

        $orderby   = Utility::convertOrderByOut($orderby);
        $linkpages = ceil($trows / $show);

        //Page Numbering
        if (1 != $linkpages && 0 != $linkpages) {
            $prev = $min - $show;
            if ($prev >= 0) {
                $pagenav .= "<a href='viewcats.php?cid=$cid&min=$prev&orderby=$orderby&show=$show'><strong><u>&laquo;</u></strong></a> ";
            }
            $counter     = 1;
            $currentpage = ($max / $show);
            while ($counter <= $linkpages) {
                $mintemp = ($show * $counter) - $show;
                if ($counter == $currentpage) {
                    $pagenav .= "<strong>($counter)</strong> ";
                } else {
                    $pagenav .= "<a href='viewcats.php?cid=$cid&min=$mintemp&orderby=$orderby&show=$show'>$counter</a> ";
                }
                ++$counter;
            }
            if ($trows > $max) {
                $pagenav .= "<a href='viewcats.php?cid=$cid&min=$max&orderby=$orderby&show=$show'>";
                $pagenav .= '<strong><u>&raquo;</u></strong></a>';
            }
        }
    }

    $GLOBALS['xoopsTpl']->assign('nav_page', $pagenav);

    if (!$GLOBALS['xoopsUser']) {
        global $xoopsDB;

        $xoopsTree = new \XoopsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
        $jump      = XOOPS_URL . '/modules/adslight/viewcats.php?cid=';
        ob_start();
        $xoopsTree->makeMySelBox('title', 'title', $cid, 1, 'pid', 'location="' . $jump . '"+this.options[this.selectedIndex].value');
        $select_go_cats = ob_get_clean();
        $GLOBALS['xoopsTpl']->assign('select_go_cats', $select_go_cats);
    }
}

#  function categorynewgraphic
#####################################################
/**
 * @param $cid
 */
function categorynewgraphic($cid)
{
    //global $xoopsDB;
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
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';
        adsView($cid, $min, $orderby, $show);
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
