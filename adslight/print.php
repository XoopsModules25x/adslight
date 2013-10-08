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

include("header.php");
require_once( XOOPS_ROOT_PATH."/modules/adslight/include/gtickets.php" ) ;
include(XOOPS_ROOT_PATH."/modules/adslight/include/functions.php");

function PrintAd($lid)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $xoopsModuleConfig, $useroffset, $myts, $xoopsLogger, $mydirname, $main_lang;
	
	$currenttheme = $xoopsConfig['theme_set'];
	
	$result = $xoopsDB->query("select l.lid, l.title, l.expire, l.type, l.desctext, l.tel, l.price, l.typeprice, l.date, l.email, l.submitter, l.town, l.country, l.photo, p.cod_img, p.lid, p.uid_owner, p.url FROM ".$xoopsDB->prefix("adslight_listing")." l LEFT JOIN ".$xoopsDB->prefix("adslight_pictures")." p ON l.lid=p.lid where l.lid=".mysql_real_escape_string($lid)."");
    list($lid, $title, $expire, $type, $desctext, $tel, $price, $typeprice, $date, $email, $submitter, $town, $country, $photo, $cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($result);
	
	$title = $myts->htmlSpecialChars($title);
	$expire = $myts->htmlSpecialChars($expire);
	$type = adslight_NameType($myts->htmlSpecialChars($type));
	$desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
	$tel = $myts->htmlSpecialChars($tel);
	$price = $myts->htmlSpecialChars($price);
	$typeprice = $myts->htmlSpecialChars($typeprice);
	$submitter = $myts->htmlSpecialChars($submitter);	
	$town = $myts->htmlSpecialChars($town);
	$country = $myts->htmlSpecialChars($country);
	
    echo "
    <html>
    <head><title>".$xoopsConfig['sitename']."</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta http-equiv=\”robots\” content=\"noindex, nofollow, noarchive\" />
	<link rel=\"StyleSheet\" href=\"../../themes/".$currenttheme."/style/style.css\" type=\"text/css\">
	</head>
    <body bgcolor=\"#FFFFFF\" text=\"#000000\">
    <table border=0><tr><td>
    <table border=0 width=100% cellpadding=0 cellspacing=1 bgcolor=\"#000000\"><tr><td>
    <table border=0 width=100% cellpadding=15 cellspacing=1 bgcolor=\"#FFFFFF\"><tr><td>";

	$useroffset = "";
    if($xoopsUser) {
		$timezone = $xoopsUser->timezone();
		if(isset($timezone)){
			$useroffset = $xoopsUser->timezone();
		}else{
			$useroffset = $xoopsConfig['default_TZ'];
		}
	}
	$date = ($useroffset*3600) + $date;	
	$date2 = $date + ($expire*86400);
	$date = formatTimestamp($date,"s");
	$date2 = formatTimestamp($date2,"s");
	
	echo "<br /><br /><table width=99% border=0>
	    <tr>
      <td>"._ADSLIGHT_CLASSIFIED." (No. $lid ) <br />"._ADSLIGHT_FROM." $submitter <br /><br />";
	
	echo " <strong>$type :</strong> <i>$title</i><br />";
	if ($price > 0) {
		echo"<strong>"._ADSLIGHT_PRICE2."</strong> $price ". $xoopsModuleConfig["adslight_money"]."  - $typeprice<br />";
	}
	if ($photo) {     
		echo "<tr><td><left><img class=\"thumb\" src=\"".XOOPS_URL."/uploads/AdsLight/$url\" width=\"130px\" border=0 /></center>";
	}
	echo "</td>
	      </tr>
    <tr>
      <td><strong>"._ADSLIGHT_DESC."</strong><br /><br /><div style=\"text-align:justify;\">$desctext</div><p>";
	if ($tel) {
		echo "<br /><strong>"._ADSLIGHT_TEL."</strong> $tel";
	}
	if ($town) {
		echo "<br /><strong>"._ADSLIGHT_TOWN."</strong> $town";
	}
	if ($country) {
		echo "<br /><strong>"._ADSLIGHT_COUNTRY."</strong> $country";
	}
	echo "<hr />";
	echo ""._ADSLIGHT_NOMAIL." <br />".XOOPS_URL."/modules/adslight/viewads.php?lid=".$lid."<br />";
	echo "<br /><br />"._ADSLIGHT_DATE2." $date "._ADSLIGHT_AND." "._ADSLIGHT_DISPO." $date2<br /><br />";
	echo "</td>
	</tr>
	</table>";
	echo "<br /><br /></td></tr></table></td></tr></table>
    <br /><br /><center>
    "._ADSLIGHT_EXTRANN." <strong>".$xoopsConfig['sitename']."</strong><br />
    <a href=\"".XOOPS_URL."/modules/adslight/\">".XOOPS_URL."/modules/adslight/</a>
    </td></tr></table>
    </body>
    </html>";
}

##############################################################

if(!isset($_POST['lid']) && isset($_GET['lid']) ) {
	$lid = intval($_GET['lid']) ;
}else {
	$lid = intval($_POST['lid']) ;
}

$op= '';
if (!empty($_GET['op'])) {
	$op = $_GET['op'];
} elseif (!empty($_POST['op'])) {
	$op = $_POST['op'];
}

switch($op) {
	
    case "PrintAd":
	PrintAd($lid);
	break;

    default:
	redirect_header("index.php",3,""._RETURNGLO."");
	break;

}