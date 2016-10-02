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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 *
 * @return array
 */
function adslight_b2_show($options)
{
    global $xoopsDB, $xoopsModuleConfig, $blockdirname, $block_lang;

    $block = array();
    $myts  = MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(__DIR__));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    include_once XOOPS_ROOT_PATH . "/modules/$blockdirname/include/functions.php";

    $block['title'] = '' . constant($block_lang . '_TITLE') . '';

    $updir      = $GLOBALS['xoopsModuleConfig'][$blockdirname . '_link_upload'];
    $cat_perms  = '';
    $categories = AdslightUtilities::getMyItemIds('adslight_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query('SELECT lid, cid, title, status, type, price, typeprice, date, town, country, contactby, usid, premium, valid, photo, hits FROM '
                              . $xoopsDB->prefix(''
                                                 . $blockdirname
                                                 . '_listing')
                              . " WHERE valid='Yes' AND status!='1' $cat_perms ORDER BY "
                              . $options[0]
                              . ' DESC', $options[1], 0);

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $a_item    = array();
        $title     = $myts->htmlSpecialChars($myrow['title']);
        $status    = $myts->htmlSpecialChars($myrow['status']);
        $type      = $myts->htmlSpecialChars($myrow['type']);
        $price     = $myts->htmlSpecialChars($myrow['price']);
        $typeprice = $myts->htmlSpecialChars($myrow['typeprice']);
        $town      = $myts->htmlSpecialChars($myrow['town']);
        $country   = $myts->htmlSpecialChars($myrow['country']);
        $usid      = $myts->htmlSpecialChars($myrow['usid']);
        $hits      = $myts->htmlSpecialChars($myrow['hits']);

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(substr($myrow['title'], 0, $options[2] - 1)) . '...';
            }
        }

        $ad_title               = $myrow['title'];
        $a_item['status']       = $status;
        $a_item['type']         = AdslightUtilities::getNameType($type);
        $a_item['price']        = $price;
        $a_item['typeprice']    = $typeprice;
        $a_item['town']         = $town;
        $a_item['country']      = $country;
        $a_item['id']           = $myrow['lid'];
        $a_item['cid']          = $myrow['cid'];
        $a_item['price_symbol'] = $GLOBALS['xoopsModuleConfig'][$blockdirname . '_money'];

        if ($status == 2) {
            $a_item['sold'] = "<img src=\"assets/images/sold.gif\" align=\"left\" alt=\"\">";
        }

        $a_item['no_photo'] = "<a href=\""
                              . XOOPS_URL
                              . "/modules/$blockdirname/viewads.php?lid="
                              . addslashes($myrow['lid'])
                              . "\"><img class=\"thumb\" src=\""
                              . XOOPS_URL
                              . "/modules/$blockdirname/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"$ad_title\"></a>";

        if ($myrow['photo'] != '') {
            //  $updir = $GLOBALS['xoopsModuleConfig']["".$blockdirname."_link_upload"];
            $sql     = 'SELECT cod_img, lid, uid_owner, url FROM '
                       . $xoopsDB->prefix('' . $blockdirname . '_pictures')
                       . ' WHERE  uid_owner='
                       . $xoopsDB->escape($usid)
                       . ' AND lid='
                       . $xoopsDB->escape($myrow['lid'])
                       . ' ORDER BY date_added ASC limit 1';
            $resultp = $xoopsDB->query($sql);
            while (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp)) {
                $a_item['photo'] = "<a href=\""
                                   . XOOPS_URL
                                   . "/modules/$blockdirname/viewads.php?lid="
                                   . addslashes($myrow['lid'])
                                   . "\"><img class=\"thumb\" src=\""
                                   . XOOPS_URL
                                   . "/uploads/AdsLight/thumbs/thumb_$url\" align=\"left\" width=\"100px\" alt=\"$title\"></a>";
            }
        } else {
            $a_item['photo'] = '';
        }
        $a_item['link'] = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/viewads.php?lid=" . addslashes($myrow['lid']) . "\"><b>$title</b></a>";
        $a_item['date'] = formatTimestamp($myrow['date'], 's');
        $a_item['hits'] = $myrow['hits'];

        $block['items'][] = $a_item;
    }
    $block['lang_title']     = constant($block_lang . '_ITEM');
    $block['lang_price']     = constant($block_lang . '_PRICE');
    $block['lang_typeprice'] = constant($block_lang . '_TYPEPRICE');
    $block['lang_date']      = constant($block_lang . '_DATE');
    $block['lang_local']     = constant($block_lang . '_LOCAL2');
    $block['lang_hits']      = constant($block_lang . '_HITS');
    $block['link']           = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . '_ALL_LISTINGS') . '</b></a><br>';
    $block['add']            = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . '_ADDNOW') . '</b></a><br>';

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
    $blockdirname = basename(dirname(__DIR__));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    $form = constant($block_lang . '_ORDER') . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ($options[0] === 'date') {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_DATE') . "</option>\n";
    $form .= "<option value='hits'";
    if ($options[0] === 'hits') {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_HITS') . '</option>';
    $form .= "</select>\n";
    $form .= '&nbsp;' . constant($block_lang . '_DISP') . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'/>&nbsp;" . constant($block_lang . '_LISTINGS');
    $form .= '&nbsp;<br><br>' . constant($block_lang . '_CHARS') . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'/>&nbsp;" . constant($block_lang . '_LENGTH') . '<br><br>';

    return $form;
}
