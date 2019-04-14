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

$op = Request::getInt('op', 'liste');

#  function index
#####################################################
function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $desctext, $moduleDirName, $admin_lang;

    //    $mytree = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
    $photo3 = '';
    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, '');

    // photo dir setting checker
    $photo_dir         = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'];
    $photo_thumb_dir   = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'] . '/thumbs';
    $photo_resized_dir = $GLOBALS['xoopsModuleConfig']['adslight_path_upload'] . '/midsize';
    if (!is_dir($photo_dir)) {
        mkdir($photo_dir);
    }
    if (!is_dir($photo_thumb_dir)) {
        mkdir($photo_thumb_dir);
    }
    if (!is_dir($photo_resized_dir)) {
        mkdir($photo_resized_dir);
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

    $result  = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, photo, usid FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes' ORDER BY lid");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        // Il y a [..] Annonces en attente d'être approuvées //////
        echo "<table class='outer bnone' cellspacing=5 cellpadding=0><tr><td width=40>";
        echo "<img src='../assets/images/admin/error_button.png' border=0 ></td><td>";
        echo "<span style='color:#00B4C4;'><b>" . _AM_ADSLIGHT_THEREIS . "</b></span> <b>$numrows</b> <b><span style='color:#00B4C4;'>" . _AM_ADSLIGHT_ADSVALIDE . '</b></span>';
        echo '</td></tr></table><br>';

        // Liste des ID  ///// Soumis par /////  Titre   /////  Description  /////  Date d'ajout
        echo "<table class='outer width100 bnone'>";
        $rank = 1;

        while (false !== (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $photo, $usid) = $xoopsDB->fetchRow($result))) {
            $title    = $myts->htmlSpecialChars($title);
            $desctext = $myts->displayTarea($desctext, 1, 0, 1, 1, 1);

            if (mb_strlen($desctext) >= 200) {
                $desctext = mb_substr($desctext, 0, 199) . '...';
            } else {
                $desctext = $myts->displayTarea($desctext, 1, 1, 1);
            }
            $date2  = formatTimestamp($date, 's');
            $color  = is_int($rank / 2) ? '#ffffff' : 'head';
            $status = $myts->htmlSpecialChars($status);
            $expire = $myts->htmlSpecialChars($expire);
            $type   = $myts->htmlSpecialChars($type);
            $tel    = $myts->htmlSpecialChars($tel);
            //            $price = number_format($price, 2, ',', ' ');
            $typeprice = $myts->htmlSpecialChars($typeprice);
            $typeusure = $myts->htmlSpecialChars($typeusure);
            $submitter = $myts->htmlSpecialChars($submitter);
            $town      = $myts->htmlSpecialChars($town);
            $country   = $myts->htmlSpecialChars($country);
            $contactby = $myts->htmlSpecialChars($contactby);
            $premium   = $myts->htmlSpecialChars($premium);

            $updir   = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'];
            $sql     = 'SELECT cod_img, lid, uid_owner, url FROM ' . $xoopsDB->prefix('adslight_pictures') . " WHERE  uid_owner={$usid} AND lid={$lid} ORDER BY date_added ASC LIMIT 1";
            $resultp = $xoopsDB->query($sql);
            while (false !== (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp))) {
                if ($photo) {
                    $photo3 = "<a href='" . XOOPS_URL . "/modules/adslight/viewads.php?lid={$lid}'><img class=\"thumb\" src=\"{$updir}/thumbs/thumb_{$url}\" align=\"left\" width=\"100px\" alt=\"{$title}\"></a>";
                }
            }
            if ($photo <= 0) {
                $photo3 = "<a href=\"index.php?op=IndexView&lid=$lid\"><img class=\"thumb\" src=\"" . XOOPS_URL . "/modules/adslight/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"$title\"></a>";
            } else {
                $photo4 = (string)$photo;
            }

            $result7 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' WHERE id_type=' . (int)$type);
            list($nom_type) = $xoopsDB->fetchRow($result7);

            $result8 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' WHERE id_price=' . (int)$typeprice);
            list($nom_price) = $xoopsDB->fetchRow($result8);

            echo '<form action="view_ads.php" method="post">';
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<tr><th class='left'>" . _AM_ADSLIGHT_LID . ": {$lid}</th><th class='left'>{$photo4} " . _AM_ADSLIGHT_NBR_PHOTO . "</th><th class='left'>" . _AM_ADSLIGHT_TITLE . ":</th><th class='left'>" . _AM_ADSLIGHT_DESC . "</th><th class='left'><!--" . _AM_ADSLIGHT_ACTIONS . '--></th></tr>';

            echo "<tr><td class='even width3'></td>";
            echo "<td class='odd width10' >{$photo3}</td>";
            echo "<td class='even width20'><b>{$title}</b><br><br>{$nom_type}<br>{$price} " . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . " $nom_price<br>";
            echo "$town - $country<br>";
            echo '<b>' . _AM_ADSLIGHT_SUBMITTER . ":</b> {$submitter}<br>";
            echo '<b>' . _AM_ADSLIGHT_DATE . ":</b> {$date2}</td>";
            echo "<td class='even width='35%'>{$desctext}</td><td class='even right width2'></td>";
            echo "</tr><tr><td width='5%'></td><td>";

            echo '<select name="op">
        <option value="ModifyAds"> ' . _AM_ADSLIGHT_MODIF . '
        <option value="ListingDel"> ' . _AM_ADSLIGHT_DEL . '
               </select><input type="submit" value="' . _AM_ADSLIGHT_GO . '">';

            echo '<input type="hidden" name="valid" value="Yes">';
            echo "<input type=\"hidden\" name=\"lid\" value=\"{$lid}\">";
            echo "<input type=\"hidden\" name=\"cid\" value=\"{$cid}\">";
            echo "<input type=\"hidden\" name=\"title\" value=\"{$title}\">";
            echo "<input type=\"hidden\" name=\"status\" value=\"{$status}\">";
            echo "<input type=\"hidden\" name=\"expire\" value=\"{$expire}\">";
            echo "<input type=\"hidden\" name=\"type\" value=\"{$type}\">";
            echo "<input type=\"hidden\" name=\"desctext\" value=\"{$desctext}\">";
            echo "<input type=\"hidden\" name=\"tel\" value=\"{$tel}\">";
            echo "<input type=\"hidden\" name=\"price\" value=\"{$price}\">";
            echo "<input type=\"hidden\" name=\"typeprice\" value=\"{$typeprice}\">";
            echo "<input type=\"hidden\" name=\"typeusure\" value=\"{$typeusure}\">";
            echo "<input type=\"hidden\" name=\"date\" value=\"{$date}\">";
            echo "<input type=\"hidden\" name=\"email\" value=\"{$email}\">";
            echo "<input type=\"hidden\" name=\"submitter\" value=\"{$submitter}\">";
            echo "<input type=\"hidden\" name=\"town\" value=\"{$town}\">";
            echo "<input type=\"hidden\" name=\"country\" value=\"{$country}\">";
            echo "<input type=\"hidden\" name=\"contactby\" value=\"{$contactby}\">";
            echo "<input type=\"hidden\" name=\"premium\" value=\"{$premium}\">";
            echo "<input type=\"hidden\" name=\"photo\" value=\"{$photo}\">";
            echo '</form><br></td></tr>';
            ++$rank;
        }

        echo '</td></tr></table>
              <br><br>';
    } else {
        echo "<table class='outer width50 bnone'><tr><td class='width40'>";
        echo "<img src='../assets/images/admin/search_button_green_32.png' border=0 ></td><td>";
        echo "<span style='color: #00B4C4;'><b>" . _AM_ADSLIGHT_NOANNVALADS . '</b></span>';
        echo '</td></tr></table><br>';
    }

    xoops_cp_footer();
}

#  function IndexView
#####################################################
/**
 * @param null $lid
 */
function indexView($lid = null)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $desctext, $moduleDirName, $admin_lang;

    $mytree = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, '');

    $result  = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, photo FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='No' AND lid='{$lid}'");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        echo "<table class='width100' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td class='top'>";
        echo '<b>' . _AM_ADSLIGHT_WAIT . '</b><br><br>';

        list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $photo) = $xoopsDB->fetchRow($result);

        $lid      = (int)$lid;
        $cid      = (int)$cid;
        $date2    = formatTimestamp($date, 's');
        $title    = $myts->htmlSpecialChars($title);
        $status   = $myts->htmlSpecialChars($status);
        $expire   = $myts->htmlSpecialChars($expire);
        $type     = $myts->htmlSpecialChars($type);
        $desctext = $myts->displayTarea($desctext, 1, 1, 1);
        $tel      = $myts->htmlSpecialChars($tel);
        //        $price = number_format($price, 2, ',', ' ');
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $typeusure = $myts->htmlSpecialChars($typeusure);
        $submitter = $myts->htmlSpecialChars($submitter);
        $town      = $myts->htmlSpecialChars($town);
        $country   = $myts->htmlSpecialChars($country);
        $contactby = $myts->htmlSpecialChars($contactby);
        $premium   = $myts->htmlSpecialChars($premium);

        echo '<form action="index.php" method="post">';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_NUMANN . " </td><td>{$lid} &nbsp;&nbsp;&nbsp;&nbsp;   " . _AM_ADSLIGHT_ADDED_ON . " &nbsp;&nbsp;&nbsp;&nbsp; {$date2}</td>
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
            </tr><tr class='head' border='1'>
        <td>" . _AM_ADSLIGHT_CONTACTBY . " </td><td><input type=\"text\" name=\"contactby\" size=\"40\" value=\"{$contactby}\"></td>
            </tr>";

        echo "<tr>
            <td class='head'>" . _AM_ADSLIGHT_STATUS . "</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
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
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE . ' </td><td><select name="type">';

        $result5 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
        while (false !== (list($nom_type) = $xoopsDB->fetchRow($result5))) {
            $sel = '';
            if ($nom_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"{$nom_type}\"{$sel}>{$nom_type}</option>";
        }

        echo '</select></td></tr>';

        ////// Etat d'usure
        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE_USURE . ' </td><td><select name="typeusure">';

        $result6 = $xoopsDB->query('SELECT nom_usure FROM ' . $xoopsDB->prefix('adslight_usure') . ' ORDER BY nom_usure');
        while (false !== (list($nom_usure) = $xoopsDB->fetchRow($result6))) {
            $sel = '';
            if ($nom_usure == $typeusure) {
                $sel = 'selected';
            }
            echo "<option value=\"{$nom_usure}\"{$sel}>{$nom_usure}</option>";
        }
        echo '</select></td></tr>';

        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> " . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . '';
        $result3 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY id_price');
        echo " <select name=\"typeprice\"><option value=\"{$typeprice}\">{$typeprice}</option>";
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result3))) {
            echo "<option value=\"${nom_price}\">${nom_price}</option>";
        }
        echo '</select></td></tr>';

        echo "<tr class='head' border='1'>
           <td>" . _AM_ADSLIGHT_PHOTO1 . " </td><td><input type=\"text\" name=\"photo\" size=\"40\" value=\"{$photo}\"></td>
            </tr>";
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_DESC . '</td><td>';
        $wysiwyg_text_area = Adslight\Utility::getEditor(_AM_ADSLIGHT_DESC, 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();
        echo '</td></tr>';
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_CAT . ' </td><td>';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo "</td>
        </tr><tr class='head' border='1'>
        <td>&nbsp;</td><td><select name=\"op\">
        <option value=\"ListingValid\"> " . _AM_ADSLIGHT_OK . '
        <option value="ListingDel"> ' . _AM_ADSLIGHT_DEL . '
        </select><input type="submit" value="' . _AM_ADSLIGHT_GO . '"></td>
        </tr></table>';
        echo '<input type="hidden" name="valid" value="Yes">';
        echo "<input type=\"hidden\" name=\"lid\" value=\"{$lid}\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"{$date}\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"{$submitter}\">
            </form>";

        echo '</td></tr></table>';
        echo '<br>';
    }

    require_once __DIR__ . '/admin_footer.php';
}

#  function modifyAds
#####################################################
/**
 * @param $lid
 */
function modifyAds($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $desctext, $moduleDirName, $admin_lang;

    $mytree = new Adslight\ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, '');

    $lid           = (int)$lid;
    $id_price      = '';
    $nom_price     = '';
    $contactselect = '';

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';

    $result = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, valid, photo FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE lid={$lid}");

    while (false !== (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo) = $xoopsDB->fetchRow($result))) {
        $title = $myts->htmlSpecialChars($title);
        //        $status    = $myts->htmlSpecialChars($status);
        $status   = (int)$status;
        $expire   = $myts->htmlSpecialChars($expire);
        $type     = $myts->htmlSpecialChars($type);
        $desctext = $myts->displayTarea($desctext, 1, 1, 1);
        $tel      = $myts->htmlSpecialChars($tel);
        //        $price     = number_format($price, 2, ',', ' ');
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $typeusure = $myts->htmlSpecialChars($typeusure);
        $submitter = $myts->htmlSpecialChars($submitter);
        $town      = $myts->htmlSpecialChars($town);
        $country   = $myts->htmlSpecialChars($country);
        $contactby = $myts->htmlSpecialChars($contactby);
        $premium   = $myts->htmlSpecialChars($premium);

        $date2 = formatTimestamp($date, 's');

        echo '<form action="view_ads.php" method="post">';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table border=0><tr class='head' border='1'>
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
            </tr>
            <tr class='head' border='1'>";

        $contactSel1 = $contactSel2 = $contactSel3 = $contactSel4 = '';
        $selected    = 'contactSel' . (int)$contactby;
        $$selected   = ' selected';

        echo " <td class='head'>"
             . _AM_ADSLIGHT_CONTACTBY
             . " </td><td class='head'><select name=\"contactby\">\n"
             //           . "  <option value=\"{$contactby}\">{$contactselect}</option>\n"
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
        // Type d'annonce
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

        // Etat d'usure
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
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"{$price}\"> {$GLOBALS['xoopsModuleConfig']['adslight_currency_symbol']}";

        // Price type
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

        // Category
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
            <option value=\"ModifyAdsS\"> " . _AM_ADSLIGHT_MODIF . '
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
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    $lid   = (int)$lid;
    $cat   = (int)$cat;
    $title = $myts->htmlSpecialChars($title);
    //    $status    = $myts->htmlSpecialChars($status);
    $status    = (int)$status;
    $expire    = $myts->htmlSpecialChars($expire);
    $type      = $myts->htmlSpecialChars($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
    $tel       = $myts->htmlSpecialChars($tel);
    $price     = str_replace([' '], '', $price);
    $typeprice = $myts->htmlSpecialChars($typeprice);
    $typeusure = $myts->htmlSpecialChars($typeusure);
    $submitter = $myts->htmlSpecialChars($submitter);
    $town      = $myts->htmlSpecialChars($town);
    $country   = $myts->htmlSpecialChars($country);
    $contactby = $myts->htmlSpecialChars($contactby);
    $premium   = $myts->htmlSpecialChars($premium);

    $xoopsDB->query('UPDATE '
                    . $xoopsDB->prefix('adslight_listing')
                    . " SET cid='{$cat}', title='{$title}', status='{$status}', expire='{$expire}', type='{$type}', desctext='{$desctext}', tel='{$tel}', price='{$price}', typeprice='{$typeprice}', typeusure='{$typeusure}', date='{$date}', email='{$email}', submitter='{$submitter}', town='{$town}', country='{$country}', contactby='{$contactby}', premium='{$premium}', valid='{$valid}', photo='{$photo}' WHERE lid={$lid}");

    redirect_header('view_ads.php', 1, _AM_ADSLIGHT_ANNMOD);
}

#  function listingDel
#####################################################
/**
 * @param $lid
 * @param $photo
 */
function listingDel($lid, $photo)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $lid     = (int)$lid;
    $result2 = $xoopsDB->query('SELECT p.url FROM ' . $xoopsDB->prefix('adslight_listing') . ' l LEFT JOIN ' . $xoopsDB->prefix('adslight_pictures') . " p  ON l.lid=p.lid WHERE l.lid={$lid}");

    while (false !== (list($purl) = $xoopsDB->fetchRow($result2))) {
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
            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_pictures') . " WHERE lid={$lid}");
        }
    }

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE lid={$lid}");
    redirect_header('view_ads.php', 1, _AM_ADSLIGHT_ANNDEL);
}

#  function listingValid
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
function listingValid($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $meta, $moduleDirName, $admin_lang;

    $lid       = (int)$lid;
    $cid       = (int)$cid;
    $title     = $myts->htmlSpecialChars($title);
    $status    = (int)$status;
    $expire    = $myts->htmlSpecialChars($expire);
    $type      = $myts->htmlSpecialChars($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
    $tel       = $myts->htmlSpecialChars($tel);
    $price     = str_replace([' '], '', $price);
    $typeprice = $myts->htmlSpecialChars($typeprice);
    $typeusure = $myts->htmlSpecialChars($typeusure);
    $submitter = $myts->htmlSpecialChars($submitter);
    $town      = $myts->htmlSpecialChars($town);
    $country   = $myts->htmlSpecialChars($country);
    $contactby = $myts->htmlSpecialChars($contactby);
    $premium   = $myts->htmlSpecialChars($premium);
    $valid     = $myts->htmlSpecialChars($valid);
    $photo     = $myts->htmlSpecialChars($photo);
    $now       = time();
    $xoopsDB->query('UPDATE '
                    . $xoopsDB->prefix('adslight_listing')
                    . " SET cid='{$cat}', title='{$title}', status='{$status}', expire='{$expire}', type='{$type}', desctext='{$desctext}', tel='{$tel}', price='{$price}', typeprice='{$typeprice}', typeusure='{$typeusure}', date='{$now}', email='{$email}', submitter='{$submitter}', town='{$town}', country='{$country}', contactby='{$contactby}', premium='{$premium}', valid='{$valid}', photo='{$photo}' WHERE lid={$lid}");

    if ('' != $email) {
        $tags               = [];
        $tags['TITLE']      = $title;
        $tags['TYPE']       = Adslight\Utility::getNameType($type);
        $tags['SUBMITTER']  = $submitter;
        $tags['DESCTEXT']   = stripslashes($desctext);
        $tags['EMAIL']      = _AM_ADSLIGHT_EMAIL;
        $tags['TEL']        = _AM_ADSLIGHT_TEL;
        $tags['HELLO']      = _AM_ADSLIGHT_HELLO;
        $tags['VEDIT_AD']   = _AM_ADSLIGHT_VEDIT_AD;
        $tags['ANNACCEPT']  = _AM_ADSLIGHT_ANNACCEPT;
        $tags['CONSULTTO']  = _AM_ADSLIGHT_CONSULTTO;
        $tags['THANKS']     = _ADSLIGHT_THANKS;
        $tags['TEAMOF']     = _AM_ADSLIGHT_TEAMOF;
        $tags['META_TITLE'] = $meta['title'];
        $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/viewads.php?lid={$lid}";
        $tags['YOUR_AD']    = _AM_ADSLIGHT_YOUR_AD;
        $tags['WEBMASTER']  = _AM_ADSLIGHT_WEBMASTER;
        $tags['YOUR_AD_ON'] = _AM_ADSLIGHT_YOUR_AD_ON;
        $tags['APPROVED']   = _AM_ADSLIGHT_APPROVED;

        $subject = _AM_ADSLIGHT_ANNACCEPT;
        $mail    = &getMailer();
        $mail->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . "/language/{$xoopsConfig['language']}/mail_template/");
        $mail->setTemplate('listing_approve.tpl');
        $mail->useMail();
        $mail->multimailer->isHTML(true);
        $mail->setFromName($meta['title']);
        $mail->setFromEmail($xoopsConfig['adminmail']);
        $mail->setToEmails($email);
        $mail->setSubject($subject);
        $mail->assign($tags);
        $mail->send();
        echo $mail->getErrors();
    }

    $tags                    = [];
    $tags['TITLE']           = $title;
    $tags['ADDED_TO_CAT']    = _AM_ADSLIGHT_ADDED_TO_CAT;
    $tags['RECIEVING_NOTIF'] = _AM_ADSLIGHT_RECIEVING_NOTIF;
    $tags['ERROR_NOTIF']     = _AM_ADSLIGHT_ERROR_NOTIF;
    $tags['WEBMASTER']       = _AM_ADSLIGHT_WEBMASTER;
    $tags['HELLO']           = _AM_ADSLIGHT_HELLO;
    $tags['FOLLOW_LINK']     = _AM_ADSLIGHT_FOLLOW_LINK;
    $tags['TYPE']            = Adslight\Utility::getNameType($type);
    $tags['LINK_URL']        = XOOPS_URL . "/modules/adslight/viewads.php?&lid={$lid}";

    $sql                    = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_categories') . " WHERE cid={$cat}";
    $result                 = $xoopsDB->query($sql);
    $row                    = $xoopsDB->fetchArray($result);
    $tags['CATEGORY_TITLE'] = $row['title'];
    $tags['CATEGORY_URL']   = XOOPS_URL . "/modules/adslight/viewcats.php?cid={$cat}";
    /** @var \XoopsNotificationHandler $notificationHandler */
    $notificationHandler = xoops_getHandler('notification');
    $notificationHandler->triggerEvent('global', 0, 'new_listing', $tags);
    $notificationHandler->triggerEvent('category', $cat, 'new_listing', $tags);
    $notificationHandler->triggerEvent('listing', $lid, 'new_listing', $tags);

    redirect_header('view_ads.php', 3, _AM_ADSLIGHT_ANNVALID);
}

#####################################################
#####################################################

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

//$pa  = Request::getString('pa', '', 'GET');
//$lid = Request::getInt('lid', 0);
//$op  = Request::getCmd('op', '');

$pa  = Request::getInt('pa', '', 'GET');
$lid = 0;
if (!Request::hasVar('lid', 'POST') && Request::hasVar('lid', 'GET')) {
    $lid = Request::getInt('lid', 0, 'GET');
}

if (!Request::hasVar('op', 'POST') && Request::hasVar('op', 'GET')) {
    $op = Request::getString('op', '', 'GET');
}

if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'IndexView':
        indexView($lid);
        break;
    case 'ListingDel':
        listingDel($lid, $photo);
        break;
    case 'ListingValid':
        listingValid($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo);
        break;
    case 'ModifyAds':
        modifyAds($lid);
        break;
    case 'ModifyAdsS':
        modifyAdsS($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo);
        break;
    default:
        index();
        break;
}
