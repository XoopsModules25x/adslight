<?php

declare(strict_types=1);

namespace XoopsModules\Adslight;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Adslight
 *
 * @category        Module
 * @package         adslight
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Helper\Permission;
use XoopsModules\Adslight\{
    Helper
};

$moduleDirName = \basename(\dirname(__DIR__));
$permHelper    = new Permission();

/**
 * Class ListingHandler
 */
class ListingHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     */
    public function __construct(?\XoopsDatabase $xoopsDatabase = null)
    {
        parent::__construct($xoopsDatabase, 'adslight_listing', Listing::class, 'lid', 'title');
    }
}
