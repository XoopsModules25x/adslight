<?php

declare(strict_types=1);

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

require __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = \Xmf\Request::getString('op', 'list');
$order = \Xmf\Request::getString('order', 'desc');
$sort  = \Xmf\Request::getString('sort', '');

$moduleDirName = \basename(\dirname(__DIR__));

$adminObject->displayNavigation(basename(__FILE__));
/** @var \Xmf\Module\Helper\Permission $permHelper */
$permHelper = new \Xmf\Module\Helper\Permission();
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/condition/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/condition/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_CONDITION_LIST, 'condition.php', 'list');
        $adminObject->displayButton('left');

        $conditionObject = $conditionHandler->create();
        $form            = $conditionObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('condition.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('id_condition', 0)) {
            $conditionObject = $conditionHandler->get(Request::getInt('id_condition', 0));
        } else {
            $conditionObject = $conditionHandler->create();
        }
        // Form save fields
        $conditionObject->setVar('nom_condition', Request::getVar('nom_condition', ''));
        if ($conditionHandler->insert($conditionObject)) {
            redirect_header('condition.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $conditionObject->getHtmlErrors();
        $form = $conditionObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_CONDITION, 'condition.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_CONDITION_LIST, 'condition.php', 'list');
        $adminObject->displayButton('left');
        $conditionObject = $conditionHandler->get(Request::getString('id_condition', ''));
        $form            = $conditionObject->getForm();
        $form->display();
        break;

    case 'delete':
        $conditionObject = $conditionHandler->get(Request::getString('id_condition', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('condition.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($conditionHandler->delete($conditionObject)) {
                redirect_header('condition.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $conditionObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id_condition' => Request::getString('id_condition', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $conditionObject->getVar('nom_condition')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('id_condition', '');

        if ($utility::cloneRecord('adslight_condition', 'id_condition', $id_field)) {
            redirect_header('condition.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('condition.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_CONDITION, 'condition.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                    = \Xmf\Request::getInt('start', 0);
        $conditionPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('id_condition ASC, nom_condition');
        $criteria->setOrder('ASC');
        $criteria->setLimit($conditionPaginationLimit);
        $criteria->setStart($start);
        $conditionTempRows  = $conditionHandler->getCount();
        $conditionTempArray = $conditionHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($conditionTempRows > $conditionPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $conditionTempRows, $conditionPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('conditionRows', $conditionTempRows);
        $conditionArray = [];

        //    $fields = explode('|', id_condition:int:11::NOT NULL::primary:ID:0|nom_condition:varchar:150::NOT NULL:::User:1);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($conditionPaginationLimit);
        $criteria->setStart($start);

        $conditionCount     = $conditionHandler->getCount($criteria);
        $conditionTempArray = $conditionHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($conditionCount > 0) {
            foreach (array_keys($conditionTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorid_condition', AM_ADSLIGHT_CONDITION_ID_CONDITION);
                $conditionArray['id_condition'] = $conditionTempArray[$i]->getVar('id_condition');

                $GLOBALS['xoopsTpl']->assign('selectornom_condition', AM_ADSLIGHT_CONDITION_NOM_CONDITION);
                $conditionArray['nom_condition'] = $conditionTempArray[$i]->getVar('nom_condition');
                $conditionArray['edit_delete']   = "<a href='condition.php?op=edit&id_condition=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='condition.php?op=delete&id_condition=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='condition.php?op=clone&id_condition=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('conditionArrays', $conditionArray);
                unset($conditionArray);
            }
            unset($conditionTempArray);
            // Display Navigation
            if ($conditionCount > $conditionPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $conditionCount, $conditionPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='condition.php?op=edit&id_condition=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='condition.php?op=delete&id_condition=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='3'>There are noXXX condition</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_condition.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
