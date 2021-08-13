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

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';

/**
 * @param $lid
 */
function PrintAd($lid)
{
    global $xoopsConfig, $xoopsDB, $useroffset, $myts;

    $currenttheme = $xoopsConfig['theme_set'];
    $lid          = (int)$lid;

    $result = $xoopsDB->query(
        'SELECT l.lid, l.title, l.expire, l.type, l.desctext, l.tel, l.price, l.typeprice, l.date_created, l.email, l.submitter, l.town, l.country, l.photo, p.cod_img, p.lid, p.uid_owner, p.url FROM '
        . $xoopsDB->prefix('adslight_listing')
        . ' l LEFT JOIN '
        . $xoopsDB->prefix('adslight_pictures')
        . ' p ON l.lid=p.lid WHERE l.lid='
        . $xoopsDB->escape($lid)
    );
    [$lid, $title, $expire, $type, $desctext, $tel, $price, $typeprice, $date_created, $email, $submitter, $town, $country, $photo, $cod_img, $pic_lid, $uid_owner, $url] = $xoopsDB->fetchRow($result);

    $title     = \htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
    $expire    = \htmlspecialchars($expire, ENT_QUOTES | ENT_HTML5);
    $type      = Adslight\Utility::getNameType(htmlspecialchars($type, ENT_QUOTES | ENT_HTML5));
    $desctext  = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
    $tel       = \htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);
    $price     = \htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);
    $typeprice = \htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);
    $submitter = \htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);
    $town      = \htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);
    $country   = \htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . "</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" >
    <meta http-equiv=\”robots\” content=\"noindex, nofollow, noarchive\" >
    <link rel=\"StyleSheet\" href=\"../../themes/" . $currenttheme . '/style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" text="#000000">
    <table border=0><tr><td>
    <table border=0 width=100% cellpadding=0 cellspacing=1 bgcolor="#000000"><tr><td>
    <table border=0 width=100% cellpadding=15 cellspacing=1 bgcolor="#FFFFFF"><tr><td>';

    $useroffset = 0;
    if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
        $timezone   = $GLOBALS['xoopsUser']->timezone();
        $useroffset = empty($timezone) ? $xoopsConfig['default_TZ'] : $GLOBALS['xoopsUser']->timezone();
    }
    $date_created  = ($useroffset * 3600) + $date_created;
    $date2 = $date_created + ($expire * 86400);
    $date_created  = formatTimestamp($date_created, 's');
    $date2 = formatTimestamp($date2, 's');

    echo '<br><br><table width=99% border=0>
        <tr>
      <td>' . _ADSLIGHT_CLASSIFIED . " (No. $lid ) <br>" . _ADSLIGHT_FROM . " $submitter <br><br>";

    echo " <strong>$type :</strong> <i>$title</i><br>";
    if ($price > 0) {
        echo '<strong>' . _ADSLIGHT_PRICE2 . "</strong> $price " . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . "  - $typeprice<br>";
    }
    if ($photo) {
        echo "<tr><td><div style='text-align:left'><img class=\"thumb\" src=\"" . XOOPS_URL . "/uploads/adslight/$url\" width=\"130px\" border=0 ></div>";
    }
    echo '</td>
          </tr>
    <tr>
      <td><strong>' . _ADSLIGHT_DESC . "</strong><br><br><div style=\"text-align:justify;\">$desctext</div><p>";
    if ('' !== $tel) {
        echo '<br><strong>' . _ADSLIGHT_TEL . "</strong> $tel";
    }
    if ('' !== $town) {
        echo '<br><strong>' . _ADSLIGHT_TOWN . "</strong> $town";
    }
    if ('' !== $country) {
        echo '<br><strong>' . _ADSLIGHT_COUNTRY . "</strong> $country";
    }
    echo '<hr>';
    echo '' . _ADSLIGHT_NOMAIL . ' <br>' . XOOPS_URL . '/modules/adslight/viewads.php?lid=' . $lid . '<br>';
    echo '<br><br>' . _ADSLIGHT_DATE2 . " $date_created " . _ADSLIGHT_AND . ' ' . _ADSLIGHT_DISPO . " $date2<br><br>";
    echo '</td>
    </tr>
    </table>';
    echo '<br><br></td></tr></table></td></tr></table>
    <br><br><div style="text-align:center">
    ' . _ADSLIGHT_EXTRANN . ' <strong>' . $xoopsConfig['sitename'] . '</strong></div><br>
    <a href="' . XOOPS_URL . '/modules/adslight/">' . XOOPS_URL . '/modules/adslight/</a>
    </td></tr></table>
    </body>
    </html>';
}

##############################################################

$lid = Request::getInt('lid', 0);
$op  = Request::getString('op', '');

switch ($op) {
    case 'PrintAd':
        PrintAd($lid);
        break;
    default:
        redirect_header('index.php', 3, ' ' . _RETURNANN . ' ');
        break;
}
