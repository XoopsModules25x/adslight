<?php
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

include_once __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';

$myts      = MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

is_object($GLOBALS['xoopsUser']) ? $groups = $GLOBALS['xoopsUser']->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;

$gpermHandler = xoops_getHandler('groupperm');

$perm_itemid = XoopsRequest::getInt('item_id', 0, 'POST');

if (!$gpermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}

(!$gpermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) ? $prem_perm = '0' : $prem_perm = '1';

include XOOPS_ROOT_PATH . '/modules/adslight/class/classifiedstree.php';
//include XOOPS_ROOT_PATH . '/modules/adslight/class/utilities.php';
$mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

#  function index
#####################################################
function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsTpl, $myts, $mytree, $meta, $mid, $moduleDirName, $main_lang, $prem_perm, $xoopsModule;
    $pathIcon16 = $xoopsModule->getInfo('icons16');

    $GLOBALS['xoopsOption']['template_main'] = 'adslight_index.tpl';

    include XOOPS_ROOT_PATH . '/header.php';

    $xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
    $xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
    $xoopsTpl->assign('only_pix', _ADSLIGHT_ONLYPIX);
    $xoopsTpl->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);
    $xoopsTpl->assign('permit', $prem_perm);

    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" />');

    $banner = xoops_getbanner();
    $xoopsTpl->assign('banner', $banner);
    $xoopsTpl->assign('use_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_use_index_code']);
    $xoopsTpl->assign('adslight_use_banner', $GLOBALS['xoopsModuleConfig']['adslight_use_banner']);
    $xoopsTpl->assign('index_extra_code', $GLOBALS['xoopsModuleConfig']['adslight_index_code']);
    $xoopsTpl->assign('index_code_place', $GLOBALS['xoopsModuleConfig']['adslight_index_code_place']);
    $xoopsTpl->assign('category_title2', _ADSLIGHT_ANNONCES);
    // adslight 2
    $xoopsTpl->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $xoopsTpl->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    //    ExpireAd();
    AdslightUtilities::expireAd();

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $xoopsTpl->assign('istheirs', true);

            list($show_user) = $xoopsDB->fetchRow($xoopsDB->query('SELECT SQL_CACHE COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid='" . $member_usid . "'"));

            $xoopsTpl->assign('show_user', $show_user);
            $xoopsTpl->assign('show_user_link', 'members.php?usid=' . $member_usid);
        }
    }

    $result = $xoopsDB->query('SELECT SQL_CACHE COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE valid="No"');
    list($propo) = $xoopsDB->fetchRow($result);

    if ($propo > 0) {
        $xoopsTpl->assign('moderated', true);
    }
    if ($GLOBALS['xoopsUser']) {
        if ($GLOBALS['xoopsUser']->isAdmin()) {
            $xoopsTpl->assign('admin_block', _ADSLIGHT_ADMINCADRE);
            if ($propo == 0) {
                $xoopsTpl->assign('confirm_ads', _ADSLIGHT_NO_CLA);
            } else {
                $xoopsTpl->assign('confirm_ads',
                                  _ADSLIGHT_THEREIS . ' ' . $propo . '  ' . _ADSLIGHT_WAIT . '<br><a href="' . XOOPS_URL . '/modules/adslight/admin/validate_ads.php">' . _ADSLIGHT_SEEIT . '</a>');
            }
        }

        $categories = AdslightUtilities::getMyItemIds('adslight_submit');
        if (is_array($categories) && count($categories) > 0) {
            $intro = _ADSLIGHT_INTRO;
        } else {
            $intro = '';
        }
        $xoopsTpl->assign('intro', $intro);
    }

    $sql = 'SELECT SQL_CACHE cid, title, img FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE pid = 0 ';

    $categories = AdslightUtilities::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
    } else {
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }

    if ($GLOBALS['xoopsModuleConfig']['adslight_csortorder'] === 'ordre') {
        $sql .= 'ORDER BY ordre';
    } else {
        $sql .= 'ORDER BY title';
    }

    $result = $xoopsDB->query($sql);

    $count   = 1;
    $content = '';
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $title = $myts->htmlSpecialChars($myrow['title']);

        if ($myrow['img'] && $myrow['img'] !== 'http://') {
            $cat_img = $myts->htmlSpecialChars($myrow['img']);
            $img     = '<a href="viewcats.php?cid='
                       . $myrow['cid']
                       . '"><img src="'
                       . XOOPS_URL
                       . '/modules/adslight/assets/images/img_cat/'
                       . $cat_img
                       . '" align="middle" alt="'
                       . $title
                       . '" /></a>';
        } else {
            $img = '';
        }

        $totallisting = AdslightUtilities::getTotalItems($myrow['cid'], 1);
        $content .= $title . ' ';

        $arr = array();
        if (in_array($myrow['cid'], $categories)) {
            $arr           = $mytree->getFirstChild($myrow['cid'], 'title');
            $space         = 0;
            $chcount       = 1;
            $subcategories = '';
            if ($GLOBALS['xoopsModuleConfig']['adslight_souscat'] == 1) {
                foreach ($arr as $ele) {
                    if (in_array($ele['cid'], $categories)) {
                        $chtitle = $myts->htmlSpecialChars($ele['title']);
                        if ($chcount > $GLOBALS['xoopsModuleConfig']['adslight_nbsouscat']) {
                            $subcategories .= '<a href="viewcats.php?cid=' . $myrow['cid'] . '">' . _ADSLIGHT_CATPLUS . '</a>';
                            break;
                        }
                        if ($space > 0) {
                            $subcategories .= '<br>';
                        }
                        $subcategories .= '-&nbsp;<a href="' . XOOPS_URL . '/modules/adslight/viewcats.php?cid=' . $ele['cid'] . '">' . $chtitle . '</a>';
                        ++$space;
                        ++$chcount;
                        $content .= $ele['title'] . ' ';
                    }
                }
            }
            $xoopsTpl->append('categories', array(
                'image'         => $img,
                'id'            => $myrow['cid'],
                'title'         => $myts->htmlSpecialChars($myrow['title']),
                'new'           => categorynewgraphic($myrow['cid']),
                'subcategories' => $subcategories,
                'totallisting'  => $totallisting,
                'count'         => $count
            ));
            ++$count;
        }
    }
    $cat_perms = '';
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    list($ads) = $xoopsDB->fetchRow($xoopsDB->query('SELECT SQL_CACHE COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes' AND status!='1' $cat_perms"));

    list($catt) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*)  FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_categories') . ''));

    $submit_perms = AdslightUtilities::getMyItemIds('adslight_submit');

    if ($GLOBALS['xoopsUser']) {
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULLOK . '<a href="add.php">' . _ADSLIGHT_ADD_LISTING_SUBOK . '</a>';
    } else {
        $add_listing = '' . _ADSLIGHT_ADD_LISTING_BULL . '<a href="' . XOOPS_URL . '/register.php">' . _ADSLIGHT_ADD_LISTING_SUB . '</a>.';
    }

    $xoopsTpl->assign('bullinfotext', _ADSLIGHT_ACTUALY . ' ' . $ads . ' ' . _ADSLIGHT_ADVERTISEMENTS . '<br>' . $add_listing);
    $xoopsTpl->assign('total_confirm', _ADSLIGHT_AND . " $propo " . _ADSLIGHT_WAIT3);

    if ($GLOBALS['xoopsModuleConfig']['adslight_newad'] == 1) {
        $cat_perms = '';
        if (is_array($categories) && count($categories) > 0) {
            $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        $result = $xoopsDB->query('SELECT SQL_CACHE lid, title, status, type, price, typeprice, date, town, country, usid, premium, valid, photo, hits FROM '
                                  . $xoopsDB->prefix('adslight_listing')
                                  . " WHERE valid='Yes' AND status!='1' $cat_perms ORDER BY date DESC LIMIT "
                                  . $GLOBALS['xoopsModuleConfig']['adslight_newcount']);
        if ($result) {
            $xoopsTpl->assign('last_head', _ADSLIGHT_THE . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_newcount'] . ' ' . _ADSLIGHT_LASTADD);
            $xoopsTpl->assign('last_head_title', _ADSLIGHT_TITLE);
            $xoopsTpl->assign('last_head_price', _ADSLIGHT_PRICE);
            $xoopsTpl->assign('last_head_date', _ADSLIGHT_DATE);
            $xoopsTpl->assign('last_head_local', _ADSLIGHT_LOCAL2);
            $xoopsTpl->assign('last_head_hits', _ADSLIGHT_VIEW);
            $xoopsTpl->assign('last_head_photo', _ADSLIGHT_PHOTO);
            $rank = 1;

            while (list($lid, $title, $status, $type, $price, $typeprice, $date, $town, $country, $usid, $premium, $valid, $photo, $hits) = $xoopsDB->fetchRow($result)) {
                $title     = $myts->htmlSpecialChars($title);
                $type      = $myts->htmlSpecialChars($type);
                $price     = number_format($price, 2, ',', ' ');
                $town      = $myts->htmlSpecialChars($town);
                $country   = $myts->htmlSpecialChars($country);
                $premium   = $myts->htmlSpecialChars($premium);
                $a_item    = array();
                $newcount  = $GLOBALS['xoopsModuleConfig']['adslight_countday'];
                $startdate = (time() - (86400 * $newcount));

                if ($startdate < $date) {
                    $newitem       = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/newred.gif" alt="new" />';
                    $a_item['new'] = $newitem;
                }

                $useroffset = '';
                if ($GLOBALS['xoopsUser']) {
                    $timezone = $GLOBALS['xoopsUser']->timezone();
                    if (isset($timezone)) {
                        $useroffset = $GLOBALS['xoopsUser']->timezone();
                    } else {
                        $useroffset = $xoopsConfig['default_TZ'];
                    }
                }

                $date = ($useroffset * 3600) + $date;
                $date = formatTimestamp($date, 's');
                if ($GLOBALS['xoopsUser']) {
                    if ($GLOBALS['xoopsUser']->isAdmin()) {
                        $a_item['admin'] = '<a href="'
                                           . XOOPS_URL
                                           . '/modules/adslight/admin/validate_ads.php?op=ModifyAds&amp;lid='
                                           . $lid
                                           . '"><img src="'
                                           . $pathIcon16
                                           . '/edit.png'
                                           . '" border=0 alt="'
                                           . _ADSLIGHT_MODADMIN
                                           . '" /></a>';
                    }
                }

                $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='" . $xoopsDB->escape($type) . "'");
                list($nom_type) = $xoopsDB->fetchRow($result7);

                $a_item['type']  = $myts->htmlSpecialChars($nom_type);
                $a_item['title'] = '<a href="' . XOOPS_URL . '/modules/adslight/viewads.php?lid=' . $lid . '"><strong>' . $title . '</strong></a>';

                $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price='" . $xoopsDB->escape($typeprice) . "'");
                list($nom_price) = $xoopsDB->fetchRow($result8);

                if ($price > 0) {
                    $a_item['price']           = $price . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_money'] . '';
                    $a_item['price_typeprice'] = $myts->htmlSpecialChars($nom_price);
                } else {
                    $a_item['price']           = '';
                    $a_item['price_typeprice'] = $myts->htmlSpecialChars($nom_price);
                }
                $a_item['premium'] = $premium;
                $a_item['date']    = $date;
                $a_item['local']   = '';
                if ($town) {
                    $a_item['local'] .= $town;
                }
                $a_item['country'] = '';
                if ($country) {
                    $a_item['country'] = $country;
                }

                if ($status == 2) {
                    $a_item['sold'] = _ADSLIGHT_RESERVEDMEMBER;
                }

                if ($GLOBALS['xoopsModuleConfig']['active_thumbsindex'] > 0) {
                    $a_item['no_photo'] = '<a href="'
                                          . XOOPS_URL
                                          . '/modules/adslight/viewads.php?lid='
                                          . $lid
                                          . '"><img class="thumb" src="'
                                          . XOOPS_URL
                                          . '/modules/adslight/assets/images/nophoto.jpg" align="left" width="100px" alt="'
                                          . $title
                                          . '" /></a>';

                    $updir   = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                    $sql     = 'SELECT cod_img, lid, uid_owner, url FROM '
                               . $xoopsDB->prefix('adslight_pictures')
                               . ' WHERE  uid_owner='
                               . $xoopsDB->escape($usid)
                               . ' AND lid='
                               . $xoopsDB->escape($lid)
                               . ' ORDER BY date_added ASC limit 1';
                    $resultp = $xoopsDB->query($sql);

                    while (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp)) {
                        if ($photo) {
                            $a_item['photo'] = '<a href="'
                                               . XOOPS_URL
                                               . '/modules/adslight/viewads.php?lid='
                                               . $lid
                                               . '"><img class="thumb" src="'
                                               . $updir
                                               . '/thumbs/thumb_'
                                               . $url
                                               . '" align="left" width="100px" alt="'
                                               . $title
                                               . '" /></a>';
                        }
                    }
                } else {
                    $a_item['no_photo'] = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/camera_nophoto.png" align="left" width="24" alt="' . $title . '" />';
                    $updir              = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
                    $sql                = 'SELECT cod_img, lid, uid_owner, url FROM '
                                          . $xoopsDB->prefix('adslight_pictures')
                                          . ' WHERE  uid_owner='
                                          . $xoopsDB->escape($usid)
                                          . ' AND lid='
                                          . $xoopsDB->escape($lid)
                                          . ' ORDER BY date_added ASC limit 1';
                    $resultp            = $xoopsDB->query($sql);

                    while (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp)) {
                        if ($photo) {
                            $a_item['photo'] = '<img src="' . XOOPS_URL . '/modules/adslight/assets/images/camera_photo.png" align="left" width="24" alt="' . $title . '" />';
                        }
                    }
                }
                $a_item['hits'] = $hits;
                ++$rank;
                $xoopsTpl->append('items', $a_item);
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

$pa      = XoopsRequest::getInt('pa', null, 'GET');
$lid     = XoopsRequest::getInt('lid', null, 'GET');
$cid     = XoopsRequest::getInt('cid', null, 'GET');
$usid    = XoopsRequest::getString('usid', '', 'GET');
$min     = XoopsRequest::getInt('min', null, 'GET');
$show    = XoopsRequest::getInt('show', null, 'GET');
$orderby = XoopsRequest::getInt('orderby', null, 'GET');

switch ($pa) {
    case 'Adsview':
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_category.tpl';
        adsView($cid, $min, $orderby, $show);
        break;
    case 'viewads':
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_item.tpl';
        viewAds($lid);
        break;
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_index.tpl';
        index();
        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
