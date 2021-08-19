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
 * @package         adslight
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Helper\Permission;
use XoopsDatabaseFactory;
use XoopsModules\Adslight\{
    Helper,
    ItemvotedataHandler,
    TypeHandler,
    UservotedataHandler,
    Utility
};
use XoopsThemeForm;

require_once \dirname(__DIR__, 2) . '/include/common.php';
$moduleDirName = \basename(\dirname(__DIR__, 2));
//$helper = Helper::getInstance();
$permHelper = new Permission();
\xoops_load('XoopsFormLoader');

/**
 * Class ListingForm
 */
class ListingForm extends XoopsThemeForm
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
        //  global $helper;
        $this->helper       = $target->helper;
        $this->targetObject = $target;
        $title              = $this->targetObject->isNew() ? \sprintf(\AM_ADSLIGHT_LISTING_ADD) : \sprintf(\AM_ADSLIGHT_LISTING_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        //include ID field, it's needed so the module knows if it is a new form or an edited form
        $hidden = new \XoopsFormHidden(
            'lid', $this->targetObject->getVar(
            'lid'
        )
        );
        $this->addElement($hidden);
        unset($hidden);
        // Lid
        $this->addElement(
            new \XoopsFormLabel(\AM_ADSLIGHT_LISTING_LID, $this->targetObject->getVar('lid'), 'lid')
        );
        // Cid
        //        $this->addElement(
        //            new \XoopsFormText(AM_ADSLIGHT_LISTING_CID, 'cid', 50, 255, $this->targetObject->getVar('cid')),
        //            false
        //        );

        //$categoriesHandler = $this->helper->getHandler('Categories');
        //$db     = \XoopsDatabaseFactory::getDatabaseConnection();
        /** @var \XoopsPersistableObjectHandler $categoriesHandler */
        $categoriesHandler = $this->helper->getHandler('Categories');

        $categories_id_select = new \XoopsFormSelect(\AM_ADSLIGHT_LISTING_CID, 'cid', $this->targetObject->getVar('cid'));
        $categories_id_select->addOptionArray($categoriesHandler->getList());
        $this->addElement($categories_id_select, false);

        // Title
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')),
            false
        );
        // Status
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_STATUS, 'status', 50, 255, $this->targetObject->getVar('status')),
            false
        );
        // Expire
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_EXPIRE, 'expire', 50, 255, $this->targetObject->getVar('expire')),
            false
        );
        // Type
        //$typeHandler = $this->helper->getHandler('Type');
        $db             = XoopsDatabaseFactory::getDatabaseConnection();
        $typeHandler    = new TypeHandler($db);
        $type_id_select = new \XoopsFormSelect(\AM_ADSLIGHT_LISTING_TYPE, 'type', $this->targetObject->getVar('type'));
        $type_id_select->addOptionArray($typeHandler->getList());
        $this->addElement($type_id_select, false);
        // Desctext
        if (\class_exists('XoopsFormEditor')) {
            $editorOptions           = [];
            $editorOptions['name']   = 'desctext';
            $editorOptions['value']  = $this->targetObject->getVar('desctext', 'e');
            $editorOptions['rows']   = 5;
            $editorOptions['cols']   = 40;
            $editorOptions['width']  = '100%';
            $editorOptions['height'] = '400px';
            //$editorOptions['editor'] = xoops_getModuleOption('adslight_editor', 'adslight');
            //$this->addElement( new \XoopsFormEditor(AM_ADSLIGHT_LISTING_DESCTEXT, 'desctext', $editorOptions), false  );
            if ($this->helper->isUserAdmin()) {
                $descEditor = new \XoopsFormEditor(
                    \AM_ADSLIGHT_LISTING_DESCTEXT, $this->helper->getConfig(
                    'adslightEditorAdmin'
                ),  $editorOptions, $nohtml = false, $onfailure = 'textarea'
                );
            } else {
                $descEditor = new \XoopsFormEditor(
                    \AM_ADSLIGHT_LISTING_DESCTEXT, $this->helper->getConfig(
                    'adslightEditorUser'
                ),  $editorOptions, $nohtml = false, $onfailure = 'textarea'
                );
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(
                \AM_ADSLIGHT_LISTING_DESCTEXT, 'description', $this->targetObject->getVar(
                'description',
                'e'
            ),  '100%', '100%'
            );
        }
        $this->addElement($descEditor);
        // Tel
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_TEL, 'tel', 50, 255, $this->targetObject->getVar('tel')),
            false
        );
        // Price
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_PRICE, 'price', 50, 255, $this->targetObject->getVar('price')),
            false
        );
        // Typeprice
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_TYPEPRICE, 'typeprice', 50, 255, $this->targetObject->getVar(
                'typeprice'
            )
            ),
            false
        );
        // Typecondition
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_TYPECONDITION, 'typecondition', 50, 255, $this->targetObject->getVar(
                'typecondition'
            )
            ),
            false
        );
        // Date
        $this->addElement(
            new \XoopsFormTextDateSelect(
                \AM_ADSLIGHT_LISTING_DATE, 'date_created', 0, \formatTimestamp($this->targetObject->getVar('date_created'), 's')
            )
        );
        // Email
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_EMAIL, 'email', 50, 255, $this->targetObject->getVar('email')),
            false
        );
        // Submitter
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_SUBMITTER, 'submitter', 50, 255, $this->targetObject->getVar(
                'submitter'
            )
            ),
            false
        );
        // Usid
        $this->addElement(
            new \XoopsFormSelectUser(
                \AM_ADSLIGHT_LISTING_USID, 'usid', false, $this->targetObject->getVar(
                'usid'
            ),  1, false
            ),
            false
        );
        // Town
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_TOWN, 'town', 50, 255, $this->targetObject->getVar('town')),
            false
        );
        // Country
        $country      = new \XoopsFormSelect(
            \AM_ADSLIGHT_LISTING_COUNTRY, 'country', $this->targetObject->getVar(
            'country'
        )
        );
        $optionsArray = Utility::enumerate('adslight_listing', 'country');
        if (!\is_array($optionsArray)) {
            throw new \RuntimeException($optionsArray . ' must be an array.');
        }
        foreach ($optionsArray as $enum) {
            $country->addOption($enum, (\defined($enum) ? \constant($enum) : $enum));
        }
        $this->addElement($country, false);
        // Contactby
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_CONTACTBY, 'contactby', 50, 255, $this->targetObject->getVar(
                'contactby'
            )
            ),
            false
        );
        // Premium
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_PREMIUM, 'premium', 50, 255, $this->targetObject->getVar(
                'premium'
            )
            ),
            false
        );
        // Valid
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_VALID, 'valid', 50, 255, $this->targetObject->getVar('valid')),
            false
        );
        // Photo
        $photo       = $this->targetObject->getVar('photo') ?: 'blank.png';
        $uploadDir   = '/uploads/adslight/';
        $imgtray     = new \XoopsFormElementTray(\AM_ADSLIGHT_LISTING_PHOTO, '<br>');
        $imgpath     = \sprintf(\AM_ADSLIGHT_FORMIMAGE_PATH, $uploadDir);
        $imageselect = new \XoopsFormSelect($imgpath, 'photo', $photo);
        $imageArray  = \XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadDir);
        foreach ($imageArray as $image) {
            $imageselect->addOption($image, $image);
        }
        $imageselect->setExtra(
            "onchange='showImgSelected(\"image_photo\", \"photo\", \"" . $uploadDir . '", "", "' . XOOPS_URL . "\")'"
        );
        $imgtray->addElement($imageselect);
        $imgtray->addElement(
            new \XoopsFormLabel(
                '', "<br><img src='" . XOOPS_URL . '/' . $uploadDir . '/' . $photo . "' name='image_photo' id='image_photo' alt=''>"
            )
        );
        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(
            new \XoopsFormFile(\AM_ADSLIGHT_FORMUPLOAD, 'photo', \xoops_getModuleOption('maxsize'))
        );
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $this->addElement($imgtray);
        // Hits
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_HITS, 'hits', 50, 255, $this->targetObject->getVar('hits')),
            false
        );
        // Item_rating
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_ITEM_RATING, 'item_rating', 50, 255, $this->targetObject->getVar(
                'item_rating'
            )
            ),
            false
        );
        // Item_votes
        //$itemvotedataHandler = $this->helper->getHandler('Itemvotedata');
        $db                     = \XoopsDatabaseFactory::getDatabaseConnection();
        $itemvotedataHandler    = new ItemvotedataHandler($db);
        $itemvotedata_id_select = new \XoopsFormSelect(
            \AM_ADSLIGHT_LISTING_ITEM_VOTES, 'item_votes', $this->targetObject->getVar(
            'item_votes'
        )
        );
        $itemvotedata_id_select->addOptionArray($itemvotedataHandler->getList());
        $this->addElement($itemvotedata_id_select, false);
        // User_rating
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_USER_RATING, 'user_rating', 50, 255, $this->targetObject->getVar(
                'user_rating'
            )
            ),
            false
        );
        // User_votes
        //$uservotedataHandler = $this->helper->getHandler('Uservotedata');
        $db                     = \XoopsDatabaseFactory::getDatabaseConnection();
        $uservotedataHandler    = new UservotedataHandler(
            $db
        );
        $uservotedata_id_select = new \XoopsFormSelect(
            \AM_ADSLIGHT_LISTING_USER_VOTES, 'user_votes', $this->targetObject->getVar(
            'user_votes'
        )
        );
        $uservotedata_id_select->addOptionArray($uservotedataHandler->getList());
        $this->addElement($uservotedata_id_select, false);
        // Comments
        $this->addElement(
            new \XoopsFormText(
                \AM_ADSLIGHT_LISTING_COMMENTS, 'comments', 50, 255, $this->targetObject->getVar(
                'comments'
            )
            ),
            false
        );
        // Remind
        $this->addElement(
            new \XoopsFormText(\AM_ADSLIGHT_LISTING_REMIND, 'remind', 50, 255, $this->targetObject->getVar('remind')),
            false
        );
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', \_SUBMIT, 'submit'));
    }
}
