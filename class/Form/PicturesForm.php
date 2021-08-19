<?php

declare(strict_types=1);

namespace XoopsModules\Adslight\Form;

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

use Xmf\Request;
use XoopsModules\Adslight;


require_once \dirname(dirname(__DIR__)) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__, 2));
//$helper = Adslight\Helper::getInstance();
$permHelper = new \Xmf\Module\Helper\Permission();

xoops_load('XoopsFormLoader');
/**
 * Class PicturesForm
 */
class PicturesForm extends \XoopsThemeForm
{
    public $targetObject;
    public $helper;
    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
      $this->helper = $target->helper;
      $this->targetObject = $target;

       $title = $this->targetObject->isNew() ? sprintf(AM_ADSLIGHT_PICTURES_ADD) : sprintf(AM_ADSLIGHT_PICTURES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'),'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        


        //include ID field, it's needed so the module knows if it is a new form or an edited form
        

        $hidden = new \XoopsFormHidden('cod_img', $this->targetObject->getVar('cod_img'));
        $this->addElement($hidden);
        unset($hidden);
        
// Cod_img
            $this->addElement(new \XoopsFormLabel(AM_ADSLIGHT_PICTURES_COD_IMG, $this->targetObject->getVar('cod_img'), 'cod_img' ));
            // Title
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_PICTURES_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')), false);
        // Date_created
        $this->addElement(new \XoopsFormTextDateSelect(AM_ADSLIGHT_PICTURES_DATE_CREATED, 'date_created', 0, formatTimestamp($this->targetObject->getVar('date_created'), 's')));
        // Date_updated
        $this->addElement(new \XoopsFormTextDateSelect(AM_ADSLIGHT_PICTURES_DATE_UPDATED, 'date_updated', 0, formatTimestamp($this->targetObject->getVar('date_updated'), 's')));
        // Lid
        //$listingHandler = $this->helper->getHandler('Listing');
         //$db     = \XoopsDatabaseFactory::getDatabaseConnection();
         /** @var \XoopsPersistableObjectHandler $listingHandler */
        $listingHandler = $this->helper->getHandler('Listing');


        $listing_id_select = new \XoopsFormSelect(AM_ADSLIGHT_PICTURES_LID, 'lid', $this->targetObject->getVar('lid'));
        $listing_id_select->addOptionArray($listingHandler->getList());
        $this->addElement($listing_id_select, false);
        // Uid_owner
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_PICTURES_UID_OWNER, 'uid_owner', false, $this->targetObject->getVar('uid_owner'), 1, false), false);
        // Url
        $this->addElement(new \XoopsFormTextArea(AM_ADSLIGHT_PICTURES_URL, 'url', $this->targetObject->getVar('url'), 4, 47), false);
                
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
