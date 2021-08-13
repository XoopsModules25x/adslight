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
 * @package         adslight
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Admin;
use Xmf\Module\Helper\Permission;
use Xmf\Request;
use XoopsModules\Adslight\{
    Helper,
    ListingHandler,
    Utility
};

/** @var Helper $helper */
/** @var Utility $utility */
/** @var Admin $adminObject */
/** @var ListingHandler $listingHandler */

require __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');
$adminObject->displayNavigation(basename(__FILE__));
$permHelper = new Permission();
$helper     = Helper::getInstance();
$uploadDir  = XOOPS_UPLOAD_PATH . '/adslight/images/';
$uploadUrl  = XOOPS_UPLOAD_URL . '/adslight/images/';
switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_ADSLIGHT_LISTING_LIST, 'listing.php', 'list');
        $adminObject->displayButton('left');
        $listingObject = $listingHandler->create();
        $form          = $listingObject->getForm();
        $form->display();
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/listing.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== Request::getInt('lid', 0)) {
            $listingObject = $listingHandler->get(Request::getInt('lid', 0));
        } else {
            $listingObject = $listingHandler->create();
        }
        // Form save fields
        $listingObject->setVar('cid', Request::getInt('cid', 0));
        $listingObject->setVar('title', Request::getString('title', ''));
        $listingObject->setVar('status', Request::getInt('status', 0));
        $listingObject->setVar('expire', Request::getString('expire', ''));
        $listingObject->setVar('type', Request::getString('type', ''));
        $listingObject->setVar('desctext', Request::getText('desctext', ''));
        $listingObject->setVar('tel', Request::getString('tel', ''));
        $listingObject->setVar('price', Request::getFloat('price', 0.00));
        $listingObject->setVar('typeprice', Request::getString('typeprice', ''));
        $listingObject->setVar('typecondition', Request::getString('typecondition', ''));
        $listingObject->setVar('date_created', strtotime($_REQUEST['date_created']));
        $listingObject->setVar('email', Request::getEmail('email', ''));
        $listingObject->setVar('submitter', Request::getString('submitter', ''));
        $listingObject->setVar('usid', Request::getString('usid', ''));
        $listingObject->setVar('town', Request::getString('town', ''));
        $listingObject->setVar('country', Request::getString('country', ''));
        $listingObject->setVar('contactby', Request::getString('contactby', ''));
        $listingObject->setVar('premium', Request::getString('premium', ''));
        $listingObject->setVar('valid', Request::getString('valid', ''));
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploadDir = XOOPS_UPLOAD_PATH . '/adslight/images/';
        $uploader  = new \XoopsMediaUploader(
            $uploadDir, xoops_getModuleOption('mimetypes', 'adslight'), xoops_getModuleOption(
                          'maxsize',
                          'adslight'
                      ), null, null
        );
        if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', [], 'POST')[0])) {
            //$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , $_FILES['attachedfile']['name']);
            //$imgName = str_replace(' ', '', $_POST['photo']).'.'.$extension;
            $uploader->setPrefix('photo_');
            $uploader->fetchMedia(Request::getArray('xoops_upload_file', [], 'POST')[0]);
            if ($uploader->upload()) {
                $listingObject->setVar('photo', $uploader->getSavedFileName());
            } else {
                $errors = $uploader->getErrors();
                redirect_header('<script>javascript:history.go(-1)</script>', 3, $errors);
            }
        } else {
            $listingObject->setVar('photo', Request::getString('photo', ''));
        }
        $listingObject->setVar('hits', Request::getInt('hits', 0));
        $listingObject->setVar('item_rating', Request::getFloat('item_rating', 0.00));
        $listingObject->setVar('item_votes', Request::getInt('item_votes', 0));
        $listingObject->setVar('user_rating', Request::getFloat('user_rating', 0.00));
        $listingObject->setVar('user_votes', Request::getInt('user_votes', 0));
        $listingObject->setVar('comments', Request::getInt('comments', 0));
        $listingObject->setVar('remind', Request::getInt('remind', 0));
        if ($listingHandler->insert($listingObject)) {
            $helper->redirect('admin/listing.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }
        echo $listingObject->getHtmlErrors();
        $form = $listingObject->getForm();
        $form->display();
        break;
    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_LISTING, 'listing.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_LISTING_LIST, 'listing.php', 'list');
        $adminObject->displayButton('left');
        $listingObject = $listingHandler->get(Request::getInt('lid', 0));
        $form          = $listingObject->getForm();
        $form->display();
        break;
    case 'delete':
        $listingObject = $listingHandler->get(Request::getInt('lid', 0));
        if (1 === Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/listing.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($listingHandler->delete($listingObject)) {
                $helper->redirect('admin/listing.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $listingObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(
                [
                    'ok'  => 1,
                    'lid' => Request::getString('lid', ''),
                    'op'  => 'delete',
                ],
                Request::getUrl('REQUEST_URI', '', 'SERVER'),
                sprintf(AM_ADSLIGHT_FORMSUREDEL, $listingObject->getVar('title'))
            );
        }
        break;
    case 'clone':
        $id_field = Request::getString('lid', '');
        if ($utility::cloneRecord('adslight_listing', 'lid', $id_field)) {
            $helper->redirect('admin/listing.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            $helper->redirect('admin/listing.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }
        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_LISTING, 'listing.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                  = Request::getInt('start', 0);
        $listingPaginationLimit = $helper->getConfig('userpager');
        $criteria               = new CriteriaCompo();
        $criteria->setSort('lid ASC, title');
        $criteria->setOrder('ASC');
        $criteria->setLimit($listingPaginationLimit);
        $criteria->setStart($start);
        $listingTempRows  = $listingHandler->getCount();
        $listingTempArray = $listingHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */
        // Display Page Navigation
        if ($listingTempRows > $listingPaginationLimit) {
            xoops_load('XoopsPageNav');
            $pagenav = new \XoopsPageNav(
                $listingTempRows, $listingPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
            );
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }
        $GLOBALS['xoopsTpl']->assign('listingRows', $listingTempRows);
        $listingArray = [];
        //    $fields = explode('|', lid:int:15::NOT NULL::primary:lid|cid:int:15::NOT NULL:0::Cid|title:varchar:100::NOT NULL:::Title|status:int:3::NOT NULL:0::Status|expire:char:3::NOT NULL:::Expire|type:varchar:15::NOT NULL:::Type|desctext:text:0::NOT NULL:::Desctext|tel:varchar:15::NOT NULL:::Tel|price:decimal:20::NOT NULL:0.00::Price|typeprice:varchar:15::NOT NULL:::Typeprice|typecondition:varchar:15::NOT NULL:::Typecondition|date_created:date:10::NOT NULL:0::Date|email:varchar:100::NOT NULL:::Email|submitter:varchar:60::NOT NULL:::Submitter|usid:varchar:6::NOT NULL:::Usid|town:varchar:200::NOT NULL:::Town|country:varchar:200::NOT NULL:::Country|contactby:varchar:50::NOT NULL:::Contactby|premium:char:3::NOT NULL:::Premium|valid:varchar:11::NOT NULL:::Valid|photo:varchar:100::NOT NULL:0::Photo|hits:int:11::NOT NULL:0::Hits|item_rating:double:6,4::NOT NULL:0.0000::Item_rating|item_votes:int:11:unsigned:NOT NULL:0::Item_votes|user_rating:double:6,4::NOT NULL:0.0000::User_rating|user_votes:int:11:unsigned:NOT NULL:0::User_votes|comments:int:11:unsigned:NOT NULL:0::Comments|remind:int:11::NOT NULL:0::Remind);
        //    $fieldsCount    = count($fields);
        $criteria = new CriteriaCompo();
        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($listingPaginationLimit);
        $criteria->setStart($start);
        $listingCount     = $listingHandler->getCount($criteria);
        $listingTempArray = $listingHandler->getAll($criteria);
        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($listingCount > 0) {
            foreach (array_keys($listingTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);
                $GLOBALS['xoopsTpl']->assign(
                    'selectorlid',
                    AM_ADSLIGHT_LISTING_LID
                );
                $listingArray['lid'] = $listingTempArray[$i]->getVar('lid');
                $selectorcid         = $utility::selectSorting(AM_ADSLIGHT_LISTING_CID, 'cid');
                $GLOBALS['xoopsTpl']->assign('selectorcid', $selectorcid);
                $listingArray['cid'] = $listingTempArray[$i]->getVar('cid');
                $GLOBALS['xoopsTpl']->assign('selectortitle', AM_ADSLIGHT_LISTING_TITLE);
                $listingArray['title'] = $listingTempArray[$i]->getVar('title');
                $selectorstatus        = $utility::selectSorting(AM_ADSLIGHT_LISTING_STATUS, 'status');
                $GLOBALS['xoopsTpl']->assign('selectorstatus', $selectorstatus);
                $listingArray['status'] = $listingTempArray[$i]->getVar('status');
                $GLOBALS['xoopsTpl']->assign('selectorexpire', AM_ADSLIGHT_LISTING_EXPIRE);
                $listingArray['expire'] = $listingTempArray[$i]->getVar('expire');
                $GLOBALS['xoopsTpl']->assign('selectortype', AM_ADSLIGHT_LISTING_TYPE);
                $listingArray['type'] = $typeHandler->get($listingTempArray[$i]->getVar('type'))->getVar('nom_type');
                $GLOBALS['xoopsTpl']->assign('selectordesctext', AM_ADSLIGHT_LISTING_DESCTEXT);
                $listingArray['desctext'] = $listingTempArray[$i]->getVar('desctext');
                $GLOBALS['xoopsTpl']->assign('selectortel', AM_ADSLIGHT_LISTING_TEL);
                $listingArray['tel'] = $listingTempArray[$i]->getVar('tel');
                $selectorprice       = $utility::selectSorting(AM_ADSLIGHT_LISTING_PRICE, 'price');
                $GLOBALS['xoopsTpl']->assign('selectorprice', $selectorprice);
                $listingArray['price'] = $listingTempArray[$i]->getVar('price');
                $GLOBALS['xoopsTpl']->assign('selectortypeprice', AM_ADSLIGHT_LISTING_TYPEPRICE);
                $listingArray['typeprice'] = $listingTempArray[$i]->getVar('typeprice');
                $GLOBALS['xoopsTpl']->assign('selectortypecondition', AM_ADSLIGHT_LISTING_TYPECONDITION);
                $listingArray['typecondition'] = $listingTempArray[$i]->getVar('typecondition');
                $GLOBALS['xoopsTpl']->assign('selectordate', AM_ADSLIGHT_LISTING_DATE);
                //                $listingArray['date_created'] = date_created(_SHORTDATESTRING, strtotime($listingTempArray[$i]->getVar('date_created')));
                $listingArray['date_created'] = formatTimestamp(
                    $listingTempArray[$i]->getVar('date_created'),
                    's'
                );
                $GLOBALS['xoopsTpl']->assign('selectoremail', AM_ADSLIGHT_LISTING_EMAIL);
                $listingArray['email'] = $listingTempArray[$i]->getVar('email');
                $GLOBALS['xoopsTpl']->assign('selectorsubmitter', AM_ADSLIGHT_LISTING_SUBMITTER);
                $listingArray['submitter'] = $listingTempArray[$i]->getVar('submitter');
                $GLOBALS['xoopsTpl']->assign('selectorusid', AM_ADSLIGHT_LISTING_USID);
                $listingArray['usid'] = $listingTempArray[$i]->getVar('usid');
                $GLOBALS['xoopsTpl']->assign('selectortown', AM_ADSLIGHT_LISTING_TOWN);
                $listingArray['town'] = $listingTempArray[$i]->getVar('town');
                $GLOBALS['xoopsTpl']->assign('selectorcountry', AM_ADSLIGHT_LISTING_COUNTRY);
                $listingArray['country'] = $listingTempArray[$i]->getVar('country');
                $GLOBALS['xoopsTpl']->assign('selectorcontactby', AM_ADSLIGHT_LISTING_CONTACTBY);
                $listingArray['contactby'] = $listingTempArray[$i]->getVar('contactby');
                $GLOBALS['xoopsTpl']->assign('selectorpremium', AM_ADSLIGHT_LISTING_PREMIUM);
                $listingArray['premium'] = $listingTempArray[$i]->getVar('premium');
                $GLOBALS['xoopsTpl']->assign('selectorvalid', AM_ADSLIGHT_LISTING_VALID);
                $listingArray['valid'] = $listingTempArray[$i]->getVar('valid');
                $GLOBALS['xoopsTpl']->assign('selectorphoto', AM_ADSLIGHT_LISTING_PHOTO);
                $listingArray['photo'] = "<img src='" . $uploadUrl . $listingTempArray[$i]->getVar(
                        'photo'
                    ) . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";
                $GLOBALS['xoopsTpl']->assign('selectorhits', AM_ADSLIGHT_LISTING_HITS);
                $listingArray['hits'] = $listingTempArray[$i]->getVar('hits');
                $GLOBALS['xoopsTpl']->assign('selectoritem_rating', AM_ADSLIGHT_LISTING_ITEM_RATING);
                $listingArray['item_rating'] = $listingTempArray[$i]->getVar('item_rating');
                $GLOBALS['xoopsTpl']->assign('selectoritem_votes', AM_ADSLIGHT_LISTING_ITEM_VOTES);
                $listingArray['item_votes'] = $itemvotedataHandler->get(
                    $listingTempArray[$i]->getVar('item_votes')
                )->getVar(
                    'ratingid'
                );
                //$GLOBALS['xoopsTpl']->assign('selectoruser_rating', AM_ADSLIGHT_LISTING_USER_RATING);
                // $listingArray['user_rating'] = $listingTempArray[$i]->getVar('user_rating');
                //$GLOBALS['xoopsTpl']->assign('selectoruser_votes', AM_ADSLIGHT_LISTING_USER_VOTES);
                // $listingArray['user_votes'] = $uservotedataHandler->get($listingTempArray[$i]->getVar('user_votes'))->getVar('ratingid');
                $GLOBALS['xoopsTpl']->assign(
                    'selectorcomments',
                    AM_ADSLIGHT_LISTING_COMMENTS
                );
                $listingArray['comments'] = $listingTempArray[$i]->getVar('comments');
                $GLOBALS['xoopsTpl']->assign('selectorremind', AM_ADSLIGHT_LISTING_REMIND);
                $listingArray['remind']      = $listingTempArray[$i]->getVar('remind');
                $listingArray['edit_delete'] = "<a href='listing.php?op=edit&lid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='listing.php?op=delete&lid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='listing.php?op=clone&lid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";
                $GLOBALS['xoopsTpl']->append_by_ref('listingArrays', $listingArray);
                unset($listingArray);
            }
            unset($listingTempArray);
            // Display Navigation
            if ($listingCount > $listingPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav(
                    $listingCount, $listingPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . ''
                );
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
            //                     echo "<td class='center width5'>
            //                    <a href='listing.php?op=edit&lid=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='listing.php?op=delete&lid=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";
            //                echo "</tr>";
            //            }
            //            echo "</table><br><br>";
            //        } else {
            //            echo "<table width='100%' cellspacing='1' class='outer'>
            //                    <tr>
            //                     <th class='center width5'>".AM_ADSLIGHT_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='29'>There are noXXX listing</td></tr>";
            //            echo "</table><br><br>";
            //-------------------------------------------
            echo $GLOBALS['xoopsTpl']->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar(
                    'dirname'
                ) . '/templates/admin/adslight_admin_listing.tpl'
            );
        }
        break;
}
require __DIR__ . '/admin_footer.php';
