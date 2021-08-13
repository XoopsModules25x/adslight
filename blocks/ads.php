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

use XoopsModules\Adslight;

/**
 * @param $options
 *
 * @return array
 */
function adslight_show($options): array
{
    global $xoopsDB, $block_lang;

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $moduleDirName = \basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $block['title'] = constant("{$block_lang}_TITLE");

    $result = $xoopsDB->query('SELECT lid, cid, title, type, date_created, hits FROM ' . $xoopsDB->prefix("{$moduleDirName}_listing") . " WHERE valid='Yes' ORDER BY {$options[0]} DESC", $options[1], 0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_item = [];
        $title  = \htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);
        $type   = \htmlspecialchars($myrow['type'], ENT_QUOTES | ENT_HTML5);

        if (!XOOPS_USE_MULTIBYTES && mb_strlen($myrow['title']) >= $options[2]) {
            $title = \htmlspecialchars(mb_substr($myrow['title'], 0, $options[2] - 1), ENT_QUOTES | ENT_HTML5) . '...';
        }

        $a_item['type'] = Adslight\Utility::getNameType($type);
        $a_item['id']   = $myrow['lid'];
        $a_item['cid']  = $myrow['cid'];

        $a_item['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/viewads.php?lid=" . addslashes($myrow['lid']) . "\"><b>{$title}</b></a>";

        if ('date_created' === $options[0]) {
            $a_item['date_created'] = formatTimestamp($myrow['date_created'], 's');
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
function adslight_edit($options): string
{
    global $xoopsDB;
    $moduleDirName = \basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date_created'";
    if ('date_created' === $options[0]) {
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
