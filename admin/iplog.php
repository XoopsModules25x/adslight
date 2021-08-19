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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/iplog/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/iplog/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_IPLOG_LIST, 'iplog.php', 'list');
        $adminObject->displayButton('left');

        $iplogObject = $iplogHandler->create();
        $form        = $iplogObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('iplog.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('ip_id', 0)) {
            $iplogObject = $iplogHandler->get(Request::getInt('ip_id', 0));
        } else {
            $iplogObject = $iplogHandler->create();
        }
        // Form save fields
        $iplogObject->setVar('lid', Request::getVar('lid', ''));
        $resDate     = Request::getArray('date_created', [], 'POST');
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, $resDate['date']);
        $dateTimeObj->setTime(0, 0, 0);
        $iplogObject->setVar('date_created', $dateTimeObj->getTimestamp() + $resDate['time']);
        $iplogObject->setVar('submitter', Request::getVar('submitter', ''));
        $iplogObject->setVar('ipnumber', Request::getVar('ipnumber', ''));
        $iplogObject->setVar('email', Request::getVar('email', ''));
        if ($iplogHandler->insert($iplogObject)) {
            redirect_header('iplog.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $iplogObject->getHtmlErrors();
        $form = $iplogObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_IPLOG, 'iplog.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_IPLOG_LIST, 'iplog.php', 'list');
        $adminObject->displayButton('left');
        $iplogObject = $iplogHandler->get(Request::getString('ip_id', ''));
        $form        = $iplogObject->getForm();
        $form->display();
        break;

    case 'delete':
        $iplogObject = $iplogHandler->get(Request::getString('ip_id', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('iplog.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($iplogHandler->delete($iplogObject)) {
                redirect_header('iplog.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $iplogObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'ip_id' => Request::getString('ip_id', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $iplogObject->getVar('ip_id')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('ip_id', '');

        if ($utility::cloneRecord('adslight_iplog', 'ip_id', $id_field)) {
            redirect_header('iplog.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('iplog.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_IPLOG, 'iplog.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                = \Xmf\Request::getInt('start', 0);
        $iplogPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('ip_id ASC, ip_id');
        $criteria->setOrder('ASC');
        $criteria->setLimit($iplogPaginationLimit);
        $criteria->setStart($start);
        $iplogTempRows  = $iplogHandler->getCount();
        $iplogTempArray = $iplogHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($iplogTempRows > $iplogPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $iplogTempRows, $iplogPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('iplogRows', $iplogTempRows);
        $iplogArray = [];

        //    $fields = explode('|', ip_id:int:11::NOT NULL::primary:ID:0|lid:int:11::NOT NULL:0::Listing:1|date_created:int:11::NULL:::DateTimeCreated:2|submitter:varchar:60::NOT NULL:::Submitter:3|ipnumber:varchar:45::NOT NULL:::Ipnumber:4|email:varchar:100::NOT NULL:::Email:5);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($iplogPaginationLimit);
        $criteria->setStart($start);

        $iplogCount     = $iplogHandler->getCount($criteria);
        $iplogTempArray = $iplogHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($iplogCount > 0) {
            foreach (array_keys($iplogTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorip_id', AM_ADSLIGHT_IPLOG_IP_ID);
                $iplogArray['ip_id'] = $iplogTempArray[$i]->getVar('ip_id');

                $GLOBALS['xoopsTpl']->assign('selectorlid', AM_ADSLIGHT_IPLOG_LID);
                $iplogArray['lid'] = $listingHandler->get($iplogTempArray[$i]->getVar('lid'))->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectordate_created', AM_ADSLIGHT_IPLOG_DATE_CREATED);
                $iplogArray['date_created'] = formatTimestamp($iplogTempArray[$i]->getVar('date_created'), 's');

                $GLOBALS['xoopsTpl']->assign('selectorsubmitter', AM_ADSLIGHT_IPLOG_SUBMITTER);
                $iplogArray['submitter'] = strip_tags(\XoopsUser::getUnameFromId($iplogTempArray[$i]->getVar('submitter')));

                $GLOBALS['xoopsTpl']->assign('selectoripnumber', AM_ADSLIGHT_IPLOG_IPNUMBER);
                $iplogArray['ipnumber'] = $iplogTempArray[$i]->getVar('ipnumber');

                $GLOBALS['xoopsTpl']->assign('selectoremail', AM_ADSLIGHT_IPLOG_EMAIL);
                $iplogArray['email']       = $iplogTempArray[$i]->getVar('email');
                $iplogArray['edit_delete'] = "<a href='iplog.php?op=edit&ip_id=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='iplog.php?op=delete&ip_id=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='iplog.php?op=clone&ip_id=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('iplogArrays', $iplogArray);
                unset($iplogArray);
            }
            unset($iplogTempArray);
            // Display Navigation
            if ($iplogCount > $iplogPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $iplogCount, $iplogPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='iplog.php?op=edit&ip_id=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='iplog.php?op=delete&ip_id=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='7'>There are noXXX iplog</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_iplog.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
