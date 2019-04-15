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

use Xmf\Module\Admin;
use Xmf\Request;

require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'liste');

#  function Index
#####################################################
function index()
{
    global $xoopsDB,  $myts, $admin_lang;
    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation(basename(__FILE__));
    // Ajouter un type
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo '<form method="post" action="options.php">
        <b>' . _AM_ADSLIGHT_ADDTYPE . '</b><br><br>
        ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddType">
        <input type="submit" value="' . _AM_ADSLIGHT_ADD . '">
        </form>';
    echo '<br>';

    // Modifier un type
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_type')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
             <b>' . _AM_ADSLIGHT_MODTYPE . '</b></font><br><br>';
        $result2 = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
        echo '' . _AM_ADSLIGHT_TYPE . ' <select name="id_type">';

        while (false !== (list($id_type, $nom_type) = $xoopsDB->fetchRow($result2))) {
            $nom_type = $myts->htmlSpecialChars($nom_type);
            echo "<option value=\"$id_type\">$nom_type</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModType">
            <input type="submit" value="' . _AM_ADSLIGHT_MODIF . '">
            </form>';
        echo '</td></tr></table>';
        echo '<br>';
    }

    // Ajouter un type de prix
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo '<form method="post" action="options.php">
        <b>' . _AM_ADSLIGHT_ADDPRICE . '</b><br><br>
        ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddPrice">
        <input type="submit" value="' . _AM_ADSLIGHT_ADD . '">
        </form>';
    echo '<br>';

    // Modifier un type de prix
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_price')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_MODPRICE . '</b></font><br><br>';
        $result3 = $xoopsDB->query('SELECT id_price, nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY nom_price');
        echo '' . _AM_ADSLIGHT_TYPE . ' <select name="id_price">';

        while (false !== (list($id_price, $nom_price) = $xoopsDB->fetchRow($result3))) {
            $nom_price = $myts->htmlSpecialChars($nom_price);
            echo "<option value=\"$id_price\">$nom_price</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModPrice">
            <input type="submit" value="' . _AM_ADSLIGHT_MODIF . '">
            </form>';
        echo '</td></tr></table>';
        echo '<br>';
    }

    // Ajouter un type d'usure
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo '<form method="post" action="options.php">
        <b>' . _AM_ADSLIGHT_ADDUSURE . '</b><br><br>
        ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddUsure">
        <input type="submit" value="' . _ADD . '">
        </form>';
    echo '<br>';

    // Modifier un type d'usure
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_usure')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_MODUSURE . '</b></font><br><br>';
        $result8 = $xoopsDB->query('SELECT id_usure, nom_usure FROM ' . $xoopsDB->prefix('adslight_usure') . ' ORDER BY nom_usure');
        echo _AM_ADSLIGHT_TYPE . ' <select name="id_usure">';

        while (false !== (list($id_usure, $nom_usure) = $xoopsDB->fetchRow($result8))) {
            $nom_usure = $myts->htmlSpecialChars($nom_usure);
            echo "<option value=\"$id_usure\">$nom_usure</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModUsure">
            <input type="submit" value="' . _AM_ADSLIGHT_MODIF . '">
            </form>';
        echo '</td></tr></table>';
        echo '<br>';
    }

    xoops_cp_footer();
}

#  function listingAddType
#####################################################
/**
 * @param $type
 */
function listingAddType($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE nom_type='{$type}'"));
    if ($numrows > 0) {
        $nom_type = $myts->htmlSpecialChars($numrows); //mb
        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORTYPE . " $nom_type " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDTYPE . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '<input type="text" name="type" size="30" maxlength="100" >
            <input type="hidden" name="op" value="ListingAddType" >
            <input type="submit" value="' . _AM_ADSLIGHT_ADD . '" >
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = $myts->htmlSpecialChars($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_type') . " values (NULL, '{$type}')");

        redirect_header('options.php', 1, _AM_ADSLIGHT_ADDTYPE2);
    }
}

#  function listingModType
#####################################################
/**
 * @param $id_type
 */
function listingModType($id_type)
{
    global $xoopsDB, $xoopsConfig,  $myts, $admin_lang;
    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    $id_type = (int)$id_type;
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODTYPE . '</legend>';
    $result = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type={$id_type}");
    list($id_type, $nom_type) = $xoopsDB->fetchRow($result);

    $nom_type = $myts->htmlSpecialChars($nom_type);

    echo '<form action="options.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo ''
         . _AM_ADSLIGHT_TYPE
         . " <input type=\"text\" name=\"nom_type\" value=\"{$nom_type}\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_type\" value=\"{$id_type}\">"
         . '<input type="hidden" name="op" value="ListingModTypeS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_ADSLIGHT_SAVMOD
         . '" ></form>'
         . '<form action="options.php" method="post">'
         . "<input type=\"hidden\" name=\"id_type\" value=\"{$id_type}\">"
         . '<input type="hidden" name="op" value="ListingDelType">'
         . '<input type="submit" value="'
         . _DELETE
         . '"></form></td></tr></table>';

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function listingModTypeS
#####################################################
/**
 * @param $id_type
 * @param $nom_type
 */
function listingModTypeS($id_type, $nom_type)
{
    global $xoopsDB, $myts;

    $id_type  = (int)$id_type;
    $nom_type = $myts->htmlSpecialChars($nom_type);
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_type') . " SET nom_type='{$nom_type}' WHERE id_type='{$id_type}'");
    redirect_header('options.php', 1, _AM_ADSLIGHT_TYPEMOD);
}

#  function listingDelType
#####################################################
/**
 * @param $id_type
 */
function listingDelType($id_type)
{
    global $xoopsDB;

    $id_type = (int)$id_type;
    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='{$id_type}'");
    redirect_header('options.php', 1, _AM_ADSLIGHT_TYPEDEL);
}

#  function listingAddPrice
#####################################################
/**
 * @param $type
 */
function listingAddPrice($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT  COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE nom_price='{$nom_price}'"));
    if ($numrows > 0) {
        $nom_price = $myts->htmlSpecialChars($numrows); //mb
        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORPRICE . " $nom_price " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDPRICE . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddPrice">
            <input type="submit" value="' . _ADD . '">
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $nom_price = $myts->htmlSpecialChars($price);
        if ('' == $nom_price) {
            $nom_price = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_price') . " values (NULL, '{$nom_price}')");

        redirect_header('options.php', 1, _AM_ADSLIGHT_ADDPRICE2);
    }
}

#  function listingModPrice
#####################################################
/**
 * @param $id_price
 */
function listingModPrice($id_price)
{
    global $xoopsDB, $myts;

    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODPRICE . '</legend>';
    echo '<b>' . _AM_ADSLIGHT_MODPRICE . '</b><br><br>';
    $id_price = (int)$id_price;
    $result   = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price={$id_price}");
    list($nom_price) = $xoopsDB->fetchRow($result);

    $nom_price = $myts->htmlSpecialChars($nom_price);

    echo '<form action="options.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo _AM_ADSLIGHT_TYPE
         . " <input type=\"text\" name=\"nom_price\" value=\"{$nom_price}\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_price\" value=\"{$id_price}\">"
         . '<input type="hidden" name="op" value="ListingModPriceS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_ADSLIGHT_SAVMOD
         . '"></form>'
         . '<form action="options.php" method="post">'
         . "<input type=\"hidden\" name=\"id_price\" value=\"{$id_price}\">"
         . '<input type="hidden" name="op" value="ListingDelPrice">'
         . '<input type="submit" value="'
         . _DELETE
         . '"></form></td></tr></table>';

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function listingModPriceS
#####################################################
/**
 * @param $id_price
 * @param $nom_price
 */
function listingModPriceS($id_price, $nom_price)
{
    global $xoopsDB, $myts;

    $id_price  = (int)$id_price;
    $nom_price = $myts->htmlSpecialChars($nom_price);
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_price') . " SET nom_price='{$nom_price}' WHERE id_price='{$id_price}'");
    redirect_header('options.php', 1, _AM_ADSLIGHT_PRICEMOD);
}

#  function listingDelPrice
#####################################################
/**
 * @param $id_price
 */
function listingDelPrice($id_price)
{
    global $xoopsDB, $admin_lang;

    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price='{$id_price}'");
    redirect_header('options.php', 1, _AM_ADSLIGHT_PRICEDEL);
}

#  function listingAddUsure
#####################################################
/**
 * @param $type
 */
function listingAddUsure($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;

    $type = $myts->htmlSpecialChars($type);

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_usure') . " WHERE nom_usure='{$type}'"));
    if ($numrows > 0) {
        $nom_usure = $myts->htmlSpecialChars($numrows); //mb

        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORUSURE . " {$nom_usure} " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDUSURE . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddUsure">
            <input type="submit" value="' . _ADD . '">
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = $myts->htmlSpecialChars($type);
        if ('' == $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_usure') . " VALUES (NULL, '{$type}')");
        redirect_header('options.php', 1, _AM_ADSLIGHT_ADDUSURE2);
    }
}

#  function listingModUsure
#####################################################
/**
 * @param $id_usure
 */
function listingModUsure($id_usure)
{
    global $xoopsDB, $myts;

    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODUSURE . '</legend>';
    echo '<b>' . _AM_ADSLIGHT_MODUSURE . '</b><br><br>';
    $result9 = $xoopsDB->query('SELECT nom_usure FROM ' . $xoopsDB->prefix('adslight_usure') . " WHERE id_usure={$id_usure}");
    list($nom_usure) = $xoopsDB->fetchRow($result9);

    $nom_usure = $myts->htmlSpecialChars($nom_usure);

    echo '<form action="options.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo _AM_ADSLIGHT_USURE
         . " <input type=\"text\" name=\"nom_usure\" value=\"{$nom_usure}\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_usure\" value=\"{$id_usure}\">"
         . '<input type="hidden" name="op" value="ListingModUsureS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_ADSLIGHT_SAVMOD
         . '"></form>'
         . '<form action="options.php" method="post">'
         . "<input type=\"hidden\" name=\"id_usure\" value=\"{$id_usure}\">"
         . '<input type="hidden" name="op" value="ListingDelUsure">'
         . '<input type="submit" value="'
         . _DELETE
         . '"></form></td></tr></table>';

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function listingModUsureS
#####################################################
/**
 * @param $id_usure
 * @param $nom_usure
 */
function listingModUsureS($id_usure, $nom_usure)
{
    global $xoopsDB,  $myts;

    $nom_usure = $myts->htmlSpecialChars($nom_usure);

    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_usure') . " SET nom_usure='{$nom_usure}' WHERE id_usure='{$id_usure}'");
    redirect_header('options.php', 1, _AM_ADSLIGHT_USUREMOD);
}

#  function listingDelUsure
#####################################################
/**
 * @param $id_usure
 */
function listingDelUsure($id_usure)
{
    global $xoopsDB, $admin_lang;

    $id_usure = (int)$id_usure;
    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_usure') . " WHERE id_usure='{$id_usure}'");

    redirect_header('options.php', 1, _AM_ADSLIGHT_USUREDEL);
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
    case 'ListingDelPrice':
        listingDelPrice($id_price);
        break;
    case 'ListingModPrice':
        listingModPrice($id_price);
        break;
    case 'ListingModPriceS':
        listingModPriceS($id_price, $nom_price);
        break;
    case 'ListingAddPrice':
        listingAddPrice($type);
        break;
    case 'ListingDelUsure':
        listingDelUsure($id_usure);
        break;
    case 'ListingModUsure':
        listingModUsure($id_usure);
        break;
    case 'ListingModUsureS':
        listingModUsureS($id_usure, $nom_usure);
        break;
    case 'ListingAddUsure':
        listingAddUsure($type);
        break;
    case 'ListingDelType':
        listingDelType($id_type);
        break;
    case 'ListingModType':
        listingModType($id_type);
        break;
    case 'ListingModTypeS':
        listingModTypeS($id_type, $nom_type);
        break;
    case 'ListingAddType':
        listingAddType($type);
        break;
    default:
        index();
        break;
}
