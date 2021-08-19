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

use XoopsModules\Adslight\{
    Helper,
    Utility
};

/** @var Helper $helper */

/**
 * @param $options
 */
function adslight_b2_show($options)
{
    if (!class_exists(Helper::class)) {
        return false;
    }

    $helper = Helper::getInstance();

    global $xoopsDB, $xoopsModuleConfig, $block_lang;
    $block          = [];
    $myts           = \MyTextSanitizer::getInstance();
    $moduleDirName  = \basename(\dirname(__DIR__));
    $block_lang     = '_MB_' . mb_strtoupper($moduleDirName);
    $block['title'] = constant("{$block_lang}_TITLE");

    $updir      = $helper->getConfig($moduleDirName . '_link_upload', '');
    $cat_perms  = '';
    $categories = Utility::getMyItemIds('adslight_view');
    if (is_iterable($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $sql =  'SELECT lid, cid, title, status, type, price, typeprice, date_created, town, country, contactby, usid, premium, valid, photo, hits FROM ' . $xoopsDB->prefix("{$moduleDirName}_listing") . " WHERE valid='Yes' AND status!='1' {$cat_perms} ORDER BY {$options[0]} DESC";
    $result = $xoopsDB->query($sql, $options[1], 0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_item = [];
        $title  = \htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);
        //        $status    = \htmlspecialchars($myrow['status']);
        $status    = (int)$myrow['status'];
        $type      = \htmlspecialchars($myrow['type'], ENT_QUOTES | ENT_HTML5);
        $price     = \htmlspecialchars($myrow['price'], ENT_QUOTES | ENT_HTML5);
        $typeprice = \htmlspecialchars($myrow['typeprice'], ENT_QUOTES | ENT_HTML5);
        $town      = \htmlspecialchars($myrow['town'], ENT_QUOTES | ENT_HTML5);
        $country   = \htmlspecialchars($myrow['country'], ENT_QUOTES | ENT_HTML5);
        $usid      = \htmlspecialchars($myrow['usid'], ENT_QUOTES | ENT_HTML5);
        $hits      = \htmlspecialchars($myrow['hits'], ENT_QUOTES | ENT_HTML5);



        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($myrow['title']) >= $options[2]) {
                $title = \htmlspecialchars(mb_substr($myrow['title'], 0, $options[2] - 1), ENT_QUOTES | ENT_HTML5) . '...';
            }
        }

        $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' WHERE id_type=' . (int)$type);
        [$nom_type] = $xoopsDB->fetchRow($result7);

        $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' WHERE id_price=' . (int)$typeprice);
        [$nom_price] = $xoopsDB->fetchRow($result8);

        $ad_title         = $myrow['title'];
        $a_item['status'] = $status;
        $a_item['type']   = Utility::getNameType($type);
        //        $a_item['price']     = $price;
        $a_item['typeprice']    = $nom_price;
        $a_item['town']         = $town;
        $a_item['country']      = $country;
        $a_item['id']           = (int)$myrow['lid'];
        $a_item['cid']          = (int)$myrow['cid'];
        $a_item['no_photo']     = '<a href="' . XOOPS_URL . "/modules/${moduleDirName}/viewads.php?lid={$a_item['id']}\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/modules/{$moduleDirName}/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"{$ad_title}\"></a>";
        $a_item['price_symbol'] = $helper->getConfig($moduleDirName . '_currency_symbol', '');

        $currencyCode                 = $helper->getConfig('adslight_currency_code');
        $currencySymbol               = $helper->getConfig('adslight_currency_symbol');
        $currencyPosition             = $helper->getConfig('currency_position');
        $formattedCurrencyUtilityTemp = Utility::formatCurrencyTemp($price, $currencyCode, $currencySymbol, $currencyPosition);

        $priceHtml = $formattedCurrencyUtilityTemp . ' - ' . $nom_price;

        $a_item['price'] = $priceHtml;

        if (2 === $status) {
            $a_item['sold'] = '<img src="assets/images/sold.gif" align="left" alt="">';
        }

        if ('' !== $myrow['photo']) {
            //            $updir = $xoopsModuleConfig["{$moduleDirName}_link_upload"];
            $sql = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix("{$moduleDirName}_pictures") . ' WHERE uid_owner=' . (int)$usid . " AND lid={$a_item['id']} ORDER BY date_created ASC LIMIT 1";

            //            if ('' != $myrow['photo']) {
            //                //  $updir = $GLOBALS['xoopsModuleConfig']["".$moduleDirName."_link_upload"];
            //                $sql = 'SELECT cod_img, lid, uid_owner, url FROM '
            //                       . $xoopsDB->prefix('' . $moduleDirName . '_pictures')
            //                       . ' WHERE  uid_owner=' . $xoopsDB->escape($usid)
            //                       . ' AND lid=' . $xoopsDB->escape($myrow['lid'])
            //                       . ' ORDER BY date_created ASC limit 1';
            //            }
            $resultp = $xoopsDB->query($sql);
            while ([$cod_img, $pic_lid, $uid_owner, $url] = $xoopsDB->fetchRow($resultp)) {
                $a_item['photo'] = '<a href="' . XOOPS_URL . "/modules/${moduleDirName}/viewads.php?lid={$a_item['id']}\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/uploads/adslight/thumbs/thumb_{$url}\" align=\"left\" width=\"100px\" alt=\"{$title}\"></a>";
            }
        } else {
            $a_item['photo'] = '';
        }
        $a_item['link']         = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/viewads.php?lid={$a_item['id']}\"><b>{$title}</b></a>";
        $a_item['date_created'] = formatTimestamp($myrow['date_created'], 's');
        $a_item['hits']         = $myrow['hits'];

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
 */
function adslight_b2_edit($options): string
{
    global $xoopsDB;
    $moduleDirName = \basename(\dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date_created'";
    if ('date_created' === $options[0]) {
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
