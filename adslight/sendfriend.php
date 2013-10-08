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
include 'header.php';
require XOOPS_ROOT_PATH.'/modules/adslight/include/gtickets.php';
include XOOPS_ROOT_PATH.'/modules/adslight/include/functions.php';

function SendFriend($lid)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $mydirname, $main_lang;

	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include XOOPS_ROOT_PATH."/header.php";
	$xoTheme -> addMeta ( 'meta', 'robots', 'noindex, nofollow');

	$result = $xoopsDB->query("select lid, title, type FROM ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lid)."");
    list($lid, $title, $type) = $xoopsDB->fetchRow($result);
	

	echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>
        <strong>"._ADSLIGHT_SENDTO." $lid \"<strong>$type : $title</strong>\" "._ADSLIGHT_FRIEND."<br /><br />
	    <form action=\"sendfriend.php\" method=post>
	    <input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
	    
    if($xoopsUser) {
		$idd = $xoopsUser->getVar("uname", "E");
		$idde = $xoopsUser->getVar("email", "E");
	}
	
    echo "
	<table width='99%' class='outer' cellspacing='1'>
    <tr>
      <td class='head' width='30%'>"._ADSLIGHT_NAME." </td>
      <td class='even'><input class='textbox' type='text' name='yname' value='$idd' /></td>
    </tr>
    <tr>
      <td class='head'>"._ADSLIGHT_MAIL." </td>
      <td class='even'><input class='textbox' type='text' name='ymail' value='$idde' /></td>
    </tr>
    <tr>
      <td class='head'>"._ADSLIGHT_NAMEFR." </td>
      <td class='even'><input class='textbox' type='text' name='fname' /></td>
    </tr>
    <tr>
      <td class='head'>"._ADSLIGHT_MAILFR." </td>
      <td class='even'><input class='textbox' type='text' name='fmail' /></td>
    </tr>";

if ($xoopsModuleConfig["adslight_use_captcha"] == '1') {
    echo "<tr><td class='head'>"._ADSLIGHT_CAPTCHA." </td><td class='even'>";
	$jlm_captcha = "";
	$jlm_captcha = (new XoopsFormCaptcha(_ADSLIGHT_CAPTCHA, "xoopscaptcha", false));
	echo $jlm_captcha->render();
    echo '</td></tr>';
}

echo "</table><br />
    <input type=hidden name=op value=MailAd>
    <input type=submit value="._ADSLIGHT_SENDFR.">
    </form></td></tr></table>";
}


function MailAd($lid, $yname, $ymail, $fname, $fmail)
{
    global $xoopsConfig, $xoopsUser, $xoopsTpl, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $myts, $xoopsLogger, $mydirname, $main_lang;

if ($xoopsModuleConfig["adslight_use_captcha"] == '1') {
	xoops_load("xoopscaptcha");
	$xoopsCaptcha = XoopsCaptcha::getInstance();
	if( !$xoopsCaptcha->verify() ) {
        redirect_header( XOOPS_URL . "/modules/adslight/index.php", 2, $xoopsCaptcha->getMessage() );
		}
	}
	
	$result = $xoopsDB->query("select lid, title, expire, type, desctext, tel, price, typeprice, date, email, submitter, town, country, photo FROM ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lid)."");
    list($lid, $title, $expire, $type, $desctext, $tel, $price, $typeprice, $date, $email, $submitter, $town, $country, $photo) = $xoopsDB->fetchRow($result);
	
	$title = $myts->addSlashes($title);
	$expire = $myts->addSlashes($expire);
	$type = $myts->addSlashes($type);
	$desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
	$tel = $myts->addSlashes($tel);
	$price = $myts->addSlashes($price);
	$typeprice = $myts->addSlashes($typeprice);
	$submitter = $myts->addSlashes($submitter);	
	$town = $myts->addSlashes($town);
	$country = $myts->addSlashes($country); 

	$tags = array();
	$tags['YNAME'] = stripslashes($yname);
	$tags['YMAIL'] = $ymail;
	$tags['FNAME'] = stripslashes($fname);
	$tags['FMAIL'] = $fmail;
	$tags['HELLO'] = _ADSLIGHT_HELLO;
	$tags['LID'] = $lid;
	$tags['LISTING_NUMBER'] = _ADSLIGHT_LISTING_NUMBER;
	$tags['TITLE'] = $title;
	$tags['TYPE'] = adslight_NameType($type);
	$tags['DESCTEXT'] = $desctext;
	$tags['PRICE'] = $price;
	$tags['TYPEPRICE'] = $typeprice;
	$tags['TEL'] = $tel;
	$tags['TOWN'] = $town;
	$tags['COUNTRY'] = $country;
	$tags['OTHER'] = ""._ADSLIGHT_INTERESS."". $xoopsConfig['sitename']."";
	$tags['LISTINGS'] = "".XOOPS_URL."/modules/adslight/";
	$tags['LINK_URL'] = "".XOOPS_URL."/modules/adslight/viewads.php?lid=".$lid."";
	$tags['THINKS_INTERESTING'] = ""._ADSLIGHT_MESSAGE."";
	$tags['NO_MAIL'] = ""._ADSLIGHT_NOMAIL."";
	$tags['YOU_CAN_VIEW_BELOW'] = ""._ADSLIGHT_YOU_CAN_VIEW_BELOW."";
	$tags['WEBMASTER'] = _ADSLIGHT_WEBMASTER;
	$tags['NO_REPLY'] = _ADSLIGHT_NOREPLY;
	$subject = ""._ADSLIGHT_SUBJET." ".$xoopsConfig['sitename']."";
	$xoopsMailer =& xoops_getMailer();
	$xoopsMailer->multimailer->isHTML(true);
	$xoopsMailer->useMail();
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/". $xoopsModule->getVar('dirname') ."/language/".$xoopsConfig['language']."/mail_template/");

	$xoopsMailer->setTemplate("listing_send_friend.tpl");
	$xoopsMailer->setFromEmail($ymail);
	$xoopsMailer->setToEmails($fmail);
	$xoopsMailer->setSubject($subject);
    $xoopsMailer->assign($tags);
    $xoopsMailer->send();
	echo $xoopsMailer->getErrors();

	redirect_header("index.php",3,_ADSLIGHT_ANNSEND);
	exit();
}

##############################################################
$yname = !empty($_POST['yname']) ? $myts->addSlashes($_POST['yname']) : "";
$ymail = !empty($_POST['ymail']) ? $myts->addSlashes($_POST['ymail']) : "";
$fname = !empty($_POST['fname']) ? $myts->addSlashes($_POST['fname']) : "";
$fmail = !empty($_POST['fmail']) ? $myts->addSlashes($_POST['fmail']) : "";

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

    case "SendFriend":
	include(XOOPS_ROOT_PATH."/header.php");
	SendFriend($lid);
	include(XOOPS_ROOT_PATH."/footer.php");
	break;
	
    case "MailAd":
	MailAd($lid, $yname, $ymail, $fname, $fmail);
	break;

    default:
	redirect_header("index.php",1,""._RETURNGLO."");
	break;

}