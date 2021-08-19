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
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 */

use Xmf\Module\Helper\Permission;
use XoopsModules\Adslight;

$moduleDirName = \basename(\dirname(__DIR__));

$permHelper = new Permission();

/**
 * Class CategoriesHandler
 */
class CategoriesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * Constructor
     * @param null|\XoopsDatabase                $db
     * @param null|\XoopsModules\Adslight\Helper $helper
     */

    public function __construct(\XoopsDatabase $db = null, $helper = null)
    {
        /** @var \XoopsModules\Adslight\Helper $this- >helper */
        $this->helper = $helper;
        parent::__construct($db, 'adslight_categories', Categories::class, 'cid', 'title');
    }

    /**
     * @param bool $isNew
     *
     * @return \XoopsObject
     */
    public function create($isNew = true): \XoopsObject
    {
        $obj         = parent::create($isNew);
        $obj->helper = $this->helper;

        return $obj;
    }
    
    
    //====================================

    /**
     * @param int $pid
     *
     * @return int
     */
    public function getCategoriesCount($pid = 0)
    {
        if (-1 == $pid) {
            return $this->getCount();
        }
        $helper = Helper::getInstance();
        $criteria = new \CriteriaCompo();
        if (isset($pid) && (-1 != $pid)) {
            $criteria->add(new \Criteria('pid', $pid));
            if (!$helper->isUserAdmin()) {
                $categoriesGranted = $this->helper->getHandler('Permission')->getGrantedItems('category_read');
                if (\count($categoriesGranted) > 0) {
                    $criteria->add(new \Criteria('cid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
                } else {
                    return 0;
                }
//                if (\is_object($GLOBALS['xoopsUser'])) {
//                    $criteria->add(new \Criteria('moderator', $GLOBALS['xoopsUser']->getVar('uid')), 'OR');
//                }
            }
        }

        return $this->getCount($criteria);
    }

    /**
     * Get all subcats and put them in an array indexed by parent id
     *
     * @param array $categories
     *
     * @return array
     */
    public function getSubCats($categories)
    {
        $helper = Helper::getInstance();
        $criteria = new \CriteriaCompo(new \Criteria('pid', '(' . \implode(',', \array_keys($categories)) . ')', 'IN'));
        $ret      = [];
        if (!$helper->isUserAdmin()) {
            $categoriesGranted = $this->helper->getHandler('Permission')->getGrantedItems('category_read');
            if (\count($categoriesGranted) > 0) {
                $criteria->add(new \Criteria('cid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }

            if (\is_object($GLOBALS['xoopsUser'])) {
                $criteria->add(new \Criteria('moderator', $GLOBALS['xoopsUser']->getVar('uid')), 'OR');
            }
        }
        $criteria->setSort('weight');
        $criteria->order = 'ASC'; // patch for XOOPS <= 2.5.10, does not set order correctly using setOrder() method
        $subcats         = $this->getObjects($criteria, true);
        /** @var Categories $subcat */
        foreach ($subcats as $subcat) {
            $ret[$subcat->getVar('pid')][$subcat->getVar('cid')] = $subcat;
        }

        return $ret;
    }
}
