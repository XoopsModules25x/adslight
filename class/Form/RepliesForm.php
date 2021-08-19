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
 * Class RepliesForm
 */
class RepliesForm extends \XoopsThemeForm
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

       $title = $this->targetObject->isNew() ? sprintf(AM_ADSLIGHT_REPLIES_ADD) : sprintf(AM_ADSLIGHT_REPLIES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'),'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        


        //include ID field, it's needed so the module knows if it is a new form or an edited form
        

        $hidden = new \XoopsFormHidden('r_lid', $this->targetObject->getVar('r_lid'));
        $this->addElement($hidden);
        unset($hidden);
        
// R_lid
            $this->addElement(new \XoopsFormLabel(AM_ADSLIGHT_REPLIES_R_LID, $this->targetObject->getVar('r_lid'), 'r_lid' ));
            // Lid
        //$listingHandler = $this->helper->getHandler('Listing');
         //$db     = \XoopsDatabaseFactory::getDatabaseConnection();
         /** @var \XoopsPersistableObjectHandler $listingHandler */
        $listingHandler = $this->helper->getHandler('Listing');


        $listing_id_select = new \XoopsFormSelect(AM_ADSLIGHT_REPLIES_LID, 'lid', $this->targetObject->getVar('lid'));
        $listing_id_select->addOptionArray($listingHandler->getList());
        $this->addElement($listing_id_select, false);
        // Title
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_REPLIES_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')), false);
        // Date
        $this->addElement(new \XoopsFormTextDateSelect(AM_ADSLIGHT_REPLIES_DATE, 'date', 0, formatTimestamp($this->targetObject->getVar('date'), 's')));
        // Submitter
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_REPLIES_SUBMITTER, 'submitter', false, $this->targetObject->getVar('submitter'), 1, false), false);
        // Message
        if (class_exists('XoopsFormEditor')) {
        $editorOptions = [];
        $editorOptions['name'] = 'message';
        $editorOptions['value'] = $this->targetObject->getVar('message', 'e');
        $editorOptions['rows'] = 5;
        $editorOptions['cols'] = 40;
        $editorOptions['width'] = '100%';
        $editorOptions['height'] = '400px';
        //$editorOptions['editor'] = xoops_getModuleOption('adslight_editor', 'adslight');
        //$this->addElement( new \XoopsFormEditor(AM_ADSLIGHT_REPLIES_MESSAGE, 'message', $editorOptions), false  );
        if ($this->helper->isUserAdmin()) {
        $descEditor = new \XoopsFormEditor(AM_ADSLIGHT_REPLIES_MESSAGE, $this->helper->getConfig('adslightEditorAdmin'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
    } else {
        $descEditor = new \XoopsFormEditor(AM_ADSLIGHT_REPLIES_MESSAGE, $this->helper->getConfig('adslightEditorUser'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
    }
} else {
    $descEditor = new \XoopsFormDhtmlTextArea(AM_ADSLIGHT_REPLIES_MESSAGE, 'description', $this->targetObject->getVar('description', 'e'), 5, 50);
}
$this->addElement($descEditor);
        // Tele
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_REPLIES_TELE, 'tele', 50, 255, $this->targetObject->getVar('tele')), false);
        // Email
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_REPLIES_EMAIL, 'email', 50, 255, $this->targetObject->getVar('email')), false);
        // R_usid
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_REPLIES_R_USID, 'r_usid', false, $this->targetObject->getVar('r_usid'), 1, false), false);
                
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
