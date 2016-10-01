<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops

        Redesigned and ameliorate By Luc Bizet user at www.frxoops.org
        Started with the Classifieds module and made MANY changes
        Website : http://www.luc-bizet.fr
        Contact : adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller
 Author Website : pascal.e-xoops@perso-search.com
 Licence Type   : GPL
-------------------------------------------------------------------------
*/
include_once __DIR__ . '/header.php';
$myts = MyTextSanitizer::getInstance();// MyTextSanitizer object
require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';
include_once XOOPS_ROOT_PATH . '/modules/adslight/class/classifiedstree.php';
//include_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
//include_once __DIR__ . '/include/functions.php';

//$erh = new ErrorHandler; //ErrorHandler object

$module_id = $xoopsModule->getVar('mid');
if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$gpermHandler = xoops_getHandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = (int)$_POST['item_id'];
} else {
    $perm_itemid = 0;
}
if (!$gpermHandler->checkRight('adslight_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
if (!$gpermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

//include_once XOOPS_ROOT_PATH . '/modules/adslight/class/utilities.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/modules/adslight/class/classifiedstree.php';
$mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

if (empty($GLOBALS['xoopsUser'])) {
    redirect_header(XOOPS_URL . '/user.php', 2, _MA_ADSLIGHT_MUSTREGFIRST);
}

if (!empty($_POST['submit'])) {
    $howlong = $GLOBALS['xoopsModuleConfig']['adslight_howlong'];

    if (!$xoopsGTicket->check(true, 'token')) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    if ($_POST['title'] == '') {
        //        $erh->show('1001'); //'0001' => 'Could not connect to the forums database.',
        $modHandler = xoops_getModuleHandler('module');
        $myModule   = $modHandler->getByDirname('adslight');
        $myModule->setErrors('Could not connect to the database.');
    }

    if (!empty($_POST['cid'])) {
        $cid = (int)$_POST['cid'];
    } else {
        $cid = 0;
    }
    $cat_perms = AdslightUtilities::getMyItemIds('adslight_submit');
    if (!in_array($cid, $cat_perms)) {
        redirect_header(XOOPS_URL, 2, _NOPERM);
    }

    $title     = $myts->addSlashes($_POST['title']);
    $status    = $myts->addSlashes($_POST['status']);
    $expire    = $myts->addSlashes($_POST['expire']);
    $type      = $myts->addSlashes($_POST['type']);
    $desctext  = $myts->displayTarea($_POST['desctext'], 1, 1, 1);
    $tel       = $myts->addSlashes($_POST['tel']);
    $price     = str_replace(array(' '), '', $_POST['price']);
    $typeprice = $myts->addSlashes($_POST['typeprice']);
    $typeusure = $myts->addSlashes($_POST['typeusure']);
    $date      = $myts->addSlashes($_POST['date']);
    $email     = $myts->addSlashes($_POST['email']);
    $submitter = $myts->addSlashes($_POST['submitter']);
    $usid      = $myts->addSlashes($_POST['usid']);
    $town      = $myts->addSlashes($_POST['town']);
    $country   = $myts->addSlashes($_POST['country']);
    $contactby = $myts->addSlashes($_POST['contactby']);
    $premium   = $myts->addSlashes($_POST['premium']);
    $valid     = $myts->addSlashes($_POST['valid']);
    $date      = time();
    $newid     = $xoopsDB->genId($xoopsDB->prefix('adslight_listing') . '_lid_seq');

    $sql = sprintf("INSERT INTO %s (lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, usid, town, country, contactby, premium, valid) VALUES (%u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                   $xoopsDB->prefix('adslight_listing'), $newid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $usid, $town,
                   $country, $contactby, $premium, $valid);
    // $xoopsDB->query($sql) || $erh->show('0013'); //            '0013' => 'Could not query the database.', // <br>Error: ' . mysql_error() . '',
    $success = $xoopsDB->query($sql);
    if (!$success) {
        $modHandler = xoops_getModuleHandler('module');
        $myModule   = $modHandler->getByDirname('adslight');
        $myModule->setErrors('Could not query the database.');
    }

    $lid = $xoopsDB->getInsertId();

    if ($valid === 'Yes') {
        $notification_handler = xoops_getHandler('notification');
        //$lid = $xoopsDB->getInsertId();
        $tags                    = array();
        $tags['TITLE']           = $title;
        $tags['ADDED_TO_CAT']    = _ADSLIGHT_ADDED_TO_CAT;
        $tags['RECIEVING_NOTIF'] = _ADSLIGHT_RECIEVING_NOTIF;
        $tags['ERROR_NOTIF']     = _ADSLIGHT_ERROR_NOTIF;
        $tags['WEBMASTER']       = _ADSLIGHT_WEBMASTER;
        $tags['HELLO']           = _ADSLIGHT_HELLO;
        $tags['FOLLOW_LINK']     = _ADSLIGHT_FOLLOW_LINK;
        $tags['TYPE']            = AdslightUtilities::getNameType($type);
        $tags['LINK_URL']        = XOOPS_URL . '/modules/adslight/viewads.php?' . '&lid=' . $lid;
        $sql                     = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . addslashes($cid);
        $result2                 = $xoopsDB->query($sql);
        $row                     = $xoopsDB->fetchArray($result2);
        $tags['CATEGORY_TITLE']  = $row['title'];
        $tags['CATEGORY_URL']    = XOOPS_URL . '/modules/adslight/viewcats.php?cid="' . addslashes($cid);
        $notification_handler    = xoops_getHandler('notification');
        $notification_handler->triggerEvent('global', 0, 'new_listing', $tags);
        $notification_handler->triggerEvent('category', $cid, 'new_listing', $tags);
        $notification_handler->triggerEvent('listing', $lid, 'new_listing', $tags);
    } else {
        $tags                   = array();
        $subject                = '' . _ADSLIGHT_NEW_WAITING_SUBJECT . '';
        $tags['TITLE']          = $title;
        $tags['DESCTEXT']       = $desctext;
        $tags['ADMIN']          = _ADSLIGHT_ADMIN;
        $tags['NEW_WAITING']    = _ADSLIGHT_NEW_WAITING;
        $tags['PLEASE_CHECK']   = _ADSLIGHT_PLEASE_CHECK;
        $tags['WEBMASTER']      = _ADSLIGHT_WEBMASTER;
        $tags['HELLO']          = _ADSLIGHT_HELLO;
        $tags['FOLLOW_LINK']    = _ADSLIGHT_FOLLOW_LINK;
        $tags['TYPE']           = AdslightUtilities::getNameType($type);
        $tags['NEED_TO_LOGIN']  = _ADSLIGHT_NEED_TO_LOGIN;
        $tags['ADMIN_LINK']     = XOOPS_URL . '/modules/adslight/admin/validate_ads.php';
        $sql                    = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . addslashes($cid);
        $result2                = $xoopsDB->query($sql);
        $row                    = $xoopsDB->fetchArray($result2);
        $tags['CATEGORY_TITLE'] = $row['title'];
        $tags['NEWAD']          = _ADSLIGHT_NEWAD;

        $mail =& xoops_getMailer();
        $mail->setTemplateDir(XOOPS_ROOT_PATH . '/modules/adslight/language/' . $xoopsConfig['language'] . '/mail_template/');
        $mail->setTemplate('listing_notify_admin.tpl');
        $mail->useMail();
        $mail->multimailer->isHTML(true);
        $mail->setFromName($xoopsConfig['sitename']);
        $mail->setFromEmail($xoopsConfig['adminmail']);
        $mail->setToEmails($xoopsConfig['adminmail']);
        $mail->setSubject($subject);
        $mail->assign($tags);
        $mail->send();
        echo $mail->getErrors();
    }

    if (!empty($_POST['addphotonow'])) {
        $addphotonow = (int)$_POST['addphotonow'];
    } else {
        $addphotonow = '0';
    }

    if ($addphotonow) {
        //$lid = $xoopsDB->getInsertId();
        redirect_header("view_photos.php?lid=$lid&uid=$usid", 3, _ADSLIGHT_ADSADDED);
    } else {
        redirect_header('index.php', 3, _ADSLIGHT_ADSADDED);
    }
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'adslight_addlisting.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    if (!empty($_POST['cid'])) {
        $cid = (int)$_POST['cid'];
    } else {
        $cid = 0;
    }

    if (!empty($_POST['cat_moderate'])) {
        $cat_moderate = (int)$_POST['cat_moderate'];
    } else {
        $cat_moderate = 0;
    }

    $howlong      = $GLOBALS['xoopsModuleConfig']['adslight_howlong'];
    $member_usid  = $GLOBALS['xoopsUser']->getVar('uid', 'E');
    $member_email = $GLOBALS['xoopsUser']->getVar('email', 'E');
    $member_uname = $GLOBALS['xoopsUser']->getVar('uname', 'E');

    $result  = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
    $result1 = $xoopsDB->query('SELECT id_price, nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY id_price');
    $result3 = $xoopsDB->query('SELECT id_usure, nom_usure FROM ' . $xoopsDB->prefix('adslight_usure') . ' ORDER BY id_usure');

    ob_start();
    $form = new XoopsThemeForm(_ADSLIGHT_ADD_LISTING, 'submitform', 'addlisting.php');
    $form->setExtra('enctype="multipart/form-data"');

    $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

    if ($cat_moderate) {
        if ($premium != '0') {
            echo '';
        } else {
            echo '';
        }
    } else {
        if ($premium != '0') {
            echo '';
        } else {
            echo '';
        }
    }

    if ($GLOBALS['xoopsModuleConfig']['adslight_diff_name'] == '1') {
        $form->addElement(new XoopsFormText(_ADSLIGHT_SUBMITTER, 'submitter', 50, 50, $member_uname), true);
    } else {
        $form->addElement(new XoopsFormLabel(_ADSLIGHT_SUBMITTER, $member_uname));
        $form->addElement(new XoopsFormHidden('submitter', $member_uname), true);
    }
    if ($GLOBALS['xoopsModuleConfig']['adslight_diff_email'] == '1') {
        $form->addElement(new XoopsFormText(_ADSLIGHT_EMAIL, 'email', 50, 50, $member_email), true);
    } else {
        $form->addElement(new XoopsFormLabel(_ADSLIGHT_EMAIL, $member_email));
        $form->addElement(new XoopsFormHidden('email', $member_email), true);
    }
    $form->addElement(new XoopsFormText(_ADSLIGHT_TOWN, 'town', 50, 50, ''), false);
    if ($GLOBALS['xoopsModuleConfig']['adslight_use_country'] == '1') {
        $form->addElement(new XoopsFormText(_ADSLIGHT_COUNTRY, 'country', 50, 50, ''), false);
    } else {
        $form->addElement(new XoopsFormHidden('country', ''), false);
    }
    $form->addElement(new XoopsFormText(_ADSLIGHT_TEL, 'tel', 50, 50, ''), false);

    $cat_id    = $_GET['cid'];
    $cid       = addslashes($cat_id);
    $cat_perms = AdslightUtilities::getMyItemIds('adslight_submit');
    if (is_array($cat_perms) && count($cat_perms) > 0) {
        if (!in_array($cid, $cat_perms)) {
            redirect_header(XOOPS_URL . '/modules/adslight/index.php', 3, _NOPERM);
        }

        $category = $xoopsDB->query('SELECT title, cat_moderate FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . $xoopsDB->escape($cid) );

        list($cat_title, $cat_moderate) = $xoopsDB->fetchRow($category);
        $form->addElement(new XoopsFormLabel(_ADSLIGHT_CAT3, "<b>$cat_title</b>"));
        $form->addElement(new XoopsFormHidden('cid', $cid), true);

        if ($premium == '1') {
            $radio        = new XoopsFormRadio(_ADSLIGHT_STATUS, 'status', '');
            $options['0'] = _ADSLIGHT_ACTIVE;
            $options['1'] = _ADSLIGHT_INACTIVE;
            $radio->addOptionArray($options);
            $form->addElement($radio, true);
        } else {
            $form->addElement(new XoopsFormHidden('status', '0'), true);
        }

        if ($premium == 1) {
            $form->addElement(new XoopsFormText(_ADSLIGHT_HOW_LONG, 'expire', 3, 3, $GLOBALS['xoopsModuleConfig']['adslight_howlong']), true);
        } else {
            $form->addElement(new XoopsFormLabel(_ADSLIGHT_WILL_LAST, $GLOBALS['xoopsModuleConfig']['adslight_howlong']));
            $form->addElement(new XoopsFormHidden('expire', $GLOBALS['xoopsModuleConfig']['adslight_howlong']), false);
        }

        /// Type d'annonce
        $type_form = new XoopsFormSelect(_ADSLIGHT_TYPE, 'type', '', '1');
        while (list($nom_type, $id_type) = $xoopsDB->fetchRow($result)) {
            $type_form->addOption($nom_type, $id_type);
        }
        /// Etat de l'objet
        $usure_form = new XoopsFormSelect(_ADSLIGHT_TYPE_USURE, 'typeusure', '', '1');
        while (list($nom_usure, $id_usure) = $xoopsDB->fetchRow($result3)) {
            $usure_form->addOption($nom_usure, $id_usure);
        }

        $form->addElement($type_form, true);
        $form->addElement($usure_form, true);

        $form->addElement(new XoopsFormText(_ADSLIGHT_TITLE2, 'title', 40, 50, ''), true);
        $form->addElement(AdslightUtilities::getEditor(_ADSLIGHT_DESC, 'desctext', '', '100%', '300px', ''), true);
        $form->addElement(new XoopsFormText(_ADSLIGHT_PRICE2, 'price', 40, 50, ''), true);
        /// Type de prix
        $sel_form = new XoopsFormSelect(_ADSLIGHT_PRICETYPE, 'typeprice', '', '1');
        while (list($nom_price, $id_price) = $xoopsDB->fetchRow($result1)) {
            $sel_form->addOption($nom_price, $id_price);
        }

        $form->addElement($sel_form);
        $contactby_form = new XoopsFormSelect(_ADSLIGHT_CONTACTBY, 'contactby', '', '1');
        $contactby_form->addOption(1, _ADSLIGHT_CONTACT_BY_EMAIL);
        $contactby_form->addOption(2, _ADSLIGHT_CONTACT_BY_PM);
        $contactby_form->addOption(3, _ADSLIGHT_CONTACT_BY_BOTH);
        $contactby_form->addOption(4, _ADSLIGHT_CONTACT_BY_PHONE);
        $form->addElement($contactby_form, true);
        $form->addElement(new XoopsFormRadioYN(_ADSLIGHT_ADD_PHOTO_NOW, 'addphotonow', 1));

        //if ($GLOBALS['xoopsModuleConfig']["adslight_use_captcha"] == '1') {
        //  $form->addElement(new XoopsFormCaptcha(_ADSLIGHT_CAPTCHA, "xoopscaptcha", false), true);
        //}

        if ($premium != '0') {
            $form->addElement(new XoopsFormHidden('premium', 'yes'), false);
        } else {
            $form->addElement(new XoopsFormHidden('premium', 'no'), false);
        }

        if ($cat_moderate == '1') {
            $form->addElement(new XoopsFormHidden('valid', 'No'), false);
            $form->addElement(new XoopsFormHidden('cat_moderate', '1'), false);
        } else {
            $form->addElement(new XoopsFormHidden('valid', 'Yes'), false);
        }
        $form->addElement(new XoopsFormHidden('usid', $member_usid), false);
        $form->addElement(new XoopsFormHidden('date', time()), false);
        $form->addElement(new XoopsFormButton('', 'submit', _ADSLIGHT_SUBMIT, 'submit'));
        $form->display();
        $xoopsTpl->assign('submit_form', ob_get_contents());
        ob_end_clean();
    } else {    // User can't see any category
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }
    include XOOPS_ROOT_PATH . '/footer.php';

}
