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

$op = XoopsRequest::getString('op', 'liste');

#  function Index
#####################################################
function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $desctext, $moduleDirName;
    //    include_once XOOPS_ROOT_PATH."/modules/adslight/class/classifiedstree.php";
    $mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

    //    include_once __DIR__ . '/admin_header.php';
    //  require_once("adsligh_rsslib.php");

    xoops_cp_header();
    //    loadModuleAdminMenu(0, "");

    echo "<table width=\"50%\" border=\"0\" cellspacing=\"8\" cellpadding=\"0\">\n" . "  <tr>\n" . "    <td class=\"top\">";

    /*
    /// Test Release ///
    $resultat_site = "http://www.i-luc.fr/release/release.html";
    $page1 = implode("", file("$resultat_site"));
    if (preg_match("1.07",$page1)) { echo "<table class='outer' border=0 cellspacing=5 cellpadding=0><tr><td width=40>";
      echo "<img src='../assets/images/admin/info_button_32.png' border=0 alt=\"._AM_ADSLIGHT_RELEASEOK.\" /></td><td>";
      echo "<span style='color: #00B4C4'><b>"._AM_ADSLIGHT_RELEASEOK."- AdsLigh 1.07</b></span>";
      echo "</td></tr></table><br>";

    } else {

      echo "<table class='outer' border=0 cellspacing=5 cellpadding=0><tr><td width=40>";
      echo "<img src='../assets/images/admin/error_button_32.png' border=0 alt=\"._AM_ADSLIGHT_RELEASEDOWNLOAD.\" /></td><td>";
      echo "<span style='color: #FE0101'><b>"._AM_ADSLIGHT_RELEASEISNOTOK."</b></span><br>";
      echo "<a href='http://www.i-luc.fr/adslight/modules/TDMDownloads/visit.php?cid=1&lid=3'>"._AM_ADSLIGHT_RELEASEDOWNLOAD." > AdsLight 1.08</a></td></tr></table><br>";

      }
      */

    ///////// Il y a [..] Annonces en attente d'être approuvées //////
    $result  = $xoopsDB->query('SELECT lid FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='No'");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        echo "<table class='outer' border=0 cellspacing=5 cellpadding=0><tr><td width=40>";
        echo "<img src='../assets/images/admin/error_button.png' border=0 /></td><td>";
        echo "<span style='color:#00B4C4;'><b>" . _AM_ADSLIGHT_THEREIS . "</b></span> <b>$numrows</b> <span style='color:#00B4C4;'>" . _AM_ADSLIGHT_WAIT . '</b></span>';
        echo '</td></tr></table><br>';
    } else {
        echo "<table class='outer' width='50%' border='0'><tr><td width=40>";
        echo "<img src='../assets/images/admin/search_button_green_32.png' border=0 alt=\"._AM_ADSLIGHT_RELEASEOK.\" /></td><td>";
        echo "<span style='color: #00B4C4;'><b>" . _AM_ADSLIGHT_NOANNVAL . '</b></span>';
        echo '</td></tr></table><br>';
    }

    //// Center menu

    /// Annonces

    echo '<table width="50%" border="0" class="outer">
                  <tr><th align="left">' . _AM_ADSLIGHT_ADSMENU_TITLE . '</th></tr>

                 <tr class="odd"><td><img src="../assets/images/admin/report_go.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/validate_ads.php">' . _AM_ADSLIGHT_ADSMENU_VALIDADS . '</a>
                  </td></tr>

                 <tr class="even"><td><img src="../assets/images/admin/report_magnify.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/view_ads.php">' . _AM_ADSLIGHT_ADSMENU_VIEWADS . '</a>
                  </td></tr>

                  <tr class="odd"><td><img src="../assets/images/admin/report_add.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/modify_ads.php">' . _AM_ADSLIGHT_ADS_MODIFADS . '</a>
                  </td></tr>

              </table><br>';

    echo '<table width="50%" border="0" class="outer">
                  <tr colspan="2"><th align="left">' . _AM_ADSLIGHT_ADSMENU_PLUGIN . '</th></tr>

                 <tr class="odd">
                 <td>
                 <strong>Plugin Waiting Module :</strong> <a href="http://sourceforge.net/projects/adslight/files/Plugins%20AdsLight/Plugin%20Waiting%20Module/">Download</a><br><br>
                 <strong>Plugin Sitemap Module :</strong> <a href="http://sourceforge.net/projects/adslight/files/Plugins%20AdsLight/Plugin%20Sitemap%20Module/">Download</a><br><br>
                <strong>Plugin Waiting Rssfit :</strong> <a href="http://sourceforge.net/projects/adslight/files/Plugins%20AdsLight/Plugin%20Waiting%20Rssfit/">Download</a><br><br>
                <strong>Plugin Xpayment :</strong> <a href="https://sourceforge.net/projects/adslight/files/Plugins%20AdsLight/Plugin%20xpayment%20Module/">Download</a><br><br>
                 </td>
                  </tr>
                  <tr class="even">
                 <td>
                 <a href="mailto:adslight.translate@gmail.com?subject=Plugin for AdsLight">' . _AM_ADSLIGHT_SEND_PLUGIN . '</a> |
                 <a href="mailto:adslight.translate@gmail.com?subject=Translation for AdsLight">' . _AM_ADSLIGHT_SEND_TRANSLATION . '</a>
                 </td>
                  </tr>

                  </table><br>';

    echo '<table width="50%" border="0" class="outer">
                  <tr colspan="2"><th align="left">' . _AM_ADSLIGHT_ADSMENU_NEW . '</th></tr>

                 <tr class="odd">
                 <td>' . _AM_ADSLIGHT_ADSMENU_NEWTXT . '</td>

                  </tr>
                  </table><br>';

    /*
 ////   RSS AdsLight Forum

          echo '<table width="50%" border="0" class="outer">
                  <tr><th align="left">'._AM_ADSLIGHT_MENURSSFORUM_TITLE.'</th></tr>
                  <tr class="even"><td width="300">';


    $url = _AM_ADSLIGHT_MENURSSFORUM_URL;
          echo RSS_DisplayForum($url, 5, false, true);


          echo '</td></tr>
                  <tr class="odd"><td><img src="../assets/images/admin/user_go.png" border=0 />
                  <a href="'._AM_ADSLIGHT_MENURSSFORUM_LINK1.'">'._AM_ADSLIGHT_MENURSSFORUM_GOFORUM.'</a>  |

                  <img src="../assets/images/admin/vcard_add.png" border=0 />
                  <a href="'._AM_ADSLIGHT_MENURSSFORUM_LINK2.'">'._AM_ADSLIGHT_MENURSSFORUM_SUBSCRIT.'</a>  |
                  </td></tr>
                  </table>';

 ////   RSS AdsLight News

        echo '<table width="50%" border="0" class="outer">
                <tr><th align="left">'._AM_ADSLIGHT_MENURSS_TITLE.'</th></tr>';

                  $url = "http://www.i-luc.fr/adslight/backend.php";
        echo RSS_Display($url, 3, false, true);


            echo '</table><br>

*/
    echo '</td>
          <td valign="top">';

    ////// Right Menu Admin

    /// Statistiques
    $Num1 = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_listing') . "  WHERE (valid='Yes' AND status!='1')"));
    $Num2 = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('adslight_categories') . ''));
    $Num3 = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('users') . "  WHERE (level = '1')"));
    $Num4 = $xoopsDB->getRowsNum($xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('xoopscomments') . "  WHERE (com_status = '2')"));

    echo '<table width=100 border="0" class="outer"><tr>
                <th align="left">' . _AM_ADSLIGHT_STAT_TITLE . '</th></tr>

                  <tr class="odd"><td>
                  <b>' . $Num1 . '</b> ' . _AM_ADSLIGHT_STAT_NUM1 . '
                  </td></tr>


                 <tr class="even"><td>
                 <b>' . $Num2 . '</b> ' . _AM_ADSLIGHT_STAT_NUM2 . '
                 </td></tr>

                  <tr class="odd"><td>
                  <b>' . $Num3 . '</b> ' . _AM_ADSLIGHT_STAT_NUM3 . '
                  </td></tr>

                 <tr class="odd"><td>
                 <b>' . $Num4 . '</b> ' . _AM_ADSLIGHT_STAT_NUM4 . '
                 </td></tr>

                  </table><br>';

    /// Menu Catégories
    echo '<table width=100 border="0" class="outer"><tr>
                   <th width="50%" align="left">' . _AM_ADSLIGHT_CATMENU_TITLE . '</th></tr>

                  <tr class="odd"><td><img src="../assets/images/admin/chart_organisation_add.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/category.php?op=AdsNewCat&cid=0">' . _AM_ADSLIGHT_CATMENU_CATEGORY . '</a>
                  </td></tr>

                  <tr class="even"><td><img src="../assets/images/admin/chart_organisation.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/map.php">' . _AM_ADSLIGHT_CATMENU_MODIFCAT . '</a>
                  </td></tr>

          </table><br>';

    /// Menu Gestion
    echo '<table width=100 border="0" class="outer"><tr>
                <th align="left">' . _AM_ADSLIGHT_USERMENU_TITLE . '</th></tr>

                  <tr class="odd"><td><img src="../assets/images/admin/email_forward.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/system/admin.php?fct=mailusers">' . _AM_ADSLIGHT_USERMENU_SENDMAIL . '</a>
                  </td></tr>

                  <tr class="even"><td><img src="../assets/images/admin/comment_user.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/system/admin.php?fct=comments">' . _AM_ADSLIGHT_USERMENU_COMMENT . '</a>
                  </td></tr>

                 <tr class="odd"><td><img src="../assets/images/admin/web_layout.png" border=0 />
                 <a href="' . XOOPS_URL . '/modules/system/admin.php?fct=banners">' . _AM_ADSLIGHT_USERMENU_BAMMIER . '</a>
                 </td></tr>

                  </table><br>';

    /// Menu Downloads
    echo '<table width=100 border="0" class="outer"><tr>
                <th align="left">' . _AM_ADSLIGHT_DOWNLOADS_TITLE . '</th></tr>

                  <tr class="odd"><td><img src="../assets/images/admin/brick.png" border=0 />
                  <a href="http://sourceforge.net/projects/adslight/files/Plugins%20AdsLight/">' . _AM_ADSLIGHT_DOWNLOADS_PLUGINS . '</a>
                  </td></tr>

                  <!-- <tr class="even"><td><img src="../assets/images/admin/world.png" border=0 />
                  <a href="http://www.i-luc.fr/adslight/modules/TDMDownloads/index.php">' . _AM_ADSLIGHT_DOWNLOADS_MAPS . '</a>
                  </td></tr> -->

                  </table><br>';

    /// Menu Devellopement
    echo '<table width=100 border="0" class="outer">

                  <tr><th align="left">' . _AM_ADSLIGHT_DEVLLP_TITLE . '</th></tr>

                 <tr class="odd"><td><img src="../assets/images/admin/page_white_code.png" border=0 />
                 <a href="mailto:adslight.translate@gmail.com?subject=Hack AdsLight">' . _AM_ADSLIGHT_DEVLLP_HACK . '</a>
                 </td></tr>

                 <tr class="even"><td><img src="../assets/images/admin/page_white_world.png" border=0 />
                 <a href="mailto:adslight.translate@gmail.com?subject=Traduction AdsLight">' . _AM_ADSLIGHT_DEVLLP_TRANSLATE . '</a>
                 </td></tr>

                  <tr class="odd"><td><img src="../assets/images/admin/page_white_text.png" border=0 />
                  <a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight">' . _AM_ADSLIGHT_DEVLLP_CORRECTION . '</a>
                  </td></tr>

                  <tr class="even"><td><img src="../assets/images/admin/page_white_flash.png" border=0 />
                  <a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight">' . _AM_ADSLIGHT_DEVLLP_MAPFLASH . '</a>
                  </td></tr>

              <!--  <tr class="odd"><td><img src="../assets/images/admin/group.png" border=0 />
                  <a href="' . XOOPS_URL . '/modules/adslight/admin/support_forum.php">' . _AM_ADSLIGHT_DEVLLP_FORUM . '</a>
                  </td></tr> -->

                  </table><br>';

    /// Faire un don
    /*    echo '<table width=100 border="0" class="outer">

                  <tr><th align="left">'._AM_ADSLIGHT_DONATE_TITLE.'</th></tr>

                 <tr class="odd"><td width=100>
                 '._AM_ADSLIGHT_DONATE.'
                 </td></tr>

                 <tr class="even"><td><center>
                 '._AM_ADSLIGHT_DONATE_LOGO.'
                 </center></td></tr>

                  </table><br>';  */

    ////// AND Right Menu Admin /////
    echo '</td>
            </tr>
                </table>';

    xoops_cp_footer();
}

#  function CopyXml
#####################################################
function copyXml()
{

    $adslight_maps = $GLOBALS['xoopsModuleConfig']['adslight_maps_set'];

    $indexFile = XOOPS_ROOT_PATH . "/modules/adslight/maps/$adslight_maps/datas.xml";
    copy($indexFile, XOOPS_ROOT_PATH . '/modules/adslight/datas.xml');

    redirect_header('index.php', 3, _AM_ADSLIGHT_ANNVALID);
}

#####################################################
#####################################################
//@todo REMOVE THIS ASAP. This code is extremely unsafe
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa  = XoopsRequest::getString('pa', '', 'GET');
$lid = XoopsRequest::getInt('lid', 0);
$op  = XoopsRequest::getString('op', '');

switch ($op) {
    case 'CopyXml':
        copyXml();
        break;
    default:
        index();
        break;
}
