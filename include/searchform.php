<?php

declare(strict_types=1);
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
// create form
$search_form  = new \XoopsThemeForm(_SR_SEARCH, 'search', 'search.php', 'get');
$mids         = $xoopsModule->getVar('mid');
$mid          = $xoopsModule->getVar('name');
$module_array = [];

// create form elements
$search_form->addElement(new \XoopsFormText(_SR_KEYWORDS, 'query', 30, 255, htmlspecialchars(stripslashes(implode(' ', $queries)), ENT_QUOTES)), true);
$type_select = new \XoopsFormSelect(_SR_TYPE, 'andor', $andor);
$type_select->addOptionArray([
                                 'AND'   => _SR_ALL,
                                 'OR'    => _SR_ANY,
                                 'exact' => _SR_EXACT,
                             ]);
$search_form->addElement($type_select);

if (!empty($mids)) {
    $mods_checkbox = new \XoopsFormCheckBox(_SR_SEARCHIN, 'mids[]', $mids);
}
if (empty($modules)) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('hassearch', 1));
    $criteria->add(new \Criteria('isactive', 1));

    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $mods_checkbox->addOptionArray($moduleHandler->getList($criteria));
} else {
    foreach ($modules as $mids => $module) {
        $module_array[$mids] = $GLOBALS['xoopsModule']->getVar('name');
    }
    $mods_checkbox->addOptionArray($module_array);
}
$search_form->addElement($mods_checkbox);
if ($xoopsConfigSearch['keyword_min'] > 0) {
    $search_form->addElement(new \XoopsFormLabel(_SR_SEARCHRULE, sprintf(_SR_KEYIGNORE, $xoopsConfigSearch['keyword_min'])));
}
$search_form->addElement(new \XoopsFormHidden('action', 'results'));
$search_form->addElement(new \XoopsFormHiddenToken('id'));
$search_form->addElement(new \XoopsFormButton('', 'submit', _SR_SEARCH, 'submit'));
