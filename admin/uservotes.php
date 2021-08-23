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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/uservotes/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/uservotes/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_USERVOTES_LIST, 'uservotes.php', 'list');
        $adminObject->displayButton('left');

        $uservotesObject = $uservotesHandler->create();
        $form            = $uservotesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('uservotes.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('ratingid', 0)) {
            $uservotesObject = $uservotesHandler->get(Request::getInt('ratingid', 0));
        } else {
            $uservotesObject = $uservotesHandler->create();
        }
        // Form save fields
        $uservotesObject->setVar('usid', Request::getVar('usid', ''));
        $uservotesObject->setVar('ratinguser', Request::getVar('ratinguser', ''));
        $uservotesObject->setVar('rating', Request::getVar('rating', ''));
        $uservotesObject->setVar('ratinghostname', Request::getVar('ratinghostname', ''));
        $resDate     = Request::getArray('ratingtimestamp', [], 'POST');
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, $resDate['date']);
        $dateTimeObj->setTime(0, 0, 0);
        $uservotesObject->setVar('ratingtimestamp', $dateTimeObj->getTimestamp() + $resDate['time']);
        if ($uservotesHandler->insert($uservotesObject)) {
            redirect_header('uservotes.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $uservotesObject->getHtmlErrors();
        $form = $uservotesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_USERVOTES, 'uservotes.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_USERVOTES_LIST, 'uservotes.php', 'list');
        $adminObject->displayButton('left');
        $uservotesObject = $uservotesHandler->get(Request::getString('ratingid', ''));
        $form            = $uservotesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $uservotesObject = $uservotesHandler->get(Request::getString('ratingid', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('uservotes.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($uservotesHandler->delete($uservotesObject)) {
                redirect_header('uservotes.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $uservotesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'ratingid' => Request::getString('ratingid', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $uservotesObject->getVar('ratingid')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('ratingid', '');

        if ($utility::cloneRecord('adslight_uservotes', 'ratingid', $id_field)) {
            redirect_header('uservotes.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('uservotes.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_USERVOTES, 'uservotes.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                    = \Xmf\Request::getInt('start', 0);
        $uservotesPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('ratingid ASC, ratingid');
        $criteria->setOrder('ASC');
        $criteria->setLimit($uservotesPaginationLimit);
        $criteria->setStart($start);
        $uservotesTempRows  = $uservotesHandler->getCount();
        $uservotesTempArray = $uservotesHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($uservotesTempRows > $uservotesPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $uservotesTempRows, $uservotesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('uservotesRows', $uservotesTempRows);
        $uservotesArray = [];

        //    $fields = explode('|', ratingid:int:11::NOT NULL::primary:ID:0|usid:int:11::NOT NULL:0::User:1|ratinguser:int:11::NOT NULL:0::Ratinguser:2|rating:tinyint:3::NOT NULL:0::Rating:3|ratinghostname:varchar:60::NOT NULL:::Ratinghostname:4|ratingtimestamp:int:11::NOT NULL:0::Ratingtimestamp:5);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($uservotesPaginationLimit);
        $criteria->setStart($start);

        $uservotesCount     = $uservotesHandler->getCount($criteria);
        $uservotesTempArray = $uservotesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($uservotesCount > 0) {
            foreach (array_keys($uservotesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorratingid', AM_ADSLIGHT_USERVOTES_RATINGID);
                $uservotesArray['ratingid'] = $uservotesTempArray[$i]->getVar('ratingid');

                $GLOBALS['xoopsTpl']->assign('selectorusid', AM_ADSLIGHT_USERVOTES_USID);
                $uservotesArray['usid'] = strip_tags(\XoopsUser::getUnameFromId($uservotesTempArray[$i]->getVar('usid')));

                $GLOBALS['xoopsTpl']->assign('selectorratinguser', AM_ADSLIGHT_USERVOTES_RATINGUSER);
                $uservotesArray['ratinguser'] = strip_tags(\XoopsUser::getUnameFromId($uservotesTempArray[$i]->getVar('ratinguser')));

                $GLOBALS['xoopsTpl']->assign('selectorrating', AM_ADSLIGHT_USERVOTES_RATING);
                $uservotesArray['rating'] = $uservotesTempArray[$i]->getVar('rating');

                $GLOBALS['xoopsTpl']->assign('selectorratinghostname', AM_ADSLIGHT_USERVOTES_RATINGHOSTNAME);
                $uservotesArray['ratinghostname'] = $uservotesTempArray[$i]->getVar('ratinghostname');

                $GLOBALS['xoopsTpl']->assign('selectorratingtimestamp', AM_ADSLIGHT_USERVOTES_RATINGTIMESTAMP);
                $uservotesArray['ratingtimestamp'] = formatTimestamp($uservotesTempArray[$i]->getVar('ratingtimestamp'), 's');
                $uservotesArray['edit_delete']     = "<a href='uservotes.php?op=edit&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='uservotes.php?op=delete&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='uservotes.php?op=clone&ratingid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('uservotesArrays', $uservotesArray);
                unset($uservotesArray);
            }
            unset($uservotesTempArray);
            // Display Navigation
            if ($uservotesCount > $uservotesPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $uservotesCount, $uservotesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='uservotes.php?op=edit&ratingid=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='uservotes.php?op=delete&ratingid=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='7'>There are noXXX uservotes</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_uservotes.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
