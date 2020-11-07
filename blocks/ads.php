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

/**
 * @param $options
 *
 * @return array
 */
function adslight_show($options)
{
    global $xoopsDB, $block_lang;

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $block['title'] = constant("{$block_lang}_TITLE");

    $result = $xoopsDB->query('SELECT lid, cid, title, type, date, hits FROM ' . $xoopsDB->prefix("{$moduleDirName}_listing") . " WHERE valid='Yes' ORDER BY {$options[0]} DESC", $options[1], 0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_item = [];
        $title  = $myts->htmlSpecialChars($myrow['title']);
        $type   = $myts->htmlSpecialChars($myrow['type']);

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(mb_substr($myrow['title'], 0, $options[2] - 1)) . '...';
            }
        }

        $a_item['type'] = Adslight\Utility::getNameType($type);
        $a_item['id']   = $myrow['lid'];
        $a_item['cid']  = $myrow['cid'];

        $a_item['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/viewads.php?lid=" . addslashes($myrow['lid']) . "\"><b>{$title}</b></a>";

        if ('date' === $options[0]) {
            $a_item['date'] = formatTimestamp($myrow['date'], 's');
        } elseif ('hits' === $options[0]) {
            $a_item['hits'] = $myrow['hits'];
        }

        $block['items'][] = $a_item;
    }

    $block['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ALL_LISTINGS") . '</b></a><br>';
    $block['add']  = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ADDNOW") . '</b></a><br>';

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function adslight_edit($options)
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ('date' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant("{$block_lang}_DATE") . "</option>\n";

    $form .= "<option value='hits'";
    if ('hits' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant($block_lang . '_HITS') . '</option>';
    $form .= "</select>\n";

    $form .= '&nbsp;' . constant($block_lang . '_DISP') . "&nbsp;<input type='text' name='options[]' value='{$options[1]}'>&nbsp;" . constant("{$block_lang}_LISTINGS");
    $form .= '&nbsp;<br><br>' . constant("{$block_lang}_CHARS") . "&nbsp;<input type='text' name='options[]' value='{$options[2]}'>&nbsp;" . constant("{$block_lang}_LENGTH") . '<br><br>';

    return $form;
}
