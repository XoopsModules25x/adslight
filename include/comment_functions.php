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
// comment callback functions

$moduleDirName = \basename(dirname(__DIR__));
if (isset($usid)) {
    /**
     * @param $usid
     * @param $total_num
     */
    function adslight_com_update($usid, $total_num)
    {
        /** @var \XoopsMySQLDatabase $xoopsDB */
        $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql     = 'UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET comments = {$total_num} WHERE usid = {$usid}";
        $xoopsDB->query($sql);
    }

    /**
     * @param $comment
     */
    function adslight_com_approve(&$comment)
    {
        // notification mail here
    }
}
