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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/replies/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/replies/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_REPLIES_LIST, 'replies.php', 'list');
        $adminObject->displayButton('left');

        $repliesObject = $repliesHandler->create();
        $form          = $repliesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('replies.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('r_lid', 0)) {
            $repliesObject = $repliesHandler->get(Request::getInt('r_lid', 0));
        } else {
            $repliesObject = $repliesHandler->create();
        }
        // Form save fields
        $repliesObject->setVar('lid', Request::getVar('lid', ''));
        $repliesObject->setVar('title', Request::getVar('title', ''));
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, Request::getString('date', '', 'POST'));

        $repliesObject->setVar('date', $dateTimeObj->getTimestamp());
        $repliesObject->setVar('submitter', Request::getVar('submitter', ''));
        $repliesObject->setVar('message', Request::getText('message', ''));
        $repliesObject->setVar('tele', Request::getVar('tele', ''));
        $repliesObject->setVar('email', Request::getVar('email', ''));
        $repliesObject->setVar('r_usid', Request::getVar('r_usid', ''));
        if ($repliesHandler->insert($repliesObject)) {
            redirect_header('replies.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $repliesObject->getHtmlErrors();
        $form = $repliesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_REPLIES, 'replies.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_REPLIES_LIST, 'replies.php', 'list');
        $adminObject->displayButton('left');
        $repliesObject = $repliesHandler->get(Request::getString('r_lid', ''));
        $form          = $repliesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $repliesObject = $repliesHandler->get(Request::getString('r_lid', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('replies.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($repliesHandler->delete($repliesObject)) {
                redirect_header('replies.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $repliesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'r_lid' => Request::getString('r_lid', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $repliesObject->getVar('title')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('r_lid', '');

        if ($utility::cloneRecord('adslight_replies', 'r_lid', $id_field)) {
            redirect_header('replies.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('replies.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_REPLIES, 'replies.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                  = \Xmf\Request::getInt('start', 0);
        $repliesPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('r_lid ASC, title');
        $criteria->setOrder('ASC');
        $criteria->setLimit($repliesPaginationLimit);
        $criteria->setStart($start);
        $repliesTempRows  = $repliesHandler->getCount();
        $repliesTempArray = $repliesHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($repliesTempRows > $repliesPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $repliesTempRows, $repliesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('repliesRows', $repliesTempRows);
        $repliesArray = [];

        //    $fields = explode('|', r_lid:int:11::NOT NULL::primary:ID:0|lid:int:11::NOT NULL:0::Listing:1|title:varchar:50::NOT NULL:::Title:2|date:int:11::NOT NULL:0::Date:3|submitter:varchar:60::NOT NULL:::Submitter:4|message:text:0::NOT NULL:::Message:5|tele:varchar:15::NOT NULL:::Phone:6|email:varchar:100::NOT NULL:::Email:7|r_usid:int:11::NOT NULL:0::User:8);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($repliesPaginationLimit);
        $criteria->setStart($start);

        $repliesCount     = $repliesHandler->getCount($criteria);
        $repliesTempArray = $repliesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($repliesCount > 0) {
            foreach (array_keys($repliesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorr_lid', AM_ADSLIGHT_REPLIES_R_LID);
                $repliesArray['r_lid'] = $repliesTempArray[$i]->getVar('r_lid');

                $GLOBALS['xoopsTpl']->assign('selectorlid', AM_ADSLIGHT_REPLIES_LID);
                $repliesArray['lid'] = $listingHandler->get($repliesTempArray[$i]->getVar('lid'))->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectortitle', AM_ADSLIGHT_REPLIES_TITLE);
                $repliesArray['title'] = $repliesTempArray[$i]->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectordate', AM_ADSLIGHT_REPLIES_DATE);
                $repliesArray['date'] = formatTimestamp($repliesTempArray[$i]->getVar('date'), 's');

                $GLOBALS['xoopsTpl']->assign('selectorsubmitter', AM_ADSLIGHT_REPLIES_SUBMITTER);
                $repliesArray['submitter'] = strip_tags(\XoopsUser::getUnameFromId($repliesTempArray[$i]->getVar('submitter')));

                $GLOBALS['xoopsTpl']->assign('selectormessage', AM_ADSLIGHT_REPLIES_MESSAGE);
                $repliesArray['message'] = $repliesTempArray[$i]->getVar('message');

                $GLOBALS['xoopsTpl']->assign('selectortele', AM_ADSLIGHT_REPLIES_TELE);
                $repliesArray['tele'] = $repliesTempArray[$i]->getVar('tele');

                $GLOBALS['xoopsTpl']->assign('selectoremail', AM_ADSLIGHT_REPLIES_EMAIL);
                $repliesArray['email'] = $repliesTempArray[$i]->getVar('email');

                $GLOBALS['xoopsTpl']->assign('selectorr_usid', AM_ADSLIGHT_REPLIES_R_USID);
                $repliesArray['r_usid']      = strip_tags(\XoopsUser::getUnameFromId($repliesTempArray[$i]->getVar('r_usid')));
                $repliesArray['edit_delete'] = "<a href='replies.php?op=edit&r_lid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='replies.php?op=delete&r_lid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='replies.php?op=clone&r_lid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('repliesArrays', $repliesArray);
                unset($repliesArray);
            }
            unset($repliesTempArray);
            // Display Navigation
            if ($repliesCount > $repliesPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $repliesCount, $repliesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='replies.php?op=edit&r_lid=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='replies.php?op=delete&r_lid=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='10'>There are noXXX replies</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_replies.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
