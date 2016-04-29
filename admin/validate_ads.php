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

include_once __DIR__ . '/admin_header.php';

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'liste';
}

#  function Index
#####################################################
function Index()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $desctext, $moduleDirName, $admin_lang;

    $mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    include_once __DIR__ . '/header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");

    // photo dir setting checker
    $photo_dir         = $xoopsModuleConfig['adslight_path_upload'];
    $photo_thumb_dir   = $xoopsModuleConfig['adslight_path_upload'] . '/thumbs';
    $photo_resized_dir = $xoopsModuleConfig['adslight_path_upload'] . '/midsize';
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
        echo "<font color='#FF0000'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_dir . '</b></font><br><br>';
        echo '</fieldset><br>';
    }

    if (!is_writable($photo_thumb_dir) || !is_readable($photo_thumb_dir)) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_CHECKER . '</legend><br>';
        echo "<font color='#FF0000'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_thumb_dir . '</b></font><br><br>';
        echo '</fieldset><br>';
    }

    if (!is_writable($photo_resized_dir) || !is_readable($photo_resized_dir)) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_CHECKER . '</legend><br>';
        echo "<font color='#FF0000'><b>" . _AM_ADSLIGHT_DIRPERMS . '' . $photo_resized_dir . '</b></font><br><br>';
        echo '</fieldset><br>';
    }

    $result  =
        $xoopsDB->query('select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, photo, usid from ' .
                        $xoopsDB->prefix('adslight_listing') .
                        " WHERE valid='no' order by lid");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {

        ///////// Il y a [..] Annonces en attente d'être approuvées //////
        echo "<table class='outer' border=0 cellspacing=5 cellpadding=0><tr><td width=40>";
        echo "<img src='../assets/images/admin/error_button.png' border=0 /></td><td>";
        echo "<font color=\"#00B4C4\"><b>" . _AM_ADSLIGHT_THEREIS . "</b></font> <b>$numrows</b> <font color=\"#00B4C4\">" . _AM_ADSLIGHT_WAIT . '</b></font>';
        echo '</td></tr></table><br>';

        ///// Liste des ID  ///// Soumis par /////  Titre   /////  Description  /////  Date d'ajout
        echo "<table width='100%' border='0' class='outer'>";
        $rank = 1;

        while (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $photo, $usid) =
            $xoopsDB->fetchRow($result)) {
            $title    = $myts->htmlSpecialChars($title);
            $desctext = $myts->displayTarea($desctext, 1, 0, 1, 1, 1);

            if (strlen($desctext) >= 200) {
                $desctext = substr($desctext, 0, 199) . '...';
            } else {
                $desctext = $myts->displayTarea($desctext, 1, 1, 1);
            }
            $date2 = formatTimestamp($date, 's');

            if (is_integer($rank / 2)) {
                $color = '#ffffff';
            } else {
                $color = 'head';
            }

            $status    = $myts->htmlSpecialChars($status);
            $expire    = $myts->htmlSpecialChars($expire);
            $type      = $myts->htmlSpecialChars($type);
            $tel       = $myts->htmlSpecialChars($tel);
            $price     = number_format($price, 2, ',', ' ');
            $typeprice = $myts->htmlSpecialChars($typeprice);
            $typeusure = $myts->htmlSpecialChars($typeusure);
            $submitter = $myts->htmlSpecialChars($submitter);
            $town      = $myts->htmlSpecialChars($town);
            $country   = $myts->htmlSpecialChars($country);
            $contactby = $myts->htmlSpecialChars($contactby);
            $premium   = $myts->htmlSpecialChars($premium);

            $updir   = $xoopsModuleConfig['adslight_link_upload'];
            $sql     = 'select cod_img, lid, uid_owner, url from ' .
                       $xoopsDB->prefix('adslight_pictures') .
                       ' where  uid_owner=' .
                       $xoopsDB->escape($usid) .
                       ' and lid=' .
                       $xoopsDB->escape($lid) .
                       ' order by date_added ASC limit 1';
            $resultp = $xoopsDB->query($sql);
            while (list($cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($resultp)) {
                if ($photo) {
                    $photo3 = "<a href='" .
                              XOOPS_URL .
                              '/modules/adslight/viewads.php?lid=' .
                              $lid .
                              "'><img class=\"thumb\" src=\"$updir/thumbs/thumb_$url\" align=\"left\" width=\"100px\" alt=\"$title\"></a>";
                }
            }
            if ($photo > 0) {
            } else {
                $photo3 = "<a href=\"index.php?op=IndexView&lid=$lid\"><img class=\"thumb\" src=\"" .
                          XOOPS_URL .
                          "/modules/adslight/assets/images/nophoto.jpg\" align=\"left\" width=\"100px\" alt=\"$title\"></a>";
            }

            if ($photo > 0) {
                $photo4 = "$photo";
            } else {
                $photo4 = '0';
            }

            $result7 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('adslight_type') . ' where id_type=' . $xoopsDB->escape($type) . '');
            list($nom_type) = $xoopsDB->fetchRow($result7);

            $result8 = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('adslight_price') . ' where id_price=' . $xoopsDB->escape($typeprice) . '');
            list($nom_price) = $xoopsDB->fetchRow($result8);

            /* $result9=$xoopsDB->query("select nom_usure from ".$xoopsDB->prefix("adslight_usure")." where id_usure=".$xoopsDB->escape($typeusure)."");
                list($nom_usure) = $xoopsDB->fetchRow($result9); */

            echo "<form action=\"validate_ads.php\" method=\"post\">";
            echo "<tr><th align='left'>" .
                 _AM_ADSLIGHT_LID .
                 ": $lid</th><th align='left'>$photo4 " .
                 _AM_ADSLIGHT_NBR_PHOTO .
                 "</th><th align='left'>" .
                 _AM_ADSLIGHT_TITLE .
                 ":</th><th align='left'>" .
                 _AM_ADSLIGHT_DESC .
                 "</th><th align='left'></th></tr>";

            echo "<tr><td class='even' width='3%'></td>";
            echo "<td class='odd' width='10%' >$photo3</td>";

            echo "<td class='even' width='20%'><b>$title</b><br/><br/>$nom_type<br/>$price " . $xoopsModuleConfig['adslight_money'] . " $nom_price<br/>";
            echo "$town - $country<br/>";
            echo '<b>' . _AM_ADSLIGHT_SUBMITTER . ":</b> $submitter<br/>";
            echo '<b>' . _AM_ADSLIGHT_DATE . ":</b> $date2</td>";
            echo "<td class='even' width='35%'>$desctext</td><td class='even' width='2%' align=right></td>";
            echo "</tr><tr><td width='5%'></td><td>";

            echo "<select name=\"op\">
        <option value=\"ListingValid\"> " . _AM_ADSLIGHT_OK . "
        <option value=\"IndexView\"> " . _AM_ADSLIGHT_MODIF . "
        <option value=\"ListingDel\"> " . _AM_ADSLIGHT_DEL . "
        </select><input type=\"submit\" value=\"" . _AM_ADSLIGHT_GO . "\">";

            echo "<input type=\"hidden\" name=\"valid\" value=\"Yes\">";
            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
            echo "<input type=\"hidden\" name=\"cid\" value=\"$cid\">";
            echo "<input type=\"hidden\" name=\"title\" value=\"$title\">";
            echo "<input type=\"hidden\" name=\"status\" value=\"$status\">";
            echo "<input type=\"hidden\" name=\"expire\" value=\"$expire\">";
            echo "<input type=\"hidden\" name=\"type\" value=\"$type\">";
            echo "<input type=\"hidden\" name=\"desctext\" value=\"$desctext\">";
            echo "<input type=\"hidden\" name=\"tel\" value=\"$tel\">";
            echo "<input type=\"hidden\" name=\"price\" value=\"$price\">";
            echo "<input type=\"hidden\" name=\"typeprice\" value=\"$typeprice\">";
            echo "<input type=\"hidden\" name=\"typeusure\" value=\"$typeusure\">";
            echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";
            echo "<input type=\"hidden\" name=\"email\" value=\"$email\">";
            echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
            echo "<input type=\"hidden\" name=\"town\" value=\"$town\">";
            echo "<input type=\"hidden\" name=\"country\" value=\"$country\">";
            echo "<input type=\"hidden\" name=\"contactby\" value=\"$contactby\">";
            echo "<input type=\"hidden\" name=\"premium\" value=\"$premium\">";
            echo "<input type=\"hidden\" name=\"photo\" value=\"$photo\">";
            echo '</form><br></td></tr>';
            ++$rank;
        }

        echo '</td></tr></table>
              <br><br>';
    } else {
        echo "<table class='outer' width='50%' border='0'><tr><td width=40>";
        echo "<img src='../assets/images/admin/search_button_green_32.png' border=0 alt=\"._AM_ADSLIGHT_RELEASEOK.\" /></td><td>";
        echo "<font color='#00B4C4'><b>" . _AM_ADSLIGHT_NOANNVAL . '</b></font>';
        echo '</td></tr></table><br>';
    }

    // Modify Annonces
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ''));
    if ($numrows > 0) {
        echo "<table width='100%' border='0' class='outer'><tr class='bg4'><td valign='top'>";
        echo "<form method=\"post\" action=\"validate_ads.php\">" .
             '<b>' .
             _AM_ADSLIGHT_MODANN .
             '</b><br><br>' .
             '' .
             _AM_ADSLIGHT_NUMANN .
             " <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\">&nbsp;&nbsp;" .
             "<input type=\"hidden\" name=\"op\" value=\"ModifyAds\">" .
             "<input type=\"submit\" value=\"" .
             _AM_ADSLIGHT_MODIF .
             "\">" .
             '</form><br>';
        echo '</td></tr></table><br>';
    }

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>";
    echo "<a href=\"map.php\">" . _AM_ADSLIGHT_GESTCAT . "</a> | <a href=\"../index.php\">" . _AM_ADSLIGHT_ACCESMYANN . '</a>';
    echo '</td></tr></table><br>';

    xoops_cp_footer();
}

#  function IndexView
#####################################################
/**
 * @param $lid
 */
function IndexView($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $desctext, $moduleDirName, $admin_lang;

    $mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    include_once __DIR__ . '/header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");

    $result  = $xoopsDB->query('select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, photo from ' .
                               $xoopsDB->prefix('adslight_listing') .
                               " WHERE valid='No' AND lid='$lid'");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>";
        echo '<b>' . _AM_ADSLIGHT_WAIT . '</b><br><br>';

        list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $photo) =
            $xoopsDB->fetchRow($result);

        $date2     = formatTimestamp($date, 's');
        $title     = $myts->htmlSpecialChars($title);
        $status    = $myts->htmlSpecialChars($status);
        $expire    = $myts->htmlSpecialChars($expire);
        $type      = $myts->htmlSpecialChars($type);
        $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
        $tel       = $myts->htmlSpecialChars($tel);
        $price     = number_format($price, 2, ',', ' ');
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $typeusure = $myts->htmlSpecialChars($typeusure);
        $submitter = $myts->htmlSpecialChars($submitter);
        $town      = $myts->htmlSpecialChars($town);
        $country   = $myts->htmlSpecialChars($country);
        $contactby = $myts->htmlSpecialChars($contactby);
        $premium   = $myts->htmlSpecialChars($premium);

        echo "<form action=\"validate_ads.php\" method=\"post\">
            <table><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_NUMANN . " </td><td>$lid &nbsp;&nbsp;&nbsp;&nbsp;   " . _AM_ADSLIGHT_ADDED_ON . " &nbsp;&nbsp;&nbsp;&nbsp; $date2</td>
            </tr><tr class='odd' border='1'>
            <td>" . _AM_ADSLIGHT_SENDBY . " </td><td>$submitter</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_EMAIL . " </td><td><input type=\"text\" name=\"email\" size=\"40\" value=\"$email\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TEL . " </td><td><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TOWN . " </td><td><input type=\"text\" name=\"town\" size=\"40\" value=\"$town\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_COUNTRY . " </td><td><input type=\"text\" name=\"country\" size=\"40\" value=\"$country\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_CONTACTBY . " </td><td><input type=\"text\" name=\"contactby\" size=\"40\" value=\"$contactby\"></td>
            </tr>";

        echo "<tr>
            <td class='head'>" . _AM_ADSLIGHT_STATUS . "</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
        if ($status == '0') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_ACTIVE . "&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"1\"";
        if ($status == '1') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_INACTIVE . "&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"2\"";
        if ($status == '2') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_SOLD . '</td></tr>';

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TITLE2 . " </td><td><input type=\"text\" name=\"title\" size=\"40\" value=\"$title\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_PREMIUM . " </td><td><input type=\"text\" name=\"premium\" size=\"3\" value=\"$premium\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_EXPIRE . " </td><td><input type=\"text\" name=\"expire\" size=\"40\" value=\"$expire\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE . " </td><td><select name=\"type\">";

        $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('adslight_type') . ' order by nom_type');
        while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';
            if ($nom_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }

        echo '</select></td></tr>';

        ////// Etat d'usure
        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TYPE_USURE . " </td><td><select name=\"typeusure\">";

        $result6 = $xoopsDB->query('select nom_usure from ' . $xoopsDB->prefix('adslight_usure') . ' order by nom_usure');
        while (list($nom_usure) = $xoopsDB->fetchRow($result6)) {
            $sel = '';
            if ($nom_usure == $typeusure) {
                $sel = 'selected';
            }
            echo "<option value=\"$nom_usure\" $sel>$nom_usure</option>";
        }
        echo '</select></td></tr>';

        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> " . $xoopsModuleConfig['adslight_money'] . '';
        $result3 = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('adslight_price') . ' order by id_price');
        echo " <select name=\"typeprice\"><option value=\"$typeprice\">$typeprice</option>";
        while (list($nom_price) = $xoopsDB->fetchRow($result3)) {
            echo "<option value=\"$nom_price\">$nom_price</option>";
        }
        echo '</select></td></tr>';

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_PHOTO1 . " </td><td><input type=\"text\" name=\"photo\" size=\"40\" value=\"$photo\"></td>
            </tr>";
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_DESC . '</td><td>';
        $wysiwyg_text_area = adslight_adminEditor(_AM_ADSLIGHT_DESC, 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();
        echo '</td></tr>';
        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_CAT . ' </td><td>';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo "</td>
        </tr><tr class='head' border='1'>
        <td>&nbsp;</td><td><select name=\"op\">
        <option value=\"ListingValid\"> " . _AM_ADSLIGHT_OK . "
        <option value=\"ListingDel\"> " . _AM_ADSLIGHT_DEL . "
        </select><input type=\"submit\" value=\"" . _AM_ADSLIGHT_GO . "\"></td>
        </tr></table>";
        echo "<input type=\"hidden\" name=\"valid\" value=\"Yes\">";
        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
            </form>";

        echo '</td></tr></table>';
        echo '<br>';
    }

    xoops_cp_footer();
}

#  function ModifyAds
#####################################################
/**
 * @param $lid
 */
function ModifyAds($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $desctext, $moduleDirName, $admin_lang;

    $mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    $id_price  = '';
    $nom_price = '';

    include_once __DIR__ . '/header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';

    $result =
        $xoopsDB->query('select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, town, country, contactby, premium, valid, photo from ' .
                        $xoopsDB->prefix('adslight_listing') .
                        " where lid=$lid");

    while (list($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo) =
        $xoopsDB->fetchRow($result)) {
        $title     = $myts->htmlSpecialChars($title);
        $status    = $myts->htmlSpecialChars($status);
        $expire    = $myts->htmlSpecialChars($expire);
        $type      = $myts->htmlSpecialChars($type);
        $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
        $tel       = $myts->htmlSpecialChars($tel);
        $price     = number_format($price, 2, ',', ' ');
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $typeusure = $myts->htmlSpecialChars($typeusure);
        $submitter = $myts->htmlSpecialChars($submitter);
        $town      = $myts->htmlSpecialChars($town);
        $country   = $myts->htmlSpecialChars($country);
        $contactby = $myts->htmlSpecialChars($contactby);
        $premium   = $myts->htmlSpecialChars($premium);

        $date2 = formatTimestamp($date, 's');

        echo "<form action=\"validate_ads.php\" method=post>
            <table border=0><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_NUMANN . " </td><td>$lid &nbsp;" . _AM_ADSLIGHT_ADDED_ON . "&nbsp; $date2</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_SENDBY . " </td><td>$submitter</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_EMAIL . " </td><td><input type=\"text\" name=\"email\" size=\"40\" value=\"$email\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TEL . " </td><td><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TOWN . " </td><td><input type=\"text\" name=\"town\" size=\"40\" value=\"$town\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_COUNTRY . " </td><td><input type=\"text\" name=\"country\" size=\"40\" value=\"$country\"></td>
            </tr>
            <tr class='head' border='1'>";

        if ($contactby == 1) {
            $contactselect = _AM_ADSLIGHT_CONTACT_BY_EMAIL;
        }
        if ($contactby == 2) {
            $contactselect = _AM_ADSLIGHT_CONTACT_BY_PM;
        }
        if ($contactby == 3) {
            $contactselect = _AM_ADSLIGHT_CONTACT_BY_BOTH;
        }
        if ($contactby == 4) {
            $contactselect = _AM_ADSLIGHT_CONTACT_BY_PHONE;
        }

        echo " <td class='head'>" . _AM_ADSLIGHT_CONTACTBY . " </td><td class='head'><select name=\"contactby\">
           <option value=\"" . $contactby . "\">" . $contactselect . "</option>
           <option value=\"1\">" . _AM_ADSLIGHT_CONTACT_BY_EMAIL . "</option>
           <option value=\"2\">" . _AM_ADSLIGHT_CONTACT_BY_PM . "</option>
           <option value=\"3\">" . _AM_ADSLIGHT_CONTACT_BY_BOTH . "</option>
           <option value=\"4\">" . _AM_ADSLIGHT_CONTACT_BY_PHONE . '</option></select></td></tr>';

        echo "<tr><td class='head'>" . _AM_ADSLIGHT_STATUS . "</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
        if ($status == '0') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_ACTIVE . "&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"1\"";
        if ($status == '1') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_INACTIVE . "&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"2\"";
        if ($status == '2') {
            echo 'checked';
        }
        echo '>' . _AM_ADSLIGHT_SOLD . '</td></tr>';

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_TITLE2 . " </td><td><input type=\"text\" name=\"title\" size=\"40\" value=\"$title\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_PREMIUM . " </td><td><input type=\"text\" name=\"premium\" size=\"3\" value=\"$premium\"></td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_EXPIRE . " </td><td><input type=\"text\" name=\"expire\" size=\"40\" value=\"$expire\"></td>
            </tr>";
        ////// Type d'annonce
        echo "<tr class='head' border='1'>
                 <td>" . _AM_ADSLIGHT_TYPE . " </td><td><select name=\"type\">";

        $result5 = $xoopsDB->query('select nom_type, id_type from ' . $xoopsDB->prefix('adslight_type') . ' order by nom_type');
        while (list($nom_type, $id_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';
            if ($id_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"$id_type\" $sel>$nom_type</option>";
        }
        echo '</select></td></tr>';

        ////// Etat d'usure

        echo "<tr class='head' border='1'>
                 <td>" . _AM_ADSLIGHT_TYPE_USURE . " </td><td><select name=\"typeusure\">";

        $result6 = $xoopsDB->query('select nom_usure, id_usure from ' . $xoopsDB->prefix('adslight_usure') . ' order by nom_usure');
        while (list($nom_usure, $id_usure) = $xoopsDB->fetchRow($result6)) {
            $sel = '';
            if ($id_usure == $typeusure) {
                $sel = 'selected';
            }
            echo "<option value=\"$id_usure\" $sel>$nom_usure</option>";
        }
        echo '</select></td></tr>';

        //////// Price

        echo "<tr class='head' border='1'><td>" . _AM_ADSLIGHT_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> " . $xoopsModuleConfig['adslight_money'] . '';

        //////// Price type

        $resultx = $xoopsDB->query('select nom_price, id_price from ' . $xoopsDB->prefix('adslight_price') . ' order by nom_price');

        echo " <select name=\"typeprice\"><option value=\"$id_price\">$nom_price</option>";
        while (list($nom_price, $id_price) = $xoopsDB->fetchRow($resultx)) {
            $sel = '';
            if ($id_price == $typeprice) {
                $sel = 'selected';
            }

            echo "<option value=\"$id_price\" $sel>$nom_price</option>";
        }
        echo '</select></td>';

        /////// Category
        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_CAT2 . ' </td><td>';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo "</td>
            </tr><tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_DESC . ' </td><td>';

        $wysiwyg_text_area = adslight_adminEditor('', 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();

        echo '</td></tr>';

        echo "<tr class='head' border='1'>
            <td>" . _AM_ADSLIGHT_PHOTO1 . " </td><td><input type=\"text\" name=\"photo\" size=\"50\" value=\"$photo\"></td>
            </tr><tr>";
        $time = time();
        echo "</tr><tr class='head' border='1'>
            <td>&nbsp;</td><td><select name=\"op\">
            <option value=\"ModifyAdsS\"> " . _AM_ADSLIGHT_MODIF . "
            <option value=\"ListingDel\"> " . _AM_ADSLIGHT_DEL . "
            </select><input type=\"submit\" value=\"" . _AM_ADSLIGHT_GO . "\"></td>
            </tr></table>";
        echo "<input type=\"hidden\" name=\"valid\" value=\"Yes\">";
        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$time\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
        </form><br>";
        echo '</fieldset><br>';
        xoops_cp_footer();
    }
}

#  function ModifyAdsS
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
function ModifyAdsS($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    $title     = $myts->htmlSpecialChars($title);
    $status    = $myts->htmlSpecialChars($status);
    $expire    = $myts->htmlSpecialChars($expire);
    $type      = $myts->htmlSpecialChars($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
    $tel       = $myts->htmlSpecialChars($tel);
    $price     = str_replace(array(' '), '', $price);
    $typeprice = $myts->htmlSpecialChars($typeprice);
    $typeusure = $myts->htmlSpecialChars($typeusure);
    $submitter = $myts->htmlSpecialChars($submitter);
    $town      = $myts->htmlSpecialChars($town);
    $country   = $myts->htmlSpecialChars($country);
    $contactby = $myts->htmlSpecialChars($contactby);
    $premium   = $myts->htmlSpecialChars($premium);

    $xoopsDB->query('update ' .
                    $xoopsDB->prefix('adslight_listing') .
                    " set cid='$cat', title='$title', status='$status', expire='$expire', type='$type', desctext='$desctext', tel='$tel', price='$price', typeprice='$typeprice', typeusure='$typeusure', date='$date', email='$email', submitter='$submitter', town='$town', country='$country', contactby='$contactby', premium='$premium', valid='$valid', photo='$photo' where lid=$lid");

    redirect_header('validate_ads.php', 1, _AM_ADSLIGHT_ANNMOD);
}

#  function ListingDel
#####################################################
/**
 * @param $lid
 * @param $photo
 */
function ListingDel($lid, $photo)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $result2 = $xoopsDB->query('select p.url FROM ' .
                               $xoopsDB->prefix('adslight_listing') .
                               ' l LEFT JOIN ' .
                               $xoopsDB->prefix('adslight_pictures') .
                               ' p  ON l.lid=p.lid where l.lid=' .
                               $xoopsDB->escape($lid) .
                               '');

    while (list($purl) = $xoopsDB->fetchRow($result2)) {
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
            $xoopsDB->query('delete from ' . $xoopsDB->prefix('adslight_pictures') . " where lid=$lid");
        }
    }

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('adslight_listing') . " where lid=$lid");

    redirect_header('validate_ads.php', 1, _AM_ADSLIGHT_ANNDEL);
}

#  function ListingValid
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
function ListingValid($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $meta, $moduleDirName, $admin_lang;

    $title     = $myts->htmlSpecialChars($title);
    $status    = $myts->htmlSpecialChars($status);
    $expire    = $myts->htmlSpecialChars($expire);
    $type      = $myts->htmlSpecialChars($type);
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1);
    $tel       = $myts->htmlSpecialChars($tel);
    $price     = str_replace(array(' '), '', $price);
    $typeprice = $myts->htmlSpecialChars($typeprice);
    $typeusure = $myts->htmlSpecialChars($typeusure);
    $submitter = $myts->htmlSpecialChars($submitter);
    $town      = $myts->htmlSpecialChars($town);
    $country   = $myts->htmlSpecialChars($country);
    $contactby = $myts->htmlSpecialChars($contactby);
    $premium   = $myts->htmlSpecialChars($premium);
    $now       = time();
    $xoopsDB->query('update ' .
                    $xoopsDB->prefix('adslight_listing') .
                    " set cid='$cat', title='$title', status='$status', expire='$expire', type='$type', desctext='$desctext', tel='$tel', price='$price', typeprice='$typeprice', typeusure='$typeusure', date='$now', email='$email', submitter='$submitter', town='$town', country='$country', contactby='$contactby', premium='$premium', valid='$valid', photo='$photo' where lid=$lid");

    if ($email == '') {
    } else {
        $tags               = array();
        $tags['TITLE']      = $title;
        $tags['TYPE']       = adslight_NameType($type);
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
        $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewads.php?lid=' . $lid . '';
        $tags['YOUR_AD']    = _AM_ADSLIGHT_YOUR_AD;
        $tags['WEBMASTER']  = _AM_ADSLIGHT_WEBMASTER;
        $tags['YOUR_AD_ON'] = _AM_ADSLIGHT_YOUR_AD_ON;
        $tags['APPROVED']   = _AM_ADSLIGHT_APPROVED;

        $subject = '' . _AM_ADSLIGHT_ANNACCEPT . '';
        $mail    =& getMailer();
        $mail->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
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

    $tags                    = array();
    $tags['TITLE']           = $title;
    $tags['ADDED_TO_CAT']    = _AM_ADSLIGHT_ADDED_TO_CAT;
    $tags['RECIEVING_NOTIF'] = _AM_ADSLIGHT_RECIEVING_NOTIF;
    $tags['ERROR_NOTIF']     = _AM_ADSLIGHT_ERROR_NOTIF;
    $tags['WEBMASTER']       = _AM_ADSLIGHT_WEBMASTER;
    $tags['HELLO']           = _AM_ADSLIGHT_HELLO;
    $tags['FOLLOW_LINK']     = _AM_ADSLIGHT_FOLLOW_LINK;
    $tags['TYPE']            = adslight_NameType($type);
    $tags['LINK_URL']        = XOOPS_URL . '/modules/adslight/viewads.php?' . '&lid=' . $lid;
    $sql                     = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_categories') . ' WHERE cid=' . addslashes($cat);
    $result                  = $xoopsDB->query($sql);
    $row                     = $xoopsDB->fetchArray($result);
    $tags['CATEGORY_TITLE']  = $row['title'];
    $tags['CATEGORY_URL']    = XOOPS_URL . '/modules/adslight/viewcats.php?cid="' . addslashes($cat);
    $notification_handler    = xoops_getHandler('notification');
    $notification_handler->triggerEvent('global', 0, 'new_listing', $tags);
    $notification_handler->triggerEvent('category', $cat, 'new_listing', $tags);
    $notification_handler->triggerEvent('listing', $lid, 'new_listing', $tags);

    redirect_header('validate_ads.php', 3, _AM_ADSLIGHT_ANNVALID);
}

#####################################################
#####################################################

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = isset($_GET['pa']) ? $_GET['pa'] : '';

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {

    case 'IndexView':
        IndexView($lid);
        break;

    case 'ListingDel':
        ListingDel($lid, $photo);
        break;

    case 'ListingValid':
        ListingValid($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo);
        break;

    case 'ModifyAds':
        ModifyAds($lid);
        break;

    case 'ModifyAdsS':
        ModifyAdsS($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid, $photo);
        break;

    default:
        Index();
        break;

}
