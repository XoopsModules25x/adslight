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

require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'list');

/**
 * Main Ad Display
 */
function index()
{
    global $xoopsDB, $xoopsModuleConfig;

    //    $mytree = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");

    // photo dir setting checker
    $photo_dir         = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'];
    $photo_thumb_dir   = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'] . '/thumbs';
    $photo_resized_dir = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'] . '/midsize';
    if (!is_dir($photo_dir)) {
        if (!mkdir($photo_dir) && !is_dir($photo_dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $photo_dir));
        }
    }
    if (!is_dir($photo_thumb_dir)) {
        if (!mkdir($photo_thumb_dir) && !is_dir($photo_thumb_dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $photo_thumb_dir));
        }
    }
    if (!is_dir($photo_resized_dir)) {
        if (!mkdir($photo_resized_dir) && !is_dir($photo_resized_dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $photo_resized_dir));
        }
    }
    if (!is_writable($photo_dir) || !is_readable($photo_dir)) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_CHECKER . '</legend><br>';
        echo "<span style='color: #FF0000;'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_dir . "</b></span><br><br>\n";
        echo '</fieldset><br>';
    }

    if (!is_writable($photo_thumb_dir) || !is_readable($photo_thumb_dir)) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_CHECKER . '</legend><br>';
        echo "<span style='color: #FF0000;'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_thumb_dir . "</b></span><br><br>\n";
        echo '</fieldset><br>';
    }

    if (!is_writable($photo_resized_dir) || !is_readable($photo_resized_dir)) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_CHECKER . '</legend><br>';
        echo "<span style='color: #FF0000;'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_resized_dir . "</b></span><br><br>\n";
        echo '</fieldset><br>';
    }

    $sql  = 'SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, photo, usid FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='no' ORDER BY lid";
    $result = $xoopsDB->query($sql);
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        ///////// Il y a [..] Annonces en attente d'être approuvées //////
        echo "<table class='outer bnone' cellspacing=5 cellpadding=0><tr><td width=40>";
        echo "<img src='../assets/images/admin/error_button.png' border=0 ></td><td>";
        echo "<span style='color:#00B4C4;'><b>" . _AM_ADSLIGHT_THEREIS . "</b></span> <b>$numrows</b> <span style='color:#00B4C4;'>" . _AM_ADSLIGHT_WAIT . '</b></span>';
        echo '</td></tr></table><br>';
    } else {
        echo "<table class='outer' width='50%' border='0'><tr><td width=40>";
        echo "<img src='../assets/images/admin/search_button_green_32.png' border=0 alt=\"._AM_ADSLIGHT_RELEASEOK.\" ></td><td>";
        echo "<span style='color: #00B4C4;'><b>" . _AM_ADSLIGHT_NOANNVAL . '</b></span>';
        echo '</td></tr></table><br>';
    }

    // Modify Annonces
    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing')));
    if ($numrows > 0) {
        echo "<table width='100%' border='0' class='outer'><tr class='bg4'><td valign='top'>";
        echo '<form method="post" action="modify_ads.php">'
             . '<b>'
             . _AM_ADSLIGHT_MODANN
             . '</b><br><br>'
             . _AM_ADSLIGHT_NUMANN
             . ' <input type="text" name="lid" size="12" maxlength="11">&nbsp;&nbsp;'
             . '<input type="hidden" name="op" value="modifyAds">'
             . '<input type="submit" value="'
             . _AM_ADSLIGHT_MODIF
             . '">'
             . '</form><br>';
        echo '</td></tr></table><br>';
    }

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>";
    echo '<a href="map.php">' . _AM_ADSLIGHT_GESTCAT . '</a> | <a href="../index.php">' . _AM_ADSLIGHT_ACCESMYANN . '</a>';
    echo '</td></tr></table><br>';

    xoops_cp_footer();
}

#  function modifyAds
#####################################################
/**
 * @param $lid
 */
function modifyAds($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $myts, $desctext;

    $mytree        = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $contactselect = '';
    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");
    $id_price  = '';
    $nom_price = '';
    $lid       = (int)$lid;

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';

    $sql = 'SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, valid, photo FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE lid={$lid}";
    $result = $xoopsDB->query($sql);
    while (false !== (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo) = $xoopsDB->fetchRow($result))) {
        $title    = htmlspecialchars($title);
        $status   = htmlspecialchars($status);
        $expire   = htmlspecialchars($expire);
        $type     = htmlspecialchars($type);
        $desctext = $myts->displayTarea($desctext, 1, 1, 1);
        $tel      = htmlspecialchars($tel);
        //        $price     = number_format($price, 2, ',', ' ');

        xoops_load('XoopsLocal');
        $tempXoopsLocal = new \XoopsLocal();
        //  For US currency with 2 numbers after the decimal comment out if you dont want 2 numbers after decimal
        $priceFormatted = $tempXoopsLocal->number_format($price, 2, ',', ' ');
        //  For other countries uncomment the below line and comment out the above line
        //      $priceFormatted = $tempXoopsLocal->number_format($price);

        $typeprice = htmlspecialchars($typeprice);
        $typeusure = htmlspecialchars($typeusure);
        $submitter = htmlspecialchars($submitter);
        $town      = htmlspecialchars($town);
        $country   = htmlspecialchars($country);
        $contactby = htmlspecialchars($contactby);
        $premium   = htmlspecialchars($premium);

        $date2 = formatTimestamp($date, 's');

        echo '<form action="modify_ads.php" method=post>';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table class='bnone'><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_NUMANN . " </td><td>{$lid} &nbsp;" . _AM_ADSLIGHT_ADDED_ON . "&nbsp; {$date2}</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_SENDBY . " </td><td>{$submitter}</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_EMAIL . " </td><td><input type=\"text\" name=\"email\" size=\"40\" value=\"{$email}\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TEL . " </td><td><input type=\"text\" name=\"tel\" size=\"50\" value=\"{$tel}\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TOWN . " </td><td><input type=\"text\" name=\"town\" size=\"40\" value=\"{$town}\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_COUNTRY . " </td><td><input type=\"text\" name=\"country\" size=\"40\" value=\"{$country}\"></td>
            </tr></tr><tr class='head' border='1'>";

        $contactSel1 = $contactSel2 = $contactSel3 = $contactSel4 = '';
        $selected    = 'contactSel' . (int)$contactby;
        $$selected   = ' selected';

        echo " <td class='head'>"
             . _AM_ADSLIGHT_CONTACTBY
             . " </td><td class='head'><select name=\"contactby\">\n"
             //           . "  <option value=\"{$contactby}\">{$contactselect[$contactby]}</option>\n"
             . "  <option value=\"1\"{$contactSel1}>"
             . _AM_ADSLIGHT_CONTACT_BY_EMAIL
             . "</option>\n"
             . "  <option value=\"2\"{$contactSel2}>"
             . _AM_ADSLIGHT_CONTACT_BY_PM
             . "</option>\n"
             . "  <option value=\"3\"{$contactSel3}>"
             . _AM_ADSLIGHT_CONTACT_BY_BOTH
             . "</option>\n"
             . "  <option value=\"4\"{$contactSel4}>"
             . _AM_ADSLIGHT_CONTACT_BY_PHONE
             . "</option></select>\n"
             . " </td>\n"
             . '</tr>';

        echo "<tr><td class='head'>" . _AM_ADSLIGHT_STATUS . "</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
        if ('0' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_ACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="1"';
        if ('1' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_INACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="2"';
        if ('2' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_SOLD . '</td></tr>';

        echo "<tr class='head' border='1'>
        <td>" . _AM_ADSLIGHT_TITLE2 . " </td><td><input type=\"text\" name=\"title\" size=\"40\" value=\"{$title}\"></td>
            </tr><tr class='head' border='1'>
        <td>" . _AM_ADSLIGHT_PREMIUM . " </td><td><input type=\"text\" name=\"premium\" size=\"3\" value=\"{$premium}\"></td>
            </tr><tr class='head' border='1'>
        <td>" . _AM_ADSLIGHT_EXPIRE . " </td><td><input type=\"text\" name=\"expire\" size=\"40\" value=\"{$expire}\"></td>
            </tr>";
        ////// Type d'annonce
        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE . ' </td><td><select name="type">';

        $result5 = $xoopsDB->query('SELECT nom_type, id_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
        while (false !== (list($nom_type, $id_type) = $xoopsDB->fetchRow($result5))) {
            $sel = '';
            if ($id_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"{$id_type}\"{$sel}>{$nom_type}</option>";
        }
        echo '</select></td></tr>';

        ////// Etat d'usure
        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE_USURE . ' </td><td><select name="typeusure">';

        $result6 = $xoopsDB->query('SELECT nom_usure, id_usure FROM ' . $xoopsDB->prefix('adslight_usure') . ' ORDER BY nom_usure');
        while (false !== (list($nom_usure, $id_usure) = $xoopsDB->fetchRow($result6))) {
            $sel = '';
            if ($id_usure == $typeusure) {
                $sel = 'selected';
            }
            echo "<option value=\"{$id_usure}\"{$sel}>{$nom_usure}</option>";
        }
        echo '</select></td></tr>';

        /////// Price
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> " . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . '';

        //////// Price type

        $resultx = $xoopsDB->query('SELECT nom_price, id_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY nom_price');

        echo " <select name=\"typeprice\"><option value=\"{$id_price}\">{$nom_price}</option>";
        while (false !== (list($nom_price, $id_price) = $xoopsDB->fetchRow($resultx))) {
            $sel = '';
            if ($id_price == $typeprice) {
                $sel = 'selected';
            }
            echo "<option value=\"{$id_price}\"{$sel}>{$nom_price}</option>";
        }
        echo '</select></td>';

        /////// Category

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_CAT2 . ' </td><td>';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo "</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_DESC . ' </td><td>';

        $wysiwyg_text_area = Adslight\Utility::getEditor('', 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();

        echo '</td></tr>';

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_PHOTO1 . " </td><td><input type=\"text\" name=\"photo\" size=\"50\" value=\"{$photo}\"></td>
            </tr><tr>";
        $time = time();
        echo "</tr><tr class='head' border='1'>
            <td>&nbsp;</td><td><select name=\"op\">
            <option value=\"modifyAdsS\"> " . _AM_ADSLIGHT_MODIF . '
            <option value="ListingDel"> ' . _AM_ADSLIGHT_DEL . '
            </select><input type="submit" value="' . _AM_ADSLIGHT_GO . '"></td>
            </tr></table>';
        echo '<input type="hidden" name="valid" value="Yes">';
        echo "<input type=\"hidden\" name=\"lid\" value=\"{$lid}\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"{$time}\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"{$submitter}\">
        </form><br>";
        echo '</fieldset><br>';
        xoops_cp_footer();
    }
}

#  function modifyAdsS
#####################################################

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
 * @param $photo
 */
function modifyAdsS($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo)
{
    global $xoopsDB, $myts;

    $lid   = (int)$lid;
    $cat   = (int)$cat;
    $title = htmlspecialchars($title);
    //    $status    = htmlspecialchars($status);
    $status    = (int)$status;
    $expire    = htmlspecialchars($expire);
    $type      = htmlspecialchars($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
    $tel       = htmlspecialchars($tel);
    $price     = str_replace([' '], '', $price);
    $typeprice = htmlspecialchars($typeprice);
    $typeusure = htmlspecialchars($typeusure);
    $date      = (int)$date;
    $email     = htmlspecialchars($email);
    $submitter = htmlspecialchars($submitter);
    $town      = htmlspecialchars($town);
    $country   = htmlspecialchars($country);
    $contactby = htmlspecialchars($contactby);
    $premium   = htmlspecialchars($premium);
    $valid     = htmlspecialchars($valid);
    $photo     = htmlspecialchars($photo);

    $sql = 'UPDATE '
           . $xoopsDB->prefix('adslight_listing')
           . " SET cid='{$cat}', title='{$title}', status='{$status}', expire='{$expire}', type='{$type}', desctext='{$desctext}', tel='{$tel}', price='{$price}', typeprice='{$typeprice}', typeusure='{$typeusure}', date='{$date}', email='{$email}', submitter='{$submitter}', town='{$town}', country='{$country}', contactby='{$contactby}', premium='{$premium}', valid='{$valid}', photo='{$photo}' WHERE lid={$lid}";

    $result = $xoopsDB->query($sql);

    redirect_header('modify_ads.php', 1, _AM_ADSLIGHT_ANNMOD);
}

/**
 * Delete Listing
 *
 * @param int    $lid
 * @param string $photo
 */
function listingDel($lid, $photo)
{
    global $xoopsDB;

    $lid = (int)$lid;

    $result2 = $xoopsDB->query('SELECT p.url FROM ' . $xoopsDB->prefix('adslight_listing') . ' l LEFT JOIN ' . $xoopsDB->prefix('adslight_pictures') . " p  ON l.lid=p.lid WHERE l.lid={$lid}");

    while (false !== (list($purl) = $xoopsDB->fetchRow($result2))) {
        if ($purl) {
            $destination = XOOPS_ROOT_PATH . '/uploads/adslight';
            if (is_file("{$destination}/{$purl}")) {
                unlink("{$destination}/{$purl}");
            }
            $destination2 = XOOPS_ROOT_PATH . '/uploads/adslight/thumbs';
            if (is_file("{$destination2}/thumb_{$purl}")) {
                unlink("{$destination2}/thumb_{$purl}");
            }
            $destination3 = XOOPS_ROOT_PATH . '/uploads/adslight/midsize';
            if (is_file("{$destination3}/resized_{$purl}")) {
                unlink("{$destination3}/resized_{$purl}");
            }
            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_pictures') . " WHERE lid={$lid}");
        }
    }

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE lid={$lid}");

    redirect_header('modify_ads.php', 1, _AM_ADSLIGHT_ANNDEL);
}

#####################################################
#####################################################
//@todo REMOVE THIS ASAP. This code is extremely unsafe
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}
$pa  = Request::getString('pa', '', 'GET');
$lid = Request::getInt('lid', 0);
$op  = Request::getString('op', '');

switch ($op) {
    case 'IndexView':
        indexView($lid);
        break;
    case 'ListingDel':
        listingDel($lid, $photo);
        break;
    case 'modifyAds':
        modifyAds($lid);
        break;
    case 'modifyAdsS':
        modifyAdsS($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo);
        break;
    default:
        index();
        break;
}
