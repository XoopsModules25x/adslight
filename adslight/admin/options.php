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
include 'admin_header.php';

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'liste';
}

#  function Index
#####################################################
function Index()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $mydirname, $admin_lang;
    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");

    // Ajouter un type
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo "<form method=\"post\" action=\"options.php\">
        <b>"._AM_ADSLIGHT_ADDTYPE."</b><br /><br />
        "._AM_ADSLIGHT_TYPE."	<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\">
        <input type=\"hidden\" name=\"op\" value=\"ListingAddType\">
        <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\">
        </form>";
    echo "<br />";

    // Modifier un type
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_type").""));
    if ($numrows>0) {
        echo "<form method=\"post\" action=\"options.php\">
             <b>"._AM_ADSLIGHT_MODTYPE."</b></font><br /><br />";
        $result2 = $xoopsDB->query("select id_type, nom_type from ".$xoopsDB->prefix("adslight_type")." order by nom_type");
        echo ""._AM_ADSLIGHT_TYPE." <select name=\"id_type\">";

        while (list($id_type, $nom_type) = $xoopsDB->fetchRow($result2)) {
            $nom_type = $myts->htmlSpecialChars($nom_type);
            echo "<option value=\"$id_type\">$nom_type</option>";
          }
        echo "</select>
            <input type=\"hidden\" name=\"op\" value=\"ListingModType\">
            <input type=\"submit\" value=\""._AM_ADSLIGHT_MODIF."\">
            </form>";
        echo '</td></tr></table>';
        echo "<br />";
    }

    // Ajouter un type de prix
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo "<form method=\"post\" action=\"options.php\">
        <b>"._AM_ADSLIGHT_ADDPRICE."</b><br /><br />
        "._AM_ADSLIGHT_TYPE."	<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\">
        <input type=\"hidden\" name=\"op\" value=\"ListingAddPrice\">
        <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\">
        </form>";
    echo "<br />";

    // Modifier un type de prix
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_price").""));
    if ($numrows>0) {
        echo "<form method=\"post\" action=\"options.php\">
            <b>"._AM_ADSLIGHT_MODPRICE."</b></font><br /><br />";
        $result3 = $xoopsDB->query("select id_price, nom_price from ".$xoopsDB->prefix("adslight_price")." order by nom_price");
        echo ""._AM_ADSLIGHT_TYPE." <select name=\"id_price\">";

        while (list($id_price, $nom_price) = $xoopsDB->fetchRow($result3)) {
            $nom_price = $myts->htmlSpecialChars($nom_price);
            echo "<option value=\"$id_price\">$nom_price</option>";
          }
        echo "</select>
            <input type=\"hidden\" name=\"op\" value=\"ListingModPrice\">
            <input type=\"submit\" value=\""._AM_ADSLIGHT_MODIF."\">
            </form>";
        echo '</td></tr></table>';
        echo "<br />";
    }

    // Ajouter un type d'usure
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
    echo "<form method=\"post\" action=\"options.php\">
        <b>"._AM_ADSLIGHT_ADDUSURE."</b><br /><br />
        "._AM_ADSLIGHT_TYPE."	<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\">
        <input type=\"hidden\" name=\"op\" value=\"ListingAddUsure\">
        <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\">
        </form>";
    echo "<br />";

    // Modifier un type d'usure
    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_usure").""));
    if ($numrows>0) {
        echo "<form method=\"post\" action=\"options.php\">
            <b>"._AM_ADSLIGHT_MODUSURE."</b></font><br /><br />";
        $result8 = $xoopsDB->query("select id_usure, nom_usure from ".$xoopsDB->prefix("adslight_usure")." order by nom_usure");
        echo ""._AM_ADSLIGHT_TYPE." <select name=\"id_usure\">";

        while (list($id_usure, $nom_usure) = $xoopsDB->fetchRow($result8)) {
            $nom_usure = $myts->htmlSpecialChars($nom_usure);
            echo "<option value=\"$id_usure\">$nom_usure</option>";
          }
        echo "</select>
            <input type=\"hidden\" name=\"op\" value=\"ListingModUsure\">
            <input type=\"submit\" value=\""._AM_ADSLIGHT_MODIF."\">
            </form>";
        echo '</td></tr></table>';
        echo "<br />";
    }

    xoops_cp_footer();
}

#  function ListingAddType
#####################################################
function ListingAddType($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $mydirname, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select  COUNT(*)  FROM ".$xoopsDB->prefix("adslight_type")." where nom_type='$type'"));
    if ($numrows>0) {
    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");

        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #DFE0E0;'><tr class='bg4'><td valign='top'>\n";
        echo "<br /><center><b>"._AM_ADSLIGHT_ERRORTYPE." $nom_type "._AM_ADSLIGHT_EXIST."</b><br /><br />";
        echo "<form method=\"post\" action=\"options.php\">
            <b>"._AM_ADSLIGHT_ADDTYPE."</b><br /><br />
            "._AM_ADSLIGHT_TYPE."<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\" />
            <input type=\"hidden\" name=\"op\" value=\"ListingAddType\" />
            <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\" />
            </form>";
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = $myts->htmlSpecialChars($type);

        if ($type == "") {
            $type = "! ! ? ! !";
        }
        $xoopsDB->query("insert into ".$xoopsDB->prefix("adslight_type")." values (NULL, '$type')");

        redirect_header("options.php",1,_AM_ADSLIGHT_ADDTYPE2);
        exit();
    }
}

#  function ListingModType
#####################################################
function ListingModType($id_type)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $mydirname, $admin_lang;
    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_ADSLIGHT_MODTYPE."</legend>";
    $result = $xoopsDB->query("select id_type, nom_type from ".$xoopsDB->prefix("adslight_type")." where id_type=$id_type");
    list($id_type, $nom_type) = $xoopsDB->fetchRow($result);

    $nom_type = $myts->htmlSpecialChars($nom_type);

    echo "<form action=\"options.php\" method=\"post\">"
        .""._AM_ADSLIGHT_TYPE." <input type=\"text\" name=\"nom_type\" value=\"$nom_type\" size=\"51\" maxlength=\"50\" /><br />"
        ."<input type=\"hidden\" name=\"id_type\" value=\"$id_type\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingModTypeS\" />"
        ."<table border=\"0\"><tr><td>"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_SAVMOD."\" /></form>"
        ."<form action=\"options.php\" method=\"post\">"
        ."<input type=\"hidden\" name=\"id_type\" value=\"$id_type\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingDelType\" />"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_DEL."\" /></form></td></tr></table>";

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function ListingModTypeS
#####################################################
function ListingModTypeS($id_type, $nom_type)
{
    global $xoopsDB,$xoopsConfig, $myts, $mydirname, $admin_lang;

    $nom_type = $myts->htmlSpecialChars($nom_type);

    $xoopsDB->query("update ".$xoopsDB->prefix("adslight_type")." set nom_type='$nom_type' where id_type='$id_type'");

    redirect_header("options.php",1,_AM_ADSLIGHT_TYPEMOD);
    exit();
}

#  function ListingDelType
#####################################################
function ListingDelType($id_type)
{
    global $xoopsDB, $mydirname, $admin_lang;

    $xoopsDB->query("delete from ".$xoopsDB->prefix("adslight_type")." where id_type='$id_type'");

    redirect_header("options.php",1,_AM_ADSLIGHT_TYPEDEL);
    exit();
}

#  function ListingAddPrice
#####################################################
function ListingAddPrice($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $mydirname, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select  COUNT(*)  FROM ".$xoopsDB->prefix("adslight_price")." where nom_price='$type'"));
    if ($numrows>0) {
    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_ADSLIGHT_MODANN."</legend>";
        echo "<br /><center><b>"._AM_ADSLIGHT_ERRORPRICE." $nom_price "._AM_ADSLIGHT_EXIST."</b><br /><br />";
        echo "<form method=\"post\" action=\"options.php\">
            <b>"._AM_ADSLIGHT_ADDPRICE."</b><br /><br />
            "._AM_ADSLIGHT_TYPE."	<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\">
            <input type=\"hidden\" name=\"op\" value=\"ListingAddPrice\">
            <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\">
            </form>";
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = $myts->htmlSpecialChars($type);
        if ($type == "") {
            $type = "! ! ? ! !";
        }
        $xoopsDB->query("insert into ".$xoopsDB->prefix("adslight_price")." values (NULL, '$type')");

        redirect_header("options.php",1,_AM_ADSLIGHT_ADDPRICE2);
        exit();
    }
}

#  function ListingModPrice
#####################################################
function ListingModPrice($id_price)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $mydirname, $admin_lang;

    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_ADSLIGHT_MODPRICE."</legend>";
    echo "<b>"._AM_ADSLIGHT_MODPRICE."</b><br /><br />";
    $result = $xoopsDB->query("select nom_price from ".$xoopsDB->prefix("adslight_price")." where id_price=$id_price");
    list($nom_price) = $xoopsDB->fetchRow($result);

    $nom_price = $myts->htmlSpecialChars($nom_price);

    echo "<form action=\"options.php\" method=\"post\">"
        .""._AM_ADSLIGHT_TYPE." <input type=\"text\" name=\"nom_price\" value=\"$nom_price\" size=\"51\" maxlength=\"50\"><br />"
        ."<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingModPriceS\">"
        ."<table border=\"0\"><tr><td>"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_SAVMOD."\"></form>"
        ."<form action=\"options.php\" method=\"post\">"
        ."<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingDelPrice\">"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_DEL."\"></form></td></tr></table>";

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function ListingModPriceS
#####################################################
function ListingModPriceS($id_price, $nom_price)
{
    global $xoopsDB, $xoopsConfig, $myts, $mydirname, $admin_lang;

    $nom_price = $myts->htmlSpecialChars($nom_price);

    $xoopsDB->query("update ".$xoopsDB->prefix("adslight_price")." set nom_price='$nom_price' where id_price='$id_price'");

    redirect_header("options.php",1,_AM_ADSLIGHT_PRICEMOD);
    exit();
}

#  function ListingDelPrice
#####################################################
function ListingDelPrice($id_price)
{
    global $xoopsDB, $mydirname, $admin_lang;

    $xoopsDB->query("delete from ".$xoopsDB->prefix("adslight_price")." where id_price='$id_price'");

    redirect_header("options.php",1,_AM_ADSLIGHT_PRICEDEL);
    exit();
}

#  function ListingAddUsure
#####################################################
function ListingAddUsure($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $mydirname, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select  COUNT(*)  FROM ".$xoopsDB->prefix("adslight_usure")." where nom_usure='$type'"));
    if ($numrows>0) {
    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");

        echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_ADSLIGHT_MODANN."</legend>";
        echo "<br /><center><b>"._AM_ADSLIGHT_ERRORUSURE." $nom_usure "._AM_ADSLIGHT_EXIST."</b><br /><br />";
        echo "<form method=\"post\" action=\"options.php\">
            <b>"._AM_ADSLIGHT_ADDUSURE."</b><br /><br />
            "._AM_ADSLIGHT_TYPE."	<input type=\"text\" name=\"type\" size=\"30\" maxlength=\"100\">
            <input type=\"hidden\" name=\"op\" value=\"ListingAddUsure\">
            <input type=\"submit\" value=\""._AM_ADSLIGHT_ADD."\">
            </form>";
        echo '</td></tr></table>';
        xoops_cp_footer();
    } else {
        $type = $myts->htmlSpecialChars($type);
        if ($type == "") {
            $type = "! ! ? ! !";
        }
        $xoopsDB->query("insert into ".$xoopsDB->prefix("adslight_usure")." values (NULL, '$type')");

        redirect_header("options.php",1,_AM_ADSLIGHT_ADDUSURE2);
        exit();
    }
}

#  function ListingModUsure
#####################################################
function ListingModUsure($id_usure)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $mydirname, $admin_lang;

    include 'header.php';
    xoops_cp_header();
//    loadModuleAdminMenu(2, "");
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_ADSLIGHT_MODUSURE."</legend>";
    echo "<b>"._AM_ADSLIGHT_MODUSURE."</b><br /><br />";
    $result9 = $xoopsDB->query("select nom_usure from ".$xoopsDB->prefix("adslight_usure")." where id_usure=$id_usure");
    list($nom_usure) = $xoopsDB->fetchRow($result9);

    $nom_usure = $myts->htmlSpecialChars($nom_usure);

    echo "<form action=\"options.php\" method=\"post\">"
        .""._AM_ADSLIGHT_USURE." <input type=\"text\" name=\"nom_usure\" value=\"$nom_usure\" size=\"51\" maxlength=\"50\"><br />"
        ."<input type=\"hidden\" name=\"id_usure\" value=\"$id_usure\">"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingModUsureS\">"
        ."<table border=\"0\"><tr><td>"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_SAVMOD."\"></form>"
        ."<form action=\"options.php\" method=\"post\">"
        ."<input type=\"hidden\" name=\"id_usure\" value=\"$id_usure\">"
        ."<input type=\"hidden\" name=\"op\" value=\"ListingDelUsure\">"
        ."<input type=\"submit\" value=\""._AM_ADSLIGHT_DEL."\"></form></td></tr></table>";

    echo '</td></tr></table>';
    xoops_cp_footer();
}

#  function ListingModUsureS
#####################################################
function ListingModUsureS($id_usure, $nom_usure)
{
    global $xoopsDB, $xoopsConfig, $myts, $mydirname, $admin_lang;

    $nom_usure = $myts->htmlSpecialChars($nom_usure);

    $xoopsDB->query("update ".$xoopsDB->prefix("adslight_usure")." set nom_usure='$nom_usure' where id_usure='$id_usure'");

    redirect_header("options.php",1,_AM_ADSLIGHT_USUREMOD);
    exit();
}

#  function ListingDelUsure
#####################################################
function ListingDelUsure($id_usure)
{
    global $xoopsDB, $mydirname, $admin_lang;

    $xoopsDB->query("delete from ".$xoopsDB->prefix("adslight_usure")." where id_usure='$id_usure'");

    redirect_header("options.php",1,_AM_ADSLIGHT_USUREDEL);
    exit();
}

#####################################################
#####################################################

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = isset( $_GET['pa'] ) ? $_GET['pa'] : '' ;

if (!isset($_POST['lid']) && isset($_GET['lid']) ) {
    $lid = $_GET['lid'] ;
}
if (!isset($_POST['op']) && isset($_GET['op']) ) {
    $op = $_GET['op'] ;
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {

    case "ListingDelPrice":
    ListingDelPrice($id_price);
    break;

    case "ListingModPrice":
    ListingModPrice($id_price);
    break;

    case "ListingModPriceS":
    ListingModPriceS($id_price, $nom_price);
    break;

    case "ListingAddPrice":
    ListingAddPrice($type);
    break;

    case "ListingDelUsure":
    ListingDelUsure($id_usure);
    break;

    case "ListingModUsure":
    ListingModUsure($id_usure);
    break;

    case "ListingModUsureS":
    ListingModUsureS($id_usure, $nom_usure);
    break;

    case "ListingAddUsure":
    ListingAddUsure($type);
    break;

    case "ListingDelType":
    ListingDelType($id_type);
    break;

    case "ListingModType":
    ListingModType($id_type);
    break;

    case "ListingModTypeS":
    ListingModTypeS($id_type, $nom_type);
    break;

    case "ListingAddType":
    ListingAddType($type);
    break;

    default:
    Index();
    break;

}
