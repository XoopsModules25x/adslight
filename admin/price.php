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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/price/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/price/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_PRICE_LIST, 'price.php', 'list');
        $adminObject->displayButton('left');

        $priceObject = $priceHandler->create();
        $form        = $priceObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('price.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('id_price', 0)) {
            $priceObject = $priceHandler->get(Request::getInt('id_price', 0));
        } else {
            $priceObject = $priceHandler->create();
        }
        // Form save fields
        $priceObject->setVar('nom_price', Request::getVar('nom_price', ''));
        if ($priceHandler->insert($priceObject)) {
            redirect_header('price.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $priceObject->getHtmlErrors();
        $form = $priceObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_PRICE, 'price.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_PRICE_LIST, 'price.php', 'list');
        $adminObject->displayButton('left');
        $priceObject = $priceHandler->get(Request::getString('id_price', ''));
        $form        = $priceObject->getForm();
        $form->display();
        break;

    case 'delete':
        $priceObject = $priceHandler->get(Request::getString('id_price', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('price.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($priceHandler->delete($priceObject)) {
                redirect_header('price.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $priceObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id_price' => Request::getString('id_price', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $priceObject->getVar('nom_price')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('id_price', '');

        if ($utility::cloneRecord('adslight_price', 'id_price', $id_field)) {
            redirect_header('price.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('price.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_PRICE, 'price.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                = \Xmf\Request::getInt('start', 0);
        $pricePaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('id_price ASC, nom_price');
        $criteria->setOrder('ASC');
        $criteria->setLimit($pricePaginationLimit);
        $criteria->setStart($start);
        $priceTempRows  = $priceHandler->getCount();
        $priceTempArray = $priceHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($priceTempRows > $pricePaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $priceTempRows, $pricePaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('priceRows', $priceTempRows);
        $priceArray = [];

        //    $fields = explode('|', id_price:int:11::NOT NULL::primary:ID|nom_price:varchar:150::NOT NULL:::Price);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($pricePaginationLimit);
        $criteria->setStart($start);

        $priceCount     = $priceHandler->getCount($criteria);
        $priceTempArray = $priceHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($priceCount > 0) {
            foreach (array_keys($priceTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorid_price', AM_ADSLIGHT_PRICE_ID_PRICE);
                $priceArray['id_price'] = $priceTempArray[$i]->getVar('id_price');

                $GLOBALS['xoopsTpl']->assign('selectornom_price', AM_ADSLIGHT_PRICE_NOM_PRICE);
                $priceArray['nom_price']   = $priceTempArray[$i]->getVar('nom_price');
                $priceArray['edit_delete'] = "<a href='price.php?op=edit&id_price=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='price.php?op=delete&id_price=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='price.php?op=clone&id_price=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('priceArrays', $priceArray);
                unset($priceArray);
            }
            unset($priceTempArray);
            // Display Navigation
            if ($priceCount > $pricePaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $priceCount, $pricePaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='price.php?op=edit&id_price=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='price.php?op=delete&id_price=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='3'>There are noXXX price</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_price.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
