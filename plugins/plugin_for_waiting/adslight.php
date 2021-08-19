<?php

declare(strict_types=1);

function b_waiting_adslight(): array
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];
    $result  = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='No'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/adslight/admin/index.php';
        [$block['pendingnum']] = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _ADSLIGHT_PI_WAITING_WAITINGS;
    }
    return $block;
}
