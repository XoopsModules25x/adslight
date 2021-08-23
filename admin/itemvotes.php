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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/itemvotes/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/itemvotes/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_ITEMVOTES_LIST, 'itemvotes.php', 'list');
        $adminObject->displayButton('left');

        $itemvotesObject = $itemvotesHandler->create();
        $form            = $itemvotesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('itemvotes.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('ratingid', 0)) {
            $itemvotesObject = $itemvotesHandler->get(Request::getInt('ratingid', 0));
        } else {
            $itemvotesObject = $itemvotesHandler->create();
        }
        // Form save fields
        $itemvotesObject->setVar('lid', Request::getVar('lid', ''));
        $itemvotesObject->setVar('ratinguser', Request::getVar('ratinguser', ''));
        $itemvotesObject->setVar('rating', Request::getVar('rating', ''));
        $itemvotesObject->setVar('ratinghostname', Request::getVar('ratinghostname', ''));
        $resDate     = Request::getArray('ratingtimestamp', [], 'POST');
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, $resDate['date']);
        $dateTimeObj->setTime(0, 0, 0);
        $itemvotesObject->setVar('ratingtimestamp', $dateTimeObj->getTimestamp() + $resDate['time']);
        if ($itemvotesHandler->insert($itemvotesObject)) {
            redirect_header('itemvotes.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $itemvotesObject->getHtmlErrors();
        $form = $itemvotesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_ITEMVOTES, 'itemvotes.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_ITEMVOTES_LIST, 'itemvotes.php', 'list');
        $adminObject->displayButton('left');
        $itemvotesObject = $itemvotesHandler->get(Request::getString('ratingid', ''));
        $form            = $itemvotesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $itemvotesObject = $itemvotesHandler->get(Request::getString('ratingid', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('itemvotes.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($itemvotesHandler->delete($itemvotesObject)) {
                redirect_header('itemvotes.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $itemvotesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'ratingid' => Request::getString('ratingid', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $itemvotesObject->getVar('ratingid')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('ratingid', '');

        if ($utility::cloneRecord('adslight_itemvotes', 'ratingid', $id_field)) {
            redirect_header('itemvotes.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('itemvotes.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_ITEMVOTES, 'itemvotes.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                    = \Xmf\Request::getInt('start', 0);
        $itemvotesPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('ratingid ASC, ratingid');
        $criteria->setOrder('ASC');
        $criteria->setLimit($itemvotesPaginationLimit);
        $criteria->setStart($start);
        $itemvotesTempRows  = $itemvotesHandler->getCount();
        $itemvotesTempArray = $itemvotesHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($itemvotesTempRows > $itemvotesPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $itemvotesTempRows, $itemvotesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('itemvotesRows', $itemvotesTempRows);
        $itemvotesArray = [];

        //    $fields = explode('|', ratingid:int:11::NOT NULL::primary:ID:0|lid:int:11::NOT NULL:0::Listing:1|ratinguser:int:11::NOT NULL:0::Ratinguser:2|rating:tinyint:3::NOT NULL:0::Rating:3|ratinghostname:varchar:60::NOT NULL:::Ratinghostname:4|ratingtimestamp:int:11::NOT NULL:0::Ratingtimestamp:5);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($itemvotesPaginationLimit);
        $criteria->setStart($start);

        $itemvotesCount     = $itemvotesHandler->getCount($criteria);
        $itemvotesTempArray = $itemvotesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($itemvotesCount > 0) {
            foreach (array_keys($itemvotesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorratingid', AM_ADSLIGHT_ITEMVOTES_RATINGID);
                $itemvotesArray['ratingid'] = $itemvotesTempArray[$i]->getVar('ratingid');

                $GLOBALS['xoopsTpl']->assign('selectorlid', AM_ADSLIGHT_ITEMVOTES_LID);
                $itemvotesArray['lid'] = $listingHandler->get($itemvotesTempArray[$i]->getVar('lid'))->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectorratinguser', AM_ADSLIGHT_ITEMVOTES_RATINGUSER);
                $itemvotesArray['ratinguser'] = strip_tags(\XoopsUser::getUnameFromId($itemvotesTempArray[$i]->getVar('ratinguser')));

                $GLOBALS['xoopsTpl']->assign('selectorrating', AM_ADSLIGHT_ITEMVOTES_RATING);
                $itemvotesArray['rating'] = $itemvotesTempArray[$i]->getVar('rating');

                $GLOBALS['xoopsTpl']->assign('selectorratinghostname', AM_ADSLIGHT_ITEMVOTES_RATINGHOSTNAME);
                $itemvotesArray['ratinghostname'] = $itemvotesTempArray[$i]->getVar('ratinghostname');

                $GLOBALS['xoopsTpl']->assign('selectorratingtimestamp', AM_ADSLIGHT_ITEMVOTES_RATINGTIMESTAMP);
                $itemvotesArray['ratingtimestamp'] = formatTimestamp($itemvotesTempArray[$i]->getVar('ratingtimestamp'), 's');
                $itemvotesArray['edit_delete']     = "<a href='itemvotes.php?op=edit&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='itemvotes.php?op=delete&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='itemvotes.php?op=clone&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('itemvotesArrays', $itemvotesArray);
                unset($itemvotesArray);
            }
            unset($itemvotesTempArray);
            // Display Navigation
            if ($itemvotesCount > $itemvotesPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $itemvotesCount, $itemvotesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='itemvotes.php?op=edit&ratingid=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='itemvotes.php?op=delete&ratingid=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='7'>There are noXXX itemvotes</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_itemvotes.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
