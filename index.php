<?php

declare(strict_types=1);

/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops

        Redesigned and ameliorate By iluc user at www.frxoops.org
        Started with the Classifieds module and made MANY changes
        Website : http://www.limonads.com
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

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Adslight\{
    ClassifiedsTree,
    Helper,
    Utility
};


$GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';

global $xoopsModule;

require_once __DIR__ . '/header.php';

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

$groups = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

$perm_itemid = Request::getInt('item_id', 0, 'POST');

if (!$grouppermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}

$prem_perm = (!$grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) ? '0' : '1';

$mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

#  function index
#####################################################

function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $mytree, $meta, $mid, $prem_perm;
    $pathIcon16 = Admin::iconUrl('', 16);
    $moduleDirName = basename(__DIR__);

    $helper = Helper::getInstance();

    if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
        require_once $GLOBALS['xoops']->path('class/template.php');
        $GLOBALS['xoopsTpl'] = new \XoopsTpl();
    }

    //    $GLOBALS['xoopsOption']['template_main'] = 'adslight_index.tpl';

    require_once XOOPS_ROOT_PATH . '/header.php';

    $GLOBALS['xoopsTpl']->assign('xmid', $xoopsModule->getVar('mid'));
    $GLOBALS['xoopsTpl']->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $GLOBALS['xoopsTpl']->assign('add_from_sitename', $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('only_pix', _ADSLIGHT_ONLYPIX);
    $GLOBALS['xoopsTpl']->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);
    $GLOBALS['xoopsTpl']->assign('permit', $prem_perm);

    $GLOBALS['xoopsTpl']->assign('xoops_module_header', '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >');

    $banner = xoops_getbanner();
    $GLOBALS['xoopsTpl']->assign('banner', $banner);
    $GLOBALS['xoopsTpl']->assign('use_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_use_index_code']);
    $GLOBALS['xoopsTpl']->assign('adslight_use_banner', $GLOBALS['xoopsModuleConfig']['adslight_use_banner']);
    $GLOBALS['xoopsTpl']->assign('index_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_index_code']);
    $GLOBALS['xoopsTpl']->assign('index_code_place', $GLOBALS['xoopsModuleConfig']['adslight_index_code_place']);
    $GLOBALS['xoopsTpl']->assign('category_title2', _ADSLIGHT_ANNONCES);
    // adslight 2
    $GLOBALS['xoopsTpl']->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $GLOBALS['xoopsTpl']->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    //    ExpireAd();
    Utility::expireAd();

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $GLOBALS['xoopsTpl']->assign('istheirs', true);

            [$show_user] = $xoopsDB->fetchRow($xoopsDB->query('SELECT SQL_CACHE COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $member_usid . ' '));

            $GLOBALS['xoopsTpl']->assign('show_user', $show_user);
            $GLOBALS['xoopsTpl']->assign('show_user_link', 'members.php?usid=' . $member_usid . '');
        }
    }

    $sql = 'SELECT COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE valid="No"';
    $result = $xoopsDB->query($sql);
    [$propo] = $xoopsDB->fetchRow($result);

    if ($propo > 0) {
        $GLOBALS['xoopsTpl']->assign('moderated', true);
    }
    if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
        if ($GLOBALS['xoopsUser']->isAdmin()) {
            $GLOBALS['xoopsTpl']->assign('admin_block', _ADSLIGHT_ADMINCADRE);
            if (0 == $propo) {
                $GLOBALS['xoopsTpl']->assign('confirm_ads', _ADSLIGHT_NO_CLA);
            } else {
                $GLOBALS['xoopsTpl']->assign('confirm_ads', _ADSLIGHT_THEREIS . ' ' . $propo . '  ' . _ADSLIGHT_WAIT . '<br><a href="' . XOOPS_URL . '/modules/adslight/admin/validate_ads.php">' . _ADSLIGHT_SEEIT . '</a>');
            }
        }

        $categories = Utility::getMyItemIds('adslight_submit');
        $intro      = (is_array($categories)
                       && (count($categories) > 0)) ? _ADSLIGHT_INTRO : '';
        $GLOBALS['xoopsTpl']->assign('intro', $intro);
    }

    $sql = 'SELECT SQL_CACHE cid, title, img FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE pid = 0 ';

    $categories = Utility::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
    } else {
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }

    $sql .= ('cat_order' === $GLOBALS['xoopsModuleConfig']['adslight_csortorder']) ? 'ORDER BY cat_order' : 'ORDER BY title';

    $result = $xoopsDB->query($sql);

    $count   = 1;
    $content = '';
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $title = \htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        if ($myrow['img'] && 'http://' !== $myrow['img']) {
            $cat_img = \htmlspecialchars($myrow['img'], ENT_QUOTES | ENT_HTML5);
            $img     = "<a href=\"viewcats.php?cid={$myrow['cid']}\"><img src=\"" . XOOPS_URL . "/modules/adslight/assets/images/img_cat/{$cat_img}\" align=\"middle\" alt=\"{$title}\"></a>";
        } else {
            $img = '';
        }

        $totallisting = Utility::getTotalItems($myrow['cid'], 1);
        $content      .= $title . ' ';

        $arr = [];
        if (in_array($myrow['cid'], $categories)) {
            $arr           = $mytree->getFirstChild($myrow['cid'], 'title');
            $space         = 0;
            $chcount       = 1;
            $subcategories = '';
            if (1 == $GLOBALS['xoopsModuleConfig']['adslight_souscat']) {
                foreach ($arr as $ele) {
                    if (in_array($ele['cid'], $categories)) {
                        $chtitle = \htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5);
                        if ($chcount > $GLOBALS['xoopsModuleConfig']['adslight_nbsouscat']) {
                            $subcategories .= "<a href=\"viewcats.php?cid={$myrow['cid']}\">" . _ADSLIGHT_CATPLUS . '</a>';
                            break;
                        }
                        if ($space > 0) {
                            $subcategories .= '<br>';
                        }
                        $subcategories .= '-&nbsp;<a href="' . XOOPS_URL . "/modules/adslight/viewcats.php?cid={$ele['cid']}\">{$chtitle}</a>";
                        ++$space;
                        ++$chcount;
                        $content .= $ele['title'] . ' ';
                    }
                }
            }
            $GLOBALS['xoopsTpl']->append('categories', [
                'image'         => $img,
                'id'            => $myrow['cid'],
                'title'         => htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5),
                'new'           => categorynewgraphic($myrow['cid']),
                'subcategories' => $subcategories,
                'totallisting'  => $totallisting,
                'count'         => $count,
            ]);
            ++$count;
        }
    }
    $cat_perms = '';
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    [$ads] = $xoopsDB->fetchRow($xoopsDB->query('SELECT SQL_CACHE COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes' AND status!='1' {$cat_perms}"));

    [$catt] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*)  FROM ' . $xoopsDB->prefix("{$moduleDirName}_categories")));

    $submit_perms = Utility::getMyItemIds('adslight_submit');

    if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
//        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULLOK . '<a href="add.php">' . _ADSLIGHT_ADD_LISTING_SUBOK . '</a>';
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULLOK . '<a rel="nofollow" class="btn btn-success text-right"  title="submit your ad" href="add.php">' . _ADSLIGHT_ADD_LISTING_SUBOK . '</a>';

    } else {
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULL . '<a href="' . XOOPS_URL . '/register.php">' . _ADSLIGHT_ADD_LISTING_SUB . '</a>.';
    }

    $GLOBALS['xoopsTpl']->assign('bullinfotext', _ADSLIGHT_ACTUALY . ' ' . $ads . ' ' . _ADSLIGHT_ADVERTISEMENTS . '<br>' . $add_listing);
    $GLOBALS['xoopsTpl']->assign('total_confirm', _ADSLIGHT_AND . " $propo " . _ADSLIGHT_WAIT3);

    if (1 == $GLOBALS['xoopsModuleConfig']['adslight_newad']) {
        $cat_perms = '';
        if (is_array($categories) && count($categories) > 0) {
            $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        $sql = 'SELECT  SQL_CACHE  lid, title, status, type, price, typeprice, date, town, country, usid, premium, valid, photo, hits FROM '
                                  . $xoopsDB->prefix('adslight_listing')
                                  . " WHERE valid='Yes' and status!='1' {$cat_perms} ORDER BY date DESC LIMIT {$GLOBALS['xoopsModuleConfig']['adslight_newcount']}";
        $result = $xoopsDB->query($sql);
        if ($result) {
            $GLOBALS['xoopsTpl']->assign('last_head', _ADSLIGHT_THE . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_newcount'] . ' ' . _ADSLIGHT_LASTADD);
            $GLOBALS['xoopsTpl']->assign('last_head_title', _ADSLIGHT_TITLE);
            $GLOBALS['xoopsTpl']->assign('last_head_price', _ADSLIGHT_PRICE);
            $GLOBALS['xoopsTpl']->assign('last_head_date', _ADSLIGHT_DATE);
            $GLOBALS['xoopsTpl']->assign('last_head_local', _ADSLIGHT_LOCAL2);
            $GLOBALS['xoopsTpl']->assign('last_head_hits', _ADSLIGHT_VIEW);
            $GLOBALS['xoopsTpl']->assign('last_head_photo', _ADSLIGHT_PHOTO);
            $rank = 1;

            while (false !== (list($lid, $title, $status, $type, $price, $typeprice, $date, $town, $country, $usid, $premium, $valid, $photo, $hits) = $xoopsDB->fetchRow($result))) {
                $title = \htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
                $type  = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);
                //                $price     = number_format($price, 2, ',', ' ');
                $town      = \htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);
                $country   = \htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);
                $premium   = \htmlspecialchars($premium, ENT_QUOTES | ENT_HTML5);
                $a_item    = [];
                $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
                $startdate = (time() - (86400 * $newcount));

                if ($startdate < $date) {
                    $newitem       = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" alt="new" >';
                    $a_item['new'] = $newitem;
                }

                $useroffset = '';
                if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
                    $timezone   = $GLOBALS['xoopsUser']->timezone();
                    $useroffset = !empty($timezone) ? $GLOBALS['xoopsUser']->timezone() : $xoopsConfig['default_TZ'];
                    if ($GLOBALS['xoopsUser']->isAdmin()) {
                        $a_item['admin'] = '<a href="' . XOOPS_URL . "/modules/adslight/admin/validate_ads.php?op=modifyAds&amp;lid={$lid}\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"" . _ADSLIGHT_MODADMIN . '"></a>';
                    }
                }

                $date = ($useroffset * 3600) + $date;
                $date = formatTimestamp($date, 's');

                $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' WHERE id_type=' . (int)$type);
                [$nom_type] = $xoopsDB->fetchRow($result7);

                $a_item['type']  = \htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);
                $a_item['title'] = '<a href="' . XOOPS_URL . "/modules/adslight/viewads.php?lid={$lid}\"><strong>{$title}</strong></a>";

                $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' WHERE id_price=' . (int)$typeprice);
                [$nom_price] = $xoopsDB->fetchRow($result8);

                if ($price > 0) {
//                    $a_item['price']           = $price . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . '';
                    $currencyCode = $helper->getConfig('adslight_currency_code');
                    $currencySymbol = $helper->getConfig('adslight_currency_symbol');
                    $currencyPosition = $helper->getConfig('currency_position');
                    $formattedCurrencyUtilityTemp = Utility::formatCurrencyTemp($price, $currencyCode, $currencySymbol, $currencyPosition);

                    $priceHtml = '<strong>' . _ADSLIGHT_PRICE2 . '</strong>' . $formattedCurrencyUtilityTemp . ' - ' . $nom_price;
                    $a_item['price']           = $priceHtml;

                    $a_item['price_typeprice'] = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
                } else {
                    $a_item['price']           = '';
                    $a_item['price_typeprice'] = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
                }

                $a_item['premium'] = $premium;
                $a_item['date']    = $date;
                $a_item['local']   = $town ?: '';
                $a_item['country'] = $country ?: '';

                if (2 == $status) {
                    $a_item['sold'] = _ADSLIGHT_RESERVEDMEMBER;
                }

                if ($helper->getConfig('active_thumbsindex') > 0) {
                    $a_item['no_photo'] = '<a href="' . XOOPS_URL . "/modules/adslight/viewads.php?lid={$lid}\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/modules/adslight/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"{$title}\"></a>";

                    $updir = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                    $sql   = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE  uid_owner=' . (int)$usid . " AND lid={$lid} ORDER BY date_added ASC LIMIT 1";

                    $resultp = $xoopsDB->query($sql);

                    while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                        if ($photo) {
                            $a_item['photo'] = '<a href="' . XOOPS_URL . "/modules/adslight/viewads.php?lid={$lid}\"><img class=\"thumb\" src=\"{$updir}/thumbs/thumb_{$url}\" align=\"left\" width=\"100px\" alt=\"{$title}\"></a>";
                        }
                    }
                } else {
                    $a_item['no_photo'] = '<img src="' . XOOPS_URL . "/modules/adslight/assets/images/camera_nophoto.png\" align=\"left\" width=\"24px\" alt=\"{$title}\">";
                    $updir              = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                    $sql                = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE uid_owner=' . (int)$usid . " AND lid={$lid} ORDER BY date_added ASC LIMIT 1";
                    $resultp            = $xoopsDB->query($sql);

                    while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                        if ($photo) {
                            $a_item['photo'] = '<img src="' . XOOPS_URL . "/modules/adslight/assets/images/camera_photo.png\" align=\"left\" width=\"24\" alt=\"{$title}\">";
                        }
                    }
                }
                $a_item['hits'] = $hits;
                ++$rank;
                $GLOBALS['xoopsTpl']->append('items', $a_item);
            }
        }
    }
}

#  function categorynewgraphic
#####################################################
/**
 * @param $cid
 */
function categorynewgraphic($cid)
{
    global $xoopsDB;
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
    case 'adsview':
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';
        adsView($cid, $min, $orderby, $show);
        break;
    case 'viewads':
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_item.tpl';
        viewAds($lid);
        break;
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_index.tpl';
//        $GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';
        index();
        break;
}

require_once XOOPS_ROOT_PATH . '/footer.php';
