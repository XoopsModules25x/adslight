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
 * Class Iplog
 */
class Iplog extends \XoopsObject
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

        $this->initVar('ip_id', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('date_created', XOBJ_DTYPE_INT);
        $this->initVar('submitter', XOBJ_DTYPE_TXTBOX);
        $this->initVar('ipnumber', XOBJ_DTYPE_TXTBOX);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX);
     }

    /**
     * Get form
     *
     * @param null
     * @return Adslight\Form\IplogForm
     */
    public function getForm()
    {
        $form = new Form\IplogForm($this);
        return $form;
    }

        /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_read', $this->getVar('ip_id'));
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
          //$permHelper = new \Xmf\Module\Helper\Permission();
          return $this->permHelper->getGroupsForItem('sbcolumns_submit', $this->getVar('ip_id'));
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_moderation', $this->getVar('ip_id'));
    }
}

