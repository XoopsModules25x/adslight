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
//require_once \dirname(__DIR__) . '/class/Tree.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
/**
 * @return array
 */
function b_adslight_add(): array
{
    global $xoopsDB;
    $moduleDirName = \basename(dirname(__DIR__));
    xoops_loadLanguage('main', $moduleDirName);
    $xoopsTree = new \XoopsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $jump      = XOOPS_URL . '/modules/adslight/addlisting.php?cid=';
    ob_start();
    $xoopsTree->makeMySelBox('title', 'title', 0, 1, 'pid', "location=\"{$jump}\"+this.options[this.selectedIndex].value");
    $block['selectbox'] = ob_get_clean();

    return $block;
}
