<?php

declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Adslight\Helper;

require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'list');

#  function Index
#####################################################
function index(): void
{
    global $xoopsDB, $myts, $admin_lang;
    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    $adminObject = Admin::getInstance();
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
    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_type')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
             <b>' . _AM_ADSLIGHT_MODTYPE . '</b></font><br><br>';
        $result2 = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('adslight_type') . ' ORDER BY nom_type');
        echo '' . _AM_ADSLIGHT_TYPE . ' <select name="id_type">';

        while ([$id_type, $nom_type] = $xoopsDB->fetchRow($result2)) {
            $nom_type = \htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);
            echo "<option value=\"${id_type}\">${nom_type}</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModType">
            <input type="submit" value="' . _AM_ADSLIGHT_MODIF . '">
            </form>';
        echo '</td></tr></table>';
        echo '<br>';
    }

    // add a price type
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo '<form method="post" action="options.php">
        <b>' . _AM_ADSLIGHT_ADDPRICE . '</b><br><br>
        ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddPrice">
        <input type="submit" value="' . _AM_ADSLIGHT_ADD . '">
        </form>';
    echo '<br>';

    // modify a price type
    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_price')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_MODPRICE . '</b></font><br><br>';
        $result3 = $xoopsDB->query('SELECT id_price, nom_price FROM ' . $xoopsDB->prefix('adslight_price') . ' ORDER BY nom_price');
        echo '' . _AM_ADSLIGHT_TYPE . ' <select name="id_price">';
        while ([$id_price, $nom_price] = $xoopsDB->fetchRow($result3)) {
            $nom_price = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
            echo "<option value=\"${id_price}\">${nom_price}</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModPrice">
            <input type="submit" value="' . _AM_ADSLIGHT_MODIF . '">
            </form>';
        echo '</td></tr></table>';
        echo '<br>';
    }

    // Add a condition type
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo '<form method="post" action="options.php">
        <b>' . _AM_ADSLIGHT_ADDCONDITION . '</b><br><br>
        ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddcondition">
        <input type="submit" value="' . _ADD . '">
        </form>';
    echo '<br>';

    // Modify a condition type
    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_condition')));
    if ($numrows > 0) {
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_MODCONDITION . '</b></font><br><br>';
        $result8 = $xoopsDB->query('SELECT id_condition, nom_condition FROM ' . $xoopsDB->prefix('adslight_condition') . ' ORDER BY nom_condition');
        echo _AM_ADSLIGHT_TYPE . ' <select name="id_condition">';

        while ([$id_condition, $nom_condition] = $xoopsDB->fetchRow($result8)) {
            $nom_condition = \htmlspecialchars($nom_condition, ENT_QUOTES | ENT_HTML5);
            echo "<option value=\"${id_condition}\">${nom_condition}</option>";
        }
        echo '</select>
            <input type="hidden" name="op" value="ListingModcondition">
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
function listingAddType($type): void
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;
    $helper = Helper::getInstance();

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE nom_type='{$type}'"));
    if ($numrows > 0) {
        $nom_type = \htmlspecialchars($numrows, ENT_QUOTES | ENT_HTML5); //mb
        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORTYPE . " ${nom_type} " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDTYPE . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '<input type="text" name="type" size="30" maxlength="100" >
            <input type="hidden" name="op" value="ListingAddType" >
            <input type="submit" value="' . _AM_ADSLIGHT_ADD . '" >
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);
        if ('' === $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_type') . " values (NULL, '{$type}')");
        $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_ADDTYPE2);
    }
}

#  function listingModType
#####################################################
/**
 * @param $id_type
 */
function listingModType($id_type): void
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;
    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    $id_type = (int)$id_type;
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODTYPE . '</legend>';
    $result = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type={$id_type}");
    [$id_type, $nom_type] = $xoopsDB->fetchRow($result);

    $nom_type = \htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);

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
function listingModTypeS($id_type, $nom_type): void
{
    global $xoopsDB, $myts;
    $helper   = Helper::getInstance();
    $id_type  = (int)$id_type;
    $nom_type = \htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_type') . " SET nom_type='{$nom_type}' WHERE id_type='{$id_type}'");
    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_TYPEMOD);
}

#  function listingDelType
#####################################################
/**
 * @param $id_type
 */
function listingDelType($id_type): void
{
    global $xoopsDB;
    $helper  = Helper::getInstance();
    $id_type = (int)$id_type;
    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='{$id_type}'");
    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_TYPEDEL);
}

#  function listingAddPrice
#####################################################
/**
 * @param $type
 */
function listingAddPrice($type): void
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;
    $helper = Helper::getInstance();
    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT  COUNT(*)  FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE nom_price='{$nom_price}'"));
    if ($numrows > 0) {
        $nom_price = \htmlspecialchars($numrows, ENT_QUOTES | ENT_HTML5); //mb
        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORPRICE . " ${nom_price} " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDPRICE . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddPrice">
            <input type="submit" value="' . _ADD . '">
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $nom_price = \htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);
        if ('' === $nom_price) {
            $nom_price = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_price') . " values (NULL, '{$nom_price}')");
        $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_ADDPRICE2);
    }
}

#  function listingModPrice
#####################################################
/**
 * @param $id_price
 */
function listingModPrice($id_price): void
{
    global $xoopsDB, $myts;

    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODPRICE . '</legend>';
    echo '<b>' . _AM_ADSLIGHT_MODPRICE . '</b><br><br>';
    $id_price = (int)$id_price;
    $result   = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price={$id_price}");
    [$nom_price] = $xoopsDB->fetchRow($result);

    $nom_price = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);

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
function listingModPriceS($id_price, $nom_price): void
{
    global $xoopsDB, $myts;
    $helper    = Helper::getInstance();
    $id_price  = (int)$id_price;
    $nom_price = \htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_price') . " SET nom_price='{$nom_price}' WHERE id_price='{$id_price}'");
    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_PRICEMOD);
}

#  function listingDelPrice
#####################################################
/**
 * @param $id_price
 */
function listingDelPrice($id_price): void
{
    global $xoopsDB, $admin_lang;
    $helper = Helper::getInstance();
    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_price') . " WHERE id_price='{$id_price}'");
    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_PRICEDEL);
}

#  function listingAddUser
#####################################################
/**
 * @param $type
 */
function listingAddcondition($type): void
{
    global $xoopsDB, $xoopsConfig, $myts, $admin_lang;
    $helper = Helper::getInstance();
    $type   = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_condition') . " WHERE nom_condition='{$type}'"));
    if ($numrows > 0) {
        $nom_condition = \htmlspecialchars($numrows, ENT_QUOTES | ENT_HTML5); //mb

        //        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();
        //    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODANN . '</legend>';
        echo '<br><div style="text-align:center;"><b>' . _AM_ADSLIGHT_ERRORCONDITION . " {$nom_condition} " . _AM_ADSLIGHT_EXIST . '</b></div><br><br>';
        echo '<form method="post" action="options.php">
            <b>' . _AM_ADSLIGHT_ADDCONDITION . '</b><br><br>
            ' . _AM_ADSLIGHT_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddcondition">
            <input type="submit" value="' . _ADD . '">
            </form>';
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = \htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);
        if ('' === $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('adslight_condition') . " VALUES (NULL, '{$type}')");
        $helper->redirect('options.php', 1, _AM_ADSLIGHT_ADDCONDITION2);
    }
}

#  function listingModcondition
#####################################################
/**
 * @param $id_condition
 */
function listingModcondition($id_condition): void
{
    global $xoopsDB, $myts;

    //    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_ADSLIGHT_MODCONDITION . '</legend>';
    echo '<b>' . _AM_ADSLIGHT_MODCONDITION . '</b><br><br>';
    $result9 = $xoopsDB->query('SELECT nom_condition FROM ' . $xoopsDB->prefix('adslight_condition') . " WHERE id_condition={$id_condition}");
    [$nom_condition] = $xoopsDB->fetchRow($result9);

    $nom_condition = \htmlspecialchars($nom_condition, ENT_QUOTES | ENT_HTML5);

    echo '<form action="options.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo _AM_ADSLIGHT_CONDITION
         . " <input type=\"text\" name=\"nom_condition\" value=\"{$nom_condition}\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_condition\" value=\"{$id_condition}\">"
         . '<input type="hidden" name="op" value="ListingModconditionS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_ADSLIGHT_SAVMOD
         . '"></form>'
         . '<form action="options.php" method="post">'
         . "<input type=\"hidden\" name=\"id_condition\" value=\"{$id_condition}\">"
         . '<input type="hidden" name="op" value="ListingDelcondition">'
         . '<input type="submit" value="'
         . _DELETE
         . '"></form></td></tr></table>';

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function listingModconditionS
#####################################################
/**
 * @param $id_condition
 * @param $nom_condition
 */
function listingModconditionS($id_condition, $nom_condition): void
{
    global $xoopsDB, $myts;
    $helper        = Helper::getInstance();
    $nom_condition = \htmlspecialchars($nom_condition, ENT_QUOTES | ENT_HTML5);

    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('adslight_condition') . " SET nom_condition='{$nom_condition}' WHERE id_condition='{$id_condition}'");
    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_CONDITIONMOD);
}

#  function listingDelcondition
#####################################################
/**
 * @param $id_condition
 */
function listingDelcondition($id_condition): void
{
    global $xoopsDB, $admin_lang;
    $helper       = Helper::getInstance();
    $id_condition = (int)$id_condition;
    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('adslight_condition') . " WHERE id_condition='{$id_condition}'");

    $helper->redirect('admin/options.php', 1, _AM_ADSLIGHT_CONDITIONDEL);
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
    case 'ListingDelcondition':
        listingDelcondition($id_condition);
        break;
    case 'ListingModcondition':
        listingModcondition($id_condition);
        break;
    case 'ListingModconditionS':
        listingModconditionS($id_condition, $nom_condition);
        break;
    case 'ListingAddcondition':
        listingAddcondition($type);
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
