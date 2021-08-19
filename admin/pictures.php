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
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');

$moduleDirName = \basename(\dirname(__DIR__));

$adminObject->displayNavigation(basename(__FILE__));
/** @var \Xmf\Module\Helper\Permission $permHelper */
$permHelper = new \Xmf\Module\Helper\Permission();
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_PICTURES_LIST, 'pictures.php', 'list');
        $adminObject->displayButton('left');

        $picturesObject = $picturesHandler->create();
        $form           = $picturesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pictures.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== Request::getInt('cod_img', 0)) {
            $picturesObject = $picturesHandler->get(Request::getInt('cod_img', 0));
        } else {
            $picturesObject = $picturesHandler->create();
        }
        // Form save fields
        $picturesObject->setVar('title', Request::getVar('title', ''));
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, Request::getString('date_created', '', 'POST'));

        $picturesObject->setVar('date_created', $dateTimeObj->getTimestamp());
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, Request::getString('date_updated', '', 'POST'));

        $picturesObject->setVar('date_updated', $dateTimeObj->getTimestamp());
        $picturesObject->setVar('lid', Request::getVar('lid', ''));
        $picturesObject->setVar('uid_owner', Request::getVar('uid_owner', ''));
        $picturesObject->setVar('url', Request::getVar('url', ''));
        if ($picturesHandler->insert($picturesObject)) {
            redirect_header('pictures.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $picturesObject->getHtmlErrors();
        $form = $picturesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_PICTURES, 'pictures.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_PICTURES_LIST, 'pictures.php', 'list');
        $adminObject->displayButton('left');
        $picturesObject = $picturesHandler->get(Request::getString('cod_img', ''));
        $form           = $picturesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $picturesObject = $picturesHandler->get(Request::getString('cod_img', ''));
        if (1 == Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('pictures.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($picturesHandler->delete($picturesObject)) {
                redirect_header('pictures.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $picturesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'cod_img' => Request::getString('cod_img', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $picturesObject->getVar('title')));
        }
        break;

    case 'clone':

        $id_field = Request::getString('cod_img', '');

        if ($utility::cloneRecord('adslight_pictures', 'cod_img', $id_field)) {
            redirect_header('pictures.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('pictures.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_PICTURES, 'pictures.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                   = Request::getInt('start', 0);
        $picturesPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('cod_img ASC, title');
        $criteria->setOrder('ASC');
        $criteria->setLimit($picturesPaginationLimit);
        $criteria->setStart($start);
        $picturesTempRows  = $picturesHandler->getCount();
        $picturesTempArray = $picturesHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($picturesTempRows > $picturesPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $picturesTempRows, $picturesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('picturesRows', $picturesTempRows);
        $picturesArray = [];

        //    $fields = explode('|', cod_img:int:11::NOT NULL::primary:ID:0|title:varchar:255::NOT NULL:::Title:1|date_created:int:11::NOT NULL:0::Added:2|date_updated:int:11::NOT NULL:0::Updated:3|lid:int:11::NOT NULL:0::Listing:4|uid_owner:varchar:50::NOT NULL:::Owner:5|url:text:0::NOT NULL:::URL:6);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($picturesPaginationLimit);
        $criteria->setStart($start);

        $picturesCount     = $picturesHandler->getCount($criteria);
        $picturesTempArray = $picturesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($picturesCount > 0) {
            foreach (array_keys($picturesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorcod_img', AM_ADSLIGHT_PICTURES_COD_IMG);
                $picturesArray['cod_img'] = $picturesTempArray[$i]->getVar('cod_img');

                $GLOBALS['xoopsTpl']->assign('selectortitle', AM_ADSLIGHT_PICTURES_TITLE);
                $picturesArray['title'] = $picturesTempArray[$i]->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectordate_created', AM_ADSLIGHT_PICTURES_DATE_CREATED);
                $picturesArray['date_created'] = formatTimestamp($picturesTempArray[$i]->getVar('date_created'), 's');

                $GLOBALS['xoopsTpl']->assign('selectordate_updated', AM_ADSLIGHT_PICTURES_DATE_UPDATED);
                $picturesArray['date_updated'] = formatTimestamp($picturesTempArray[$i]->getVar('date_updated'), 's');

                $GLOBALS['xoopsTpl']->assign('selectorlid', AM_ADSLIGHT_PICTURES_LID);
//                $picturesArray['lid'] = $listingHandler->get($picturesTempArray[$i]->getVar('lid'))->getVar('title');
                $picturesArray['lid'] = "<a href='" . $helper->url('viewads.php?lid=') .  $listingHandler->get($picturesTempArray[$i]->getVar('lid'))->getVar('lid') . "'>" . $listingHandler->get($picturesTempArray[$i]->getVar('lid'))->getVar('title'). "</a>";

                $GLOBALS['xoopsTpl']->assign('selectoruid_owner', AM_ADSLIGHT_PICTURES_UID_OWNER);
//                $picturesArray['uid_owner'] = strip_tags(\XoopsUser::getUnameFromId($picturesTempArray[$i]->getVar('uid_owner')));
                $picturesArray['uid_owner'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . ($picturesTempArray[$i]->getVar('uid_owner')) . "'>" . strip_tags(\XoopsUser::getUnameFromId($picturesTempArray[$i]->getVar('uid_owner'))). "</a>";

                $GLOBALS['xoopsTpl']->assign('selectorurl', AM_ADSLIGHT_PICTURES_URL);
//                $picturesArray['url']         = strip_tags($picturesTempArray[$i]->getVar('url'));

                $picturesArray['url'] = "<img src='" . $uploadUrl . $picturesTempArray[$i]->getVar('url') . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";


                $picturesArray['edit_delete'] = "<a href='pictures.php?op=edit&cod_img=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='pictures.php?op=delete&cod_img=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='pictures.php?op=clone&cod_img=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('picturesArrays', $picturesArray);
                unset($picturesArray);
            }
            unset($picturesTempArray);
            // Display Navigation
            if ($picturesCount > $picturesPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $picturesCount, $picturesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='pictures.php?op=edit&cod_img=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='pictures.php?op=delete&cod_img=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='8'>There are noXXX pictures</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_pictures.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
