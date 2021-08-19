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
 * Class ItemvotesForm
 */
class ItemvotesForm extends \XoopsThemeForm
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

       $title = $this->targetObject->isNew() ? sprintf(AM_ADSLIGHT_ITEMVOTES_ADD) : sprintf(AM_ADSLIGHT_ITEMVOTES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'),'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        


        //include ID field, it's needed so the module knows if it is a new form or an edited form
        

        $hidden = new \XoopsFormHidden('ratingid', $this->targetObject->getVar('ratingid'));
        $this->addElement($hidden);
        unset($hidden);
        
// Ratingid
            $this->addElement(new \XoopsFormLabel(AM_ADSLIGHT_ITEMVOTES_RATINGID, $this->targetObject->getVar('ratingid'), 'ratingid' ));
            // Lid
        //$listingHandler = $this->helper->getHandler('Listing');
         //$db     = \XoopsDatabaseFactory::getDatabaseConnection();
         /** @var \XoopsPersistableObjectHandler $listingHandler */
        $listingHandler = $this->helper->getHandler('Listing');


        $listing_id_select = new \XoopsFormSelect(AM_ADSLIGHT_ITEMVOTES_LID, 'lid', $this->targetObject->getVar('lid'));
        $listing_id_select->addOptionArray($listingHandler->getList());
        $this->addElement($listing_id_select, false);
        // Ratinguser
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_ITEMVOTES_RATINGUSER, 'ratinguser', false, $this->targetObject->getVar('ratinguser'), 1, false), false);
        // Rating
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_ITEMVOTES_RATING, 'rating', 50, 255, $this->targetObject->getVar('rating')), false);
        // Ratinghostname
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_ITEMVOTES_RATINGHOSTNAME, 'ratinghostname', 50, 255, $this->targetObject->getVar('ratinghostname')), false);
        // Ratingtimestamp
        $this->addElement(new \XoopsFormDateTime(AM_ADSLIGHT_ITEMVOTES_RATINGTIMESTAMP, 'ratingtimestamp', 0, $this->targetObject->getVar('ratingtimestamp')));
                
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
