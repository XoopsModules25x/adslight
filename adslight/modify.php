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
$mydirname = basename( dirname( __FILE__ ) ) ;
$main_lang =  '_' . strtoupper( $mydirname ) ;
require_once( XOOPS_ROOT_PATH."/modules/adslight/include/gtickets.php" ) ;
$myts =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}
$gperm_handler =& xoops_gethandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = intval($_POST['item_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight("adslight_submit", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL."/modules/adslight/index.php", 3, _NOPERM);
    exit();
}

function ListingDel($lid, $ok)
{
	global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger, $mydirname, $main_lang;

	$result = $xoopsDB->query("select usid FROM ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lid)."");
	list($usid) = $xoopsDB->fetchRow($result);
	
	$result1 = $xoopsDB->query("select url FROM ".$xoopsDB->prefix("adslight_pictures")." where lid=".mysql_real_escape_string($lid)."");
	
	if ($xoopsUser) {
		$currentid = $xoopsUser->getVar("uid", "E");
		if ($usid == $currentid) {
			if($ok==1) {
				while( list( $purl ) = $xoopsDB->fetchRow( $result1 ) ) {
				if ($purl) 
				{
					$destination = XOOPS_ROOT_PATH."/uploads/AdsLight";
					if (file_exists("$destination/$purl")) {
						unlink("$destination/$purl");
					}
					$destination2 = XOOPS_ROOT_PATH."/uploads/AdsLight/thumbs";
					if (file_exists("$destination2/thumb_$purl")) {
						unlink("$destination2/thumb_$purl");
					}
					$destination3 = XOOPS_ROOT_PATH."/uploads/AdsLight/midsize";
					if (file_exists("$destination3/resized_$purl")) {
						unlink("$destination3/resized_$purl");
					}	
					
				$xoopsDB->queryf("delete from ".$xoopsDB->prefix("adslight_pictures")." where lid=".mysql_real_escape_string($lid)."");
				}
				}
				$xoopsDB->queryf("delete from ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lid)."");
				redirect_header("index.php",1,_ADSLIGHT_ANNDEL);
				exit();
			} else {
				echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
				echo "<br /><center>";
				echo "<strong>"._ADSLIGHT_SURDELANN."</strong><br /><br />";
			}
			echo "[ <a href=\"modify.php?op=ListingDel&amp;lid=".$lid."&amp;ok=1\">"._ADSLIGHT_OUI."</a> | <a href=\"index.php\">"._ADSLIGHT_NON."</a> ]<br /><br />";
			echo "</td></tr></table>";
		}
	}
}

function DelReply($r_lid, $ok)
{
	global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger, $mydirname, $main_lang;

	$result = $xoopsDB->query("select l.usid, r.r_lid, r.lid, r.title, r.date, r.submitter, r.message, r.tele, r.email, r.r_usid FROM ".$xoopsDB->prefix("adslight_listing")." l LEFT JOIN ".$xoopsDB->prefix("adslight_replies")." r ON l.lid=r.lid  where r.r_lid=".mysql_real_escape_string($r_lid)."");
	list($usid, $r_lid, $rlid, $title, $date, $submitter, $message, $tele, $email, $r_usid) = $xoopsDB->fetchRow($result);

	if ($xoopsUser) {
		$currentid = $xoopsUser->getVar("uid", "E");
		if ($usid == $currentid) {
			if($ok==1) {
			    $xoopsDB->queryf("delete from ".$xoopsDB->prefix("adslight_replies")." where r_lid=".mysql_real_escape_string($r_lid)."");
				redirect_header("members.php?usid=".addslashes($usid)."",1,_ADSLIGHT_ANNDEL);
				exit();
			} else {
				echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
				echo "<br /><center>";
				echo "<strong>"._ADSLIGHT_SURDELANN."</strong><br /><br />";
			}
			echo "[ <a href=\"modify.php?op=DelReply&amp;r_lid=".addslashes($r_lid)."&amp;ok=1\">"._ADSLIGHT_OUI."</a> | <a href=\"members.php?usid=".addslashes($usid)."\">"._ADSLIGHT_NON."</a> ]<br /><br />";
			echo "</td></tr></table>";
			
		}
	}
}

function ModAd($lid)
{
	global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsTheme, $myts, $xoopsLogger, $mydirname, $main_lang;

	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	include_once XOOPS_ROOT_PATH."/modules/adslight/include/functions.php";
	echo "<script language=\"javascript\">\nfunction CLA(CLA) { var MainWindow = window.open (CLA, \"_blank\",\"width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no\");}\n</script>";

	include_once(XOOPS_ROOT_PATH."/modules/adslight/class/classifiedstree.php");
	$mytree = new ClassifiedsTree($xoopsDB->prefix("adslight_categories"),"cid","pid");

	$result = $xoopsDB->query("select lid, cid, title, status, expire, type, desctext, tel, price, typeprice, typeusure, date, email, submitter, usid, town, country, contactby, premium, valid from ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lid)."");
	list($lid, $cide, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $usid, $town, $country, $contactby, $premium, $valid) = $xoopsDB->fetchRow($result);

	$categories = adslight_MygetItemIds("adslight_submit");
	if(is_array($categories) && count($categories) > 0) {
	if(!in_array($cide, $categories)) {
	redirect_header(XOOPS_URL."/modules/adslight/index.php", 3, _NOPERM);
	exit();
	}
	} else {	// User can't see any category
	redirect_header(XOOPS_URL.'/index.php', 3, _NOPERM);
	exit();
	}

	if ($xoopsUser) {
	$calusern = $xoopsUser->uid();
	if ($usid == $calusern) {
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._ADSLIGHT_MODIFANN."</legend><br /><br />";
	$title = $myts->htmlSpecialChars($title);
	$status = $myts->htmlSpecialChars($status);
	$expire = $myts->htmlSpecialChars($expire);
	$type = $myts->htmlSpecialChars($type);
	$desctext = $myts->displayTarea($desctext,1);
	$tel = $myts->htmlSpecialChars($tel);
	$price = number_format($price, 2, ",", " ");
	$typeprice = $myts->htmlSpecialChars($typeprice);
	$typeusure = $myts->htmlSpecialChars($typeusure);
	$submitter = $myts->htmlSpecialChars($submitter);	
	$town = $myts->htmlSpecialChars($town);
	$country = $myts->htmlSpecialChars($country);
	$contactby = $myts->htmlSpecialChars($contactby);
	$premium = $myts->htmlSpecialChars($premium);
	$useroffset = "";
	if($xoopsUser) {
		$timezone = $xoopsUser->timezone();
		if(isset($timezone)){
		$useroffset = $xoopsUser->timezone();
		}else{
		$useroffset = $xoopsConfig['default_TZ'];
		}
	}
	$dates = ($useroffset*3600) + $date;	
	$dates = formatTimestamp($date,"s");
		
	echo "<form action=\"modify.php\" method=post enctype=\"multipart/form-data\">
	<table><tr class=\"head\" border=\"2\">
	<td class=\"head\">"._ADSLIGHT_NUMANNN." </td><td class=\"head\" border=\"1\">$lid "._ADSLIGHT_DU." $dates</td>
	</tr><tr>";

	if ($xoopsModuleConfig["adslight_diff_name"] == "1") {
	echo "<td class=\"head\">"._ADSLIGHT_SENDBY." </td><td class=\"head\"><input type=\"text\" name=\"submitter\" size=\"50\" value=\"$submitter\" /></td>";
	}else{
	echo "<td class=\"head\">"._ADSLIGHT_SENDBY." </td><td class=\"head\"><input type=\"hidden\" name=\"submitter\" value=\"$submitter\">$submitter</td>";
	}
	echo "</tr><tr>";
	
	if ($contactby == 1) { $contactselect = _ADSLIGHT_CONTACT_BY_EMAIL; }
	if ($contactby == 2) { $contactselect = _ADSLIGHT_CONTACT_BY_PM; }
	if ($contactby == 3) { $contactselect = _ADSLIGHT_CONTACT_BY_BOTH; }
	if ($contactby == 4) { $contactselect = _ADSLIGHT_CONTACT_BY_PHONE; }
	
	echo " <td class='head'>"._ADSLIGHT_CONTACTBY." </td><td class='head'><select name=\"contactby\">
	<option value=\"".$contactby."\">".$contactselect."</option>
	<option value=\"1\">"._ADSLIGHT_CONTACT_BY_EMAIL."</option>
	<option value=\"2\">"._ADSLIGHT_CONTACT_BY_PM."</option>
	<option value=\"3\">"._ADSLIGHT_CONTACT_BY_BOTH."</option>
	<option value=\"4\">"._ADSLIGHT_CONTACT_BY_PHONE."</option></select></td></tr>";

	if ($xoopsModuleConfig["adslight_diff_email"] == '1') {
			
	echo "<tr><td class=\"head\">"._ADSLIGHT_EMAIL." </td><td class=\"head\"><input type=\"text\" name=\"email\" size=\"50\" value=\"$email\" /></td>";
	}else{
	echo "<tr><td class=\"head\">"._ADSLIGHT_EMAIL." </td><td class=\"head\">$email<input type=\"hidden\" name=\"email\" value=\"$email\" /></td>";
	}
	echo "</tr><tr>
	<td class=\"head\">"._ADSLIGHT_TEL." </td><td class=\"head\"><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\" /></td>
	</tr>";
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_TOWN." </td><td class=\"head\"><input type=\"text\" name=\"town\" size=\"50\" value=\"$town\" /></td>
	</tr>";
	if ($xoopsModuleConfig["adslight_use_country"] == '1') {
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_COUNTRY." </td><td class=\"head\"><input type=\"text\" name=\"country\" size=\"50\" value=\"$country\" /></td>
	</tr>";
	} else {
	echo "<input type=\"hidden\" name=\"country\" value=\"\">";
	}

	echo "<tr><td class='head'>"._ADSLIGHT_STATUS."</td><td class='head'><input type=\"radio\" name=\"status\" value=\"0\"";
	if ($status == "0") {
	echo "checked";
	}
	echo ">"._ADSLIGHT_ACTIVE."&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"1\""; 
	if ($status == "1") {
	echo "checked";
	} 
	echo ">"._ADSLIGHT_INACTIVE."&nbsp;&nbsp; <input type=\"radio\" name=\"status\" value=\"2\"";
	if ($status == "2") {
	echo "checked";
	} 
	echo ">"._ADSLIGHT_SOLD."</td></tr>";
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_TITLE2." </td><td class=\"head\"><input type=\"text\" name=\"title\" size=\"50\" value=\"$title\" /></td>
	</tr>";
	echo "<tr><td class=\"head\">"._ADSLIGHT_PRICE2." </td><td class=\"head\"><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\" /> ". $xoopsModuleConfig["adslight_money"];
	
	
	
	$result3 = $xoopsDB->query("select nom_price, id_price from ".$xoopsDB->prefix("adslight_price")." order by id_price");
	echo " <select name=\"typeprice\">";
	while(list($nom_price, $id_price) = $xoopsDB->fetchRow($result3)) {
	$sel = "";
	if ($id_price == $typeprice) {
	$sel = "selected";
	}
	echo "<option value=\"$id_price\" $sel>$nom_price</option>";
	}
	echo "</select></td></tr>";
	$module_id = $xoopsModule->getVar('mid');
	if (is_object($xoopsUser)) {
 	   $groups = $xoopsUser->getGroups();
	} else {
		$groups = XOOPS_GROUP_ANONYMOUS;
	}
	$gperm_handler =& xoops_gethandler('groupperm');
	if (isset($_POST['item_id'])) {
  	  $perm_itemid = intval($_POST['item_id']);
	} else {
	    $perm_itemid = 0;
	}
				//If no access
	if (!$gperm_handler->checkRight("adslight_premium", $perm_itemid, $groups, $module_id)) {
	echo "<tr>
	<td width='30%' class='head'>"._ADSLIGHT_WILL_LAST." </td><td class='head'>$expire  "._ADSLIGHT_DAY."</td>
	</tr>";
	echo "<input type=\"hidden\" name=\"expire\" value=\"$expire\" />";
	}else{
	echo "<tr>
	<td width='30%' class='head'>"._ADSLIGHT_HOW_LONG." </td><td class='head'><input type=\"text\" name=\"expire\" size=\"3\" maxlength=\"3\" value=\"$expire\" />  "._ADSLIGHT_DAY."</td>
	</tr>";
	}
	

	
/// Type d'annonce	
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_TYPE." </td><td class=\"head\"><select name=\"type\">";
		
	$result5=$xoopsDB->query("select nom_type, id_type from ".$xoopsDB->prefix("adslight_type")." order by nom_type");
	while(list($nom_type, $id_type) = $xoopsDB->fetchRow($result5)) {
	$sel = "";
	if ($id_type == $type) {
	$sel = "selected";
	}
	echo "<option value=\"$id_type\" $sel>$nom_type</option>";
	}
	echo "</select></td></tr>";

/// Etat de l'objet	
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_TYPE_USURE." </td><td class=\"head\"><select name=\"typeusure\">";
		
	$result6=$xoopsDB->query("select nom_usure, id_usure from ".$xoopsDB->prefix("adslight_usure")." order by nom_usure");
	while(list($nom_usure, $id_usure) = $xoopsDB->fetchRow($result6)) {
	$sel = "";
	if ($id_usure == $typeusure) {
	$sel = "selected";
	}
	echo "<option value=\"$id_usure\" $sel>$nom_usure</option>";
	}
	echo "</select></td></tr>";	
	
	echo "<tr>
	<td class=\"head\">"._ADSLIGHT_CAT." </td><td class=\"head\">";
	$mytree->makeMySelBox('title','title', $cide,'','cid');
	echo "</td>
	</tr><tr>
	<td class=\"head\">"._ADSLIGHT_DESC." </td><td class=\"head\">";
	$wysiwyg_text_area= adslight_getEditor( _ADSLIGHT_DESC, "desctext", $desctext, '100%', '200px');
	echo $wysiwyg_text_area->render();
	echo "</td></tr>
	<td colspan=2><br /><input type=\"submit\" value=\""._ADSLIGHT_MODIFANN."\" /></td>
	</tr></table>";
	echo "<input type=\"hidden\" name=\"op\" value=\"ModAdS\" />";

	$module_id = $xoopsModule->getVar('mid');
	if (is_object($xoopsUser)) {
 	   $groups = $xoopsUser->getGroups();
	} else {
		$groups = XOOPS_GROUP_ANONYMOUS;
	}
	$gperm_handler =& xoops_gethandler('groupperm');
	if (isset($_POST['item_id'])) {
  	  $perm_itemid = intval($_POST['item_id']);
	} else {
	    $perm_itemid = 0;
	}
				//If no access
	if (!$gperm_handler->checkRight("adslight_premium", $perm_itemid, $groups, $module_id)) {

	if ($xoopsModuleConfig["adslight_moderated"] == '1') {
	echo "<input type=\"hidden\" name=\"valid\" value=\"No\" />";
	echo "<br />"._ADSLIGHT_MODIFBEFORE."<br />";
	} else {
	echo "<input type=\"hidden\" name=\"valid\" value=\"Yes\" />";
	}
	} else {
	echo "<input type=\"hidden\" name=\"valid\" value=\"Yes\" />";
	}
	echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
	echo "<input type=\"hidden\" name=\"premium\" value=\"$premium\" />";
	echo "<input type=\"hidden\" name=\"date\" value=\"$date\" />
	".$GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ , 1800 , 'token')."";
    	echo "</form><br /></fieldset><br />";
        	}
	}
}


function ModAdS($lid, $cat, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid)
{
	global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $xoopsLogger, $mydirname, $main_lang, $xoopsGTicket;
	
	if ( ! $xoopsGTicket->check( true , 'token' ) ) {
		redirect_header(XOOPS_URL."/modules/adslight/index.php", 3,$xoopsGTicket->getErrors());
	}
	$title = $myts->addSlashes($title);
	$status = $myts->addSlashes($status);
	$expire = $myts->addSlashes($expire);
	$type = $myts->addSlashes($type);
	$desctext = $myts->displayTarea($desctext,1,1,1,1,1);
	$tel = $myts->addSlashes($tel);
	$price = str_replace(array(' '), '', $price);
	$typeprice = $myts->addSlashes($typeprice);
	$typeusure = $myts->addSlashes($typeusure);
	$submitter = $myts->addSlashes($submitter);	
	$town = $myts->addSlashes($town);
	$country = $myts->addSlashes($country);
	$contactby = $myts->addSlashes($contactby);
	$premium = $myts->addSlashes($premium);

    $xoopsDB->query("update ".$xoopsDB->prefix("adslight_listing")." set cid='$cat', title='$title', status='$status',  expire='$expire', type='$type', desctext='$desctext', tel='$tel', price='$price', typeprice='$typeprice', typeusure='$typeusure', email='$email', submitter='$submitter', town='$town', country='$country', contactby='$contactby', premium='$premium', valid='$valid' where lid=$lid");

	redirect_header("index.php",1,_ADSLIGHT_ANNMOD2);
	exit();
}

####################################################
foreach ($_POST as $k => $v) {
	${$k} = $v;
}
$ok = isset( $_GET['ok'] ) ? $_GET['ok'] : '' ;

if(!isset($_POST['lid']) && isset($_GET['lid']) ) {
	$lid = $_GET['lid'] ;
}
if(!isset($_POST['r_lid']) && isset($_GET['r_lid']) ) {
	$r_lid = $_GET['r_lid'] ;
}
if(!isset($_POST['op']) && isset($_GET['op']) ) {
	$op = $_GET['op'] ;
}
switch ($op) {

	case "ModAd":
		include(XOOPS_ROOT_PATH."/header.php");
		ModAd($lid);
		include(XOOPS_ROOT_PATH."/footer.php");
	break;
	
	case "ModAdS":
		    ModAdS($lid, $cid, $title, $status, $expire, $type, $desctext, $tel, $price, $typeprice, $typeusure, $date, $email, $submitter, $town, $country, $contactby, $premium, $valid);
	break;

    	case "ListingDel":
		include(XOOPS_ROOT_PATH."/header.php");
 		ListingDel($lid, $ok);
		include(XOOPS_ROOT_PATH."/footer.php");
    	break;

	case "DelReply":
		include(XOOPS_ROOT_PATH."/header.php");
 		DelReply($r_lid, $ok);
		include(XOOPS_ROOT_PATH."/footer.php");
    	break;

	default:
		redirect_header("index.php",1,""._RETURNANN."");
	break;
}