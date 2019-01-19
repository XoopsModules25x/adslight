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

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
$main_lang     = '_' . mb_strtoupper($moduleDirName);
//require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';
$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

$groups = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid      = Request::getInt('item_id', 0, 'POST');

//If no access
if (!$grouppermHandler->checkRight('adslight_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/modules/adslight/index.php', 3, _NOPERM);
}

/**
 * @param $lid
 * @param $ok
 */
function listingDel($lid, $ok)
{
    global $xoopsDB, $xoopsConfig, $xoopsTheme, $xoopsLogger, $moduleDirName, $main_lang;

    $result = $xoopsDB->query('SELECT usid FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lid));
    list($usid) = $xoopsDB->fetchRow($result);

    $result1 = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE lid=' . $xoopsDB->escape($lid));

    if ($GLOBALS['xoopsUser']) {
        $currentid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
        if ($usid == $currentid) {
            if (1 == $ok) {
                while (false !== (list($purl) = $xoopsDB->fetchRow($result1))) {
                    if ($purl) {
                        $destination = XOOPS_ROOT_PATH . '/uploads/AdsLight';
                        if (file_exists("$destination/$purl")) {
                            unlink("$destination/$purl");
                        }
                        $destination2 = XOOPS_ROOT_PATH . '/uploads/AdsLight/thumbs';
                        if (file_exists("$destination2/thumb_$purl")) {
                            unlink("$destination2/thumb_$purl");
                        }
                        $destination3 = XOOPS_ROOT_PATH . '/uploads/AdsLight/midsize';
                        if (file_exists("$destination3/resized_$purl")) {
                            unlink("$destination3/resized_$purl");
                        }

                        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE lid=' . $xoopsDB->escape($lid));
                    }
                }
                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lid));
                redirect_header('index.php', 1, _ADSLIGHT_ANNDEL);
            } else {
                echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
                echo '<br><div style="text-align:center">';
                echo '<strong>' . _ADSLIGHT_SURDELANN . '</strong></div><br><br>';
            }
            echo '[ <a href="modify.php?op=ListingDel&amp;lid=' . $lid . '&amp;ok=1">' . _ADSLIGHT_OUI . '</a> | <a href="index.php">' . _ADSLIGHT_NON . '</a> ]<br><br>';
            echo '</td></tr></table>';
        }
    }
}

/**
 * @param $r_lid
 * @param $ok
 */
function delReply($r_lid, $ok)
{
    global $xoopsDB, $xoopsConfig, $xoopsTheme, $xoopsLogger, $moduleDirName, $main_lang;

    $result = $xoopsDB->query('SELECT l.usid, r.r_lid, r.lid, r.title, r.date, r.submitter, r.message, r.tele, r.email, r.r_usid FROM ' . $xoopsDB->prefix('adslight_listing') . ' l LEFT JOIN ' . $xoopsDB->prefix('adslight_replies') . ' r ON l.lid=r.lid  WHERE r.r_lid=' . $xoopsDB->escape($r_lid));
    list($usid, $r_lid, $rlid, $title, $date, $submitter, $message, $tele, $email, $r_usid) = $xoopsDB->fetchRow($result);

    if ($GLOBALS['xoopsUser']) {
        $currentid = $GLOBALS['xoopsUser']->getVar('uid', 'E');
        if ($usid == $currentid) {
            if (1 == $ok) {
                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('adslight_replies') . ' WHERE r_lid=' . $xoopsDB->escape($r_lid));
                redirect_header('members.php?usid=' . addslashes($usid) . '', 1, _ADSLIGHT_ANNDEL);
            } else {
                echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
                echo '<br><div style="text-align:center">';
                echo '<strong>' . _ADSLIGHT_SURDELANN . '</strong></div><br><br>';
            }
            echo '[ <a href="modify.php?op=DelReply&amp;r_lid=' . addslashes($r_lid) . '&amp;ok=1">' . _ADSLIGHT_OUI . '</a> | <a href="members.php?usid=' . addslashes($usid) . '">' . _ADSLIGHT_NON . '</a> ]<br><br>';
            echo '</td></tr></table>';
        }
    }
}

/**
 * @param $lid
 */
function modAd($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsTheme, $myts, $xoopsLogger, $moduleDirName, $main_lang;
    $contactselect = '';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/modules/adslight/class/Utility.php';
    echo "<script language=\"javascript\">\nfunction CLA(CLA) { var MainWindow = window.open (CLA, \"_blank\",\"width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no\");}\n</script>";

    require_once XOOPS_ROOT_PATH . '/modules/adslight/class/classifiedstree.php';
    $mytree = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    $result = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, usid, town, country, contactby, premium, valid FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lid));
    list($lid, $cide, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $usid, $town, $country, $contactby, $premium, $valid) = $xoopsDB->fetchRow($result);

    $categories = Adslight\Utility::getMyItemIds('adslight_submit');
    if (is_array($categories) && count($categories) > 0) {
        if (!in_array($cide, $categories, true)) {
            redirect_header(XOOPS_URL . '/modules/adslight/index.php', 3, _NOPERM);
        }
    } else {    // User can't see any category
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }

    if ($GLOBALS['xoopsUser']) {
        $calusern = $GLOBALS['xoopsUser']->uid();
        if ($usid == $calusern) {
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _ADSLIGHT_MODIFANN . '</legend><br><br>';
            $title    = $myts->htmlSpecialChars($title);
            $status   = $myts->htmlSpecialChars($status);
            $expire   = $myts->htmlSpecialChars($expire);
            $type     = $myts->htmlSpecialChars($type);
            $desctext = $myts->displayTarea($desctext, 1);
            $tel      = $myts->htmlSpecialChars($tel);

            //            $price      = number_format($price, 2, ',', ' ');

            xoops_load('XoopsLocal');
            $tempXoopsLocal = new \XoopsLocal();
            //  For US currency with 2 numbers after the decimal comment out if you dont want 2 numbers after decimal
            $price = $tempXoopsLocal->number_format($price, 2, ',', ' ');
            //  For other countries uncomment the below line and comment out the above line
            //      $price = $tempXoopsLocal->number_format($price);

            $typeprice  = $myts->htmlSpecialChars($typeprice);
            $typeusure  = $myts->htmlSpecialChars($typeusure);
            $submitter  = $myts->htmlSpecialChars($submitter);
            $town       = $myts->htmlSpecialChars($town);
            $country    = $myts->htmlSpecialChars($country);
            $contactby  = $myts->htmlSpecialChars($contactby);
            $premium    = $myts->htmlSpecialChars($premium);
            $useroffset = '';
            if ($GLOBALS['xoopsUser']) {
                $timezone   = $GLOBALS['xoopsUser']->timezone();
                $useroffset = !empty($timezone) ? $GLOBALS['xoopsUser']->timezone() : $xoopsConfig['default_TZ'];
            }
            $dates = ($useroffset * 3600) + $date;
            $dates = formatTimestamp($date, 's');

            echo '<form action="modify.php" method=post enctype="multipart/form-data">';
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo '<table><tr class="head" border="2">
    <td class="head">' . _ADSLIGHT_NUMANNN . " </td><td class=\"head\" border=\"1\">$lid " . _ADSLIGHT_DU . " $dates</td>
    </tr><tr>";

            if ('1' == $GLOBALS['xoopsModuleConfig']['adslight_diff_name']) {
                echo '<td class="head">' . _ADSLIGHT_SENDBY . " </td><td class=\"head\"><input type=\"text\" name=\"submitter\" size=\"50\" value=\"$submitter\" ></td>";
            } else {
                echo '<td class="head">' . _ADSLIGHT_SENDBY . " </td><td class=\"head\"><input type=\"hidden\" name=\"submitter\" value=\"$submitter\">$submitter</td>";
            }
            echo '</tr><tr>';

            if (1 == $contactby) {
                $contactselect = _ADSLIGHT_CONTACT_BY_EMAIL;
            }
            if (2 == $contactby) {
                $contactselect = _ADSLIGHT_CONTACT_BY_PM;
            }
            if (3 == $contactby) {
                $contactselect = _ADSLIGHT_CONTACT_BY_BOTH;
            }
            if (4 == $contactby) {
                $contactselect = _ADSLIGHT_CONTACT_BY_PHONE;
            }

            echo " <td class='head'>" . _ADSLIGHT_CONTACTBY . " </td><td class='head'><select name=\"contactby\">
    <option value=\"" . $contactby . '">' . $contactselect . '</option>
    <option value="1">' . _ADSLIGHT_CONTACT_BY_EMAIL . '</option>
    <option value="2">' . _ADSLIGHT_CONTACT_BY_PM . '</option>
    <option value="3">' . _ADSLIGHT_CONTACT_BY_BOTH . '</option>
    <option value="4">' . _ADSLIGHT_CONTACT_BY_PHONE . '</option></select></td></tr>';

            if ('1' == $GLOBALS['xoopsModuleConfig']['adslight_diff_email']) {
                echo '<tr><td class="head">' . _ADSLIGHT_EMAIL . " </td><td class=\"head\"><input type=\"text\" name=\"email\" size=\"50\" value=\"$email\" ></td>";
            } else {
                echo '<tr><td class="head">' . _ADSLIGHT_EMAIL . " </td><td class=\"head\">$email<input type=\"hidden\" name=\"email\" value=\"$email\" ></td>";
            }
            echo '</tr><tr>
    <td class="head">' . _ADSLIGHT_TEL . " </td><td class=\"head\"><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\" ></td>
    </tr>";
            echo '<tr>
    <td class="head">' . _ADSLIGHT_TOWN . " </td><td class=\"head\"><input type=\"text\" name=\"town\" size=\"50\" value=\"$town\" ></td>
    </tr>";
            if ('1' == $GLOBALS['xoopsModuleConfig']['adslight_use_country']) {
                echo '<tr>
    <td class="head">' . _ADSLIGHT_COUNTRY . " </td><td class=\"head\"><input type=\"text\" name=\"country\" size=\"50\" value=\"$country\" ></td>
    </tr>";
            } else {
                echo '<input type="hidden" name="country" value="">';
            }

            echo "<tr><td class='head'>" . _ADSLIGHT_STATUS . "</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
            if ('0' == $status) {
                echo 'checked';
            }
            echo '>' . _ADSLIGHT_ACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="1"';
            if ('1' == $status) {
                echo 'checked';
            }
            echo '>' . _ADSLIGHT_INACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="2"';
            if ('2' == $status) {
                echo 'checked';
            }
            echo '>' . _ADSLIGHT_SOLD . '</td></tr>';
            echo '<tr>
    <td class="head">' . _ADSLIGHT_TITLE2 . " </td><td class=\"head\"><input type=\"text\" name=\"title\" size=\"50\" value=\"$title\" ></td>
    </tr>";
            echo '<tr><td class="head">' . _ADSLIGHT_PRICE2 . " </td><td class=\"head\"><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\" > " . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'];

            $result3 = $xoopsDB->query('SELECT nom_price, id_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY id_price');
            echo ' <select name="typeprice">';
            while (false !== (list($nom_price, $id_price) = $xoopsDB->fetchRow($result3))) {
                $sel = '';
                if ($id_price == $typeprice) {
                    $sel = 'selected';
                }
                echo "<option value=\"$id_price\" $sel>$nom_price</option>";
            }
            echo '</select></td></tr>';
            $module_id = $xoopsModule->getVar('mid');
            $groups    = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;

            /** @var \XoopsGroupPermHandler $grouppermHandler */
            $grouppermHandler = xoops_getHandler('groupperm');
            $perm_itemid      = Request::getInt('item_id', 0, 'GET');

            //If no access
            if (!$grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
                echo "<tr>
    <td width='30%' class='head'>" . _ADSLIGHT_WILL_LAST . " </td><td class='head'>$expire  " . _ADSLIGHT_DAY . '</td>
    </tr>';
                echo "<input type=\"hidden\" name=\"expire\" value=\"$expire\" >";
            } else {
                echo "<tr>
    <td width='30%' class='head'>" . _ADSLIGHT_HOW_LONG . " </td><td class='head'><input type=\"text\" name=\"expire\" size=\"3\" maxlength=\"3\" value=\"$expire\" >  " . _ADSLIGHT_DAY . '</td>
    </tr>';
            }

            /// Type d'annonce
            echo '<tr>
    <td class="head">' . _ADSLIGHT_TYPE . ' </td><td class="head"><select name="type">';

            $result5 = $xoopsDB->query('SELECT nom_type, id_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
            while (false !== (list($nom_type, $id_type) = $xoopsDB->fetchRow($result5))) {
                $sel = '';
                if ($id_type == $type) {
                    $sel = 'selected';
                }
                echo "<option value=\"$id_type\" $sel>$nom_type</option>";
            }
            echo '</select></td></tr>';

            /// Etat de l'objet
            echo '<tr>
    <td class="head">' . _ADSLIGHT_TYPE_USURE . ' </td><td class="head"><select name="typeusure">';

            $result6 = $xoopsDB->query('SELECT nom_usure, id_usure FROM ' . $xoopsDB->prefix('adslight_usure') . ' ORDER BY nom_usure');
            while (false !== (list($nom_usure, $id_usure) = $xoopsDB->fetchRow($result6))) {
                $sel = '';
                if ($id_usure == $typeusure) {
                    $sel = 'selected';
                }
                echo "<option value=\"$id_usure\" $sel>$nom_usure</option>";
            }
            echo '</select></td></tr>';

            echo '<tr>
    <td class="head">' . _ADSLIGHT_CAT . ' </td><td class="head">';
            $mytree->makeMySelBox('title', 'title', $cide, '', 'cid');
            echo '</td>
    </tr><tr>
    <td class="head">' . _ADSLIGHT_DESC . ' </td><td class="head">';
            $wysiwyg_text_area = Adslight\Utility::getEditor(_ADSLIGHT_DESC, 'desctext', $desctext, '100%', '200px');
            echo $wysiwyg_text_area->render();
            echo '</td></tr>
    <td colspan=2><br><input type="submit" value="' . _ADSLIGHT_MODIFANN . '" ></td>
    </tr></table>';
            echo '<input type="hidden" name="op" value="ModAdS" >';

            $module_id = $xoopsModule->getVar('mid');
            if (is_object($GLOBALS['xoopsUser'])) {
                $groups = &$GLOBALS['xoopsUser']->getGroups();
            } else {
                $groups = XOOPS_GROUP_ANONYMOUS;
            }
            /** @var \XoopsGroupPermHandler $grouppermHandler */
            $grouppermHandler = xoops_getHandler('groupperm');
            $perm_itemid      = Request::getInt('item_id', 0, 'POST');
            //If no access
            if (!$grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
                if ('1' == $GLOBALS['xoopsModuleConfig']['adslight_moderated']) {
                    echo '<input type="hidden" name="valid" value="No" >';
                    echo '<br>' . _ADSLIGHT_MODIFBEFORE . '<br>';
                } else {
                    echo '<input type="hidden" name="valid" value="Yes" >';
                }
            } else {
                echo '<input type="hidden" name="valid" value="Yes" >';
            }
            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" >";
            echo "<input type=\"hidden\" name=\"premium\" value=\"$premium\" >";
            echo "<input type=\"hidden\" name=\"date\" value=\"$date\" >
    " . $GLOBALS['xoopsSecurity']->getTokenHTML() . '';
            echo '</form><br></fieldset><br>';
        }
    }
}

/**
 * @param $lid
 * @param $cat
 * @param $title
 * @param $status
 * @param $expire
 * @param $type
 * @param $desctext
 * @param $tel
 * @param $price
 * @param $typeprice
 * @param $typeusure
 * @param $date
 * @param $email
 * @param $submitter
 * @param $town
 * @param $country
 * @param $contactby
 * @param $premium
 * @param $valid
 */
function modAdS($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsLogger, $moduleDirName, $main_lang;

    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . '/modules/adslight/index.php', 3, $GLOBALS['xoopsSecurity']->getErrors());
    }
    $title     = $myts->addSlashes($title);
    $status    = $myts->addSlashes($status);
    $expire    = $myts->addSlashes($expire);
    $type      = $myts->addSlashes($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
    $tel       = $myts->addSlashes($tel);
    $price     = str_replace([' '], '', $price);
    $typeprice = $myts->addSlashes($typeprice);
    $typeusure = $myts->addSlashes($typeusure);
    $submitter = $myts->addSlashes($submitter);
    $town      = $myts->addSlashes($town);
    $country   = $myts->addSlashes($country);
    $contactby = $myts->addSlashes($contactby);
    $premium   = $myts->addSlashes($premium);

    $xoopsDB->query('UPDATE '
                    . $xoopsDB->prefix('adslight_listing')
                    . " SET cid='$cat', title='$title', status='$status',  expire='$expire', type='$type', desctext='$desctext', tel='$tel', price='$price', typeprice='$typeprice', typeusure='$typeusure', email='$email', submitter='$submitter', town='$town', country='$country', contactby='$contactby', premium='$premium', valid='$valid' WHERE lid=$lid");

    redirect_header('index.php', 1, _ADSLIGHT_ANNMOD2);
}

####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}
$ok = Request::getString('ok', '', 'GET');

if (!Request::hasVar('lid', 'POST') && Request::hasVar('lid', 'GET')) {
    $lid = Request::getInt('lid', 0, 'GET');
}
if (!Request::hasVar('r_lid', 'POST') && Request::hasVar('r_lid', 'GET')) {
    $r_lid = Request::getInt('r_lid', '', 'GET');
}
if (!Request::hasVar('op', 'POST') && Request::hasVar('op', 'GET')) {
    $op = Request::getString('op', '', 'GET');
}
switch ($op) {
    case 'ModAd':
        require_once XOOPS_ROOT_PATH . '/header.php';
        modAd($lid);
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'ModAdS':
        modAdS($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid);
        break;
    case 'ListingDel':
        require_once XOOPS_ROOT_PATH . '/header.php';
        listingDel($lid, $ok);
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'DelReply':
        require_once XOOPS_ROOT_PATH . '/header.php';
        delReply($r_lid, $ok);
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNANN);
        break;
}
