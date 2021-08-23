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

//$xoTheme->addStylesheet('browse.php?Frameworks/jquery/plugins/css/tablesorter/theme.blue.min.css');
$xoTheme->addStylesheet($helper->url( 'assets/css/tablesorter/theme.blue.min.css'));

$moduleDirName = \basename(\dirname(__DIR__));

$adminObject->displayNavigation(basename(__FILE__));
/** @var \Xmf\Module\Helper\Permission $permHelper */
$permHelper = new \Xmf\Module\Helper\Permission();
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/type/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/type/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_TYPE_LIST, 'type.php', 'list');
        $adminObject->displayButton('left');

        $typeObject = $typeHandler->create();
        $form       = $typeObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('type.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('id_type', 0)) {
            $typeObject = $typeHandler->get(Request::getInt('id_type', 0));
        } else {
            $typeObject = $typeHandler->create();
        }
        // Form save fields
        $typeObject->setVar('nom_type', Request::getVar('nom_type', ''));
        if ($typeHandler->insert($typeObject)) {
            redirect_header('type.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $typeObject->getHtmlErrors();
        $form = $typeObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_TYPE, 'type.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_TYPE_LIST, 'type.php', 'list');
        $adminObject->displayButton('left');
        $typeObject = $typeHandler->get(Request::getString('id_type', ''));
        $form       = $typeObject->getForm();
        $form->display();
        break;

    case 'delete':
        $typeObject = $typeHandler->get(Request::getString('id_type', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('type.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($typeHandler->delete($typeObject)) {
                redirect_header('type.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $typeObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id_type' => Request::getString('id_type', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $typeObject->getVar('nom_type')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('id_type', '');

        if ($utility::cloneRecord('adslight_type', 'id_type', $id_field)) {
            redirect_header('type.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('type.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_TYPE, 'type.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start               = \Xmf\Request::getInt('start', 0);
        $typePaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('id_type ASC, nom_type');
        $criteria->setOrder('ASC');
        $criteria->setLimit($typePaginationLimit);
        $criteria->setStart($start);
        $typeTempRows  = $typeHandler->getCount();
        $typeTempArray = $typeHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($typeTempRows > $typePaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $typeTempRows, $typePaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('typeRows', $typeTempRows);
        $typeArray = [];

        //    $fields = explode('|', id_type:int:11::NOT NULL::primary:ID|nom_type:varchar:150::NOT NULL:::Name);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($typePaginationLimit);
        $criteria->setStart($start);

        $typeCount     = $typeHandler->getCount($criteria);
        $typeTempArray = $typeHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($typeCount > 0) {
            foreach (array_keys($typeTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorid_type', AM_ADSLIGHT_TYPE_ID_TYPE);
                $typeArray['id_type'] = $typeTempArray[$i]->getVar('id_type');

                $GLOBALS['xoopsTpl']->assign('selectornom_type', AM_ADSLIGHT_TYPE_NOM_TYPE);
                $typeArray['nom_type']    = $typeTempArray[$i]->getVar('nom_type');
                $typeArray['edit_delete'] = "<a href='type.php?op=edit&id_type=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='type.php?op=delete&id_type=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='type.php?op=clone&id_type=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('typeArrays', $typeArray);
                unset($typeArray);
            }
            unset($typeTempArray);
            // Display Navigation
            if ($typeCount > $typePaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $typeCount, $typePaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='type.php?op=edit&id_type=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='type.php?op=delete&id_type=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='3'>There are noXXX type</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_type.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
