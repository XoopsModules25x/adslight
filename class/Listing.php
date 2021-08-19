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

use Xmf\Module\Helper\Permission;

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

//$permHelper = new \Xmf\Module\Helper\Permission();

/**
 * Class Listing
 */
class Listing extends \XoopsObject
{
    public $helper;
    public $permHelper;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        /** @var Helper $helper */
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();
        $this->initVar('lid', \XOBJ_DTYPE_INT);
        $this->initVar('cid', \XOBJ_DTYPE_INT);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('expire', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('type', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('desctext', \XOBJ_DTYPE_OTHER);
        $this->initVar('tel', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('price', \XOBJ_DTYPE_DECIMAL);
        $this->initVar('typeprice', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('typecondition', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('date_created', \XOBJ_DTYPE_INT);
        $this->initVar('email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('submitter', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('usid', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('town', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('country', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('contactby', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('premium', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('valid', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('photo', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('hits', \XOBJ_DTYPE_INT);
        $this->initVar('item_rating', \XOBJ_DTYPE_DECIMAL);
        $this->initVar('item_votes', \XOBJ_DTYPE_INT);
        $this->initVar('user_rating', \XOBJ_DTYPE_DECIMAL);
        $this->initVar('user_votes', \XOBJ_DTYPE_INT);
        $this->initVar('comments', \XOBJ_DTYPE_INT);
        $this->initVar('remind', \XOBJ_DTYPE_INT);
    }

    /**
     * Get form
     *
     * @return \XoopsModules\Adslight\Form\ListingForm
     */
    public function getForm(): Form\ListingForm
    {
        return new Form\ListingForm($this);
    }


    public function getGroupsRead(): ?array
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_read',
            $this->getVar('lid')
        );
    }


    public function getGroupsSubmit(): ?array
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_submit',
            $this->getVar('lid')
        );
    }


    public function getGroupsModeration(): ?array
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_moderation',
            $this->getVar('lid')
        );
    }
}
