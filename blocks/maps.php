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

// <{$xoops_url}>/modules/adslight/maps/<{$block.mapsname}>/assets/images/map.png

/**
 * @param array $options
 *
 * @return array
 */
function adslight_maps_show($options): array
{
    global $xoopsConfig, $block_lang;

    $maps_name = $xoopsConfig['language'];

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $moduleDirName = \basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $block['title'] = constant("{$block_lang}_TITLE");
    //@todo - move language string to language file
    $block['imgmapsurl'] = '<a title="Recherche dans votre region" href="' . XOOPS_URL . '/modules/adslight/maps.php"><img src="' . XOOPS_URL . '/modules/adslight/maps/' . $xoopsConfig['language'] . '/assets/images/map.png" alt="Recherche dans votre region" border="0"></a><br>';

    $block['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ALL_LISTINGS") . '</b></a><br>';
    $block['add']  = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ADDNOW") . '</b></a><br>';

    return $block;
}

/**
 * @param array $options
 *
 * @return string html form to display
 */
function adslight_maps_edit($options): string
{
    $moduleDirName = \basename(dirname(__DIR__));
    $block_lang    = '_MB_' . mb_strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date_created'";
    if ('date_created' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_DATE') . "</option>\n";

    $form .= "<option value='hits'";
    if ('hits' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . constant("{$block_lang}_HITS") . '</option>';
    $form .= "</select>\n";

    $form .= '&nbsp;' . constant("{$block_lang}_DISP") . "&nbsp;<input type='text' name='options[]' value='{$options[1]}'>&nbsp;" . constant("{$block_lang}_LISTINGS");
    $form .= '&nbsp;<br><br>' . constant("{$block_lang}_CHARS") . "&nbsp;<input type='text' name='options[]' value='{$options[2]}'>&nbsp;" . constant("{$block_lang}_LENGTH") . '<br><br>';

    return $form;
}
