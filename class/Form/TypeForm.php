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

use Xmf\Module\Helper\Permission;
use XoopsModules\Adslight\{
    Helper
};

require_once \dirname(__DIR__, 2) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__, 2));
$helper        = Helper::getInstance();
$permHelper    = new Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class TypeForm
 */
class TypeForm extends \XoopsThemeForm
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
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? \sprintf(\AM_ADSLIGHT_TYPE_ADD) : \sprintf(\AM_ADSLIGHT_TYPE_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('id_type', $this->targetObject->getVar('id_type'));
        $this->addElement($hidden);
        unset($hidden);

        // Id_type
        $this->addElement(new \XoopsFormLabel(\AM_ADSLIGHT_TYPE_ID_TYPE, $this->targetObject->getVar('id_type'), 'id_type'));
        // Nom_type
        $this->addElement(new \XoopsFormText(\AM_ADSLIGHT_TYPE_NOM_TYPE, 'nom_type', 50, 255, $this->targetObject->getVar('nom_type')), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', \_SUBMIT, 'submit'));
    }
}
