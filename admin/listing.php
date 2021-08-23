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
/** @var ListingHandler $listingHandler */
/** @var Admin $adminObject */
/** @var Helper $helper */
/** @var \Xmf\Module\Helper\Permission $permHelper */

require __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');

//$xoTheme->addStylesheet('browse.php?Frameworks/jquery/plugins/css/tablesorter/theme.blue.min.css');
$xoTheme->addStylesheet($helper->url( 'assets/css/tablesorter/theme.blue.min.css'));

$moduleDirName = \basename(\dirname(__DIR__));

$adminObject->displayNavigation(basename(__FILE__));
$permHelper = new \Xmf\Module\Helper\Permission();
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/listing/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/listing/";

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
            redirect_header('listing.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 !== Request::getInt('lid', 0)) {
            $listingObject = $listingHandler->get(Request::getInt('lid', 0));
        } else {
            $listingObject = $listingHandler->create();
        }
        // Form save fields
        $listingObject->setVar('cid', Request::getVar('cid', ''));
        $listingObject->setVar('title', Request::getVar('title', ''));
        $listingObject->setVar('status', Request::getVar('status', ''));
        $listingObject->setVar('expire', Request::getVar('expire', ''));
        $listingObject->setVar('type', Request::getVar('type', ''));
        $listingObject->setVar('desctext', Request::getText('desctext', ''));
        $listingObject->setVar('tel', Request::getVar('tel', ''));
        $listingObject->setVar('price', Request::getVar('price', ''));
        $listingObject->setVar('typeprice', Request::getVar('typeprice', ''));
        $listingObject->setVar('typecondition', Request::getVar('typecondition', ''));
        $resDate     = Request::getArray('date_created', [], 'POST');
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, $resDate['date']);
        $dateTimeObj->setTime(0, 0, 0);
        $listingObject->setVar('date_created', $dateTimeObj->getTimestamp() + $resDate['time']);
        $listingObject->setVar('email', Request::getVar('email', ''));
        $listingObject->setVar('submitter', Request::getVar('submitter', ''));
        $listingObject->setVar('usid', Request::getVar('usid', ''));
        $listingObject->setVar('town', Request::getVar('town', ''));
        $listingObject->setVar('country', Request::getVar('country', ''));
        $listingObject->setVar('contactby', Request::getVar('contactby', ''));
        $listingObject->setVar('premium', Request::getVar('premium', ''));
        $listingObject->setVar('valid', Request::getVar('valid', ''));

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploadDir = XOOPS_UPLOAD_PATH . '/adslight/listing/';
        $uploader  = new \XoopsMediaUploader(
            $uploadDir, $helper->getConfig('mimetypes'), $helper->getConfig('maxsize'), null, null
        );
        if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0])) {
            //$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , $_FILES['attachedfile']['name']);
            //$imgName = str_replace(' ', '', $_POST['photo']).'.'.$extension;

            $uploader->setPrefix('photo_');
            $uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $listingObject->setVar('photo', $uploader->getSavedFileName());
            }
        } else {
            $listingObject->setVar('photo', Request::getVar('photo', ''));
        }

        $listingObject->setVar('hits', Request::getVar('hits', ''));
        $listingObject->setVar('item_rating', Request::getVar('item_rating', ''));
        $listingObject->setVar('item_votes', Request::getVar('item_votes', ''));
        $listingObject->setVar('user_rating', Request::getVar('user_rating', ''));
        $listingObject->setVar('user_votes', Request::getVar('user_votes', ''));
        $listingObject->setVar('comments', Request::getVar('comments', ''));
        $listingObject->setVar('remind', Request::getVar('remind', ''));
        if ($listingHandler->insert($listingObject)) {
            redirect_header('listing.php?op=list', 2, AM_ADSLIGHT_FORMOK);
        }

        echo $listingObject->getHtmlErrors();
        $form = $listingObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_LISTING, 'listing.php?op=new', 'add');
        $adminObject->addItemButton(AM_ADSLIGHT_LISTING_LIST, 'listing.php', 'list');
        $adminObject->displayButton('left');
        $listingObject = $listingHandler->get(Request::getString('lid', ''));
        $form          = $listingObject->getForm();
        $form->display();
        break;

    case 'delete':
        $listingObject = $listingHandler->get(Request::getString('lid', ''));
        if (1 == Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('listing.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($listingHandler->delete($listingObject)) {
                redirect_header('listing.php', 3, AM_ADSLIGHT_FORMDELOK);
            } else {
                echo $listingObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'lid' => Request::getString('lid', ''), 'op' => 'delete',], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_ADSLIGHT_FORMSUREDEL, $listingObject->getVar('title')));
        }
        break;

    case 'clone':

        $id_field = Request::getString('lid', '');

        if ($utility::cloneRecord('adslight_listing', 'lid', $id_field)) {
            redirect_header('listing.php', 3, AM_ADSLIGHT_CLONED_OK);
        } else {
            redirect_header('listing.php', 3, AM_ADSLIGHT_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_ADSLIGHT_ADD_LISTING, 'listing.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                  = Request::getInt('start', 0);
        $listingPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
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

        //    $fields = explode('|', lid:int: 15::NOT NULL::primary:ID:0|cid:int: 15::NOT NULL:0::Category:1|title:varchar: 100::NOT NULL:::Title:2|status:int: 3::NOT NULL:0::Status:3|expire:char: 3::NOT NULL:::Expire:4|type:varchar: 15::NOT NULL:::Type:5|desctext:text: ::NOT NULL:::Description:6|tel:varchar: 15::NOT NULL:::Phone:7|price:decimal: 20,2::NOT NULL:0.00::Price:8|typeprice:varchar: 15::NOT NULL:::PriceType:9|typecondition:varchar: 15::NOT NULL:::Condition:10|date_created:int: 11:UNSIGNED:NOT NULL:0::Created:11|email:varchar: 100::NOT NULL:::Email:12|submitter:varchar: 60::NOT NULL:::Submitter:13|usid:varchar: 6::NOT NULL:::Zip:14|town:varchar: 200::NOT NULL:::Town:15|country:varchar: 200::NOT NULL:::country:16|contactby:varchar: 50::NOT NULL:::Contactby:17|premium:char: 3::NOT NULL:::premium:18|valid:varchar: 11::NOT NULL:::Valid:19|photo:varchar: 100::NOT NULL:::Photo:20|hits:int: 11::NOT NULL: 0::Hits:21|item_rating:double:6,4::NOT NULL:0.0000::ItemRating:22|item_votes:int: 11::NOT NULL: 0::ItemVotes:23|user_rating:double:6,4::NOT NULL:0.0000::user_rating:24|user_votes:int: 11::NOT NULL: 0::user_votes:25|comments:int: 11::NOT NULL: 0::comments:26|remind:int: 11::NOT NULL: 0::remind:27);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

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

                $GLOBALS['xoopsTpl']->assign('selectorlid', AM_ADSLIGHT_LISTING_LID);
                $listingArray['lid'] = $listingTempArray[$i]->getVar('lid');

                $GLOBALS['xoopsTpl']->assign('selectorcid', AM_ADSLIGHT_LISTING_CID);
                $listingArray['cid'] = $categoriesHandler->get($listingTempArray[$i]->getVar('cid'))->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectortitle', AM_ADSLIGHT_LISTING_TITLE);
                $listingArray['title'] = $listingTempArray[$i]->getVar('title');

                $GLOBALS['xoopsTpl']->assign('selectorstatus', AM_ADSLIGHT_LISTING_STATUS);
                $listingArray['status'] = $listingTempArray[$i]->getVar('status');

                $GLOBALS['xoopsTpl']->assign('selectorexpire', AM_ADSLIGHT_LISTING_EXPIRE);
                $listingArray['expire'] = $listingTempArray[$i]->getVar('expire');

                $GLOBALS['xoopsTpl']->assign('selectortype', AM_ADSLIGHT_LISTING_TYPE);
                $listingArray['type'] = $typeHandler->get($listingTempArray[$i]->getVar('type'))->getVar('nom_type');

                $GLOBALS['xoopsTpl']->assign('selectordesctext', AM_ADSLIGHT_LISTING_DESCTEXT);
                $listingArray['desctext'] = $listingTempArray[$i]->getVar('desctext');

                $GLOBALS['xoopsTpl']->assign('selectortel', AM_ADSLIGHT_LISTING_TEL);
                $listingArray['tel'] = $listingTempArray[$i]->getVar('tel');

                $GLOBALS['xoopsTpl']->assign('selectorprice', AM_ADSLIGHT_LISTING_PRICE);
                $listingArray['price'] = $listingTempArray[$i]->getVar('price');

                $GLOBALS['xoopsTpl']->assign('selectortypeprice', AM_ADSLIGHT_LISTING_TYPEPRICE);
                $listingArray['typeprice'] = $priceHandler->get($listingTempArray[$i]->getVar('typeprice'))->getVar('nom_price');

                $GLOBALS['xoopsTpl']->assign('selectortypecondition', AM_ADSLIGHT_LISTING_TYPECONDITION);
                $listingArray['typecondition'] = $conditionHandler->get($listingTempArray[$i]->getVar('typecondition'))->getVar('nom_condition');

                $GLOBALS['xoopsTpl']->assign('selectordate_created', AM_ADSLIGHT_LISTING_DATE_CREATED);
                $listingArray['date_created'] = formatTimestamp($listingTempArray[$i]->getVar('date_created'), 's');

                $GLOBALS['xoopsTpl']->assign('selectoremail', AM_ADSLIGHT_LISTING_EMAIL);
                $listingArray['email'] = $listingTempArray[$i]->getVar('email');

                $GLOBALS['xoopsTpl']->assign('selectorsubmitter', AM_ADSLIGHT_LISTING_SUBMITTER);
                $listingArray['submitter'] = $listingTempArray[$i]->getVar('submitter');

                $GLOBALS['xoopsTpl']->assign('selectorusid', AM_ADSLIGHT_LISTING_USID);
                $listingArray['usid'] = $listingTempArray[$i]->getVar('usid');

                $GLOBALS['xoopsTpl']->assign('selectortown', AM_ADSLIGHT_LISTING_TOWN);
                $listingArray['town'] = $listingTempArray[$i]->getVar('town');

                $GLOBALS['xoopsTpl']->assign('selectorcountry', AM_ADSLIGHT_LISTING_COUNTRY);
                //                $listingArray['country'] = strip_tags(\XoopsLists::getCountryList()[$listingTempArray[$i]->getVar('country')]);
                $listingArray['country'] = $listingTempArray[$i]->getVar('country');

                $GLOBALS['xoopsTpl']->assign('selectorcontactby', AM_ADSLIGHT_LISTING_CONTACTBY);
                $listingArray['contactby'] = $listingTempArray[$i]->getVar('contactby');

                $GLOBALS['xoopsTpl']->assign('selectorpremium', AM_ADSLIGHT_LISTING_PREMIUM);
                $listingArray['premium'] = $listingTempArray[$i]->getVar('premium');

                $GLOBALS['xoopsTpl']->assign('selectorvalid', AM_ADSLIGHT_LISTING_VALID);
                $listingArray['valid'] = $listingTempArray[$i]->getVar('valid');

                $GLOBALS['xoopsTpl']->assign('selectorphoto', AM_ADSLIGHT_LISTING_PHOTO);
                $listingArray['photo'] = "<img src='" . $uploadUrl . $listingTempArray[$i]->getVar('photo') . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";

                $GLOBALS['xoopsTpl']->assign('selectorhits', AM_ADSLIGHT_LISTING_HITS);
                $listingArray['hits'] = $listingTempArray[$i]->getVar('hits');

                $GLOBALS['xoopsTpl']->assign('selectoritem_rating', AM_ADSLIGHT_LISTING_ITEM_RATING);
                $listingArray['item_rating'] = $listingTempArray[$i]->getVar('item_rating');

                $GLOBALS['xoopsTpl']->assign('selectoritem_votes', AM_ADSLIGHT_LISTING_ITEM_VOTES);
                $listingArray['item_votes'] = $listingTempArray[$i]->getVar('item_votes');

                $GLOBALS['xoopsTpl']->assign('selectoruser_rating', AM_ADSLIGHT_LISTING_USER_RATING);
                $listingArray['user_rating'] = $listingTempArray[$i]->getVar('user_rating');

                $GLOBALS['xoopsTpl']->assign('selectoruser_votes', AM_ADSLIGHT_LISTING_USER_VOTES);
                $listingArray['user_votes'] = $listingTempArray[$i]->getVar('user_votes');

                $GLOBALS['xoopsTpl']->assign('selectorcomments', AM_ADSLIGHT_LISTING_COMMENTS);
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
                XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/adslight_admin_listing.tpl'
            );
        }

        break;
}

require __DIR__ . '/admin_footer.php';
