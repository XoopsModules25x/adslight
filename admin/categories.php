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
/** @var Helper $helper */

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
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/categories/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/categories/";

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_CATEGORIES_LIST, 'categories.php', 'list');
        $adminObject->displayButton('left');

        $categoriesObject = $categoriesHandler->create();
        $form             = $categoriesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('categories.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== \Xmf\Request::getInt('cid', 0)) {
            $categoriesObject = $categoriesHandler->get(Request::getInt('cid', 0));
        } else {
            $categoriesObject = $categoriesHandler->create();
        }
        // Form save fields
        $categoriesObject->setVar('pid', Request::getVar('pid', ''));
        $categoriesObject->setVar('title', Request::getVar('title', ''));
        $categoriesObject->setVar('cat_desc', Request::getText('cat_desc', ''));
        $categoriesObject->setVar('cat_keywords', Request::getVar('cat_keywords', ''));

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploadDir = XOOPS_UPLOAD_PATH . '/adslight/categories/';
        $uploader  = new \XoopsMediaUploader(
            $uploadDir, $helper->getConfig('mimetypes'), $helper->getConfig('maxsize'), null, null
        );
        if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0])) {
            //$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , $_FILES['attachedfile']['name']);
            //$imgName = str_replace(' ', '', $_POST['img']).'.'.$extension;

            $uploader->setPrefix('img_');
            $uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $categoriesObject->setVar('img', $uploader->getSavedFileName());
            }
        } else {
            $categoriesObject->setVar('img', Request::getVar('img', ''));
        }

        $categoriesObject->setVar('cat_order', Request::getVar('cat_order', ''));
        $categoriesObject->setVar('affprice', Request::getVar('affprice', ''));
        $categoriesObject->setVar('cat_moderate', Request::getVar('cat_moderate', ''));
        $categoriesObject->setVar('moderate_subcat', Request::getVar('moderate_subcat', ''));
        if ($categoriesHandler->insert($categoriesObject)) {
            redirect_header('categories.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $categoriesObject->getHtmlErrors();
        $form = $categoriesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_CATEGORIES, 'categories.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_CATEGORIES_LIST, 'categories.php', 'list');
        $adminObject->displayButton('left');
        $categoriesObject = $categoriesHandler->get(Request::getString('cid', ''));
        $form             = $categoriesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $categoriesObject = $categoriesHandler->get(Request::getString('cid', ''));
        if (1 == \Xmf\Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('categories.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($categoriesHandler->delete($categoriesObject)) {
                redirect_header('categories.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $categoriesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'cid' => Request::getString('cid', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $categoriesObject->getVar('title')));
        }
        break;

    case 'clone':

        $id_field = \Xmf\Request::getString('cid', '');

        if ($utility::cloneRecord('adslight_categories', 'cid', $id_field)) {
            redirect_header('categories.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('categories.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_CATEGORIES, 'categories.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                     = \Xmf\Request::getInt('start', 0);
        $categoriesPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('cid ASC, title');
        $criteria->setOrder('ASC');
        $criteria->setLimit($categoriesPaginationLimit);
        $criteria->setStart($start);
        $categoriesTempRows  = $categoriesHandler->getCount();
        $categoriesTempArray = $categoriesHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */




//    // Creating the objects for top categories
//    $categoriesObj = $helper->getHandler('Categories')->getCategories($helper->getConfig('idxcat_perpage'), $startcategory, 0);
//
//    echo '</tr>';
//    /** @var \XoopsPersistableObjectHandler $categoriesHandler */
//    $totalCategories = $categoriesHandler->getCount();
//
////    $totalCategories2 = $helper->getHandler('Categories')->getCategoriesCount(0);
//
//    if (is_iterable($categoriesObject) && count($categoriesObject) > 0) {
//        foreach ($categoriesObject as $key => $thiscat) {
//            Utility::displayCategory($thiscat);
//        }
//        unset($key);
//    } else {
//        echo '<tr>';
//        echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOCAT . '</td>';
//        echo '</tr>';
//        $categoryId = '0';
//    }




        // Display Page Navigation
        if ($categoriesTempRows > $categoriesPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav(
                $categoriesTempRows, $categoriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('categoriesRows', $categoriesTempRows);
        $categoriesArray = [];

        //    $fields = explode('|', cid:int:11::NOT NULL::primary:ID:0|pid:int:5:unsigned:NOT NULL:0::Parent:1|title:varchar:50::NOT NULL:::Title:2|cat_desc:text:200::NOT NULL:::Desc:3|cat_keywords:varchar:1000::NOT NULL:::Keywords:4|img:varchar:150::NOT NULL:default.png::Image:5|cat_order:int:5::NOT NULL:0::Order:6|affprice:int:5::NOT NULL:1::Price:7|cat_moderate:int:5::NOT NULL:1::CatModerator:8|moderate_subcat:int:5::NOT NULL:1::SubcatModerator:9);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($categoriesPaginationLimit);
        $criteria->setStart($start);

        $categoriesCount     = $categoriesHandler->getCount($criteria);
        $categoriesTempArray = $categoriesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($categoriesCount > 0) {
            foreach (array_keys($categoriesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $GLOBALS['xoopsTpl']->assign('selectorcid', AM_ADSLIGHT_CATEGORIES_CID);
                $categoriesArray['cid'] = $categoriesTempArray[$i]->getVar('cid');

                $GLOBALS['xoopsTpl']->assign('selectorpid', AM_ADSLIGHT_CATEGORIES_PID);
                $categoriesArray['pid'] = $categoriesTempArray[$i]->getVar('pid');

                $GLOBALS['xoopsTpl']->assign('selectortitle', AM_ADSLIGHT_CATEGORIES_TITLE);
                $categoriesArray['title'] = $categoriesTempArray[$i]->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectorcat_desc', AM_ADSLIGHT_CATEGORIES_CAT_DESC);
                $categoriesArray['cat_desc'] = $categoriesTempArray[$i]->getVar('cat_desc');

                $GLOBALS['xoopsTpl']->assign('selectorcat_keywords', AM_ADSLIGHT_CATEGORIES_CAT_KEYWORDS);
                $categoriesArray['cat_keywords'] = $categoriesTempArray[$i]->getVar('cat_keywords');

                $GLOBALS['xoopsTpl']->assign('selectorimg', AM_ADSLIGHT_CATEGORIES_IMG);
                $categoriesArray['img'] = "<img src='" . $uploadUrl . $categoriesTempArray[$i]->getVar('img') . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";

                $GLOBALS['xoopsTpl']->assign('selectorcat_order', AM_ADSLIGHT_CATEGORIES_CAT_ORDER);
                $categoriesArray['cat_order'] = $categoriesTempArray[$i]->getVar('cat_order');

                $GLOBALS['xoopsTpl']->assign('selectoraffprice', AM_ADSLIGHT_CATEGORIES_AFFPRICE);
                $categoriesArray['affprice'] = $categoriesTempArray[$i]->getVar('affprice');

                $GLOBALS['xoopsTpl']->assign('selectorcat_moderate', AM_ADSLIGHT_CATEGORIES_CAT_MODERATE);
                $categoriesArray['cat_moderate'] = strip_tags(\XoopsUser::getUnameFromId($categoriesTempArray[$i]->getVar('cat_moderate')));

                $GLOBALS['xoopsTpl']->assign('selectormoderate_subcat', AM_ADSLIGHT_CATEGORIES_MODERATE_SUBCAT);
                $categoriesArray['moderate_subcat'] = strip_tags(\XoopsUser::getUnameFromId($categoriesTempArray[$i]->getVar('moderate_subcat')));
                $categoriesArray['edit_delete']     = "<a href='categories.php?op=edit&cid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='categories.php?op=delete&cid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='categories.php?op=clone&cid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('categoriesArrays', $categoriesArray);
                unset($categoriesArray);
            }
            unset($categoriesTempArray);
            // Display Navigation
            if ($categoriesCount > $categoriesPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $categoriesCount, $categoriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='categories.php?op=edit&cid=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='categories.php?op=delete&cid=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='11'>There are noXXX categories</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_categories.tpl'
            );
        }

        break;
}
require __DIR__ . '/admin_footer.php';
