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

use Xmf\Request;

require_once \dirname(__DIR__, 2) . '/mainfile.php';

$moduleDirName = \basename(__DIR__);

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    // Get link title
    $sql            = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid={$com_itemid}";
    $result         = $xoopsDB->query($sql);
    $row            = $xoopsDB->fetchArray($result);
    $com_replytitle = $row['title'];
    require_once XOOPS_ROOT_PATH . '/include/comment_new.php';
}
