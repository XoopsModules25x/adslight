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

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

//if ( !is_readable(XOOPS_ROOT_PATH. "/Frameworks/art/functions.admin.php")) {
//    adslight_adminmenu(7, "");
//} else {
//    require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//    loadModuleAdminMenu (7, "");
//}
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$versioninfo   = $moduleHandler->get($xoopsModule->getVar('mid'));
echo '
    <style type="text/css">
    label,text {
        display: block;
        float: left;
        margin-bottom: 2px;
    }
    label {
        text-align: right;
        width: 150px;
        padding-right: 20px;
    }
    br {
        clear: left;
    }
    </style>
';

///// People who participate in improving the module
echo "<fieldset><legend style='font-weight: bold; color: #555;'>" . _AM_ADSLIGHT_SUPPORTFORUM_TITLE . '</legend>';
echo "<div style='padding: 8px;'>";

//// France ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/fr.png" width="16" height="11" border="0"><b>  Fran&#231;ais</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=2" target="_blank" >Forum support Fran&#231;ais</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ":</label>Forum d'entraide, Traduction, d&#233;veloppement et support Fran&#231;ais<br>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Envoyer une correction</a><br>';
echo '</tr></table><br>';

//// Espagne ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/es.png" width="16" height="11" border="0"><b>  Espa&#241;ol</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=33" target="_blank" >Foro de soporte en Espa&#241;ol</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label>Foro de soporte, la traducci&oacute;n, el desarrollo y soporte en Espa&#241;ol<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Enviar una correcci&#243;n</a><br>';
echo '</tr></table><br>';

//// Anglais ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/uk.png" width="16" height="11" border="0"><b>  English</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=56" target="_blank" >English Support Forum</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label>Support forum, translation, development and English support<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Send a correction</a><br>';
echo '</tr></table><br>';

//// Allemand ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/de.png" width="16" height="11" border="0"><b>  Deutsch</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=64" target="_blank" >Deutsch Support Forum</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label>Support Forum, &#220;bersetzung, Entwicklung und deutsche Support<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Senden Sie eine Korrektur</a><br>';
echo '</tr></table><br>';

//// Bosnie ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/ba.png" width="16" height="11" border="0"><b>  Bosanski</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=66" target="_blank" >Bosanski Forum za pomoc</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label>Podrska forum, prijevod, razvoj i podrsku bosanskih<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Po&#353;alji ispravak</a><br>';
echo '</tr></table><br>';

//// Néerlandais ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/nl.png" width="16" height="11" border="0"><b>  Nederlands</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label><a href="http://www.i-luc.fr/adslight/modules/newbb/index.php?cat=58" target="_blank" >Nederlandse steun Forum</a><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label>Support forum, Vertaling, Nederlands Ontwikkeling en Ondersteuning<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Correction AdsLight"> Stuur een correctie</a><br>';
echo '</tr></table><br><br><br>';

///////////////////////////////////

//// Italie ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/unavailable.png" width="16" height="11" border="0"><b>  Italiano</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ': </label><a href="mailto:adslight.translate@gmail.com?subject=Traduction AdsLight"> Presentare una traduzione</a><br>';
echo '</tr></table><br>';

//// Russie ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/unavailable.png" width="16" height="11" border="0"><b>  &#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ':</label><a href="mailto:adslight.translate@gmail.com?subject=Traduction AdsLight"> &#1054;&#1090;&#1087;&#1088;&#1072;&#1074;&#1080;&#1090;&#1100; &#1087;&#1077;&#1088;&#1077;&#1074;&#1086;&#1076;</a><br>';
echo '</tr></table><br>';

//// Polonais ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/unavailable.png" width="16" height="11" border="0"><b>  Polski</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ':</label><a href="mailto:adslight.translate@gmail.com?subject=Traduction AdsLight"> Przedstawi&#263; t&#322;umaczenie</a><br>';
echo '</tr></table><br>';

//// Portugais ///
echo "<table width='100%' border='0' class='outer'><tr><td>";
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG . ':</label><img src="../assets/images/flags/unavailable.png" width="16" height="11" border="0"><b>  Portugu&#234;s</b><br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_DESC . ':</label> -<br>';
echo '<label>' . _AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE . ':</label><a href="mailto:adslight.translate@gmail.com?subject=Traduction AdsLight"> Enviar uma Enviar tradu&#231;&#227;o</a><br>';
echo '</tr></table><br>';

echo '</div>';
echo '</fieldset>';
echo '<br clear="all" >';

xoops_cp_footer();
