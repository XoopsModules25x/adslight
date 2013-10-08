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

if ($_POST['submit']) {
    // Define Variables for register_globals Off. contribution by Peekay
    $id        = !isset($_REQUEST['id']) ? NULL : $_REQUEST['id'];
    $date      = !isset($_REQUEST['date']) ? NULL : $_REQUEST['date'];
    $namep     = !isset($_REQUEST['namep']) ? NULL : $_REQUEST['namep'];
    $ipnumber  = !isset($_REQUEST['ipnumber']) ? NULL : $_REQUEST['ipnumber'];
    $messtext  = !isset($_REQUEST['messtext']) ? NULL : $_REQUEST['messtext'];
    $typeprice = !isset($_REQUEST['typeprice']) ? NULL : $_REQUEST['typeprice'];
    $price     = !isset($_REQUEST['price']) ? NULL : $_REQUEST['price'];
    $tele      = !isset($_REQUEST['tele']) ? NULL : $_REQUEST['tele'];
    // end define vars

    include("header.php");

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
    if (!$gperm_handler->checkRight("adslight_view", $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
        exit();
    }
    global $xoopsConfig, $xoopsModuleConfig, $xoopsDB, $myts, $meta;
    require_once(XOOPS_ROOT_PATH . "/modules/adslight/include/gtickets.php");


    if (!$xoopsGTicket->check(true, 'token')) {
        redirect_header(
            XOOPS_URL . "/modules/adslight/viewads.php?lid=" . addslashes($id) . "", 3, $xoopsGTicket->getErrors()
        );
    }
    if ($xoopsModuleConfig["adslight_use_captcha"] == '1') {
        xoops_load("xoopscaptcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            redirect_header(
                XOOPS_URL . "/modules/adslight/contact.php?lid=" . addslashes($id) . "", 2, $xoopsCaptcha->getMessage()
            );
        }
    }
    $lid    = $_POST['id'];
    $result = $xoopsDB->query(
        "select email, submitter, title, type, desctext, price, typeprice FROM  " . $xoopsDB->prefix("adslight_listing")
            . " WHERE lid = " . mysql_real_escape_string($id) . ""
    );

    while (list($email, $submitter, $title, $type, $desctext, $price, $typeprice) = $xoopsDB->fetchRow($result)) {

        if ($_POST['tele']) {
            $teles = $_POST['tele'];
        } else {
            $teles = "";
        }

        if ($price) {
            $price = "" . _ADSLIGHT_PRICE . " " . $xoopsModuleConfig["adslight_money"] . " $price";
        } else {
            $price = "";
        }

        $date   = time();
        $r_usid = $xoopsUser->getVar("uid", "E");

        $tags                = array();
        $tags['TITLE']       = $title;
        $tags['TYPE'] = adslight_NameType($type);
        $tags['PRICE']       = $price;
        $tags['DESCTEXT']    = stripslashes($desctext);
        $tags['MY_SITENAME'] = $xoopsConfig['sitename'];
        $tags['REPLY_ON']    = _ADSLIGHT_REMINDANN;
        $tags['DESCRIPT']    = _ADSLIGHT_DESC;
        $tags['STARTMESS']   = _ADSLIGHT_STARTMESS;
        $tags['MESSFROM']    = _ADSLIGHT_MESSFROM;
        $tags['CANJOINT']    = _ADSLIGHT_CANJOINT;
        $tags['NAMEP']       = $_POST['namep'];
        $tags['TO']          = _ADSLIGHT_TO;
        $tags['POST']        = "<a href=\"mailto:" . $_POST['post'] . "\">" . $_POST['post'] . "</a>";
        $tags['TELE']        = $teles;
        $tags['MESSAGE_END'] = _ADSLIGHT_MESSAGE_END;
        $tags['ENDMESS']     = _ADSLIGHT_ENDMESS;
        $tags['SECURE_SEND'] = _ADSLIGHT_SECURE_SEND;
        $tags['SUBMITTER']   = $submitter;
        $tags['MESSTEXT']    = stripslashes($messtext);
        $tags['EMAIL']       = _ADSLIGHT_EMAIL;
        $tags['TEL']         = _ADSLIGHT_TEL;
        $tags['HELLO']       = _ADSLIGHT_HELLO;
        $tags['REPLIED_BY']  = _ADSLIGHT_REPLIED_BY;
        $tags['YOUR_AD']     = _ADSLIGHT_YOUR_AD;
        $tags['THANKS']      = _ADSLIGHT_THANKS;
        $tags['WEBMASTER']   = _ADSLIGHT_WEBMASTER;
        $tags['SITE_URL']    = "<a href=\"" . XOOPS_URL . "\">" . XOOPS_URL . "</a>";
        $tags['AT']          = _ADSLIGHT_AT;
        $tags['LINK_URL']
                             =
            "<a href=\"" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/viewads.php?lid="
                . addslashes($id) . "\">" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname')
                . "/viewads.php?lid=" . addslashes($id) . "</a>";
        $tags['VIEW_AD']     = _ADSLIGHT_VIEW_AD;

        $subject = "" . _ADSLIGHT_CONTACTAFTERANN . "";
        $mail    =& xoops_getMailer();

        $mail->setTemplateDir(
            XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language']
                . "/mail_template/"
        );
        $mail->setTemplate("listing_contact.tpl");

        $mail->useMail();
        $mail->setFromEmail($_POST['post']);
        $mail->setToEmails($email);
        $mail->setSubject($subject);
        $mail->multimailer->isHTML(true);
        $mail->assign($tags);
        //	$mail->setBody(stripslashes("$message"));
        $mail->send();
        echo $mail->getErrors();

        $xoopsDB->query(
            "INSERT INTO " . $xoopsDB->prefix("adslight_ip_log") . " values ( '', '$lid', '$date', '$namep', '$ipnumber', '"
                . $_POST['post'] . "')"
        );

        $xoopsDB->query(
            "INSERT INTO " . $xoopsDB->prefix("adslight_replies") . " values ('','$id', '$title', '$date', '$namep', '$messtext', '$tele', '"
                . $_POST['post'] . "', '$r_usid')"
        );

        redirect_header("index.php", 3, _ADSLIGHT_MESSEND);
        exit();
    }
} else {
    $lid = intval($_GET['lid']);

    include("header.php");

    global $xoopsConfig, $xoopsModuleConfig, $xoopsDB, $myts, $meta;


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
    if (!$gperm_handler->checkRight("adslight_view", $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
        exit();
    }

    require_once(XOOPS_ROOT_PATH . "/modules/adslight/include/gtickets.php");
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";


    include(XOOPS_ROOT_PATH . "/header.php");
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    $time     = time();
    $ipnumber = "$_SERVER[REMOTE_ADDR]";
    echo "<script type=\"text/javascript\">
          function verify() {
                var msg = \"" . _ADSLIGHT_VALIDERORMSG . "\\n__________________________________________________\\n\\n\";
                var errors = \"FALSE\";
				if (window.document.cont.namep.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _ADSLIGHT_VALIDSUBMITTER . "\\n\";
                }
				if (window.document.cont.post.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _ADSLIGHT_VALIDEMAIL . "\\n\";
                }
				if (window.document.cont.messtext.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _ADSLIGHT_VALIDMESS . "\\n\";
                }
                if (errors == \"TRUE\") {
                        msg += \"__________________________________________________\\n\\n" . _ADSLIGHT_VALIDMSG . "\\n\";
                        alert(msg);
                        return false;
                }
          }
          </script>";

    echo "<b>" . _ADSLIGHT_CONTACTAUTOR . "</b><br /><br />";
    echo "" . _ADSLIGHT_TEXTAUTO . "<br />";
    echo "<form onSubmit=\"return verify();\" method=\"post\" action=\"contact.php\" name=\"cont\">";
    echo "<input type=\"hidden\" name=\"id\" value=\"$lid\" />";
    echo "<input type=\"hidden\" name=\"submit\" value=\"1\" />";
    echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
      <td class='head'>" . _ADSLIGHT_YOURNAME . "</td>";
    if ($xoopsUser) {
        $idd  = $xoopsUser->getVar("uname", "E");
        $idde = $xoopsUser->getVar("email", "E");

        echo "<td class='even'><input type=\"text\" name=\"namep\" size=\"42\" value=\"$idd\" />";
    } else {
        echo "<td class='even'><input type=\"text\" name=\"namep\" size=\"42\" /></td>";
    }
    echo "</tr>
    <tr>
      <td class='head'>" . _ADSLIGHT_YOUREMAIL . "</td>
      <td class='even'><input type=\"text\" name=\"post\" size=\"42\" value=\"$idde\" /></font></td>
    </tr>
    <tr>
      <td class='head'>" . _ADSLIGHT_YOURPHONE . "</td>
      <td class='even'><input type=\"text\" name=\"tele\" size=\"42\" /></font></td>
    </tr>
    <tr>
      <td class='head'>" . _ADSLIGHT_YOURMESSAGE . "</td>
      <td class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"40\" /></textarea></td>
    </tr>";
    if ($xoopsModuleConfig["adslight_use_captcha"] == '1') {
        echo "<tr><td class='head'>" . _ADSLIGHT_CAPTCHA . " </td><td class='even'>";
        $jlm_captcha = "";
        $jlm_captcha = (new XoopsFormCaptcha(_ADSLIGHT_CAPTCHA, "xoopscaptcha", false));
        echo $jlm_captcha->render();
    }

    echo "</td></tr></table>";
    echo "<table class='outer'><tr><td>" . _ADSLIGHT_YOUR_IP . "&nbsp;
        <img src=\"" . XOOPS_URL . "/modules/adslight/ip_image.php\" alt=\"\" /><br />" . _ADSLIGHT_IP_LOGGED . "
        </td></tr></table>
	<br />";
    echo "<input type=\"hidden\" name=\"ip_id\" value=\"\" />";
    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
    echo "<input type=\"hidden\" name=\"ipnumber\" value=\"$ipnumber\" />";
    echo "<input type=\"hidden\" name=\"date\" value=\"$time\" />";
    echo "<p><input type=\"submit\" name=\"submit\" value=\"" . _ADSLIGHT_SENDFR . "\" /></p>
" . $GLOBALS['xoopsGTicket']->getTicketHtml(__LINE__, 1800, 'token') . "
	</form>";
}
echo "</td></tr></table>";
include(XOOPS_ROOT_PATH . "/footer.php");