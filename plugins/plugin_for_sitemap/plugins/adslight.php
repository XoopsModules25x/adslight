<?php

declare(strict_types=1);

function b_sitemap_adslight()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    return sitemap_get_categoires_map($xoopsDB->prefix('adslight_categories'), 'cid', 'pid', 'title', 'viewcats.php?cid=', 'title');
}
