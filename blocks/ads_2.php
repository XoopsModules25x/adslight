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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

use XoopsModules\Adslight;
use XoopsModules\Adslight\Helper;

/**
 * @param $options
 *
 * @return array
 */
function adslight_b2_show($options)
{
    global $xoopsDB, $xoopsModuleConfig, $block_lang;

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);
    /** @var \XoopsModules\Adslight\Helper $helper */
    $helper = Helper::getInstance();

    $block['title'] = constant("{$block_lang}_TITLE");

    $updir      = $helper->getConfig($moduleDirName . '_link_upload', '');
    $cat_perms  = '';
    $categories = Adslight\Utility::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query('SELECT lid, cid, title, status, type, price, typeprice, date, town, country, contactby, usid, premium, valid, photo, hits FROM ' . $xoopsDB->prefix("{$moduleDirName}_listing") . " WHERE valid='Yes' AND status!='1' {$cat_perms} ORDER BY {$options[0]} DESC", $options[1],
                              0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_item = [];
        $title  = $myts->htmlSpecialChars($myrow['title']);
        //        $status    = $myts->htmlSpecialChars($myrow['status']);
        $status    = (int)$myrow['status'];
        $type      = $myts->htmlSpecialChars($myrow['type']);
        $price     = $myts->htmlSpecialChars($myrow['price']);
        $typeprice = $myts->htmlSpecialChars($myrow['typeprice']);
        $town      = $myts->htmlSpecialChars($myrow['town']);
        $country   = $myts->htmlSpecialChars($myrow['country']);
        $usid      = $myts->htmlSpecialChars($myrow['usid']);
        $hits      = $myts->htmlSpecialChars($myrow['hits']);

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(mb_substr($myrow['title'], 0, $options[2] - 1)) . '...';
            }
        }

        $ad_title            = $myrow['title'];
        $a_item['status']    = $status;
        $a_item['type']      = Adslight\Utility::getNameType($type);
        $a_item['price']     = $price;
        $a_item['typeprice'] = $typeprice;
        $a_item['town']      = $town;
        $a_item['country']   = $country;
        $a_item['id']        = (int)$myrow['lid'];
        $a_item['cid']       = (int)$myrow['cid'];
        $a_item['no_photo']  = '<a href="' . XOOPS_URL . "/modules/$moduleDirName/viewads.php?lid={$a_item['id']}\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/modules/{$moduleDirName}/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"{$ad_title}\"></a>";

        $a_item['price_symbol'] = $helper->getConfig($moduleDirName . '_currency_symbol', '');

        if (2 == $status) {
            $a_item['sold'] = '<img src="assets/images/sold.gif" align="left" alt="">';
        }

        if ('' != $myrow['photo']) {
            //            $updir = $xoopsModuleConfig["{$moduleDirName}_link_upload"];
            $sql = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix("{$moduleDirName}_pictures") . ' WHERE uid_owner=' . (int)$usid . " AND lid={$a_item['id']} ORDER BY date_added ASC LIMIT 1";

            //            if ('' != $myrow['photo']) {
            //                //  $updir = $GLOBALS['xoopsModuleConfig']["".$moduleDirName."_link_upload"];
            //                $sql = 'SELECT cod_img, lid, uid_owner, url FROM '
            //                       . $xoopsDB->prefix('' . $moduleDirName . '_pictures')
            //                       . ' WHERE  uid_owner=' . $xoopsDB->escape($usid)
            //                       . ' AND lid=' . $xoopsDB->escape($myrow['lid'])
            //                       . ' ORDER BY date_added ASC limit 1';
            //            }
            $resultp = $xoopsDB->query($sql);
            while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                $a_item['photo'] = '<a href="' . XOOPS_URL . "/modules/$moduleDirName/viewads.php?lid={$a_item['id']}\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/uploads/adslight/thumbs/thumb_{$url}\" align=\"left\" width=\"100px\" alt=\"{$title}\"></a>";
            }
        } else {
            $a_item['photo'] = '';
        }
        $a_item['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/viewads.php?lid={$a_item['id']}\"><b>{$title}</b></a>";
        $a_item['date'] = formatTimestamp($myrow['date'], 's');
        $a_item['hits'] = $myrow['hits'];

        $block['items'][] = $a_item;
    }
    $block['lang_title']     = constant("{$block_lang}_ITEM");
    $block['lang_price']     = constant("{$block_lang}_PRICE");
    $block['lang_typeprice'] = constant("{$block_lang}_TYPEPRICE");
    $block['lang_date']      = constant("{$block_lang}_DATE");
    $block['lang_local']     = constant("{$block_lang}_LOCAL2");
    $block['lang_hits']      = constant("{$block_lang}_HITS");
    $block['link']           = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant($block_lang . '_ALL_LISTINGS') . '</b></a><br>';
    $block['add']            = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant($block_lang . '_ADDNOW') . '</b></a><br>';

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function adslight_b2_edit($options)
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ('date' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant($block_lang . '_DATE') . "</option>\n";
    $form .= "<option value='hits'";
    if ('hits' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant("{$block_lang}_HITS") . '</option>';
    $form .= "</select>\n";
    $form .= '&nbsp;' . constant("{$block_lang}_DISP") . "&nbsp;<input type='text' name='options[]' value='{$options[1]}'>&nbsp;" . constant("{$block_lang}_LISTINGS");
    $form .= '&nbsp;<br><br>' . constant("{$block_lang}_CHARS") . "&nbsp;<input type='text' name='options[]' value='{$options[2]}'>&nbsp;" . constant("{$block_lang}_LENGTH") . '<br><br>';

    return $form;
}
