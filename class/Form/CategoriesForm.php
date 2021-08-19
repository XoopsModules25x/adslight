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
 * Class CategoriesForm
 */
class CategoriesForm extends \XoopsThemeForm
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

       $title = $this->targetObject->isNew() ? sprintf(AM_ADSLIGHT_CATEGORIES_ADD) : sprintf(AM_ADSLIGHT_CATEGORIES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'),'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        


        //include ID field, it's needed so the module knows if it is a new form or an edited form
        

        $hidden = new \XoopsFormHidden('cid', $this->targetObject->getVar('cid'));
        $this->addElement($hidden);
        unset($hidden);
        
// Cid
            $this->addElement(new \XoopsFormLabel(AM_ADSLIGHT_CATEGORIES_CID, $this->targetObject->getVar('cid'), 'cid' ));
            // Pid
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
       //$categoriesHandler = xoops_getModuleHandler('categories', 'adslight' );
         //$db     = \XoopsDatabaseFactory::getDatabaseConnection();
         /** @var \XoopsPersistableObjectHandler $categoriesHandler */
        $categoriesHandler = $this->helper->getHandler('Categories'); 

        $criteria = new \CriteriaCompo();
        $categoryArray = $categoriesHandler->getObjects( $criteria );
        if (!empty($categoryArray)) {

            $categoryTree = new \XoopsObjectTree( $categoryArray, 'cid', 'pid' );

          // if (Adslight\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
                 $categoriesPid = $categoryTree->makeSelectElement('pid', 'title', '--', $this->targetObject->getVar('pid'), true, 0, '', AM_ADSLIGHT_CATEGORIES_PID);
                 $this->addElement($categoriesPid);
          //  } else {
          //      $categoriesPid = $categoryTree->makeSelBox( 'pid', 'title','--', $this->targetObject->getVar('pid', 'e' ), true );
          //      $this->addElement( new \XoopsFormLabel ( AM_ADSLIGHT_CATEGORIES_PID, $categoriesPid ) );
          //  }

        }
        // Title
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_CATEGORIES_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')), false);
        // Cat_desc
        if (class_exists('XoopsFormEditor')) {
        $editorOptions = [];
        $editorOptions['name'] = 'cat_desc';
        $editorOptions['value'] = $this->targetObject->getVar('cat_desc', 'e');
        $editorOptions['rows'] = 5;
        $editorOptions['cols'] = 40;
        $editorOptions['width'] = '100%';
        $editorOptions['height'] = '400px';
        //$editorOptions['editor'] = xoops_getModuleOption('adslight_editor', 'adslight');
        //$this->addElement( new \XoopsFormEditor(AM_ADSLIGHT_CATEGORIES_CAT_DESC, 'cat_desc', $editorOptions), false  );
        if ($this->helper->isUserAdmin()) {
        $descEditor = new \XoopsFormEditor(AM_ADSLIGHT_CATEGORIES_CAT_DESC, $this->helper->getConfig('adslightEditorAdmin'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
    } else {
        $descEditor = new \XoopsFormEditor(AM_ADSLIGHT_CATEGORIES_CAT_DESC, $this->helper->getConfig('adslightEditorUser'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
    }
} else {
    $descEditor = new \XoopsFormDhtmlTextArea(AM_ADSLIGHT_CATEGORIES_CAT_DESC, 'description', $this->targetObject->getVar('description', 'e'), 5, 50);
}
$this->addElement($descEditor);
        // Cat_keywords
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_CATEGORIES_CAT_KEYWORDS, 'cat_keywords', 50, 255, $this->targetObject->getVar('cat_keywords')), false);
        // Img
        $img = $this->targetObject->getVar('img') ?: 'blank.png';

        $uploadDir = '/uploads/adslight/categories/';
        $imgtray = new \XoopsFormElementTray(AM_ADSLIGHT_CATEGORIES_IMG,'<br>');
        $imgpath = sprintf(AM_ADSLIGHT_FORMIMAGE_PATH, $uploadDir);
        $imageselect = new \XoopsFormSelect($imgpath, 'img', $img);
        $imageArray = \XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . $uploadDir );
        foreach ($imageArray as $image) {
            $imageselect->addOption((string)$image, $image);
        }
        $imageselect->setExtra( "onchange='showImgSelected(\"image_img\", \"img\", \"".$uploadDir.'", "", "'.XOOPS_URL."\")'" );
        $imgtray->addElement($imageselect);
        $imgtray->addElement( new \XoopsFormLabel( '', "<br><img src='".XOOPS_URL.'/'.$uploadDir.'/'.$img."' name='image_img' id='image_img' alt='' style='max-width:300px' >" ) );
        $fileseltray = new \XoopsFormElementTray('','<br>');
        $fileseltray->addElement(new \XoopsFormFile(AM_ADSLIGHT_FORMUPLOAD , 'img', $this->helper->getConfig('maxsize')));
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $this->addElement($imgtray);
        // Cat_order
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_CATEGORIES_CAT_ORDER, 'cat_order', 50, 255, $this->targetObject->getVar('cat_order')), false);
        // Affprice
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_CATEGORIES_AFFPRICE, 'affprice', 50, 255, $this->targetObject->getVar('affprice')), false);
        // Cat_moderate
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_CATEGORIES_CAT_MODERATE, 'cat_moderate', false, $this->targetObject->getVar('cat_moderate'), 1, false), false);
        // Moderate_subcat
        $this->addElement(new \XoopsFormSelectUser(AM_ADSLIGHT_CATEGORIES_MODERATE_SUBCAT, 'moderate_subcat', false, $this->targetObject->getVar('moderate_subcat'), 1, false), false);
                
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
