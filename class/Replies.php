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

use XoopsModules\Adslight;
use XoopsModules\Adslight\Form;

//$permHelper = new \Xmf\Module\Helper\Permission();


/**
 * Class Replies
 */
class Replies extends \XoopsObject
{
    public $helper, $permHelper;
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
//        /** @var  Adslight\Helper $helper */
//        $this->helper = Adslight\Helper::getInstance();
         $this->permHelper = new \Xmf\Module\Helper\Permission();

        $this->initVar('r_lid', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('submitter', XOBJ_DTYPE_TXTBOX);
        $this->initVar('message', XOBJ_DTYPE_OTHER);
        $this->initVar('tele', XOBJ_DTYPE_TXTBOX);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX);
        $this->initVar('r_usid', XOBJ_DTYPE_INT);
     }

    /**
     * Get form
     *
     * @param null
     * @return Adslight\Form\RepliesForm
     */
    public function getForm()
    {
        $form = new Form\RepliesForm($this);
        return $form;
    }

        /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_read', $this->getVar('r_lid'));
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
          //$permHelper = new \Xmf\Module\Helper\Permission();
          return $this->permHelper->getGroupsForItem('sbcolumns_submit', $this->getVar('r_lid'));
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_moderation', $this->getVar('r_lid'));
    }
}

