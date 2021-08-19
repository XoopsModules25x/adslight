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
 * Class PriceForm
 */
class PriceForm extends \XoopsThemeForm
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

       $title = $this->targetObject->isNew() ? sprintf(AM_ADSLIGHT_PRICE_ADD) : sprintf(AM_ADSLIGHT_PRICE_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'),'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        


        //include ID field, it's needed so the module knows if it is a new form or an edited form
        

        $hidden = new \XoopsFormHidden('id_price', $this->targetObject->getVar('id_price'));
        $this->addElement($hidden);
        unset($hidden);
        
// Id_price
            $this->addElement(new \XoopsFormLabel(AM_ADSLIGHT_PRICE_ID_PRICE, $this->targetObject->getVar('id_price'), 'id_price' ));
            // Nom_price
        $this->addElement(new \XoopsFormText(AM_ADSLIGHT_PRICE_NOM_PRICE, 'nom_price', 50, 255, $this->targetObject->getVar('nom_price')), false);
                
        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
