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

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

// <{$xoops_url}>/modules/adslight/maps/<{$block.mapsname}>/assets/images/map.png

/**
 * @param array $options
 *
 * @return array
 */
function adslight_maps_show($options)
{
    global $xoopsConfig, $moduleDirName, $block_lang;

    $maps_name = $xoopsConfig['language'];

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . strtoupper($moduleDirName);

    $block['title'] = constant("{$block_lang}_TITLE");
    //@todo - move language string to language file
    $block['imgmapsurl'] = '<a title="Recherche dans votre r&eacute;gion" href="' . XOOPS_URL . '/modules/adslight/maps.php"><img src="' . XOOPS_URL . '/modules/adslight/maps/' . $xoopsConfig['language'] . '/assets/images/map.png" alt="Recherche dans votre r&eacute;gion" border="0"></a><br>';

    $block['link'] = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ALL_LISTINGS") . '</b></a><br>';
    $block['add']  = '<a href="' . XOOPS_URL . "/modules/{$moduleDirName}/\"><b>" . constant("{$block_lang}_ADDNOW") . '</b></a><br>';

    return $block;
}

/**
 * @param array $options
 *
 * @return string html form to display
 */
function adslight_maps_edit($options)
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $block_lang    = '_MB_' . strtoupper($moduleDirName);

    $form = constant("{$block_lang}_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ('date' === $options[0]) {
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
