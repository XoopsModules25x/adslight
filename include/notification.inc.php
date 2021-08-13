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

/**
 * @param $category
 * @param $item_id
 *
 * @return array|void
 */
function adslight_notify_iteminfo($category, $item_id)
{
    global $xoopsDB;
    $moduleDirName = \basename(dirname(__DIR__));
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);

    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    $item_id = (int)$item_id;
    if ('category' === $category) {
        // Assume we have a valid topid id
        $sql = 'SELECT SQL_CACHE title  FROM ' . $xoopsDB->prefix('adslight_categories') . " WHERE cid ={$item_id} LIMIT 1";

        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url']  = XOOPS_URL . '/modules/adslight/index.php?pa=adsview&amp;cid=' . $item_id;

            return $item;
        } else {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $myModule      = $moduleHandler->getByDirname('adslight');
            $myModule->setErrors('Could not query the database.');
        }
    }

    if ('listing' === $category) {
        // Assume we have a valid post id
        $sql          = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE lid={$item_id} LIMIT 1";
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        //      $item['catname'] = $result_array['cat.title'];
        $item['url'] = XOOPS_URL . '/modules/adslight/viewads.php?lid= ' . $item_id;

        return $item;
    }
}
